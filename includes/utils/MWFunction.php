<?php
/**
 * Helper methods to call functions and instance objects.
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
 */

class MWFunction {

	/**
	 * @deprecated since 1.22; use call_user_func()
	 * @param $callback
	 * @return mixed
	 */
	public static function call( $callback ) {
		wfDeprecated( __METHOD__, '1.22' );
		$args = func_get_args();

		return call_user_func_array( 'call_user_func', $args );
	}

	/**
	 * @deprecated since 1.22; use call_user_func_array()
	 * @param $callback
	 * @param $argsarams
	 * @return mixed
	 */
	public static function callArray( $callback, $argsarams ) {
		wfDeprecated( __METHOD__, '1.22' );

		return call_user_func_array( $callback, $argsarams );
	}

	/**
	 * @param $class
	 * @param $args array
	 * @return object
	 */
	public static function newObj( $class, $args = array() ) {
		if ( !count( $args ) ) {
			return new $class;
		}

		$ref = new ReflectionClass( $class );

		return $ref->newInstanceArgs( $args );
	}
}
