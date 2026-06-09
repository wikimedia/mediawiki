<?php

/**
 * @license GPL-2.0-or-later
 */

namespace Wikimedia\Iterators;

use Iterator;

/**
 * Allows extending classes to decorate an Iterator with
 * reduced boilerplate.
 *
 * @stable to extend
 * @ingroup Maintenance
 */
abstract class IteratorDecorator implements Iterator {
	protected Iterator $iterator;

	/**
	 * @stable to call
	 *
	 * @param Iterator $iterator
	 */
	public function __construct( Iterator $iterator ) {
		$this->iterator = $iterator;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return $this->iterator->current();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	#[\ReturnTypeWillChange]
	public function key() {
		return $this->iterator->key();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function next(): void {
		$this->iterator->next();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function rewind(): void {
		$this->iterator->rewind();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function valid(): bool {
		return $this->iterator->valid();
	}
}

/** @deprecated class alias since 1.47 */
class_alias( IteratorDecorator::class, 'IteratorDecorator' );
