<?php
/**
 * OpenStack Swift based file backend.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
