<?php
declare( strict_types = 1 );

use Wikimedia\Assert\Assert;

// We want to allow dynamic property creation on any object.
// phpcs:disable MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam

/**
 * Utility class for dealing with dynamic property creation in tests.
 *
 * PHP 8.2 has deprecated dynamic property creation for objects not explicitly annotated with #[AllowDynamicProperties].
 * The recommended migration path for associating arbitrary data with objects is WeakMap, which is only available starting from PHP 8.0.
 * Whilst MediaWiki still supported PHP 7.4, this required a compatibility layer for dynamic property creation on classes
 * that do not support it, by utilizing WeakMap when available and falling back to regular dynamic property creation
 * on PHP 7.4.
 *
 * This class can be removed and its usages converted into direct WeakMap usage now that MediaWiki only supports PHP 8.0 and above.
 *
 * @since 1.42 (also backported to 1.39.8, 1.40.4 and 1.41.2)
 * @internal Only for use by core PHPUnit setup functionality.
 */
class DynamicPropertyTestHelper {
	/**
	 * Associative array of WeakMaps holding dynamic properties keyed by property name.
	 * @var WeakMap[]
	 */
	private static $properties = [];

	/**
	 * Get the value of the given dynamic property from the given object.
	 * @param object $owner Object to fetch the dynamic property for
	 * @param string $propName Name of the dynamic property to get
	 * @return mixed The value of the dynamic property, or null if not set
	 */
	public static function getDynamicProperty( object $owner, string $propName ) {
		if ( class_exists( WeakMap::class ) ) {
			self::$properties[$propName] ??= new WeakMap();
			return self::$properties[$propName][$owner] ?? null;
		}

		return $owner->$propName ?? null;
	}

	/**
	 * Set the given dynamic property to the given value on an object.
	 * @param object $owner Object to set the dynamic property on
	 * @param string $propName Name of the dynamic property to set
	 * @param mixed $value The property value to set; must not be null
	 * @return void
	 */
	public static function setDynamicProperty( object $owner, string $propName, $value ): void {
		// getDynamicProperty() uses a null return value as an idiom for "property does not exist",
		// which precludes supporting null values for dynamic properties without a separate method
		// to check for the existence of a possibly nullable property.
		// Since existing test cases do not seem to extensively rely on setting null values,
		// explicitly forbid them here.
		Assert::parameter( $value !== null, '$value', 'must not be null' );

		if ( class_exists( WeakMap::class ) ) {
			self::$properties[$propName] ??= new WeakMap();
			self::$properties[$propName][$owner] = $value;
		} else {
			$owner->$propName = $value;
		}
	}

	/**
	 * Unset the given dynamic property on the given object.
	 * @param object $owner Object to unset the dynamic property on
	 * @param string $propName Name of the dynamic property to unset
	 * @return void
	 */
	public static function unsetDynamicProperty( object $owner, string $propName ): void {
		if ( class_exists( WeakMap::class ) ) {
			self::$properties[$propName] ??= new WeakMap();
			unset( self::$properties[$propName][$owner] );
		} else {
			unset( $owner->$propName );
		}
	}

}

// phpcs:enable
