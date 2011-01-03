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
		
		$callback = (array) $callback;
		
		if( count( $callback ) == 2 && $callback[0] == 'self' || $callback[0] == 'parent' ) {
			
			throw new MWException( 'MWFunction cannot call self::method() or parent::method()' );
			
		}
		
		// Run autoloader (workaround for call_user_func_array bug: http://bugs.php.net/bug.php?id=51329)
		is_callable( $callback );
		
		return $callback;
	}
	
	public static function call( $callback ) {
		$callback = self::cleanCallback( $callback );
		
		$args = func_get_args();
		
		return call_user_func_array( 'call_user_func', $args );
		
	}
	
	public static function callArray( $callback, $argsarams ) {
	
		$callback = self::cleanCallback( $callback );
		return call_user_func_array( $callback, $argsarams );
		
	}
	
	public static function newObj( $class, $args = array(), $force_fallback = false ) {
		if( !count( $args ) ) {
			return new $class;
		}
		
		if ( version_compare( PHP_VERSION, '5.1.3', '<' ) || $force_fallback ) {
		
			//If only MW needed 5.1.3 and up... sigh
			
			$args = array_values( $args );
			switch ( count( $args ) ) {
				case 0:
					return new $class;
				case 1:
					return new $class( $args[0] );
				case 2:
					return new $class( $args[0], $args[1] );
				case 3:
					return new $class( $args[0], $args[1], $args[2] );
				case 4:
					return new $class( $args[0], $args[1], $args[2], $args[3] );
				case 5:
					return new $class( $args[0], $args[1], $args[2], $args[3], $args[4] );
				case 6:
					return new $class( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );
				case 7:
					return new $class( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6] );
				case 8:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7] 
					);
				case 9:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8] 
					);
				case 10:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9]
					);
				case 11:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9], $args[10] 
					);
				case 12:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9], $args[10], $args[11] 
					);
				case 13:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9], $args[10], $args[11], $args[12]
					);
				case 14:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9], $args[10], $args[11], $args[12], $args[13]
					);
				case 15:
					return new $class( 
						$args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], 
						$args[7], $args[8], $args[9], $args[10], $args[11], $args[12], $args[13], 
						$args[14]
					);
				default:
					throw new MWException( 'Too many arguments to construtor in MWFunction::newObj' );
			}
		}
	
		else {
	
			$ref = new ReflectionClass($class); 
			return $ref->newInstanceArgs($args);
		}
		
	}
	
}
