<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileIteration;

class FSFileBackendDirList extends FSFileBackendList {
	protected function filterViaNext() {
		while ( $this->iter->valid() ) {
			if ( $this->iter->current()->isDot() || !$this->iter->current()->isDir() ) {
				$this->iter->next(); // skip non-directories and dot files
			} else {
				break;
			}
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FSFileBackendDirList::class, 'FSFileBackendDirList' );
