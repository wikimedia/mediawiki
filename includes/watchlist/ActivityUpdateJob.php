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
use MediaWiki\JobQueue\Job;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;

/**
 * Job for updating user activity like "last viewed" timestamps
 *
 * Job parameters include:
 *   - type: one of (updateWatchlistNotification) [required]
 *   - userid: affected user ID [required]
 *   - notifTime: timestamp to set watchlist entries to [required]
 *   - curTime: UNIX timestamp of the event that triggered this job [required]
 *
 * @since 1.26
 * @ingroup JobQueue
 */
class ActivityUpdateJob extends Job {
	/**
	 * @param LinkTarget|PageReference $title
	 * @param array $params
	 */
	public function __construct( $title, array $params ) {
		// If we know its a PageReference, we could just pass that to the parent
		// constructor, but its simpler to just extract namespace and dbkey, and
		// that works for both LinkTarget and PageReference
		$params['namespace'] = $title->getNamespace();
		$params['title'] = $title->getDBkey();

		parent::__construct( 'activityUpdateJob', $params );

		static $required = [ 'type', 'userid', 'notifTime', 'curTime' ];
		$missing = implode( ', ', array_diff( $required, array_keys( $this->params ) ) );
		if ( $missing != '' ) {
			throw new InvalidArgumentException( "Missing parameter(s) $missing" );
		}

		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		if ( $this->params['type'] === 'updateWatchlistNotification' ) {
			$this->updateWatchlistNotification();
		} else {
			throw new InvalidArgumentException( "Invalid 'type' '{$this->params['type']}'." );
		}

		return true;
	}

	protected function updateWatchlistNotification() {
		$casTimestamp = $this->params['notifTime'] ?? $this->params['curTime'];

		// TODO: Inject
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		// Add a "check and set" style comparison to handle conflicts.
		// The inequality always avoids updates when the current value
		// is already NULL per ANSI SQL. This is desired since NULL means
		// that the user is "caught up" on edits already. When the field
		// is non-NULL, make sure not to set it back in time or set it to
		// NULL when newer revisions were in fact added to the page.
		$casTimeCond = $dbw->expr( 'wl_notificationtimestamp', '<', $dbw->timestamp( $casTimestamp ) );

		// select primary key first instead of directly update to avoid deadlocks per T204561
		$wlId = $dbw->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [
				'wl_user' => $this->params['userid'],
				'wl_namespace' => $this->title->getNamespace(),
				'wl_title' => $this->title->getDBkey(),
				$casTimeCond
			] )->caller( __METHOD__ )->fetchField();

		if ( !$wlId ) {
			return;
		}
		$dbw->newUpdateQueryBuilder()
			->update( 'watchlist' )
			->set( [ 'wl_notificationtimestamp' => $dbw->timestampOrNull( $this->params['notifTime'] ) ] )
			->where( [ 'wl_id' => (int)$wlId, $casTimeCond ] )
			->caller( __METHOD__ )->execute();
	}
}
/** @deprecated class alias since 1.43 */
class_alias( ActivityUpdateJob::class, 'ActivityUpdateJob' );
