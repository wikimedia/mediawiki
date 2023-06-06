<?php

namespace MediaWiki\HookContainer;

use InvalidArgumentException;
use LogicException;
use OutOfBoundsException;

/**
 * @internal
 */
class FauxHookHandlerArray implements \ArrayAccess, \IteratorAggregate {

	private HookContainer $hookContainer;

	private string $name;

	private ?array $handlers = null;

	/**
	 * @param HookContainer $hookContainer
	 * @param string $name
	 */
	public function __construct( HookContainer $hookContainer, string $name ) {
		$this->hookContainer = $hookContainer;
		$this->name = $name;
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return $this->getHandler( $offset ) !== null;
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		$handler = $this->getHandler( $offset );

		if ( !$handler ) {
			throw new OutOfBoundsException( "No such index in the handler list: $offset" );
		}

		return $handler;
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		if ( $offset !== null ) {
			throw new InvalidArgumentException( '$offset must be null, this array is append only' );
		}

		$this->hookContainer->register( $this->name, $value );
		$this->handlers = null;
	}

	/**
	 * @inheritDoc
	 * @return never
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		throw new LogicException( 'unset is not supported for hook handler arrays' );
	}

	private function getHandler( $offset ) {
		if ( $this->handlers === null ) {
			// NOTE: getHandlerCallbacks() only exists to support this.
			//       It should be deleted when we no longer need it here.
			$this->handlers = $this->hookContainer->getHandlerCallbacks( $this->name );
		}

		return $this->handlers[$offset] ?? null;
	}

	#[\ReturnTypeWillChange]
	public function getIterator() {
		if ( $this->handlers === null ) {
			// NOTE: getHandlerCallbacks() only exists to support this.
			//       It should be deleted when we no longer need it here.
			$this->handlers = $this->hookContainer->getHandlerCallbacks( $this->name );
		}

		return new \ArrayIterator( $this->handlers );
	}
}
