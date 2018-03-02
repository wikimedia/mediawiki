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
 * Job for updating user activity like "last viewed" timestamps
 *
 * Job parameters include:
 *   - type: one of (updateWatchlistNotification, resetWatchlistAllNotifications)
 *   - userid: affected user ID
 *   - notifTime: timestamp to set watchlist entries to
 *   - curTime: UNIX timestamp of the event that triggered this job
 *
 * @ingroup JobQueue
 * @since 1.26
 */
class ActivityUpdateJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'activityUpdateJob', $title, $params );

		if ( !isset( $params['type'] ) ) {
			throw new InvalidArgumentException( "Missing 'type' parameter." );
		}

		$this->removeDuplicates = true;
	}

	public function run() {
		if ( $this->params['type'] === 'updateWatchlistNotification' ) {
			$this->updateWatchlistNotification();
		} elseif ( $this->params['type'] === 'resetWatchlistAllNotifications' ) {
			$this->resetWatchlistAllNotifications();
		} else {
			throw new InvalidArgumentException(
				"Invalid 'type' parameter '{$this->params['type']}'." );
		}

		return true;
	}

	protected function updateWatchlistNotification() {
		$casTimestamp = ( $this->params['notifTime'] !== null )
			? $this->params['notifTime']
			: $this->params['curTime'];

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist',
			[
				'wl_notificationtimestamp' => $dbw->timestampOrNull( $this->params['notifTime'] )
			],
			[
				'wl_user' => $this->params['userid'],
				'wl_namespace' => $this->title->getNamespace(),
				'wl_title' => $this->title->getDBkey(),
				// Add a "check and set" style comparison to handle conflicts.
				// The inequality always avoids updates when the current value
				// is already NULL per ANSI SQL. This is desired since NULL means
				// that the user is "caught up" on edits already. When the field
				// is non-NULL, make sure not to set it back in time or set it to
				// NULL when newer revisions were in fact added to the page.
				'wl_notificationtimestamp < ' . $dbw->addQuotes( $dbw->timestamp( $casTimestamp ) )
			],
			__METHOD__
		);
	}

	protected function resetWatchlistAllNotifications() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$rowsPerQuery = $services->getMainConfig()->get( 'UpdateRowsPerQuery' );

		$dbw = wfGetDB( DB_MASTER );
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		$asOfTimes = array_unique( $dbw->selectFieldValues(
			'watchlist',
			'wl_notificationtimestamp',
			[ 'wl_user' => $this->params['userid'], 'wl_notificationtimestamp IS NOT NULL' ],
			__METHOD__,
			[ 'ORDER BY' => 'wl_notificationtimestamp DESC' ]
		) );

		foreach ( array_chunk( $asOfTimes, $rowsPerQuery ) as $asOfTimeBatch ) {
			$dbw->update(
				'watchlist',
				[ 'wl_notificationtimestamp' => null ],
				[
					'wl_user' => $this->params['userid'],
					'wl_notificationtimestamp' => $asOfTimeBatch
				],
				__METHOD__
			);
			$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		}
	}
}
