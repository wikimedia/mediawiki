<?php
class ZeroMQFeedEngine implements RCFeedEngine {
	/**
	 * Sends the notification to the specified host via ZeroMQ
	 * Requires the ZeroMQ PHP extension to be installed, which
	 * can be obtained from https://github.com/mkoppanen/php-zmq
	 *
	 * If the feed URI contains a path component, it will be used to generate a
	 * channel name by stripping the leading slash and replacing any remaining
	 * slashes with '.'. If no path component is present, the channel is set to
	 * 'rc'.
	 *
	 * @example $wgRCFeeds['zmq'] = array(
	 *      'formatter' => 'JSONRCFeedFormatter',
	 *      'uri'       => "zmq://127.0.0.1:6379/rc.$wgDBname",
	 * );
	 *
	 * @since 1.23
	 */
	public function send( array $feed, $line ) {
		if ( !extension_loaded( 'zmq' ) ) {
			throw new MWException( 'ZeroMQ extension not installed' );
		}

		// We want to reuse the socket if possible,
		// so use the same context
		static $context = false;
		if ( !$context ) {
			$context = new ZMQContext();
		}

		$parsed = parse_url( $feed['uri'] );
		$name = isset( $parsed['path'] ) ? str_replace( '/', '.', ltrim( $parsed['path'], '/' ) ) : 'rc';

		$queue = new ZMQSocket( $context, ZMQ::SOCKET_PUB, $name );

		// This should be equal to the 'uri', except with "tcp://" in front
		$endpoint = "tcp://{$parsed['host']}:{$parsed['port']}";

		$queue->connect( $endpoint );
		$queue->send( $line );
	}
}
