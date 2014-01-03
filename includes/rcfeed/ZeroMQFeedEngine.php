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
class ZeroMQFeedEngine implements RCFeedEngine {

	/**
	 * @see RCFeedEngine::send
	 * @param array $feed
	 * @param string $line
	 * @return bool
	 * @throws MWException
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

		try {
			$queue->send( $line );
			return true;
		} catch ( ZMQSocketException $e ) {
			wfDebug( __METHOD__ . ": sending via ZeroMQ failed: {$e->getMessage()}" );
			return false;
		}
	}
}
