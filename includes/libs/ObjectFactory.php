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

/**
 * Construct objects from configuration instructions.
 *
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
class ObjectFactory {

	/**
	 * Instantiate an object based on a specification array.
	 *
	 * The specification array must contain a 'class' key with string value
	 * that specifies the class name to instantiate or a 'factory' key with
	 * a callable (is_callable() === true). It can optionally contain
	 * an 'args' key that provides arguments to pass to the
	 * constructor/callable.
	 *
	 * Object construction using a specification having both 'class' and
	 * 'args' members will call the constructor of the class using
	 * ReflectionClass::newInstanceArgs. The use of ReflectionClass carries
	 * a performance penalty and should not be used to create large numbers of
	 * objects. If this is needed, consider introducing a factory method that
	 * can be called via call_user_func_array() instead.
	 *
	 * Values in the arguments collection which are Closure instances will be
	 * expanded by invoking them with no arguments before passing the
	 * resulting value on to the constructor/callable. This can be used to
	 * pass DatabaseBase instances or other live objects to the
	 * constructor/callable. This behavior can be suppressed by adding
	 * closure_expansion => false to the specification.
	 *
	 * The specification may also contain a 'calls' key that describes method
	 * calls to make on the newly created object before returning it. This
	 * pattern is often known as "setter injection". The value of this key is
	 * expected to be an associative array with method names as keys and
	 * argument lists as values. The argument list will be expanded (or not)
	 * in the same way as the 'args' key for the main object.
	 *
	 * @param array $spec Object specification
	 * @return object
	 * @throws InvalidArgumentException when object specification does not
	 * contain 'class' or 'factory' keys
	 * @throws ReflectionException when 'args' are supplied and 'class'
	 * constructor is non-public or non-existent
	 */
	public static function getObjectFromSpec( $spec ) {
		$args = isset( $spec['args'] ) ? $spec['args'] : array();
		$expandArgs = !isset( $spec['closure_expansion'] ) ||
			$spec['closure_expansion'] === true;

		if ( $expandArgs ) {
			$args = static::expandClosures( $args );
		}

		if ( isset( $spec['class'] ) ) {
			$clazz = $spec['class'];
			if ( !$args ) {
				$obj = new $clazz();
			} else {
				$ref = new ReflectionClass( $clazz );
				$obj = $ref->newInstanceArgs( $args );
			}
		} elseif ( isset( $spec['factory'] ) ) {
			$obj = call_user_func_array( $spec['factory'], $args );
		} else {
			throw new InvalidArgumentException(
				'Provided specification lacks both factory and class parameters.'
			);
		}

		if ( isset( $spec['calls'] ) && is_array( $spec['calls'] ) ) {
			// Call additional methods on the newly created object
			foreach ( $spec['calls'] as $method => $margs ) {
				if ( $expandArgs ) {
					$margs = static::expandClosures( $margs );
				}
				call_user_func_array( array( $obj, $method ), $margs );
			}
		}

		return $obj;
	}

	/**
	 * Iterate a list and call any closures it contains.
	 *
	 * @param array $list List of things
	 * @return array List with any Closures replaced with their output
	 */
	protected static function expandClosures( $list ) {
		return array_map( function ( $value ) {
			if ( is_object( $value ) && $value instanceof Closure ) {
				// If $value is a Closure, call it.
				return $value();
			} else {
				return $value;
			}
		}, $list );
	}
}
