<?php

namespace MediaWiki\HookContainer;

use InvalidArgumentException;

/**
 * @internal
 */
class FauxGlobalHookArray implements \ArrayAccess {

	private HookContainer $hookContainer;

	/**
	 * The original handler array.
	 * @var array
	 */
	private array $originalArray;

	/**
	 * @param HookContainer $hookContainer
	 * @param array $originalArray
	 */
	public function __construct( HookContainer $hookContainer, array $originalArray = [] ) {
		$this->hookContainer = $hookContainer;
		$this->originalArray = $originalArray;
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $key ) {
		return $this->hookContainer->isRegistered( $key );
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $key ) {
		return new FauxHookHandlerArray( $this->hookContainer, $key );
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $key, $value ) {
		if ( !is_string( $key ) ) {
			throw new InvalidArgumentException( '$key must be a string' );
		}
		if ( !is_array( $value ) ) {
			throw new InvalidArgumentException( '$value must be an array' );
		}

		$this->hookContainer->clear( $key );

		foreach ( $value as $handler ) {
			$this->hookContainer->register( $key, $handler );
		}
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $key ) {
		if ( $this->hookContainer->isRegistered( $key ) ) {
			$this->hookContainer->clear( $key );
		}
	}

	public function getOriginalArray(): array {
		return $this->originalArray;
	}
}
