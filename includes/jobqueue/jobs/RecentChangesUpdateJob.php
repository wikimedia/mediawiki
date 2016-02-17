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
 * @author Aaron Schulz
 * @ingroup JobQueue
 */

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
		global $wgRCMaxAge;

		$lockKey = wfWikiID() . ':recentchanges-prune';

		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lockIsFree( $lockKey, __METHOD__ )
			|| !$dbw->lock( $lockKey, __METHOD__, 1 )
		) {
			return; // already in progress
		}

		$batchSize = 100; // avoid slave lag
		$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
		do {
			$rcIds = $dbw->selectFieldValues( 'recentchanges',
				'rc_id',
				[ 'rc_timestamp < ' . $dbw->addQuotes( $cutoff ) ],
				__METHOD__,
				[ 'LIMIT' => $batchSize ]
			);
			if ( $rcIds ) {
				$dbw->delete( 'recentchanges', [ 'rc_id' => $rcIds ], __METHOD__ );
			}
			// Commit in chunks to avoid slave lag
			$dbw->commit( __METHOD__, 'flush' );

			if ( count( $rcIds ) === $batchSize ) {
				// There might be more, so try waiting for slaves
				try {
					wfGetLBFactory()->waitForReplication( [ 'timeout' => 3 ] );
				} catch ( DBReplicationWaitError $e ) {
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
		// JobRunner uses DBO_TRX, but doesn't call begin/commit itself;
		// onTransactionIdle() will run immediately since there is no trx.
		$dbw->onTransactionIdle( function() use ( $dbw, $days, $window ) {
			// Avoid disconnect/ping() cycle that makes locks fall off
			$dbw->setSessionOptions( [ 'connTimeout' => 900 ] );

			$lockKey = wfWikiID() . '-activeusers';
			if ( !$dbw->lock( $lockKey, __METHOD__, 1 ) ) {
				return; // exclusive update (avoids duplicate entries)
			}

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
			$res = $dbw->select(
				[ 'recentchanges' ],
				[ 'rc_user_text', 'lastedittime' => 'MAX(rc_timestamp)' ],
				[
					'rc_user > 0', // actual accounts
					'rc_type != ' . $dbw->addQuotes( RC_EXTERNAL ), // no wikidata
					'rc_log_type IS NULL OR rc_log_type != ' . $dbw->addQuotes( 'newusers' ),
					'rc_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $sTimestamp ) ),
					'rc_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $eTimestamp ) )
				],
				__METHOD__,
				[
					'GROUP BY' => [ 'rc_user_text' ],
					'ORDER BY' => 'NULL' // avoid filesort
				]
			);
			$names = [];
			foreach ( $res as $row ) {
				$names[$row->rc_user_text] = $row->lastedittime;
			}

			// Rotate out users that have not edited in too long (according to old data set)
			$dbw->delete( 'querycachetwo',
				[
					'qcc_type' => 'activeusers',
					'qcc_value < ' . $dbw->addQuotes( $nowUnix - $days * 86400 ) // TS_UNIX
				],
				__METHOD__
			);

			// Find which of the recently active users are already accounted for
			if ( count( $names ) ) {
				$res = $dbw->select( 'querycachetwo',
					[ 'user_name' => 'qcc_title' ],
					[
						'qcc_type' => 'activeusers',
						'qcc_namespace' => NS_USER,
						'qcc_title' => array_keys( $names ) ],
					__METHOD__
				);
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
					wfGetLBFactory()->waitForReplication();
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
		} );
	}
}
