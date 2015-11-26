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
 * Job for updating user activity like "last viewed" timestamps
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
		} else {
			throw new Exception( "Invalid 'type' parameter '{$this->params['type']}'." );
		}

		return true;
	}

	protected function updateWatchlistNotification() {
		$casTimestamp = ( $this->params['notifTime'] !== null )
			? $this->params['notifTime']
			: $this->params['curTime'];

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist',
			array(
				'wl_notificationtimestamp' => $dbw->timestampOrNull( $this->params['notifTime'] )
			),
			array(
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
			),
			__METHOD__
		);
	}
}
