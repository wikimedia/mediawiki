<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup JobQueue
 */
use MediaWiki\MediaWikiServices;

/**
 * Job for pruning recent changes
 *
 * @ingroup JobQueue
 * @since 1.25
 */
class RecentChangesUpdateJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'recentChangesUpdate', $title, $params );

		if ( !isset( $params['type'] ) ) {
			throw new Exception( "Missing 'type' parameter." );
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
		global $wgRCMaxAge, $wgUpdateRowsPerQuery;

		$dbw = wfGetDB( DB_MASTER );
		$lockKey = $dbw->getDomainID() . ':recentchanges-prune';
		if ( !$dbw->lock( $lockKey, __METHOD__, 0 ) ) {
			// already in progress
			return;
		}

		$factory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$ticket = $factory->getEmptyTransactionTicket( __METHOD__ );
		$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
		$rcQuery = RecentChange::getQueryInfo();
		do {
			$rcIds = [];
			$rows = [];
			$res = $dbw->select(
				$rcQuery['tables'],
				$rcQuery['fields'],
				[ 'rc_timestamp < ' . $dbw->addQuotes( $cutoff ) ],
				__METHOD__,
				[ 'LIMIT' => $wgUpdateRowsPerQuery ],
				$rcQuery['joins']
			);
			foreach ( $res as $row ) {
				$rcIds[] = $row->rc_id;
				$rows[] = $row;
			}
			if ( $rcIds ) {
				$dbw->delete( 'recentchanges', [ 'rc_id' => $rcIds ], __METHOD__ );
				Hooks::run( 'RecentChangesPurgeRows', [ $rows ] );
				// There might be more, so try waiting for replica DBs
				if ( !$factory->commitAndWaitForReplication(
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
		global $wgActiveUserDays;

		// Users that made edits at least this many days ago are "active"
		$days = $wgActiveUserDays;
		// Pull in the full window of active users in this update
		$window = $wgActiveUserDays * 86400;

		$dbw = wfGetDB( DB_MASTER );
		$factory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$ticket = $factory->getEmptyTransactionTicket( __METHOD__ );

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
		$cTime = $dbw->selectField( 'querycache_info',
			'qci_timestamp',
			[ 'qci_type' => 'activeusers' ]
		);
		$cTimeUnix = $cTime ? wfTimestamp( TS_UNIX, $cTime ) : 1;

		// Pick the date range to fetch from. This is normally from the last
		// update to till the present time, but has a limited window for sanity.
		// If the window is limited, multiple runs are need to fully populate it.
		$sTimestamp = max( $cTimeUnix, $nowUnix - $days * 86400 );
		$eTimestamp = min( $sTimestamp + $window, $nowUnix );

		// Get all the users active since the last update
		$actorQuery = ActorMigration::newMigration()->getJoin( 'rc_user' );
		$res = $dbw->select(
			[ 'recentchanges' ] + $actorQuery['tables'],
			[
				'rc_user_text' => $actorQuery['fields']['rc_user_text'],
				'lastedittime' => 'MAX(rc_timestamp)'
			],
			[
				$actorQuery['fields']['rc_user'] . ' > 0', // actual accounts
				'rc_type != ' . $dbw->addQuotes( RC_EXTERNAL ), // no wikidata
				'rc_log_type IS NULL OR rc_log_type != ' . $dbw->addQuotes( 'newusers' ),
				'rc_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $sTimestamp ) ),
				'rc_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $eTimestamp ) )
			],
			__METHOD__,
			[
				'GROUP BY' => [ $actorQuery['fields']['rc_user_text'] ],
				'ORDER BY' => 'NULL' // avoid filesort
			],
			$actorQuery['joins']
		);
		$names = [];
		foreach ( $res as $row ) {
			$names[$row->rc_user_text] = $row->lastedittime;
		}

		// Find which of the recently active users are already accounted for
		if ( count( $names ) ) {
			$res = $dbw->select( 'querycachetwo',
				[ 'user_name' => 'qcc_title' ],
				[
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title' => array_keys( $names ),
					'qcc_value >= ' . $dbw->addQuotes( $nowUnix - $days * 86400 ), // TS_UNIX
				 ],
				__METHOD__
			);
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
					'qcc_value' => wfTimestamp( TS_UNIX, $lastEditTime ),
					'qcc_namespacetwo' => 0, // unused
					'qcc_titletwo' => '' // unused
				];
			}
			foreach ( array_chunk( $newRows, 500 ) as $rowBatch ) {
				$dbw->insert( 'querycachetwo', $rowBatch, __METHOD__ );
				$factory->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}

		// If a transaction was already started, it might have an old
		// snapshot, so kludge the timestamp range back as needed.
		$asOfTimestamp = min( $eTimestamp, (int)$dbw->trxTimestamp() );

		// Touch the data freshness timestamp
		$dbw->replace( 'querycache_info',
			[ 'qci_type' ],
			[ 'qci_type' => 'activeusers',
				'qci_timestamp' => $dbw->timestamp( $asOfTimestamp ) ], // not always $now
			__METHOD__
		);

		$dbw->unlock( $lockKey, __METHOD__ );

		// Rotate out users that have not edited in too long (according to old data set)
		$dbw->delete( 'querycachetwo',
			[
				'qcc_type' => 'activeusers',
				'qcc_value < ' . $dbw->addQuotes( $nowUnix - $days * 86400 ) // TS_UNIX
			],
			__METHOD__
		);
	}
}
