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

/**
 * View helper for authentication-related interfaces.
 */
class AuthFrontend {
	/**
	 * Get the name of the user.
	 * @param AuthenticationRequest[] $requests
	 * @return mixed
	 * @throws Exception
	 */
	public static function getUsernameFromRequests( $requests ) {
		$username = null;
		foreach ( $requests as $request ) {
			if ( property_exists( $request, 'username') ) {
				if ( $username === null ) {
					$username = $request->username;
				} elseif ( $username !== $request->username ) {
					$requestClass = get_class( $request);
					throw new LogicException( "conflicting username fields: '{$request->username}' from '
						. '$requestClass::\$username vs. $username from earlier class" );
				}
			}
		}
		return $username;
	}

	/**
	 * Merge the output of multiple AuthenticationRequest::getFieldInfo() calls.
	 * @param AuthenticationRequest[]|string[] $requestsOrTypes
	 * @return array
	 * @throws Exception
	 */
	public static function mergeFieldInfo( array $requestsOrTypes ) {
		$merged = array();
		foreach ( $requestsOrTypes as $type ) {
			$info = call_user_func( array( $type, 'getFieldInfo' ) );
			foreach ( $info as $name => $options ) {
				if ( !array_key_exists( $name, $merged ) ) {
					$merged[$name] = $options;
				} elseif ( $merged['type'] !== $options['type'] ) {
					throw new LogicException( 'Field name conflict' );
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
	 * @throws LogicException
	 */
	public static function fieldInfoToFormDescriptor(
		array $fieldInfo, $action, array $coreFields = array()
	) {
		// auth request fields are lowercase, form descriptor fields are camelcase
		// we need to do some array gymnastics to preserve form descriptor case, which is used
		// as the HTML form field name, which needs to be be stable for B/C with form submit hooks
		$caseMap = self::getCaseMap( $fieldInfo, $coreFields );

		$formDescriptor = $coreFields;
		foreach ( $fieldInfo as $fieldName => $singleFieldInfo ) {
			$newName = isset( $caseMap[$fieldName] ) ? $caseMap[$fieldName] : ucfirst($fieldName);
			$infoDescriptor = self::mapSingleFieldInfo( $singleFieldInfo );

			// core messages should always take priority
			if ( array_intersect(
				array_keys( $formDescriptor[$newName] ),
				array( 'label', 'label-message' )
			) ) {
				unset( $singleFieldInfo['label'], $singleFieldInfo['label-message'] );
			}

			// options should be defined by the auth request but messages by core
			if ( array_intersect(
				array_keys( $formDescriptor[$newName] ),
				array( 'options', 'options-messages', 'options-message' )
			) ) {

			}

			// require should work either way
			if ( !empty( $formDescriptor['required'] ) || !empty( $singleFieldInfo['required'] ) ) {
				$formDescriptor['required'] = true;
			}

			// for all else, core settings should take priority
			$formDescriptor[$newName] += self::mapSingleFieldInfo( $singleFieldInfo );

			Hooks::run( 'AuthChangeFormField', array( $fieldName, $singleFieldInfo,
				&$formDescriptor[$newName], $action ) );
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
		$descriptor = array_filter( array(
			'type' => self::mapType( $singleFieldInfo['type'] ),
			// help-message is omitted as it is usually not really useful for a web interface
			'label-message' => self::getField( $singleFieldInfo, 'label' ),
			'required' => !self::getField( $singleFieldInfo, 'optional' ),
		) );

		if ( isset( $singleFieldInfo['options'] ) ) {
			$descriptor['options'] = array_flip( array_map( function ( $message ) {
				return $message->parse();
			}, $singleFieldInfo['options'] ) );
		}

		return $descriptor;
	}

	/**
	 * Maps AuthenticationRequest::getFieldInfo() types to HTMLForm types
	 * @param string $type
	 * @return string
	 * @throws LogicException
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
			throw new LogicException( 'invalid field type: ' . $type );
		}
		return $map[$type];
	}

	/**
	 * Given an array, returns a case-ignoring mapping of keys
	 * @param array $from
	 * @param array $to
	 * @return array
	 */
	protected static function getCaseMap( array $from, array $to ) {
		foreach ( array( $from, $to ) as $a ) {
			if ( count( array_unique( array_map( 'strtolower', array_keys( $a ) ) ) ) !==
				 count( $a )
			) {
				// Two AuthenticationRequest fields or two fields of the core form descriptor
				// were identical apart from case. We consider an auth request field and a form
				// descriptor field the same if they only differ in case so field names within
				// each group must be case-insensitively unique.
				throw new LogicException( 'auth field case conflict' );
			}
		}

		$map = array();
		foreach ( $from as $key => $_ ) {
			$map[$key] = strtolower($key);
		}
		$backMap = array_flip( $map );
		foreach ( $to as $key => $_ ) {
			if ( isset( $backMap[strtolower($key)] ) ) {
				$map[$backMap[strtolower( $key )]] = $key;
			}
		}

		return $map;
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
