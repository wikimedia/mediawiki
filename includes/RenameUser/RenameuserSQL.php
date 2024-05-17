<?php

namespace MediaWiki\RenameUser;

use IDBAccessObject;
use JobQueueGroup;
use JobSpecification;
use ManualLogEntry;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;
use MediaWiki\Specials\SpecialLog;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
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
	 */
	public function __construct( $old, $new, $uid, User $renamer, $options = [] ) {
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

		$this->tables = []; // Immediate updates
		$this->tablesJob = []; // Slow updates

		$this->hookRunner->onRenameUserSQL( $this );
	}

	protected function debug( $msg ) {
		if ( $this->debugPrefix ) {
			$msg = "{$this->debugPrefix}: $msg";
		}
		$this->logger->debug( $msg );
	}

	/**
	 * Do the rename operation
	 * @return bool
	 */
	public function rename() {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$atomicId = $dbw->startAtomic( __METHOD__, $dbw::ATOMIC_CANCELABLE );

		$this->hookRunner->onRenameUserPreRename( $this->uid, $this->old, $this->new );

		// Make sure the user exists if needed
		if ( $this->checkIfUserExists && !$this->lockUserAndGetId( $this->old ) ) {
			$this->debug( "User {$this->old} does not exist, bailing out" );
			$dbw->cancelAtomic( __METHOD__, $atomicId );

			return false;
		}

		// Grab the user's edit count before any updates are made; used later in a log entry
		$contribs = $this->userFactory->newFromId( $this->uid )->getEditCount();

		// Rename and touch the user before re-attributing edits to avoid users still being
		// logged in and making new edits (under the old name) while being renamed.
		$this->debug( "Starting rename of {$this->old} to {$this->new}" );
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_name' => $this->new, 'user_touched' => $dbw->timestamp() ] )
			->where( [ 'user_name' => $this->old, 'user_id' => $this->uid ] )
			->caller( __METHOD__ )->execute();
		$dbw->newUpdateQueryBuilder()
			->update( 'actor' )
			->set( [ 'actor_name' => $this->new ] )
			->where( [ 'actor_name' => $this->old, 'actor_user' => $this->uid ] )
			->caller( __METHOD__ )->execute();

		// Reset token to break login with central auth systems.
		// Again, avoids user being logged in with old name.
		$user = $this->userFactory->newFromId( $this->uid );

		$user->load( IDBAccessObject::READ_LATEST );
		SessionManager::singleton()->invalidateSessionsForUser( $user );

		// Purge user cache
		$user->invalidateCache();

		// Update the block_target table rows if this user has a block in there.
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_user_text' => $this->new ] )
			->where( [ 'bt_user' => $this->uid, 'bt_user_text' => $this->old ] )
			->caller( __METHOD__ )->execute();

		// Update this users block/rights log. Ideally, the logs would be historical,
		// but it is really annoying when users have "clean" block logs by virtue of
		// being renamed, which makes admin tasks more of a pain...
		$oldTitle = $this->titleFactory->makeTitle( NS_USER, $this->old );
		$newTitle = $this->titleFactory->makeTitle( NS_USER, $this->new );
		$this->debug( "Updating logging table for {$this->old} to {$this->new}" );

		// Exclude user renames per T200731
		$logTypesOnUser = array_diff( SpecialLog::getLogTypesOnUser(), [ 'renameuser' ] );

		$dbw->newUpdateQueryBuilder()
			->update( 'logging' )
			->set( [ 'log_title' => $newTitle->getDBkey() ] )
			->where( [
				'log_type' => $logTypesOnUser,
				'log_namespace' => NS_USER,
				'log_title' => $oldTitle->getDBkey()
			] )
			->caller( __METHOD__ )->execute();

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

		// Do immediate re-attribution table updates...
		foreach ( $this->tables as $table => $fieldSet ) {
			[ $nameCol, $userCol ] = $fieldSet;
			$dbw->newUpdateQueryBuilder()
				->update( $table )
				->set( [ $nameCol => $this->new ] )
				->where( [ $nameCol => $this->old, $userCol => $this->uid ] )
				->caller( __METHOD__ )->execute();
		}

		/** @var RenameUserJob[] $jobs */
		$jobs = []; // jobs for all tables
		// Construct jobqueue updates...
		// FIXME: if a bureaucrat renames a user in error, he/she
		// must be careful to wait until the rename finishes before
		// renaming back. This is due to the fact the job "queue"
		// is not really FIFO, so we might end up with a bunch of edits
		// randomly mixed between the two new names. Some sort of rename
		// lock might be in order...
		foreach ( $this->tablesJob as $table => $params ) {
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
					$jobs[] = new JobSpecification( 'renameUser', $jobParams, [], $oldTitle );
					$jobParams['minTimestamp'] = '0';
					$jobParams['maxTimestamp'] = '0';
					$jobParams['count'] = 0;
				}
			}
			// If there are any job rows left, add it to the queue as one job
			if ( $jobParams['count'] > 0 ) {
				$jobs[] = new JobSpecification( 'renameUser', $jobParams, [], $oldTitle );
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
			'6::edits' => $contribs
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

		return true;
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
}
