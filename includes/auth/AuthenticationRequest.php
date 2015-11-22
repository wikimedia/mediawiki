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

	/** @var string|null The AuthManager::ACTION_* constant this request was
	 * created to be used for. The *_CONTINUE constants are not used here, the
	 * corresponding "begin" constant is used instead.
	 */
	public $action = null;

	/** @var string|null Return-to URL, in case of redirect */
	public $returnToUrl = null;

	/** @var string|null Username. May not be used by all subclasses. */
	public $username = null;

	/**
	 * Supply a unique key for deduplication
	 *
	 * When the AuthenticationRequests instances returned by the providers are
	 * merged, the value returned here is used for keeping only one copy of
	 * duplicate requests.
	 *
	 * Subclasses should override this if multiple distinct instances would
	 * make sense, i.e. the request class has internal state of some sort.
	 *
	 * This value might be exposed to the user in web forms so it should not
	 * contain private information.
	 *
	 * @return string
	 */
	public function getUniqueId() {
		return get_called_class();
	}

	/**
	 * Fetch input field info
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
	 *  - options: (array) Maps option values to Messages for the
	 *      'select' and 'multiselect' types.
	 *  - image: (string) URL of an image to use in connection with the input
	 *  - label: (Message) Text suitable for a label in an HTML form
	 *  - help: (Message) Text suitable as a description of what the field is
	 *  - optional: (bool) If set and truthy, the field may be left empty
	 *
	 * @return array As above
	 */
	abstract public function getFieldInfo();

	/**
	 * Initialize form submitted form data.
	 *
	 * Should always return false if self::getFieldInfo() returns an empty
	 * array.
	 *
	 * @param array $data Submitted data as an associative array
	 * @return bool Whether the request data was successfully loaded
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
	 * Describe the credentials represented by this request
	 *
	 * This is used on requests returned by
	 * AuthenticationProvider::getAuthenticationRequests() for ACTION_LINK
	 * and ACTION_REMOVE and for requests returned in
	 * AuthenticationResponse::$linkRequest to create useful user interfaces.
	 *
	 * @return array with the following keys:
	 *  - provider: A Message identifying the service that provides
	 *    the credentials, e.g. the name of the third party authentication
	 *    service.
	 *  - account: A Message identifying the credentials themselves,
	 *    e.g. the email address used with the third party authentication
	 *    service.
	 */
	public function describeCredentials() {
		return array(
			'provider' => new \RawMessage( '$1', array( get_called_class() ) ),
			'account' => new \RawMessage( '$1', array( $this->getUniqueId() ) ),
		);
	}

	/**
	 * Update a set of requests with form submit data, discarding ones that fail
	 * @param AuthenticationRequest[] $reqs
	 * @param array $data
	 * @return AuthenticationRequest[]
	 */
	public static function loadRequestsFromSubmission( array $reqs, array $data ) {
		return array_values( array_filter( $reqs, function ( $req ) use ( $data ) {
			return $req->loadFromSubmission( $data );
		} ) );
	}

	/**
	 * Select a request by class name.
	 * @param AuthenticationRequest[] $reqs
	 * @param string $class Class name
	 * @return AuthenticationRequest|null Returns null if there is not exactly
	 *  one matching request.
	 */
	public static function getRequestByClass( array $reqs, $class ) {
		$requests = array_filter( $reqs, function ( $req ) use ( $class ) {
			return get_class( $req ) === $class;
		} );
		return count( $requests ) === 1 ? reset( $requests ) : null;
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
