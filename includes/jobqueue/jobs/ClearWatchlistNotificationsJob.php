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
 * Job for clearing all of the "last viewed" timestamps for a user's watchlist
 *
 * Job parameters include:
 *   - userId: affected user ID [required]
 *   - casTime: UNIX timestamp of the event that triggered this job [required]
 *
 * @ingroup JobQueue
 * @since 1.31
 */
class ClearWatchlistNotificationsJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'clearWatchlistNotifications', $title, $params );

		static $required = [ 'userId', 'casTime' ];
		$missing = implode( ', ', array_diff( $required, array_keys( $this->params ) ) );
		if ( $missing != '' ) {
			throw new InvalidArgumentException( "Missing paramter(s) $missing" );
		}

		$this->removeDuplicates = true;
	}

	public function run() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$rowsPerQuery = $services->getMainConfig()->get( 'UpdateRowsPerQuery' );

		$dbw = $lbFactory->getMainLB()->getConnection( DB_MASTER );
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		$asOfTimes = array_unique( $dbw->selectFieldValues(
			'watchlist',
			'wl_notificationtimestamp',
			[ 'wl_user' => $this->params['userId'], 'wl_notificationtimestamp IS NOT NULL' ],
			__METHOD__,
			[ 'ORDER BY' => 'wl_notificationtimestamp DESC' ]
		) );

		foreach ( array_chunk( $asOfTimes, $rowsPerQuery ) as $asOfTimeBatch ) {
			$dbw->update(
				'watchlist',
				[ 'wl_notificationtimestamp' => null ],
				[
					'wl_user' => $this->params['userId'],
					'wl_notificationtimestamp' => $asOfTimeBatch,
					// New notifications since the reset should not be cleared
					'wl_notificationtimestamp < ' .
						$dbw->addQuotes( $dbw->timestamp( $this->params['casTime'] ) )
				],
				__METHOD__
			);
			$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		}
	}
}
