<?php

namespace MediaWiki\Debug;

use ArrayAccess;

/**
 * ArrayAccess implementation that supports deprecating access to certain properties.
 * It behaves mostly as a normal array, however in order to avoid instantiating
 * deprecated properties by default, a callable initializer can be set to the property.
 * It will be executed upon 'get'.
 * @note setting properties does not emit deprecation warnings.
 * @newable
 * @since 1.35
 */
class DeprecatablePropertyArray implements ArrayAccess {

	/** @var array */
	private $container;

	/** @var array Map of deprecated property names to deprecation versions */
	private $deprecatedProperties;

	/** @var string */
	private $name;

	/** @var string|null */
	private $component;

	/**
	 * @param array $initializer Initial value of the array.
	 * @param array $deprecatedProperties Map of deprecated property names to versions.
	 * @param string $name Descriptive identifier for the array
	 * @param string|null $component Component to which array belongs.
	 *  If not provided, assumed to be MW Core
	 */
	public function __construct(
		array $initializer,
		array $deprecatedProperties,
		string $name,
		string $component = null
	) {
		$this->container = $initializer;
		$this->deprecatedProperties = $deprecatedProperties;
		$this->name = $name;
		$this->component = $component;
	}

	public function offsetExists( $offset ) {
		$this->checkDeprecatedAccess( $offset, 'exists' );
		return isset( $this->container[$offset] );
	}

	public function offsetGet( $offset ) {
		if ( $this->checkDeprecatedAccess( $offset, 'get' ) ) {
			if ( is_callable( $this->container[$offset] ) ) {
				$this->container[$offset] = call_user_func( $this->container[$offset] );
			}
		}
		return $this->container[$offset] ?? null;
	}

	public function offsetSet( $offset, $value ) {
		if ( $offset === null ) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetUnset( $offset ) {
		$this->checkDeprecatedAccess( $offset, 'unset' );
		unset( $this->container[$offset] );
	}

	/**
	 * @param string|int $offset
	 * @param string $fname
	 * @return bool
	 */
	private function checkDeprecatedAccess( $offset, string $fname ) : bool {
		if ( array_key_exists( $offset, $this->deprecatedProperties ) ) {
			$deprecatedVersion = $this->deprecatedProperties[$offset];
			wfDeprecated(
				"{$this->name} {$fname} '{$offset}'",
				$deprecatedVersion,
				$this->component ?? false,
				3
			);
			return true;
		}
		return false;
	}
}
