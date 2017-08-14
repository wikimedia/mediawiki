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
 * Variant of the Message class.
 *
 * Rather than treating the message key as a lookup
 * value (which is passed to the MessageCache and
 * translated as necessary), a RawMessage key is
 * treated as the actual message.
 *
 * All other functionality (parsing, escaping, etc.)
 * is preserved.
 *
 * @since 1.21
 */
class RawMessage extends Message {

	/**
	 * Call the parent constructor, then store the key as
	 * the message.
	 *
	 * @see Message::__construct
	 *
	 * @param string $text Message to use.
	 * @param array $params Parameters for the message.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $text, $params = [] ) {
		if ( !is_string( $text ) ) {
			throw new InvalidArgumentException( '$text must be a string' );
		}

		parent::__construct( $text, $params );

		// The key is the message.
		$this->message = $text;
	}

	/**
	 * Fetch the message (in this case, the key).
	 *
	 * @return string
	 */
	public function fetchMessage() {
		// Just in case the message is unset somewhere.
		if ( $this->message === null ) {
			$this->message = $this->key;
		}

		return $this->message;
	}

}
