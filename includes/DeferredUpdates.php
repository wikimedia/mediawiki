<?php
/**
 * Interface and manager for deferred updates.
 *
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

/**
 * Interface that deferrable updates should implement. Basically required so we
 * can validate input on DeferredUpdates::addUpdate()
 *
 * @since 1.19
 */
interface DeferrableUpdate {
	/**
	 * Perform the actual work
	 */
	function doUpdate();
}

/**
 * Class for mananging the deferred updates.
 *
 * @since 1.19
 */
class DeferredUpdates {
	/**
	 * Store of updates to be deferred until the end of the request.
	 */
	private static $updates = array();

	/**
	 * Add an update to the deferred list
	 * @param $update DeferrableUpdate Some object that implements doUpdate()
	 */
	public static function addUpdate( DeferrableUpdate $update ) {
		array_push( self::$updates, $update );
	}

	/**
	 * HTMLCacheUpdates are the most common deferred update people use. This
	 * is a shortcut method for that.
	 * @see HTMLCacheUpdate::__construct()
	 * @param $title
	 * @param $table
	 */
	public static function addHTMLCacheUpdate( $title, $table ) {
		self::addUpdate( new HTMLCacheUpdate( $title, $table ) );
	}

	/**
	 * Do any deferred updates and clear the list
	 *
	 * @param $commit String: set to 'commit' to commit after every update to
	 *                prevent lock contention
	 */
	public static function doUpdates( $commit = '' ) {
		global $wgDeferredUpdateList;

		wfProfileIn( __METHOD__ );

		$updates = array_merge( $wgDeferredUpdateList, self::$updates );

		// No need to get master connections in case of empty updates array
		if ( !count( $updates ) ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		$doCommit = $commit == 'commit';
		if ( $doCommit ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		foreach ( $updates as $update ) {
			try {
				$update->doUpdate();

				if ( $doCommit && $dbw->trxLevel() ) {
					$dbw->commit( __METHOD__ );
				}
			} catch ( MWException $e ) {
				// We don't want exceptions thrown during deferred updates to
				// be reported to the user since the output is already sent.
				// Instead we just log them.
				if ( !$e instanceof ErrorPageError ) {
					wfDebugLog( 'exception', $e->getLogMessage() );
				}
			}
		}

		self::clearPendingUpdates();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Clear all pending updates without performing them. Generally, you don't
	 * want or need to call this. Unit tests need it though.
	 */
	public static function clearPendingUpdates() {
		global $wgDeferredUpdateList;
		$wgDeferredUpdateList = self::$updates = array();
	}
}
