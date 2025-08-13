<?php

namespace MediaWiki\RenameUser;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Specials\SpecialLog;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Class which performs the actual renaming of users
 */
class RenameuserSQL {
	/**
	 * The old username of the user being renamed
	 *
	 * @var string
	 */
	public $old;

	/**
	 * The new username of the user being renamed
	 *
	 * @var string
	 */
	public $new;

	/**
	 * The user ID of the user being renamed
	 *
	 * @var int
	 */
	public $uid;

	/**
	 * The [ tables => fields ] to be updated
	 *
	 * @var array
	 */
	public $tables;

	/**
	 * [ tables => fields ] to be updated in a deferred job
	 *
	 * @var array[]
	 */
	public $tablesJob;

	/**
	 * Flag that can be set to false, in case another process has already started
	 * the updates and the old username may have already been renamed in the user table.
	 *
	 * @var bool
	 */
	public $checkIfUserExists;

	/**
	 * User object of the user performing the rename, for logging purposes
	 *
	 * @var User
	 */
	private $renamer;

	/**
	 * Reason for the rename to be used in the log entry
	 *
	 * @var string
	 */
	private $reason = '';

	/**
	 * A prefix to use in all debug log messages
	 *
	 * @var string
	 */
	private $debugPrefix = '';

	/**
	 * Whether shared tables and virtual domains should be updated
	 *
	 * When this is set to true, it is assumed that the shared tables are already updated.
	 *
	 * @var bool
	 */
	private $derived = false;

	// B/C constants for tablesJob field
	public const NAME_COL = 0;
	public const UID_COL  = 1;
	public const TIME_COL = 2;

	/** @var HookRunner */
	private $hookRunner;

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var UserFactory */
	private $userFactory;

	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var LoggerInterface */
	private $logger;

	/** @var int */
	private $updateRowsPerJob;

	/**
	 * Constructor
	 *
	 * @param string $old The old username
	 * @param string $new The new username
	 * @param int $uid
	 * @param User $renamer
	 * @param array $options Optional extra options.
	 *    'reason' - string, reason for the rename
	 *    'debugPrefix' - string, prefixed to debug messages
	 *    'checkIfUserExists' - bool, whether to update the user table
	 *    'derived' - bool, whether to skip updates to shared tables
	 */
	public function __construct( string $old, string $new, int $uid, User $renamer, $options = [] ) {
		$services = MediaWikiServices::getInstance();
		$this->hookRunner = new HookRunner( $services->getHookContainer() );
		$this->dbProvider = $services->getConnectionProvider();
		$this->userFactory = $services->getUserFactory();
		$this->jobQueueGroup = $services->getJobQueueGroup();
		$this->titleFactory = $services->getTitleFactory();
		$this->logger = LoggerFactory::getInstance( 'Renameuser' );

		$config = $services->getMainConfig();
		$this->updateRowsPerJob = $config->get( MainConfigNames::UpdateRowsPerJob );

		$this->old = $old;
		$this->new = $new;
		$this->uid = $uid;
		$this->renamer = $renamer;
		$this->checkIfUserExists = true;

		if ( isset( $options['checkIfUserExists'] ) ) {
			$this->checkIfUserExists = $options['checkIfUserExists'];
		}

		if ( isset( $options['debugPrefix'] ) ) {
			$this->debugPrefix = $options['debugPrefix'];
		}

		if ( isset( $options['reason'] ) ) {
			$this->reason = $options['reason'];
		}

		if ( isset( $options['derived'] ) ) {
			$this->derived = $options['derived'];
		}

		$this->tables = []; // Immediate updates
		$this->tablesJob = []; // Slow updates

		$this->hookRunner->onRenameUserSQL( $this );
	}

	protected function debug( string $msg ) {
		if ( $this->debugPrefix ) {
			$msg = "{$this->debugPrefix}: $msg";
		}
		$this->logger->debug( $msg );
	}

	/**
	 * Do the rename operation
	 * @deprecated since 1.44 use renameUser
	 * @return bool
	 */
	public function rename() {
		wfDeprecated( __METHOD__, '1.44' );
		return $this->renameUser()->isOK();
	}

	/**
	 * Do the rename operation
	 * @return Status
	 */
	public function renameUser(): Status {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$atomicId = $dbw->startAtomic( __METHOD__, $dbw::ATOMIC_CANCELABLE );

		$this->hookRunner->onRenameUserPreRename( $this->uid, $this->old, $this->new );

		// Make sure the user exists if needed
		if ( $this->checkIfUserExists ) {
			// if derived is true and 'user' table is shared,
			// then 'user.user_name' should already be updated
			$userRenamed = $this->derived && self::isTableShared( 'user' );
			$currentName = $userRenamed ? $this->new : $this->old;
			if ( !$this->lockUserAndGetId( $currentName ) ) {
				$this->debug( "User $currentName does not exist, bailing out" );
				$dbw->cancelAtomic( __METHOD__, $atomicId );

				return Status::newFatal( 'renameusererrordoesnotexist', $this->new );
			}
		}

		// Grab the user's edit count before any updates are made; used later in a log entry
		$contribs = $this->userFactory->newFromId( $this->uid )->getEditCount();

		// Rename and touch the user before re-attributing edits to avoid users still being
		// logged in and making new edits (under the old name) while being renamed.
		$this->debug( "Starting rename of {$this->old} to {$this->new}" );
		if ( !$this->derived ) {
			$this->debug( "Rename of {$this->old} to {$this->new} will update shared tables" );
		}
		if ( $this->shouldUpdate( 'user' ) ) {
			$this->debug( "Updating user table for {$this->old} to {$this->new}" );
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [ 'user_name' => $this->new, 'user_touched' => $dbw->timestamp() ] )
				->where( [ 'user_name' => $this->old, 'user_id' => $this->uid ] )
				->caller( __METHOD__ )->execute();
		} else {
			$this->debug( "Skipping user table for rename from {$this->old} to {$this->new}" );
		}
		if ( $this->shouldUpdate( 'actor' ) ) {
			$this->debug( "Updating actor table for {$this->old} to {$this->new}" );
			$dbw->newUpdateQueryBuilder()
				->update( 'actor' )
				->set( [ 'actor_name' => $this->new ] )
				->where( [ 'actor_name' => $this->old, 'actor_user' => $this->uid ] )
				->caller( __METHOD__ )->execute();
		} else {
			$this->debug( "Skipping actor table for rename from {$this->old} to {$this->new}" );
		}

		// If this user is renaming themself, make sure that code below uses a proper name
		if ( $this->renamer->getId() === $this->uid ) {
			$this->renamer->setName( $this->new );
		}

		// Reset token to break login with central auth systems.
		// Again, avoids user being logged in with old name.
		$user = $this->userFactory->newFromId( $this->uid );

		$user->load( IDBAccessObject::READ_LATEST );
		MediaWikiServices::getInstance()->getSessionManager()->invalidateSessionsForUser( $user );

		// Purge user cache
		$user->invalidateCache();

		// Update the block_target table rows if this user has a block in there.
		if ( $this->shouldUpdate( 'block_target' ) ) {
			$this->debug( "Updating block_target table for {$this->old} to {$this->new}" );
			$dbw->newUpdateQueryBuilder()
				->update( 'block_target' )
				->set( [ 'bt_user_text' => $this->new ] )
				->where( [ 'bt_user' => $this->uid, 'bt_user_text' => $this->old ] )
				->caller( __METHOD__ )->execute();
		} else {
			$this->debug( "Skipping block_target table for rename from {$this->old} to {$this->new}" );
		}

		// Update this users block/rights log. Ideally, the logs would be historical,
		// but it is really annoying when users have "clean" block logs by virtue of
		// being renamed, which makes admin tasks more of a pain...
		$oldTitle = $this->titleFactory->makeTitle( NS_USER, $this->old );
		$newTitle = $this->titleFactory->makeTitle( NS_USER, $this->new );

		// Exclude user renames per T200731
		$logTypesOnUser = array_diff( SpecialLog::getLogTypesOnUser(), [ 'renameuser' ] );

		if ( $this->shouldUpdate( 'logging' ) ) {
			$this->debug( "Updating logging table for {$this->old} to {$this->new}" );
			$dbw->newUpdateQueryBuilder()
				->update( 'logging' )
				->set( [ 'log_title' => $newTitle->getDBkey() ] )
				->where( [
					'log_type' => $logTypesOnUser,
					'log_namespace' => NS_USER,
					'log_title' => $oldTitle->getDBkey()
				] )
				->caller( __METHOD__ )->execute();
		} else {
			$this->debug( "Skipping logging table for rename from {$this->old} to {$this->new}" );
		}

		if ( $this->shouldUpdate( 'recentchanges' ) ) {
			$this->debug( "Updating recentchanges table for rename from {$this->old} to {$this->new}" );
			$dbw->newUpdateQueryBuilder()
				->update( 'recentchanges' )
				->set( [ 'rc_title' => $newTitle->getDBkey() ] )
				->where( [
					'rc_type' => RC_LOG,
					'rc_log_type' => $logTypesOnUser,
					'rc_namespace' => NS_USER,
					'rc_title' => $oldTitle->getDBkey()
				] )
				->caller( __METHOD__ )->execute();
		} else {
			$this->debug( "Skipping recentchanges table for rename from {$this->old} to {$this->new}" );
		}

		// Do immediate re-attribution table updates...
		foreach ( $this->tables as $table => $fieldSet ) {
			[ $nameCol, $userCol ] = $fieldSet;
			if ( $this->shouldUpdate( $table ) ) {
				$this->debug( "Updating {$table} table for rename from {$this->old} to {$this->new}" );
				$dbw->newUpdateQueryBuilder()
					->update( $table )
					->set( [ $nameCol => $this->new ] )
					->where( [ $nameCol => $this->old, $userCol => $this->uid ] )
					->caller( __METHOD__ )->execute();
			} else {
				$this->debug( "Skipping {$table} table for rename from {$this->old} to {$this->new}" );
			}
		}

		/** @var \MediaWiki\RenameUser\Job\RenameUserTableJob[] $jobs */
		$jobs = []; // jobs for all tables
		// Construct jobqueue updates...
		// FIXME: if a bureaucrat renames a user in error, they
		// must be careful to wait until the rename finishes before
		// renaming back. This is due to the fact the job "queue"
		// is not really FIFO, so we might end up with a bunch of edits
		// randomly mixed between the two new names. Some sort of rename
		// lock might be in order...
		foreach ( $this->tablesJob as $table => $params ) {
			if ( !$this->shouldUpdate( $table ) ) {
				$this->debug( "Skipping {$table} table for rename from {$this->old} to {$this->new}" );
				continue;
			}
			$this->debug( "Updating {$table} table for rename from {$this->old} to {$this->new}" );

			$userTextC = $params[self::NAME_COL]; // some *_user_text column
			$userIDC = $params[self::UID_COL]; // some *_user column
			$timestampC = $params[self::TIME_COL]; // some *_timestamp column

			$res = $dbw->newSelectQueryBuilder()
				->select( [ $timestampC ] )
				->from( $table )
				->where( [ $userTextC => $this->old, $userIDC => $this->uid ] )
				->orderBy( $timestampC, SelectQueryBuilder::SORT_ASC )
				->caller( __METHOD__ )->fetchResultSet();

			$jobParams = [];
			$jobParams['table'] = $table;
			$jobParams['column'] = $userTextC;
			$jobParams['uidColumn'] = $userIDC;
			$jobParams['timestampColumn'] = $timestampC;
			$jobParams['oldname'] = $this->old;
			$jobParams['newname'] = $this->new;
			$jobParams['userID'] = $this->uid;
			// Timestamp column data for index optimizations
			$jobParams['minTimestamp'] = '0';
			$jobParams['maxTimestamp'] = '0';
			$jobParams['count'] = 0;
			// Unique column for replica lag avoidance
			if ( isset( $params['uniqueKey'] ) ) {
				$jobParams['uniqueKey'] = $params['uniqueKey'];
			}

			// Insert jobs into queue!
			foreach ( $res as $row ) {
				// Since the ORDER BY is ASC, set the min timestamp with first row
				if ( $jobParams['count'] === 0 ) {
					$jobParams['minTimestamp'] = $row->$timestampC;
				}
				// Keep updating the last timestamp, so it should be correct
				// when the last item is added.
				$jobParams['maxTimestamp'] = $row->$timestampC;
				// Update row counter
				$jobParams['count']++;
				// Once a job has $wgUpdateRowsPerJob rows, add it to the queue
				if ( $jobParams['count'] >= $this->updateRowsPerJob ) {
					$jobs[] = new JobSpecification( 'renameUserTable', $jobParams, [], $oldTitle );
					$jobParams['minTimestamp'] = '0';
					$jobParams['maxTimestamp'] = '0';
					$jobParams['count'] = 0;
				}
			}
			// If there are any job rows left, add it to the queue as one job
			if ( $jobParams['count'] > 0 ) {
				$jobs[] = new JobSpecification( 'renameUserTable', $jobParams, [], $oldTitle );
			}
		}

		// Log it!
		$logEntry = new ManualLogEntry( 'renameuser', 'renameuser' );
		$logEntry->setPerformer( $this->renamer );
		$logEntry->setTarget( $oldTitle );
		$logEntry->setComment( $this->reason );
		$logEntry->setParameters( [
			'4::olduser' => $this->old,
			'5::newuser' => $this->new,
			'6::edits' => $contribs,
			'derived' => $this->derived
		] );
		$logid = $logEntry->insert();

		// Insert any jobs as needed. If this fails, then an exception will be thrown and the
		// DB transaction will be rolled back. If it succeeds but the DB commit fails, then the
		// jobs will see that the transaction was not committed and will cancel themselves.
		$count = count( $jobs );
		if ( $count > 0 ) {
			$this->jobQueueGroup->push( $jobs );
			$this->debug( "Queued $count jobs for rename from {$this->old} to {$this->new}" );
		}

		// Commit the transaction
		$dbw->endAtomic( __METHOD__ );

		$fname = __METHOD__;
		$dbw->onTransactionCommitOrIdle(
			function () use ( $dbw, $logEntry, $logid, $fname ) {
				$dbw->startAtomic( $fname );
				// Clear caches and inform authentication plugins
				$user = $this->userFactory->newFromId( $this->uid );
				$user->load( IDBAccessObject::READ_LATEST );
				// Trigger the UserSaveSettings hook
				$user->saveSettings();
				$this->hookRunner->onRenameUserComplete( $this->uid, $this->old, $this->new );
				// Publish to RC
				$logEntry->publish( $logid );
				$dbw->endAtomic( $fname );
			},
			$fname
		);

		$this->debug( "Finished rename from {$this->old} to {$this->new}" );

		return Status::newGood();
	}

	/**
	 * @param string $name Current wiki local username
	 * @return int Returns 0 if no row was found
	 */
	private function lockUserAndGetId( $name ) {
		return (int)$this->dbProvider->getPrimaryDatabase()->newSelectQueryBuilder()
			->select( 'user_id' )
			->forUpdate()
			->from( 'user' )
			->where( [ 'user_name' => $name ] )
			->caller( __METHOD__ )->fetchField();
	}

	/**
	 * Checks if a table should be updated in this rename.
	 * @param string $table
	 * @return bool
	 */
	private function shouldUpdate( string $table ) {
		return !$this->derived || !self::isTableShared( $table );
	}

	/**
	 * Check if a table is shared.
	 *
	 * @param string $table The table name
	 * @return bool Returns true if the table is shared
	 */
	private static function isTableShared( string $table ) {
		global $wgSharedTables, $wgSharedDB;
		return $wgSharedDB && in_array( $table, $wgSharedTables, true );
	}
}
