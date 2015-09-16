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
					if ( !empty( $merged['optional'] ) && !empty( $info['optional'] ) ) {
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
	 * Turn
	 * @param array $fieldInfo Field information, in the format used by
	 *   AuthenticationRequest::getFieldInfo()
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return array A form descriptor that can be passed to HTMLForm
	 */
	public static function fieldInfoToFormDescriptor( array $fieldInfo, $action ) {
		$formDescriptor = array();

		self::onAuthChangeFieldInfo( $fieldInfo, $formDescriptor, $action );
		Hooks::run( 'AuthChangeFieldInfo', array( &$fieldInfo, &$formDescriptor, $action ) );

		foreach ( $fieldInfo as $fieldName => $config ) {
			$formDescriptor[$fieldName] = self::mapSingleField( $config );
		}

		uasort( $formDescriptor, function ( $first, $second ) {
			return self::getField( $first, 'weigth', 0 ) - self::getField( $second, 'weight', 0 );
		} );

		return $formDescriptor;
	}

	/**
	 * Maps an authentication field configuration for a single field (as returned by
	 * AuthenticationRequest::getFieldInfo()) to a HTMLForm field descriptor.
	 * @param array $config
	 * @return array
	 */
	public static function mapSingleField( $config ) {
		return array_filter( array(
			'type' => self::mapType( $config['type'] ),
			'label-message' => self::getField( $config, 'label' ),
			'help-message' => self::getField( $config, 'help' ),
			'options-messages' => array_flip( self::getField( $config, 'options', array() ) ),
			'required' => !self::getField( $config, 'optional' ),
		) );
	}

	/**
	 * Maps AuthenticationRequest::getFieldInfo() types to HTMLForm types
	 * @param string $type
	 * @return string
	 * @throws Exception
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
			throw new Exception( 'invalid field type: ' . $type );
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

	/**
	 * AuthChangeFieldInfo pseudo-hook that handles some frontend logic for fields that come from
	 * core request types.
	 * @param array $fieldInfo
	 * @param array $formDescriptor
	 * @param string $action
	 */
	private static function onAuthChangeFieldInfo( &$fieldInfo, &$formDescriptor, $action ) {
		// TODO registration via email
	}
}
