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
 * @author Aaron Schulz
 */

/**
 * Base class for reliable event relays
 */
abstract class EventRelayer {
	/** Flag to only use PubSub to push changes **/
	const NOTIFY_UNLOGGED = 1;

	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {}

	/**
	 * @param string $channel
	 * @param string|array $events Message string or list of strings
	 * @param integer $flags Bitfield of EventRelayer::NOTIFY_*
	 * @return bool Success
	 */
	final public function notify( $channel, $events, $flags = 0 ) {
		$events = is_array( $events ) ? $events : array( $events );

		return $this->doNotify( $channel, $events, $flags );
	}

	/**
	 * @param string $channel
	 * @param array $msgs List of message strings
	 * @param integer $flags Bitfield of EventRelayer::NOTIFY_*
	 * @return bool Success
	 */
	abstract protected function doNotify( $channel, array $msgs, $flags );
}

/**
 * No-op class for publishing messages into a PubSub system
 */
class NullEventRelayer extends EventRelayer {
	public function doNotify( $channel, array $msgs, $flags ) {
		return true;
	}
}

class EventRelayerException extends Exception {}
