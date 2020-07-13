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
 * Extension of Message implementing IApiMessage
 * @newable
 * @since 1.25
 * @ingroup API
 */
class ApiMessage extends Message implements IApiMessage {
	use ApiMessageTrait;

	/**
	 * Create an IApiMessage for the message
	 *
	 * This returns $msg if it's an IApiMessage, calls 'new ApiRawMessage' if
	 * $msg is a RawMessage, or calls 'new ApiMessage' in all other cases.
	 *
	 * @stable to call
	 * @param Message|RawMessage|array|string $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @return IApiMessage
	 */
	public static function create( $msg, $code = null, array $data = null ) {
		if ( is_array( $msg ) ) {
			// From StatusValue
			if ( isset( $msg['message'] ) ) {
				if ( isset( $msg['params'] ) ) {
					$msg = array_merge( [ $msg['message'] ], $msg['params'] );
				} else {
					$msg = [ $msg['message'] ];
				}
			}

			// Weirdness that comes in sometimes, including the above
			if ( $msg[0] instanceof MessageSpecifier ) {
				$msg = $msg[0];
			}
		}

		if ( $msg instanceof IApiMessage ) {
			return $msg;
		} elseif ( $msg instanceof RawMessage ) {
			return new ApiRawMessage( $msg, $code, $data );
		} else {
			return new ApiMessage( $msg, $code, $data );
		}
	}

	/**
	 * @param Message|string|array $msg
	 *  - Message: is cloned
	 *  - array: first element is $key, rest are $params to Message::__construct
	 *  - string: passed to Message::__construct
	 * @param string|null $code
	 * @param array|null $data
	 */
	public function __construct( $msg, $code = null, array $data = null ) {
		if ( $msg instanceof Message ) {
			foreach ( get_class_vars( get_class( $this ) ) as $key => $value ) {
				if ( isset( $msg->$key ) ) {
					$this->$key = $msg->$key;
				}
			}
		} elseif ( is_array( $msg ) ) {
			$key = array_shift( $msg );
			parent::__construct( $key, $msg );
		} else {
			parent::__construct( $msg );
		}
		$this->setApiCode( $code, $data );
	}
}
