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

namespace MediaWiki\Debug;

use Error;
use ReflectionFunction;
use ReflectionProperty;

/**
 * Trait for issuing warnings on deprecated access.
 *
 * Use this trait in classes which have properties for which public access
 * is deprecated or implementation has been moved to another class.
 * Set the list of properties in $deprecatedPublicProperties
 * and make the properties non-public. The trait will preserve public access
 * but issue deprecation warnings when it is needed.
 *
 * Example usage:
 *     class Foo {
 *         use DeprecationHelper;
 *         protected $bar;
 *         public function __construct() {
 *             $this->deprecatePublicProperty( 'bar', '1.21', __CLASS__ );
 *             $this->deprecatePublicPropertyFallback(
 *                 'movedValue',
 *                 '1.35',
 *                 function () {
 *                     return MediaWikiServices()::getInstance()
 *                         ->getNewImplementationService()->getValue();
 *                 },
 *                 function ( $value ) {
 *                     MediaWikiServices()::getInstance()
 *                         ->getNewImplementationService()->setValue( $value );
 *                 }
 *             );
 *         }
 *     }
 *
 *     $foo = new Foo;
 *     $foo->bar; // works but logs a warning
 *     $foo->movedValue = 10; // works but logs a warning
 *     $movedValue = $foo->movedValue; // also works
 *
 * Cannot be used with classes that have their own __get/__set methods.
 *
 * @since 1.32
 */
trait DeprecationHelper {

	/**
	 * List of deprecated properties, in the format:
	 *     <property name> => [<version>, <class>, <component>, <getter>, <setter> ]
	 * where:
	 * - <version> is the MediaWiki version where the property got deprecated,
	 * - <class> is the the name of the class defining the property,
	 * - <component> is the MediaWiki component (extension, skin etc.) for use in the deprecation
	 *   warning) or false if it is MediaWiki.
	 * E.g. [ 'mNewRev' => [ '1.32', 'DifferenceEngine', false ]
	 * @var array<string, array{string, class-string, string|false, callable|string|null, callable|string|null}>
	 */
	protected static $deprecatedPublicProperties = [];

	/**
	 * Whether to emit a deprecation warning when unknown properties are accessed.
	 *
	 * @var bool|array
	 */
	private $dynamicPropertiesAccessDeprecated = false;

	/**
	 * Mark a property as deprecated. Only use this for properties that used to be public and only
	 *   call it in the constructor.
	 *
	 * @note  Providing callbacks makes it not serializable
	 *
	 * @param string $property The name of the property.
	 * @param string $version MediaWiki version where the property became deprecated.
	 * @param string|null $class The class which has the deprecated property. This can usually be
	 *   guessed, but PHP can get confused when both the parent class and the subclass use the
	 *   trait, so it should be specified in classes meant for subclassing.
	 * @param string|null $component
	 * @see wfDeprecated()
	 */
	protected function deprecatePublicProperty(
		$property,
		$version,
		$class = null,
		$component = null
	) {
		if ( isset( self::$deprecatedPublicProperties[$property] ) ) {
			return;
		}
		self::$deprecatedPublicProperties[$property] = [
			$version,
			$class ?: __CLASS__,
			$component ?: false,
			null,
			null,
		];
	}

	/**
	 * Mark a removed public property as deprecated and provide fallback getter and setter callables.
	 * Only use this for properties that used to be public and only
	 * call it in the constructor.
	 *
	 * @param string $property The name of the property.
	 * @param string $version MediaWiki version where the property became deprecated.
	 * @param callable|string $getter A user provided getter that implements a `get` logic
	 *        for the property. If a string is given, it is called as a method on $this.
	 * @param callable|string|null $setter A user provided setter that implements a `set` logic
	 *        for the property. If a string is given, it is called as a method on $this.
	 * @param string|null $class The class which has the deprecated property.
	 * @param string|null $component
	 *
	 * @since 1.36
	 * @see wfDeprecated()
	 */
	protected function deprecatePublicPropertyFallback(
		string $property,
		string $version,
		$getter,
		$setter = null,
		$class = null,
		$component = null
	) {
		if ( isset( self::$deprecatedPublicProperties[$property] ) ) {
			return;
		}
		self::$deprecatedPublicProperties[$property] = [
			$version,
			$class ?: __CLASS__,
			$component ?: false,
			$getter,
			$setter,
		];
	}

	/**
	 * Emit deprecation warnings when dynamic and unknown properties
	 * are accessed.
	 *
	 * @param string $version MediaWiki version where the property became deprecated.
	 * @param class-string|null $class The class which has the deprecated property.
	 * @param string|null $component
	 */
	protected function deprecateDynamicPropertiesAccess(
		string $version,
		?string $class = null,
		?string $component = null
	) {
		$this->dynamicPropertiesAccessDeprecated = [ $version, $class ?: __CLASS__, $component ];
	}

	public function __isset( $name ) {
		// Overriding magic __isset is required not only for isset() and empty(),
		// but to correctly support null coalescing for dynamic properties,
		// e.g. $foo->bar ?? 'default'
		if ( isset( self::$deprecatedPublicProperties[$name] ) ) {
			[ $version, $class, $component, $getter ] = self::$deprecatedPublicProperties[$name];
			$qualifiedName = $class . '::$' . $name;
			wfDeprecated( $qualifiedName, $version, $component, 2 );
			if ( $getter ) {
				return $this->deprecationHelperCallGetter( $getter );
			}
			return true;
		}

		$ownerClass = $this->deprecationHelperGetPropertyOwner( $name );
		if ( $ownerClass ) {
			// Someone tried to access a normal non-public property. Try to behave like PHP would.
			return false;
		} else {
			if ( $this->dynamicPropertiesAccessDeprecated ) {
				[ $version, $class, $component ] = $this->dynamicPropertiesAccessDeprecated;
				$qualifiedName = $class . '::$' . $name;
				wfDeprecated( $qualifiedName, $version, $component, 2 );
			}
			return false;
		}
	}

	public function __get( $name ) {
		if ( get_object_vars( $this ) === [] ) {
			// Object is being destructed, all bets are off (T363492);
			// in particular, we can't check $this->dynamicPropertiesAccessDeprecated anymore.
			// Just get the property and hope for the best...
			return $this->$name;
		}

		if ( isset( self::$deprecatedPublicProperties[$name] ) ) {
			[ $version, $class, $component, $getter ] = self::$deprecatedPublicProperties[$name];
			$qualifiedName = $class . '::$' . $name;
			wfDeprecated( $qualifiedName, $version, $component, 2 );
			if ( $getter ) {
				return $this->deprecationHelperCallGetter( $getter );
			}
			return $this->$name;
		}

		$ownerClass = $this->deprecationHelperGetPropertyOwner( $name );
		$qualifiedName = ( $ownerClass ?: get_class( $this ) ) . '::$' . $name;
		if ( $ownerClass ) {
			// Someone tried to access a normal non-public property. Try to behave like PHP would.
			throw new Error( "Cannot access non-public property $qualifiedName" );
		} elseif ( property_exists( $this, $name ) ) {
			// Normally __get method will not be even called if the property exists,
			// but in tests if we mock an object that uses DeprecationHelper,
			// __get and __set magic methods will be mocked as well, and called
			// regardless of the property existence. Support that use-case.
			return $this->$name;
		} else {
			// Non-existing property. Try to behave like PHP would.
			trigger_error( "Undefined property: $qualifiedName", E_USER_NOTICE );
		}
		return null;
	}

	public function __set( $name, $value ) {
		if ( get_object_vars( $this ) === [] ) {
			// Object is being destructed, all bets are off (T363492);
			// in particular, we can't check $this->dynamicPropertiesAccessDeprecated anymore.
			// Just set the property and hope for the best...
			$this->$name = $value;
			return;
		}

		if ( isset( self::$deprecatedPublicProperties[$name] ) ) {
			[ $version, $class, $component, , $setter ] = self::$deprecatedPublicProperties[$name];
			$qualifiedName = $class . '::$' . $name;
			wfDeprecated( $qualifiedName, $version, $component, 2 );
			if ( $setter ) {
				$this->deprecationHelperCallSetter( $setter, $value );
			} elseif ( property_exists( $this, $name ) ) {
				$this->$name = $value;
			} else {
				throw new Error( "Cannot access non-public property $qualifiedName" );
			}
			return;
		}

		$ownerClass = $this->deprecationHelperGetPropertyOwner( $name );
		$qualifiedName = ( $ownerClass ?: get_class( $this ) ) . '::$' . $name;
		if ( $ownerClass ) {
			// Someone tried to access a normal non-public property. Try to behave like PHP would.
			throw new Error( "Cannot access non-public property $qualifiedName" );
		} else {
			if ( $this->dynamicPropertiesAccessDeprecated ) {
				[ $version, $class, $component ] = $this->dynamicPropertiesAccessDeprecated;
				$qualifiedName = $class . '::$' . $name;
				wfDeprecated( $qualifiedName, $version, $component, 2 );
			}
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

	/**
	 * @param string|\Closure $getter
	 * @return mixed
	 */
	private function deprecationHelperCallGetter( $getter ) {
		if ( is_string( $getter ) ) {
			$getter = [ $this, $getter ];
		} elseif ( ( new ReflectionFunction( $getter ) )->getClosureThis() !== null ) {
			$getter = $getter->bindTo( $this );
		}
		return $getter();
	}

	/**
	 * @param string|\Closure $setter
	 * @param mixed $value
	 */
	private function deprecationHelperCallSetter( $setter, $value ) {
		if ( is_string( $setter ) ) {
			$setter = [ $this, $setter ];
		} elseif ( ( new ReflectionFunction( $setter ) )->getClosureThis() !== null ) {
			$setter = $setter->bindTo( $this );
		}
		$setter( $value );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( DeprecationHelper::class, 'DeprecationHelper' );
