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
