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
 * Iterator for listing regular files
 */
class SwiftFileBackendFileList extends SwiftFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		[ $path, $stat ] = current( $this->iterableBuffer );
		$relPath = substr( $path, $this->suffixStart );
		if ( is_array( $stat ) ) {
			$storageDir = rtrim( $this->params['dir'], '/' );
			$this->backend->loadListingStatInternal( "$storageDir/$relPath", $stat );
		}

		return $relPath;
	}

	/** @inheritDoc */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( SwiftFileBackendFileList::class, 'SwiftFileBackendFileList' );
