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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use MessageSpecifier;

/**
 * This is an authentication request that just implements a simple button.
 * @ingroup Auth
 * @since 1.27
 */
class ButtonAuthenticationRequest extends AuthenticationRequest {
	/** @var string */
	protected $name;

	/** @var MessageSpecifier */
	protected $label;

	/**
	 * @param string $name Button name
	 * @param MessageSpecifier $label Button label
	 */
	public function __construct( $name, MessageSpecifier $label ) {
		$this->name = $name;
		$this->label = $label;
	}

	public function getUniqueId() {
		return parent::getUniqueId() . ':' . $this->name;
	}

	public function getFieldInfo() {
		return array(
			$this->name => array(
				'type' => 'button',
				'label' => $this->label,
			)
		);
	}

	/**
	 * Fetch a ButtonAuthenticationRequest or subclass by name
	 * @param AuthenticationRequest[] $reqs Requests to search
	 * @param string $name Name to look for
	 * @return ButtonAuthenticationRequest|null Returns null if there is not
	 *  exactly one matching request.
	 */
	public static function getRequestByName( array $reqs, $name ) {
		$requests = array_filter( $reqs, function ( $req ) use ( $name ) {
			return $req instanceof ButtonAuthenticationRequest && $req->name === $name;
		} );
		return count( $requests ) === 1 ? reset( $requests ) : null;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function __set_state( $data ) {
		if ( !isset( $data['label'] ) ) {
			$data['label'] = new \RawMessage( '$1', $data['name'] );
		} elseif ( is_string( $data['label'] ) ) {
			$data['label'] = new \Message( $data['label'] );
		} elseif ( is_array( $data['label'] ) ) {
			$data['label'] = call_user_func_array( 'Message::newFromKey', $data['label'] );
		}
		$ret = new static( $data['name'], $data['label'] );
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}
}
