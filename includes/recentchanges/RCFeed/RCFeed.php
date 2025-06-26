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

namespace MediaWiki\RCFeed;

use InvalidArgumentException;
use MediaWiki\RecentChanges\RecentChange;

/**
 * @see $wgRCFeeds
 * @since 1.29
 * @ingroup RecentChanges
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

	final public static function factory( array $params ): RCFeed {
		if ( !isset( $params['class'] ) ) {
			if ( !isset( $params['uri'] ) ) {
				throw new InvalidArgumentException( 'RCFeeds must have a class set' );
			}
			if ( str_starts_with( $params['uri'], 'udp:' ) ) {
				$params['class'] = UDPRCFeedEngine::class;
			} elseif ( str_starts_with( $params['uri'], 'redis:' ) ) {
				$params['class'] = RedisPubSubFeedEngine::class;
			} else {
				global $wgRCEngines;
				wfDeprecated( '$wgRCFeeds without class', '1.38' );
				$scheme = parse_url( $params['uri'], PHP_URL_SCHEME );
				if ( !$scheme ) {
					throw new InvalidArgumentException( "Invalid RCFeed uri: {$params['uri']}" );
				}
				if ( !isset( $wgRCEngines[$scheme] ) ) {
					throw new InvalidArgumentException( "Unknown RCFeed engine: $scheme" );
				}
				$params['class'] = $wgRCEngines[$scheme];
			}
		}

		$class = $params['class'];
		if ( defined( 'MW_PHPUNIT_TEST' ) && is_object( $class ) ) {
			return $class;
		}
		if ( !class_exists( $class ) ) {
			throw new InvalidArgumentException( "Unknown class '$class'." );
		}
		return new $class( $params );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( RCFeed::class, 'RCFeed' );
