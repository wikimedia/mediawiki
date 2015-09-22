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
 * @ingroup auth
 */

namespace MediaWiki\Auth;

use Message;

/**
 * View helper for authentication-related interfaces.
 */
class AuthFrontend {
	/**
	 * Get the name of the user.
	 * @param AuthenticationRequest[] $requests
	 * @return mixed
	 * @throws \Exception
	 */
	public static function getUsernameFromRequests( $requests ) {
		$username = null;
		$otherClass = null;
		foreach ( $requests as $request ) {
			if ( array_key_exists( 'username', $request->getFieldInfo() ) ) {
				if ( $username === null ) {
					$username = $request->username;
					$otherClass = get_class( $request);
				} elseif ( $username !== $request->username ) {
					$requestClass = get_class( $request);
					throw new \LogicException( "conflicting username fields: '{$request->username}' from "
						. "$requestClass::\$username vs. $username from $otherClass::\$username" );
				}
			}
		}
		return $username;
	}

	/**
	 * Merge the output of multiple AuthenticationRequest::getFieldInfo() calls.
	 * @param AuthenticationRequest[]|string[] $requestsOrTypes
	 * @return array
	 * @throws \Exception
	 */
	public static function mergeFieldInfo( array $requestsOrTypes ) {
		$merged = array();
		foreach ( $requestsOrTypes as $type ) {
			$info = call_user_func( array( $type, 'getFieldInfo' ) );
			foreach ( $info as $name => $options ) {
				if ( !array_key_exists( $name, $merged ) ) {
					$merged[$name] = $options;
				} elseif ( $merged['type'] !== $options['type'] ) {
					throw new \LogicException( 'Field name conflict' );
				} else {
					$merged['options'] = array_merge(
						self::getField( $merged, 'options', array() ),
						self::getField( $info, 'options', array() )
					);
					if ( !empty( $merged['optional'] ) || !empty( $info['optional'] ) ) {
						$merged['optional'] = true;
					}
					// there isn't any sensible way to merge descriptions so we just overwrite them
					$merged['label'] = $info['label'];
					$merged['help'] = $info['help'];
				}
			}
		}
		return $merged;
	}

	/**
	 * Turns a field info array into a form descriptor. Behavior can be modified by the
	 * AuthChangeFormField hook.
	 * @param array $fieldInfo Field information, in the format used by
	 *   AuthenticationRequest::getFieldInfo()
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param array $coreFields Form descriptor fragment for fields used by MediaWiki core. Will
	 *   also determine field order (fields not included here will be appended to the end, in the
	 *   order they appear in $fieldInfo), which can be overriden via the weights.
	 * @return array A form descriptor that can be passed to HTMLForm
	 * @throws \LogicException
	 */
	public static function fieldInfoToFormDescriptor(
		array $fieldInfo, $action, array $coreFields = array()
	) {
		$formDescriptor = $coreFields;
		foreach ( $fieldInfo as $fieldName => $singleFieldInfo ) {
			$infoDescriptor = self::mapSingleFieldInfo( $singleFieldInfo );
			if ( !isset( $formDescriptor[$fieldName] ) ) {
				$formDescriptor[$fieldName] = array();
			}

			// labels should be defined by the auth request but
			// core messages should always take priority
			if (
				isset( $formDescriptor[$fieldName]['label'] )
				|| isset( $formDescriptor[$fieldName]['label-message'] )
			) {
				unset( $singleFieldInfo['label'], $singleFieldInfo['label-message'] );
			}

			// require should work either way
			if ( !empty( $formDescriptor[$fieldName]['required'] ) || !empty( $singleFieldInfo['required'] ) ) {
				$formDescriptor[$fieldName]['required'] = true;
			}
			// FIXME figure this out later. If the user can choose between fulfilling multiple
			// AuthRequests, they can't have required fields; should required be interpreted as
			// "required if any other fields of this request are non-empty"?
			// FIXME there is no visible error message when a required field is missing
			$formDescriptor[$fieldName]['required'] = false;

			// TODO allow overriding option labels but don't allow overriding options?

			// for all else, core settings should take priority
			$formDescriptor[$fieldName] += self::mapSingleFieldInfo( $singleFieldInfo );

			\Hooks::run( 'AuthChangeFormField', array( $fieldName, $singleFieldInfo,
				&$formDescriptor[$fieldName], $action ) );
		}

		// Process the special 'weight' property, which is a way for AuthChangeFormField hook
		// subscribers (who only see one field at a time) to influence ordering.
		self::sortFields( $formDescriptor );

		return $formDescriptor;
	}

	/**
	 * Sort the fields of a form descriptor by their 'weight' property. (Fields with higher weight
	 * are shown closer to the bottom; weight defaults to 0. Negative weight is allowed.)
	 * Keep order if weights are equal.
	 * @param array $formDescriptor
	 * @return array
	 */
	protected static function sortFields( array &$formDescriptor ) {
		$i = 0;
		foreach ( $formDescriptor as &$field ) {
			$field['__index'] = $i++;
		}
		uasort( $formDescriptor, function ( $first, $second ) {
			return self::getField( $first, 'weight', 0 ) - self::getField( $second, 'weight', 0 )
				?: $first['__index'] - $second['__index'];
		} );
		foreach ( $formDescriptor as &$field ) {
			unset( $field['__index'] );
		}
	}

	/**
	 * Maps an authentication field configuration for a single field (as returned by
	 * AuthenticationRequest::getFieldInfo()) to a HTMLForm field descriptor.
	 * @param array $singleFieldInfo
	 * @return array
	 */
	protected static function mapSingleFieldInfo( $singleFieldInfo ) {
		$type = self::mapType( $singleFieldInfo['type'] );
		$descriptor = array( 'type' => $type );

		if ( $type === 'submit' && isset( $singleFieldInfo['label'] ) ) {
			$descriptor['default'] = wfMessage( $singleFieldInfo['label'] );
		} elseif ( $type !== 'submit' ) {
			$descriptor += array_filter( array(
				// help-message is omitted as it is usually not really useful for a web interface
				'label-message' => self::getField( $singleFieldInfo, 'label' ),
				'required' => !self::getField( $singleFieldInfo, 'optional' ),
			) );

			if ( isset( $singleFieldInfo['options'] ) ) {
				$descriptor['options'] = array_flip( array_map( function ( $message ) {
					/** @var $message Message */
					return $message->parse();
				}, $singleFieldInfo['options'] ) );
			}
		}

		return $descriptor;
	}

	/**
	 * Maps AuthenticationRequest::getFieldInfo() types to HTMLForm types
	 * @param string $type
	 * @return string
	 * @throws \LogicException
	 */
	protected static function mapType( $type ) {
		$map = array(
			'string' => 'text',
			'password' => 'password',
			'select' => 'select',
			'checkbox' => 'check',
			'multiselect' => 'multiselect',
			'button' => 'submit',
			'null' => 'info',
		);
		if ( !array_key_exists( $type, $map ) ) {
			throw new \LogicException( 'invalid field type: ' . $type );
		}
		return $map[$type];
	}

	/**
	 * Get an array value, or a default if it does not exist.
	 * @param array $array
	 * @param string $fieldName
	 * @param mixed $default
	 * @return mixed
	 */
	private static function getField( array $array, $fieldName, $default = null ) {
		if ( array_key_exists( $fieldName, $array ) ) {
			return $array[$fieldName];
		} else {
			return $default;
		}
	}
}
