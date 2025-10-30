<?php

namespace MediaWiki\Debug;

use ArrayAccess;

/**
 * ArrayAccess with support for deprecating access to certain offsets.
 *
 * It behaves mostly as a normal array, however in order to avoid instantiating
 * deprecated properties by default, a callable initializer can be set to the property.
 * It will be executed upon 'get'.
 * @note Setting properties does not emit deprecation warnings.
 *
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
		?string $component = null
	) {
		$this->container = $initializer;
		$this->deprecatedProperties = $deprecatedProperties;
		$this->name = $name;
		$this->component = $component;
	}

	/** @inheritDoc */
	public function offsetExists( $offset ): bool {
		$this->checkDeprecatedAccess( $offset, 'exists' );
		return isset( $this->container[$offset] );
	}

	/** @inheritDoc */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		if ( $this->checkDeprecatedAccess( $offset, 'get' ) ) {
			if ( is_callable( $this->container[$offset] ) ) {
				$this->container[$offset] = $this->container[$offset]();
			}
		}
		return $this->container[$offset] ?? null;
	}

	/** @inheritDoc */
	public function offsetSet( $offset, $value ): void {
		if ( $offset === null ) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	/** @inheritDoc */
	public function offsetUnset( $offset ): void {
		$this->checkDeprecatedAccess( $offset, 'unset' );
		unset( $this->container[$offset] );
	}

	/**
	 * @param string|int $offset
	 * @param string $fname
	 * @return bool
	 */
	private function checkDeprecatedAccess( $offset, string $fname ): bool {
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
