<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RCFeed;

use UDPTransport;

/**
 * Send recent change notifications to a destination address over UDP.
 *
 * Parameters:
 * - `formatter`: (Required) Which RCFeedFormatter class to use.
 * - `uri`: (Required) Where to send the messages.
 *
 * @par Example:
 * @code
 * $wgRCFeeds['rc-to-udp'] = [
 *      'class' => 'UDPRCFeedEngine',
 *      'formatter' => 'JSONRCFeedFormatter',
 *      'uri' => 'udp://localhost:1336',
 * ];
 * @endcode
 *
 * @see $wgRCFeeds
 * @since 1.22
 * @ingroup RecentChanges
 */
class UDPRCFeedEngine extends FormattedRCFeed {

	/**
	 * @see FormattedRCFeed::send
	 * @param array $feed
	 * @param string $line
	 * @return bool
	 */
	public function send( array $feed, $line ) {
		$transport = UDPTransport::newFromString( $feed['uri'] );
		$transport->emit( $line );
		return true;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( UDPRCFeedEngine::class, 'UDPRCFeedEngine' );
