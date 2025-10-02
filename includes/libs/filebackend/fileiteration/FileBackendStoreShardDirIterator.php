<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileIteration;

use ArrayIterator;

/**
 * Iterator for listing directories
 */
class FileBackendStoreShardDirIterator extends FileBackendStoreShardListIterator {
	/** @inheritDoc */
	protected function listFromShard( $container ) {
		$list = $this->backend->getDirectoryListInternal(
			$container, $this->directory, $this->params );
		if ( $list === null ) {
			return new ArrayIterator( [] );
		} else {
			// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
			return is_array( $list ) ? new ArrayIterator( $list ) : $list;
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileBackendStoreShardDirIterator::class, 'FileBackendStoreShardDirIterator' );
