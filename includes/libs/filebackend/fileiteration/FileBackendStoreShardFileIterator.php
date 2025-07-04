<?php
/**
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
 */

namespace Wikimedia\FileBackend\FileIteration;

use ArrayIterator;

/**
 * Iterator for listing regular files
 */
class FileBackendStoreShardFileIterator extends FileBackendStoreShardListIterator {
	/** @inheritDoc */
	protected function listFromShard( $container ) {
		$list = $this->backend->getFileListInternal(
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
class_alias( FileBackendStoreShardFileIterator::class, 'FileBackendStoreShardFileIterator' );
