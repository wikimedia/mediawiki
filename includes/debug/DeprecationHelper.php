<?php
/**
 * Trait for issuing warnings on deprecated access.
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
 * Use this trait in classes which have properties for which public access
 * is deprecated. Set the list of properties in $deprecatedPublicProperties
 * and make the properties non-public. The trait will preserve public access
 * but issue deprecation warnings when it is needed.
 *
 * Example usage:
 *     class Foo {
 *         use DeprecationHelper;
 *         protected $bar;
 *         public function __construct() {
 *             $this->deprecatePublicProperty( 'bar', '1.21', __CLASS__ );
 *         }
 *     }
 *
 *     $foo = new Foo;
 *     $foo->bar; // works but logs a warning
 *
 * Cannot be used with classes that have their own __get/__set methods.
 *
 * @since 1.32
 */
trait DeprecationHelper {

	/**
	 * List of deprecated properties, in <property name> => [<version>, <class>, <component>] format
	 * where <version> is the MediaWiki version where the property got deprecated, <class> is the
	 * the name of the class defining the property, <component> is the MediaWiki component
	 * (extension, skin etc.) for use in the deprecation warning) or null if it is MediaWiki.
	 * E.g. [ 'mNewRev' => [ '1.32', 'DifferenceEngine', null ]
	 * @var string[][]
	 */
	protected $deprecatedPublicProperties = [];

	/**
	 * Mark a property as deprecated. Only use this for properties that used to be public and only
	 *   call it in the constructor.
	 * @param string $property The name of the property.
	 * @param string $version MediaWiki version where the property became deprecated.
	 * @param string|null $class The class which has the deprecated property. This can usually be
	 *   guessed, but PHP can get confused when both the parent class and the subclass use the
	 *   trait, so it should be specified in classes meant for subclassing.
	 * @param string|null $component
	 * @see wfDeprecated()
	 */
	protected function deprecatePublicProperty(
		$property, $version, $class = null, $component = null
	) {
		$this->deprecatedPublicProperties[$property] = [ $version, $class ?: __CLASS__, $component ];
	}

	public function __get( $name ) {
		if ( isset( $this->deprecatedPublicProperties[$name] ) ) {
			list( $version, $class, $component ) = $this->deprecatedPublicProperties[$name];
			$qualifiedName = $class . '::$' . $name;
			wfDeprecated( $qualifiedName, $version, $component, 3 );
			return $this->$name;
		}

		$ownerClass = $this->deprecationHelperGetPropertyOwner( $name );
		$qualifiedName = ( $ownerClass ?: get_class( $this ) ) . '::$' . $name;
		if ( $ownerClass ) {
			// Someone tried to access a normal non-public property. Try to behave like PHP would.
			trigger_error( "Cannot access non-public property $qualifiedName", E_USER_ERROR );
		} else {
			// Non-existing property. Try to behave like PHP would.
			trigger_error( "Undefined property: $qualifiedName", E_USER_NOTICE );
		}
		return null;
	}

	public function __set( $name, $value ) {
		if ( isset( $this->deprecatedPublicProperties[$name] ) ) {
			list( $version, $class, $component ) = $this->deprecatedPublicProperties[$name];
			$qualifiedName = $class . '::$' . $name;
			wfDeprecated( $qualifiedName, $version, $component, 3 );
			$this->$name = $value;
			return;
		}

		$ownerClass = $this->deprecationHelperGetPropertyOwner( $name );
		$qualifiedName = ( $ownerClass ?: get_class( $this ) ) . '::$' . $name;
		if ( $ownerClass ) {
			// Someone tried to access a normal non-public property. Try to behave like PHP would.
			trigger_error( "Cannot access non-public property $qualifiedName", E_USER_ERROR );
		} else {
			// Non-existing property. Try to behave like PHP would.
			$this->$name = $value;
		}
	}

	/**
	 * Like property_exists but also check for non-visible private properties and returns which
	 * class in the inheritance chain declared the property.
	 * @param string $property
	 * @return string|bool Best guess for the class in which the property is defined. False if
	 *   the object does not have such a property.
	 */
	private function deprecationHelperGetPropertyOwner( $property ) {
		// Returning false is a non-error path and should avoid slow checks like reflection.
		// Use array cast hack instead.
		$obfuscatedProps = array_keys( (array)$this );
		$obfuscatedPropTail = "\0$property";
		foreach ( $obfuscatedProps as $obfuscatedProp ) {
			// private props are in the form \0<classname>\0<propname>
			if ( strpos( $obfuscatedProp, $obfuscatedPropTail, 1 ) !== false ) {
				$classname = substr( $obfuscatedProp, 1, -strlen( $obfuscatedPropTail ) );
				if ( $classname === '*' ) {
					// protected property; we didn't get the name, but we are on an error path
					// now so it's fine to use reflection
					return ( new ReflectionProperty( $this, $property ) )->getDeclaringClass()->getName();
				}
				return $classname;
			}
		}
		return false;
	}

}
