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
 * Base class for publishing messages into a PubSub system
 */
abstract class PubSubPublisher {
	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {}

	/**
	 * @param string $channel
	 * @param string $msg
	 * @throws PubSubException
	 */
	abstract public function publish( $channel, $msg );
}

/**
 * No-op class for publishing messages into a PubSub system
 */
class NullPubSubPublisher extends PubSubPublisher {
	public function publish( $channel, $msg ) {
	}
}

class PubSubException extends Exception {}
