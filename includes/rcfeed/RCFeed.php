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

/**
 * @see $wgRCFeeds
 * @since 1.29
 */
abstract class RCFeed {
	/**
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
	}

	/**
	 * Dispatch the recent changes notification.
	 *
	 * @param RecentChange $rc
	 * @param string|null $actionComment
	 * @return bool Success
	 */
	abstract public function notify( RecentChange $rc, $actionComment = null );

	/**
	 * @param array $params
	 * @return RCFeed
	 * @throws Exception
	 */
	final public static function factory( array $params ) {
		if ( !isset( $params['class'] ) ) {
			if ( !isset( $params['uri'] ) ) {
				throw new Exception( "RCFeeds must have a 'class' or 'uri' set." );
			}
			return RecentChange::getEngine( $params['uri'], $params );
		}
		$class = $params['class'];
		if ( !class_exists( $class ) ) {
			throw new Exception( "Unknown class '$class'." );
		}
		return new $class( $params );
	}
}
