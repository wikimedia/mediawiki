<?php
/**
 * Some functions that are useful during startup.
 *
 * This class previously contained some functionality related to a PHP compiler
 * called hphpc. That compiler has now been discontinued.
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

/**
 * Some functions that are useful during startup.
 *
 * This class previously contained some functionality related to a PHP compiler
 * called hphpc. That compiler has now been discontinued. All methods are now
 * deprecated.
 */
class MWInit {
	static $compilerVersion;

	/**
	 * @deprecated since 1.22
	 */
	static function getCompilerVersion() {
		return false;
	}

	/**
	 * Returns true if we are running under HipHop, whether in compiled or
	 * interpreted mode.
	 *
	 * @deprecated since 1.22
	 * @return bool
	 */
	static function isHipHop() {
		return defined( 'HPHP_VERSION' );
	}

	/**
	 * Get a fully-qualified path for a source file relative to $IP.
	 * @deprecated since 1.22
	 *
	 * @param $file string
	 *
	 * @return string
	 */
	static function interpretedPath( $file ) {
		global $IP;
		return "$IP/$file";
	}

	/**
	 * @deprecated since 1.22
	 * @param $file string
	 * @return string
	 */
	static function compiledPath( $file ) {
		global $IP;
		return "$IP/$file";
	}

	/**
	 * @deprecated since 1.22
	 * @param $file string
	 * @return string
	 */
	static function extCompiledPath( $file ) {
		return false;
	}

	/**
	 * Deprecated wrapper for class_exists()
	 * @deprecated since 1.22
	 *
	 * @param $class string
	 *
	 * @return bool
	 */
	static function classExists( $class ) {
		return class_exists( $class );
	}

	/**
	 * Deprecated wrapper for method_exists()
	 * @deprecated since 1.22
	 *
	 * @param $class string
	 * @param $method string
	 *
	 * @return bool
	 */
	static function methodExists( $class, $method ) {
		return method_exists( $class, $method );
	}

	/**
	 * Deprecated wrapper for function_exists()
	 * @deprecated since 1.22
	 *
	 * @param $function string
	 *
	 * @return bool
	 */
	static function functionExists( $function ) {
		return function_exists( $function );
	}

	/**
	 * Deprecated wrapper for call_user_func_array()
	 * @deprecated since 1.22
	 *
	 * @param $className string
	 * @param $methodName string
	 * @param $args array
	 *
	 * @return mixed
	 */
	static function callStaticMethod( $className, $methodName, $args ) {
		return call_user_func_array( array( $className, $methodName ), $args );
	}
}
