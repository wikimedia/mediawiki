<?php
class RedisPubSubFeedEngine implements RCFeedEngine {
	/**
	 * Emit a recent change notification via Redis Pub/Sub
	 *
	 * If the feed URI contains a path component, it will be used to generate a
	 * channel name by stripping the leading slash and replacing any remaining
	 * slashes with '.'. If no path component is present, the channel is set to
	 * 'rc'. If the URI contains a query string, its parameters will be parsed
	 * as RedisConnectionPool options.
	 *
	 * @example $wgRCFeeds['redis'] = array(
	 *      'formatter' => 'JSONRCFeedFormatter',
	 *      'uri'       => "redis://127.0.0.1:6379/rc.$wgDBname",
	 * );
	 *
	 * @since 1.22
	 */
	public function send( array $feed, $line ) {
		$parsed = parse_url( $feed['uri'] );
		$server = $parsed['host'];
		$options = array( 'serializer' => 'none' );
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
		$conn->publish( $channel, $line );
	}
}
