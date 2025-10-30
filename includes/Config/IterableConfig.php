<?php
/**
 * Interface for iterable configuration instances.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

use IteratorAggregate;
use Traversable;

/**
 * Interface for iterable configuration instances.
 *
 * @stable to implement
 *
 * @since 1.38
 */
interface IterableConfig extends Config, IteratorAggregate {

	/**
	 * Returns a traversable view of the configuration variables in this Config object.
	 *
	 * @return Traversable<string,mixed>
	 */
	public function getIterator(): Traversable;

	/**
	 * Returns the names of configuration variables in this Config object.
	 *
	 * @return string[]
	 */
	public function getNames(): array;

}
