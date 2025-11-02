<?php
/**
 * OpenStack Swift based file backend.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 * @author Russ Nelson
 */

namespace Wikimedia\FileBackend\FileIteration;

/**
 * Iterator for listing directories
 */
class SwiftFileBackendDirList extends SwiftFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return substr( current( $this->iterableBuffer ), $this->suffixStart, -1 );
	}

	/** @inheritDoc */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getDirListPageInternal( $container, $dir, $after, $limit, $params );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( SwiftFileBackendDirList::class, 'SwiftFileBackendDirList' );
