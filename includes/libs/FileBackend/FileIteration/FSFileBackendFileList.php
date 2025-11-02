<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileIteration;

class FSFileBackendFileList extends FSFileBackendList {
	protected function filterViaNext() {
		while ( $this->iter->valid() ) {
			if ( !$this->iter->current()->isFile() ) {
				$this->iter->next(); // skip non-files and dot files
			} else {
				break;
			}
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FSFileBackendFileList::class, 'FSFileBackendFileList' );
