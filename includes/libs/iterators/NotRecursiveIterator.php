<?php
/**
 * @license GPL-2.0-or-later
 */

/**
 * Wraps a non-recursive iterator with methods to be recursive
 * without children.
 *
 * Alternatively wraps a recursive iterator to prevent recursing deeper
 * than the wrapped iterator.
 *
 * @ingroup Maintenance
 */
class NotRecursiveIterator extends IteratorDecorator implements RecursiveIterator {
	public function hasChildren(): bool {
		return false;
	}

	public function getChildren(): ?RecursiveIterator {
		// @phan-suppress-next-line PhanTypeMismatchReturnProbablyReal False positive
		return null;
	}
}
