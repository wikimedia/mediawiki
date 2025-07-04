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
 */

namespace MediaWiki\Watchlist;

use InvalidArgumentException;
use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Job for clearing all of the "last viewed" timestamps for a user's watchlist, or setting them all
 * to the same value.
 *
 * Job parameters include:
 *   - userId: affected user ID [required]
 *   - casTime: UNIX timestamp of the event that triggered this job [required]
 *   - timestamp: value to set all of the "last viewed" timestamps to [optional, defaults to null]
 *
 * @since 1.31
 * @ingroup JobQueue
 */
class ClearWatchlistNotificationsJob extends Job implements GenericParameterJob {
	public function __construct( array $params ) {
		parent::__construct( 'clearWatchlistNotifications', $params );

		static $required = [ 'userId', 'casTime' ];
		$missing = implode( ', ', array_diff( $required, array_keys( $this->params ) ) );
		if ( $missing != '' ) {
			throw new InvalidArgumentException( "Missing parameter(s) $missing" );
		}

		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		$services = MediaWikiServices::getInstance();
		$dbProvider = $services->getConnectionProvider();
		$rowsPerQuery = $services->getMainConfig()->get( MainConfigNames::UpdateRowsPerQuery );

		$dbw = $dbProvider->getPrimaryDatabase();
		$ticket = $dbProvider->getEmptyTransactionTicket( __METHOD__ );
		if ( !isset( $this->params['timestamp'] ) ) {
			$timestamp = null;
			$timestampCond = $dbw->expr( 'wl_notificationtimestamp', '!=', null );
		} else {
			$timestamp = $dbw->timestamp( $this->params['timestamp'] );
			$timestampCond = $dbw->expr( 'wl_notificationtimestamp', '!=', $timestamp )
				->or( 'wl_notificationtimestamp', '=', null );
		}
		// New notifications since the reset should not be cleared
		$casTimeCond = $dbw->expr( 'wl_notificationtimestamp', '<', $dbw->timestamp( $this->params['casTime'] ) )
			->or( 'wl_notificationtimestamp', '=', null );

		$firstBatch = true;
		do {
			$idsToUpdate = $dbw->newSelectQueryBuilder()
				->select( 'wl_id' )
				->from( 'watchlist' )
				->where( [ 'wl_user' => $this->params['userId'] ] )
				->andWhere( $timestampCond )
				->andWhere( $casTimeCond )
				->limit( $rowsPerQuery )
				->caller( __METHOD__ )->fetchFieldValues();

			if ( $idsToUpdate ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'watchlist' )
					->set( [ 'wl_notificationtimestamp' => $timestamp ] )
					->where( [ 'wl_id' => $idsToUpdate ] )
					->andWhere( $casTimeCond )
					->caller( __METHOD__ )->execute();
				if ( !$firstBatch ) {
					$dbProvider->commitAndWaitForReplication( __METHOD__, $ticket );
				}
				$firstBatch = false;
			}
		} while ( $idsToUpdate );
		return true;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( ClearWatchlistNotificationsJob::class, 'ClearWatchlistNotificationsJob' );
