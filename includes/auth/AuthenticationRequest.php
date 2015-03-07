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

/**
 * This is a value object for authentication requests.
 * @ingroup Auth
 * @since 1.25
 */
abstract class AuthenticationRequest {

	/** @var string|null Return-to URL, in case of redirect */
	public $returnToUrl = null;

	/** @var string|null Username. May not be used by all subclasses. */
	public $username = null;

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
	 *     - button: <input type="image"> if 'image', otherwise <input type="submit">
	 *       (uses 'label' as button text)
	 *     - null: No widget, just display the 'label' message.
	 *  - options: (array) Maps option values to MessageSpecifiers for the
	 *      'select' and 'multiselect' types.
	 *  - image: (string) URL of an image to use in connection with the input
	 *  - label: (string) A MessageSpecifier for text suitable for a label in an HTML form
	 *  - help: (string) A MessageSpecifier for text suitable as a description of what the field is
	 *  - optional: (bool) If set and truthy, the field may be left empty
	 *
	 * @note Subclasses must reimplement this static method
	 * @return array As above
	 */
	public static function getFieldInfo() {
		throw new Exception( get_called_class() . ' must override getFieldInfo()' );
	}

	/**
	 * Create all possible AuthenticationRequests from submitted data
	 *
	 * @param string[] $types Request types to try to create
	 * @param array $data Submitted data as an associative array
	 * @param string|null $returnToURL
	 * @return AuthenticationRequest[]
	 */
	public static function requestsFromSubmission( array $types, array $data, $returnToURL ) {
		$ret = array();
		foreach ( $types as $type ) {
			$req = call_user_func_array( array( $type, 'newFromSubmission' ), $data );
			if ( $req !== null ) {
				$req->returnToUrl = $returnToURL;
				$ret[] = $req;
			}
		}
		return $ret;
	}

	/**
	 * Create an AuthenticationRequest from submitted data
	 *
	 * Always fails if self::getFieldInfo() is falsey
	 *
	 * @param array $data Submitted data as an associative array
	 * @return AuthenticationRequest|null
	 */
	public static function newFromSubmission( array $data ) {
		$fields = static::getFieldInfo();
		if ( !$fields ) {
			return null;
		}

		$ret = new static;
		foreach ( $fields as $field => $info ) {
			if ( !isset( $data[$field] ) ) {
				return null;
			}
			if ( $data[$field] === '' ) {
				if ( empty( $info['optional'] ) ) {
					return null;
				}
			} else {
				switch ( $info['type'] ) {
					case 'select':
						if ( !in_array( $data[$field], $info['options'], true ) ) {
							return null;
						}
						break;
				}
			}

			/** @todo Is this ok, or should we have getters and setters or something like that? */
			$ret->$field = $data[$field];
		}
		return $ret;
	}

}
