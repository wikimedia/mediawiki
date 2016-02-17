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
 * Emit a recent change notification via Redis Pub/Sub
 *
 * If the feed URI contains a path component, it will be used to generate a
 * channel name by stripping the leading slash and replacing any remaining
 * slashes with '.'. If no path component is present, the channel is set to
 * 'rc'. If the URI contains a query string, its parameters will be parsed
 * as RedisConnectionPool options.
 *
 * @example
 * $wgRCFeeds['redis'] = array(
 *      'formatter' => 'JSONRCFeedFormatter',
 *      'uri'       => "redis://127.0.0.1:6379/rc.$wgDBname",
 * );
 *
 * @since 1.22
 */
class RedisPubSubFeedEngine implements RCFeedEngine {

	/**
	 * @see RCFeedEngine::send
	 */
	public function send( array $feed, $line ) {
		$parsed = wfParseUrl( $feed['uri'] );
		$server = $parsed['host'];
		$options = [ 'serializer' => 'none' ];
		$channel = 'rc';

		if ( isset( $parsed['port'] ) ) {
			$server .= ":{$parsed['port']}";
		}
		if ( isset( $parsed['query'] ) ) {
			parse_str( $parsed['query'], $options );
		}
		if ( isset( $parsed['pass'] ) ) {
			$options['password'] = $parsed['pass'];
		}
		if ( isset( $parsed['path'] ) ) {
			$channel = str_replace( '/', '.', ltrim( $parsed['path'], '/' ) );
		}
		$pool = RedisConnectionPool::singleton( $options );
		$conn = $pool->getConnection( $server );
		if ( $conn !== false ) {
			$conn->publish( $channel, $line );
			return true;
		} else {
			return false;
		}
	}
}
