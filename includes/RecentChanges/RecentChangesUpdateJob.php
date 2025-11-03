<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\Job;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Purge expired rows from the recentchanges table.
 *
 * @since 1.25
 * @ingroup RecentChanges
 * @ingroup JobQueue
 */
class RecentChangesUpdateJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'recentChangesUpdate', $title, $params );

		if ( !isset( $params['type'] ) ) {
			throw new InvalidArgumentException( "Missing 'type' parameter." );
		}

		$this->executionFlags |= self::JOB_NO_EXPLICIT_TRX_ROUND;
		$this->removeDuplicates = true;
	}

	/**
	 * @return RecentChangesUpdateJob
	 */
	final public static function newPurgeJob() {
		return new self(
			SpecialPage::getTitleFor( 'Recentchanges' ), [ 'type' => 'purge' ]
		);
	}

	/**
	 * @return RecentChangesUpdateJob
	 * @since 1.26
	 */
	final public static function newCacheUpdateJob() {
		return new self(
			SpecialPage::getTitleFor( 'Recentchanges' ), [ 'type' => 'cacheUpdate' ]
		);
	}

	/** @inheritDoc */
	public function run() {
		if ( $this->params['type'] === 'purge' ) {
			$this->purgeExpiredRows();
		} elseif ( $this->params['type'] === 'cacheUpdate' ) {
			$this->updateActiveUsers();
		} else {
			throw new InvalidArgumentException(
				"Invalid 'type' parameter '{$this->params['type']}'." );
		}

		return true;
	}

	protected function purgeExpiredRows() {
		$services = MediaWikiServices::getInstance();
		$rcMaxAge = $services->getMainConfig()->get(
			MainConfigNames::RCMaxAge );
		$updateRowsPerQuery = $services->getMainConfig()->get(
			MainConfigNames::UpdateRowsPerQuery );
		$dbProvider = $services->getConnectionProvider();
		$dbw = $dbProvider->getPrimaryDatabase();
		$lockKey = $dbw->getDomainID() . ':recentchanges-prune';
		if ( !$dbw->lock( $lockKey, __METHOD__, 0 ) ) {
			// already in progress
			return;
		}
		$ticket = $dbProvider->getEmptyTransactionTicket( __METHOD__ );
		$hookContainer = $services->getHookContainer();
		$hookRunner = new HookRunner( $hookContainer );
		$cutoff = $dbw->timestamp( ConvertibleTimestamp::time() - $rcMaxAge );
		$hasLegacyHook = $hookContainer->isRegistered( 'RecentChangesPurgeRows' );
		if ( $hasLegacyHook ) {
			$query = $dbw->newSelectQueryBuilder()
				->queryInfo( RecentChange::getQueryInfo( RecentChange::STRAIGHT_JOIN_ACTOR ) )
				->where( $dbw->expr( 'rc_timestamp', '<', $cutoff ) )
				->limit( $updateRowsPerQuery )
				->caller( __METHOD__ );
		} else {
			$query = $dbw->newSelectQueryBuilder()
				->select( 'rc_id' )
				->from( 'recentchanges' )
				->where( $dbw->expr( 'rc_timestamp', '<', $cutoff ) )
				->limit( $updateRowsPerQuery )
				->caller( __METHOD__ );
		}
		$callbacks = [];
		$hookRunner->onRecentChangesPurgeQuery( $query, $callbacks );
		do {
			$res = $query->fetchResultSet();
			$rcIds = [];
			if ( $res->numRows() ) {
				$rows = [];
				foreach ( $res as $row ) {
					$rcIds[] = $row->rc_id;
					if ( $hasLegacyHook ) {
						$rows[] = $row;
					}
				}

				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'recentchanges' )
					->where( [ 'rc_id' => $rcIds ] )
					->caller( __METHOD__ )->execute();

				foreach ( $callbacks as $callback ) {
					$callback( $res );
				}
				if ( $hasLegacyHook ) {
					$hookRunner->onRecentChangesPurgeRows( $rows );
				}
				// There might be more, so try waiting for replica DBs
				if ( !$dbProvider->commitAndWaitForReplication(
					__METHOD__, $ticket, [ 'timeout' => 3 ]
				) ) {
					// Another job will continue anyway
					break;
				}
			}
		} while ( $rcIds );

		$dbw->unlock( $lockKey, __METHOD__ );
	}

	protected function updateActiveUsers() {
		$services = MediaWikiServices::getInstance();
		$activeUserDays = $services->getMainConfig()->get(
			MainConfigNames::ActiveUserDays );

		// Users that made edits at least this many days ago are "active"
		$days = $activeUserDays;
		// Pull in the full window of active users in this update
		$window = $activeUserDays * 86400;

		$rcLookup = $services->getRecentChangeLookup();
		$dbProvider = $services->getConnectionProvider();
		$dbw = $dbProvider->getPrimaryDatabase();
		$ticket = $dbProvider->getEmptyTransactionTicket( __METHOD__ );

		$lockKey = $dbw->getDomainID() . '-activeusers';
		if ( !$dbw->lock( $lockKey, __METHOD__, 0 ) ) {
			// Exclusive update (avoids duplicate entries)… it's usually fine to just
			// drop out here, if the Job is already running.
			return;
		}

		// Long-running queries expected
		$dbw->setSessionOptions( [ 'connTimeout' => 900 ] );

		$nowUnix = time();
		// Get the last-updated timestamp for the cache
		$cTime = $dbw->newSelectQueryBuilder()
			->select( 'qci_timestamp' )
			->from( 'querycache_info' )
			->where( [ 'qci_type' => 'activeusers' ] )
			->caller( __METHOD__ )->fetchField();
		$cTimeUnix = $cTime ? (int)wfTimestamp( TS_UNIX, $cTime ) : 1;

		// Pick the date range to fetch from. This is normally from the last
		// update to till the present time, but has a limited window.
		// If the window is limited, multiple runs are need to fully populate it.
		$sTimestamp = max( $cTimeUnix, $nowUnix - $days * 86400 );
		$eTimestamp = min( $sTimestamp + $window, $nowUnix );

		// Get all the users active since the last update
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'actor_name', 'lastedittime' => 'MAX(rc_timestamp)' ] )
			->from( 'recentchanges' )
			->join( 'actor', null, 'actor_id=rc_actor' )
			->where( [
				$dbw->expr( 'actor_user', '!=', null ), // actual accounts
				$dbw->expr( 'rc_source', '=', $rcLookup->getPrimarySources() ),
				$dbw->expr( 'rc_log_type', '=', null )->or( 'rc_log_type', '!=', 'newusers' ),
				$dbw->expr( 'rc_timestamp', '>=', $dbw->timestamp( $sTimestamp ) ),
				$dbw->expr( 'rc_timestamp', '<=', $dbw->timestamp( $eTimestamp ) ),
			] )
			->groupBy( 'actor_name' )
			->orderBy( 'NULL' ) // avoid filesort
			->caller( __METHOD__ )->fetchResultSet();

		$names = [];
		foreach ( $res as $row ) {
			$names[$row->actor_name] = $row->lastedittime;
		}

		// Find which of the recently active users are already accounted for
		if ( count( $names ) ) {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'user_name' => 'qcc_title' ] )
				->from( 'querycachetwo' )
				->where( [
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title' => array_map( 'strval', array_keys( $names ) ),
					$dbw->expr( 'qcc_value', '>=', $nowUnix - $days * 86400 ),
				] )
				->caller( __METHOD__ )->fetchResultSet();
			// Note: In order for this to be actually consistent, we would need
			// to update these rows with the new lastedittime.
			foreach ( $res as $row ) {
				unset( $names[$row->user_name] );
			}
		}

		// Insert the users that need to be added to the list
		if ( count( $names ) ) {
			$newRows = [];
			foreach ( $names as $name => $lastEditTime ) {
				$newRows[] = [
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title' => $name,
					'qcc_value' => (int)wfTimestamp( TS_UNIX, $lastEditTime ),
					'qcc_namespacetwo' => 0, // unused
					'qcc_titletwo' => '' // unused
				];
			}
			foreach ( array_chunk( $newRows, 500 ) as $rowBatch ) {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'querycachetwo' )
					->rows( $rowBatch )
					->caller( __METHOD__ )->execute();
				$dbProvider->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}

		// If a transaction was already started, it might have an old
		// snapshot, so kludge the timestamp range back as needed.
		$asOfTimestamp = min( $eTimestamp, (int)$dbw->trxTimestamp() );

		// Touch the data freshness timestamp
		$dbw->newReplaceQueryBuilder()
			->replaceInto( 'querycache_info' )
			->row( [
				'qci_type' => 'activeusers',
				'qci_timestamp' => $dbw->timestamp( $asOfTimestamp ), // not always $now
			] )
			->uniqueIndexFields( [ 'qci_type' ] )
			->caller( __METHOD__ )->execute();

		// Rotate out users that have not edited in too long (according to old data set)
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'querycachetwo' )
			->where( [
				'qcc_type' => 'activeusers',
				$dbw->expr( 'qcc_value', '<', $nowUnix - $days * 86400 ) // TS_UNIX
			] )
			->caller( __METHOD__ )->execute();

		if ( !$services->getMainConfig()->get( MainConfigNames::MiserMode ) ) {
			SiteStatsUpdate::cacheUpdate( $dbw );
		}

		$dbw->unlock( $lockKey, __METHOD__ );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RecentChangesUpdateJob::class, 'RecentChangesUpdateJob' );
