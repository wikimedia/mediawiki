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
	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {
	}

	/**
	 * @param string $channel
	 * @param array $event Event data map
	 * @return bool Success
	 */
	final public function notify( $channel, $event ) {
		return $this->doNotify( $channel, array( $event ) );
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	final public function notifyMulti( $channel, $events ) {
		return $this->doNotify( $channel, $events );
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	abstract protected function doNotify( $channel, array $events );
}

/**
 * No-op class for publishing messages into a PubSub system
 */
class EventRelayerNull extends EventRelayer {
	public function doNotify( $channel, array $events ) {
		return true;
	}
}
