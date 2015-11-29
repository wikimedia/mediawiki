<?php
/**
 * Authentication request value object
 *
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

/**
 * This is a value object for authentication requests.
 *
 * An AuthenticationRequest represents a set of form fields that are needed on
 * and provided from the login, account creation, or password change forms.
 *
 * @ingroup Auth
 * @since 1.27
 */
abstract class AuthenticationRequest {
	/** @var string|null The action type (an AuthManager::ACTION_* constant) for which the request
	 * will be used. *_CONTINUE types are not used here as the same request can be used for
	 * beginning and continuing an authentication process. */
	public $action = null;

	/** @var string|null Return-to URL, in case of redirect */
	public $returnToUrl = null;

	/** @var string|null Username. May not be used by all subclasses. */
	public $username = null;

	/**
	 * Fetch input field info.
	 *
	 * $this->action should be set by the time this is called.
	 *
	 * The field info is an associative array mapping field names to info
	 * arrays. The info arrays have the following keys:
	 *  - type: (string) Type of input. Types and equivalent HTML widgets are:
	 *     - string: <input type="text">
	 *     - password: <input type="password">
	 *     - select: <select>
	 *     - checkbox: <input type="checkbox">
	 *     - multiselect: More a grid of checkboxes than <select multi>
	 *     - button: <input type="image"> if 'image' is set, otherwise <input type="submit">
	 *       (uses 'label' as button text)
	 *     - null: No widget, just display the 'label' message.
	 *  - options: (array) Maps option values to MessageSpecifiers for the
	 *      'select' and 'multiselect' types.
	 *  - image: (string) URL of an image to use in connection with the input
	 *  - label: (MessageSpecifier) Text suitable for a label in an HTML form
	 *  - help: (MessageSpecifier) Text suitable as a description of what the field is
	 *  - optional: (bool) If set and truthy, the field may be left empty
	 *
	 * @note Subclasses must reimplement this static method
	 * @return array As above
	 */
	public function getFieldInfo() {
		throw new \BadMethodCallException( get_called_class() . ' must override getFieldInfo()' );
	}

	/**
	 * Initialize an AuthenticationRequest from submitted data. A false return value means
	 * the data is not applicable and this request should not be used.
	 *
	 * Always fails if self::getFieldInfo() is falsey.
	 *
	 * @param array $data Submitted data as an associative array
	 * @return bool
	 */
	public function loadFromSubmission( array $data ) {
		$fields = $this->getFieldInfo();
		if ( !$fields ) {
			return false;
		}

		foreach ( $fields as $field => $info ) {
			// Checkboxes and buttons are special.
			if ( $info['type'] === 'checkbox' || $info['type'] === 'button' ) {
				$this->$field = isset( $data[$field] ) || isset( $data["{$field}_x"] );
				if ( !$this->$field && empty( $info['optional'] ) ) {
					return false;
				}
				continue;
			}

			// Multiselect are too, slightly
			if ( !isset( $data[$field] ) && $info['type'] === 'multiselect' ) {
				$data[$field] = array();
			}

			if ( !isset( $data[$field] ) ) {
				return false;
			}
			if ( $data[$field] === '' || $data[$field] === array() ) {
				if ( empty( $info['optional'] ) ) {
					return false;
				}
			} else {
				switch ( $info['type'] ) {
					case 'select':
						if ( !isset( $info['options'][$data[$field]] ) ) {
							return false;
						}
						break;

					case 'multiselect':
						$data[$field] = (array)$data[$field];
						$allowed = array_keys( $info['options'] );
						if ( array_diff( $data[$field], $allowed ) !== array() ) {
							return false;
						}
						break;
				}
			}

			$this->$field = $data[$field];
		}
		return true;
	}

	/**
	 * Update a set of requests with form submit data, discard the ones that failed.
	 * @param AuthenticationRequest[] $requests
	 * @param array $data
	 * @return AuthenticationRequest[]
	 */
	public static function loadRequestsFromSubmission( $requests, $data ) {
		return array_values( array_filter( $requests, function ( $req ) use ( $data ) {
			return $req->loadFromSubmission( $data );
		} ) );
	}

	/**
	 * Select from a list of requests the one that has the right class. Returns null if there are
	 * no or multiple such requests.
	 * @param AuthenticationRequest[] $requests
	 * @param string $class
	 * @return AuthenticationRequest|null
	 */
	public static function getRequestByClass( array $requests, $class ) {
		$requests = array_values( array_filter( $requests, function ( $item ) use ( $class ) {
			return get_class( $item ) === $class;
		} ) );
		return ( count( $requests ) === 1 ) ? $requests[0] : null;
	}

	/**
	 * Implementing this mainly for use from the unit tests.
	 * @param array $data
	 * @return AuthenticationRequest
	 */
	public static function __set_state( $data ) {
		$ret = new static();
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}

}
