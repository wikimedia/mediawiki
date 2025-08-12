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

namespace MediaWiki\Auth;

use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use UnexpectedValueException;

/**
 * This is a value object for authentication requests.
 *
 * An AuthenticationRequest represents a set of form fields that are needed on
 * and provided from a login, account creation, password change or similar form. Form fields can
 * be shared by multiple AuthenticationRequests (see {@see ::mergeFieldInfo()}).
 *
 * Authentication providers that expect user input need to implement one or more subclasses
 * of this class and return them from AuthenticationProvider::getAuthenticationRequests().
 * A typical subclass would override getFieldInfo() and set $required.
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AuthenticationRequest {

	/** Indicates that the request is not required for authentication to proceed. */
	public const OPTIONAL = 0;

	/**
	 * Indicates that the request is required for authentication to proceed.
	 * This will only be used for UI purposes; it is the authentication providers'
	 * responsibility to verify that all required requests are present.
	 */
	public const REQUIRED = 1;

	/**
	 * Indicates that the request is required by a primary authentication
	 * provider. Since the user can choose which primary to authenticate with,
	 * the request might or might not end up being actually required.
	 */
	public const PRIMARY_REQUIRED = 2;

	/**
	 * The AuthManager::ACTION_* constant this request was created to be used for.
	 * Usually set by AuthManager. The *_CONTINUE constants are not used here,
	 * the corresponding "begin" constant is used instead.
	 *
	 * @var string|null
	 */
	public $action = null;

	/**
	 * Whether the authentication request is required (for login, continue, and link
	 * actions). Setting this to optional is roughly equivalent to setting the 'optional' flag for
	 * all fields in the field info.
	 *
	 * Set this to self::OPTIONAL or self::REQUIRED. When coming from a primary provider,
	 * self::REQUIRED will be automatically modified to self::PRIMARY_REQUIRED.
	 *
	 * @var int
	 */
	public $required = self::REQUIRED;

	/**
	 * Return-to URL, in case of a REDIRECT AuthenticationResponse. Set by AuthManager.
	 * @var string|null
	 */
	public $returnToUrl = null;

	/**
	 * Username. Usually set by AuthManager. See AuthenticationProvider::getAuthenticationRequests()
	 * for details of what this means and how it behaves.
	 *
	 * Often this doubles as a normal field (ie. getFieldInfo() has a 'username' key).
	 *
	 * @var string|null
	 */
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
	 * @stable to override
	 * @return string
	 */
	public function getUniqueId() {
		return get_called_class();
	}

	/**
	 * Fetch input field info. This will be used in the AuthManager APIs and web UIs to define
	 * API input parameters / form fields and to process the submitted data.
	 *
	 * The field info is an associative array mapping field names to info
	 * arrays. The info arrays have the following keys:
	 *  - type: (string) Type of input. Types and equivalent HTML widgets are:
	 *     - string: <input type="text">
	 *     - password: <input type="password">
	 *     - select: <select>
	 *     - checkbox: <input type="checkbox">
	 *     - multiselect: More a grid of checkboxes than <select multi>
	 *     - button: <input type="submit"> (uses 'label' as button text)
	 *     - hidden: Not visible to the user, but needs to be preserved for the next request
	 *     - null: No widget, just display the 'label' message.
	 *  - options: (array) Maps option values to Messages for the
	 *      'select' and 'multiselect' types.
	 *  - value: (string) Value (for 'null' and 'hidden') or default value (for other types).
	 *  - label: (Message) Text suitable for a label in an HTML form
	 *  - help: (Message) Text suitable as a description of what the field is. Used in API
	 *      documentation. To add a help text to the web UI, use the AuthChangeFormFields hook.
	 *  - optional: (bool) If set and truthy, the field may be left empty
	 *  - sensitive: (bool) If set and truthy, the field is considered sensitive. Code using the
	 *      request should avoid exposing the value of the field.
	 *  - skippable: (bool) If set and truthy, the client is free to hide this
	 *      field from the user to streamline the workflow. If all fields are
	 *      skippable (except possibly a single button), no user interaction is
	 *      required at all.
	 *
	 * All AuthenticationRequests are populated from the same data, so most of the time you'll
	 * want to prefix fields names with something unique to the extension/provider (although
	 * in some cases sharing the field with other requests is the right thing to do, e.g. for
	 * a 'password' field). When multiple fields have the same name, they will be merged (see
	 * AuthenticationRequests::mergeFieldInfo).
	 * Typically, AuthenticationRequest subclasses define public properties with names matching
	 * the field info keys, and those fields will be populated from the submitted data. More
	 * complex behavior can be implemented by overriding {@see ::loadFromSubmission()}.
	 *
	 * @return array As above
	 * @phan-return array<string,array{type:string,options?:array,value?:string,label:Message,help:Message,optional?:bool,sensitive?:bool,skippable?:bool}>
	 */
	abstract public function getFieldInfo();

	/**
	 * Returns metadata about this request.
	 *
	 * This is mainly for the benefit of API clients which need more detailed render hints
	 * than what's available through getFieldInfo(). Semantics are unspecified and left to the
	 * individual subclasses, but the contents of the array should be primitive types so that they
	 * can be transformed into JSON or similar formats.
	 *
	 * @stable to override
	 * @return array A (possibly nested) array with primitive types
	 */
	public function getMetadata() {
		return [];
	}

	/**
	 * Initialize form submitted form data.
	 *
	 * The default behavior is to check for each key of self::getFieldInfo()
	 * in the submitted data, and copy the value - after type-appropriate transformations -
	 * to $this->$key. Most subclasses won't need to override this; if you do override it,
	 * make sure to always return false if self::getFieldInfo() returns an empty array.
	 *
	 * @stable to override
	 * @param array $data Submitted data as an associative array (keys will correspond
	 *   to getFieldInfo())
	 * @return bool Whether the request data was successfully loaded
	 */
	public function loadFromSubmission( array $data ) {
		$fields = array_filter( $this->getFieldInfo(), static function ( $info ) {
			return $info['type'] !== 'null';
		} );
		if ( !$fields ) {
			return false;
		}

		foreach ( $fields as $field => $info ) {
			// Checkboxes and buttons are special. Depending on the method used
			// to populate $data, they might be unset meaning false or they
			// might be boolean. Further, image buttons might submit the
			// coordinates of the click rather than the expected value.
			if ( $info['type'] === 'checkbox' || $info['type'] === 'button' ) {
				$this->$field = ( isset( $data[$field] ) && $data[$field] !== false )
					|| ( isset( $data["{$field}_x"] ) && $data["{$field}_x"] !== false );
				if ( !$this->$field && empty( $info['optional'] ) ) {
					return false;
				}
				continue;
			}

			// Multiselect are too, slightly
			if ( !isset( $data[$field] ) && $info['type'] === 'multiselect' ) {
				$data[$field] = [];
			}

			if ( !isset( $data[$field] ) ) {
				return false;
			}
			if ( $data[$field] === '' || $data[$field] === [] ) {
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
						// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset required for multiselect
						$allowed = array_keys( $info['options'] );
						if ( array_diff( $data[$field], $allowed ) !== [] ) {
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
	 * @stable to override
	 *
	 * @return Message[] with the following keys:
	 *  - provider: A Message identifying the service that provides
	 *    the credentials, e.g. the name of the third party authentication
	 *    service.
	 *  - account: A Message identifying the credentials themselves,
	 *    e.g. the email address used with the third party authentication
	 *    service.
	 */
	public function describeCredentials() {
		return [
			'provider' => new RawMessage( '$1', [ get_called_class() ] ),
			'account' => new RawMessage( '$1', [ $this->getUniqueId() ] ),
		];
	}

	/**
	 * Update a set of requests with form submit data, discarding ones that fail
	 *
	 * @param AuthenticationRequest[] $reqs
	 * @param array $data
	 * @return AuthenticationRequest[]
	 */
	public static function loadRequestsFromSubmission( array $reqs, array $data ) {
		$result = [];
		foreach ( $reqs as $req ) {
			if ( $req->loadFromSubmission( $data ) ) {
				$result[] = $req;
			}
		}
		return $result;
	}

	/**
	 * Select a request by class name.
	 *
	 * @phan-template T
	 * @param AuthenticationRequest[] $reqs
	 * @param string $class Class name
	 * @phan-param class-string<T> $class
	 * @param bool $allowSubclasses If true, also returns any request that's a subclass of the given
	 *   class.
	 * @return AuthenticationRequest|null Returns null if there is not exactly
	 *  one matching request.
	 * @phan-return T|null
	 */
	public static function getRequestByClass( array $reqs, $class, $allowSubclasses = false ) {
		$requests = array_filter( $reqs, static function ( $req ) use ( $class, $allowSubclasses ) {
			if ( $allowSubclasses ) {
				return is_a( $req, $class, false );
			} else {
				return get_class( $req ) === $class;
			}
		} );
		// @phan-suppress-next-line PhanTypeMismatchReturn False positive
		return count( $requests ) === 1 ? reset( $requests ) : null;
	}

	/**
	 * Get the username from the set of requests
	 *
	 * Only considers requests that have a "username" field.
	 *
	 * @param AuthenticationRequest[] $reqs
	 * @return string|null
	 * @throws UnexpectedValueException If multiple different usernames are present.
	 */
	public static function getUsernameFromRequests( array $reqs ) {
		$username = null;
		$otherClass = null;
		foreach ( $reqs as $req ) {
			$info = $req->getFieldInfo();
			if ( $info && array_key_exists( 'username', $info ) && $req->username !== null ) {
				if ( $username === null ) {
					$username = $req->username;
					$otherClass = get_class( $req );
				} elseif ( $username !== $req->username ) {
					$requestClass = get_class( $req );
					throw new UnexpectedValueException( "Conflicting username fields: \"{$req->username}\" from "
						// @phan-suppress-next-line PhanTypeSuspiciousStringExpression $otherClass always set
						. "$requestClass::\$username vs. \"$username\" from $otherClass::\$username" );
				}
			}
		}
		return $username;
	}

	/**
	 * Merge the output of multiple AuthenticationRequest::getFieldInfo() calls.
	 * @param AuthenticationRequest[] $reqs
	 * @return array Field info in the same format as getFieldInfo().
	 * @throws UnexpectedValueException If the requests include fields with the same name but
	 *   incompatible definitions (e.g. different field types).
	 */
	public static function mergeFieldInfo( array $reqs ) {
		$merged = [];

		// fields that are required by some primary providers but not others are not actually required
		$sharedRequiredPrimaryFields = null;
		foreach ( $reqs as $req ) {
			if ( $req->required !== self::PRIMARY_REQUIRED ) {
				continue;
			}
			$required = [];
			foreach ( $req->getFieldInfo() as $fieldName => $options ) {
				if ( empty( $options['optional'] ) ) {
					$required[] = $fieldName;
				}
			}
			if ( $sharedRequiredPrimaryFields === null ) {
				$sharedRequiredPrimaryFields = $required;
			} else {
				$sharedRequiredPrimaryFields = array_intersect( $sharedRequiredPrimaryFields, $required );
			}
		}

		foreach ( $reqs as $req ) {
			$info = $req->getFieldInfo();
			if ( !$info ) {
				continue;
			}

			foreach ( $info as $name => $options ) {
				if (
					// If the request isn't required, its fields aren't required either.
					$req->required === self::OPTIONAL
					// If there is a primary not requiring this field, no matter how many others do,
					// authentication can proceed without it.
					|| ( $req->required === self::PRIMARY_REQUIRED
						// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal False positive
						&& !in_array( $name, $sharedRequiredPrimaryFields, true ) )
				) {
					$options['optional'] = true;
				} else {
					$options['optional'] = !empty( $options['optional'] );
				}

				$options['sensitive'] = !empty( $options['sensitive'] );
				$type = $options['type'];

				if ( !array_key_exists( $name, $merged ) ) {
					$merged[$name] = $options;
				} elseif ( $merged[$name]['type'] !== $type ) {
					throw new UnexpectedValueException( "Field type conflict for \"$name\", " .
						"\"{$merged[$name]['type']}\" vs \"$type\""
					);
				} else {
					if ( isset( $options['options'] ) ) {
						if ( isset( $merged[$name]['options'] ) ) {
							$merged[$name]['options'] += $options['options'];
						} else {
							// @codeCoverageIgnoreStart
							$merged[$name]['options'] = $options['options'];
							// @codeCoverageIgnoreEnd
						}
					}

					$merged[$name]['optional'] = $merged[$name]['optional'] && $options['optional'];
					$merged[$name]['sensitive'] = $merged[$name]['sensitive'] || $options['sensitive'];

					// No way to merge 'value', 'image', 'help', or 'label', so just use
					// the value from the first request.
				}
			}
		}

		return $merged;
	}

	/**
	 * Implementing this mainly for use from the unit tests.
	 * @param array $data
	 * @return AuthenticationRequest
	 */
	public static function __set_state( $data ) {
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		$ret = new static();
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}
}
