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
 */

class MWFunction {
	
	protected static function cleanCallback( $callback ) {
		
		if( is_string( $callback ) ) {
			if ( strpos( $callback, '::' ) !== false ) {
				//PHP 5.1 cannot use call_user_func( 'Class::Method' )
				//It can only handle only call_user_func( array( 'Class', 'Method' ) )
				$callback = explode( '::', $callback, 2);
			}
		}
		
		return $callback;
	}
	
	public static function call( $callback ) {
		$callback = self::cleanCallback( $callback );
		
		$args = func_get_args();
		
		return call_user_func_array( 'call_user_func', $args );
		
	}
	
	public static function callArray( $callback, $params ) {
	
		$callback = self::cleanCallback( $callback );
		return call_user_func_array( $callback, $params );
		
	}
	
}
