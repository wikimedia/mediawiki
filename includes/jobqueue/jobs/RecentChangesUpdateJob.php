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
 */

/**
 * Job for pruning recent changes
 *
 * @ingroup JobQueue
 * @since 1.25
 */
class RecentChangesUpdateJob extends Job {
	function __construct( $title, $params ) {
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
			SpecialPage::getTitleFor( 'Recentchanges' ), array( 'type' => 'purge' )
		);
	}

	public function run() {
		if ( $this->params['type'] === 'purge' ) {
			$this->purgeExpiredRows();
		} else {
			throw new Exception( "Invalid 'type' parameter '{$this->params['type']}'." );
		}

		return true;
	}

	protected function purgeExpiredRows() {
		global $wgRCMaxAge;

		$lockKey = wfWikiID() . ':recentchanges-prune';

		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lock( $lockKey, __METHOD__, 1 ) ) {
			return; // already in progress
		}

		$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
		do {
			$rcIds = $dbw->selectFieldValues( 'recentchanges',
				'rc_id',
				array( 'rc_timestamp < ' . $dbw->addQuotes( $cutoff ) ),
				__METHOD__,
				array( 'LIMIT' => 100 ) // avoid slave lag
			);
			if ( $rcIds ) {
				$dbw->delete( 'recentchanges', array( 'rc_id' => $rcIds ), __METHOD__ );
			}
			// No need for this to be in a transaction.
			$dbw->commit( __METHOD__, 'flush' );

			if ( count( $rcIds ) === 100 ) {
				// There might be more, so try waiting for slaves
				$goOn = wfWaitForSlaves( null, false, false, /* $timeout = */ 3 );
				if ( !$goOn ) {
					// Another job will continue anyway
					break;
				}
			}
		} while ( $rcIds );

		$dbw->unlock( $lockKey, __METHOD__ );
	}
}
