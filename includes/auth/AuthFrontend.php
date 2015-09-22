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
	 * Turns a field info array into a form descriptor. Behavior can be modified by the
	 * AuthChangeFormFields hook.
	 * @param array $fieldInfo Field information, in the format used by
	 *   AuthenticationRequest::getFieldInfo()
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return array A form descriptor that can be passed to HTMLForm
	 * @throws \LogicException
	 */
	public static function fieldInfoToFormDescriptor( array $fieldInfo, $action ) {
		$formDescriptor = [];
		foreach ( $fieldInfo as $fieldName => $singleFieldInfo ) {
			$formDescriptor[$fieldName] = self::mapSingleFieldInfo( $singleFieldInfo, $fieldName );
		}

		\Hooks::run( 'AuthChangeFormFields', [ $fieldInfo, &$formDescriptor, $action ] );

		// Process the special 'weight' property, which is a way for AuthChangeFormFields hook
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
	protected static function mapSingleFieldInfo( $singleFieldInfo, $fieldName ) {
		$type = self::mapType( $singleFieldInfo['type'] );
		$descriptor = [
			'type' => $type,
			// Do not prefix input name with 'wp'. This is important for the redirect flow.
			'name' => $fieldName,
		];

		if ( $type === 'submit' && isset( $singleFieldInfo['label'] ) ) {
			$descriptor['default'] = wfMessage( $singleFieldInfo['label'] )->plain();
		} elseif ( $type !== 'submit' ) {
			$descriptor += array_filter( [
				// help-message is omitted as it is usually not really useful for a web interface
				'label-message' => self::getField( $singleFieldInfo, 'label' ),
			] );

			if ( isset( $singleFieldInfo['options'] ) ) {
				$descriptor['options'] = array_flip( array_map( function ( $message ) {
					/** @var $message Message */
					return $message->parse();
				}, $singleFieldInfo['options'] ) );
			}

			if ( empty( $singleFieldInfo['optional'] ) ) {
				$descriptor['required'] = true;
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
		$map = [
			'string' => 'text',
			'password' => 'password',
			'select' => 'select',
			'checkbox' => 'check',
			'multiselect' => 'multiselect',
			'button' => 'submit',
			'null' => 'info',
		];
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
