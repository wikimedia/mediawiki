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

namespace Wikimedia\FileBackend\FileOps;

use StatusValue;

/**
 * Create a file in the backend with the given content.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class CreateFileOp extends FileOp {
	protected function allowedParams() {
		return [
			[ 'content', 'dst' ],
			[ 'overwrite', 'overwriteSame', 'headers' ],
			[ 'dst' ]
		];
	}

	protected function doPrecheck(
		FileStatePredicates $opPredicates,
		FileStatePredicates $batchPredicates
	) {
		$status = StatusValue::newGood();

		// Check if the source data is too big
		$sourceSize = $this->getSourceSize();
		$maxFileSize = $this->backend->maxFileSizeInternal();
		if ( $sourceSize > $maxFileSize ) {
			$status->fatal( 'backend-fail-maxsize', $this->params['dst'], $maxFileSize );

			return $status;
		}
		// Check if an incompatible destination file exists
		$sourceSha1 = $this->getSourceSha1Base36();
		$status->merge( $this->precheckDestExistence( $opPredicates, $sourceSize, $sourceSha1 ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()

		// Update file existence predicates if the operation is expected to be allowed to run
		if ( $status->isOK() ) {
			$batchPredicates->assumeFileExists( $this->params['dst'], $sourceSize, $sourceSha1 );
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( $this->overwriteSameCase ) {
			$status = StatusValue::newGood(); // nothing to do
		} else {
			// Create the file at the destination
			$status = $this->backend->createInternal( $this->setFlags( $this->params ) );
		}

		return $status;
	}

	protected function getSourceSize(): int {
		return strlen( $this->params['content'] );
	}

	protected function getSourceSha1Base36(): string {
		return \Wikimedia\base_convert( sha1( $this->params['content'] ), 16, 36, 31 );
	}

	public function storagePathsChanged() {
		return [ $this->params['dst'] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( CreateFileOp::class, 'CreateFileOp' );
