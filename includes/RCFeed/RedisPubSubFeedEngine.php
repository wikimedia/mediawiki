<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RCFeed;

use Wikimedia\ObjectCache\RedisConnectionPool;

/**
 * Send recent change to a Redis Pub/Sub channel.
 *
 * If the feed URI contains a path component, it will be used to generate a
 * channel name by stripping the leading slash and replacing any remaining
 * slashes with '.'. If no path component is present, the channel is set to
 * 'rc'. If the URI contains a query string, its parameters will be parsed
 * as RedisConnectionPool options.
 *
 * Parameters:
 * - `formatter`: (Required) Which RCFeedFormatter class to use.
 * - `uri`: (Required) Where to send the messages.
 *
 * @par Example:
 * @code
 * $wgRCFeeds['rc-to-redis'] = [
 *      'class' => 'RedisPubSubFeedEngine',
 *      'formatter' => 'JSONRCFeedFormatter',
 *      'uri' => "redis://127.0.0.1:6379/rc.$wgDBname",
 * ];
 * @endcode
 *
 * @see $wgRCFeeds
 * @since 1.22
 * @ingroup RecentChanges
 */
class RedisPubSubFeedEngine extends FormattedRCFeed {

	/**
	 * @see FormattedRCFeed::send
	 * @param array $feed
	 * @param string $line
	 * @return bool
	 */
	public function send( array $feed, $line ) {
		$parsed = wfGetUrlUtils()->parse( $feed['uri'] );
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable Valid URL
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
		}

		return false;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( RedisPubSubFeedEngine::class, 'RedisPubSubFeedEngine' );
