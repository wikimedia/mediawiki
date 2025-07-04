<?php
/**
 * Helper class for representing operations with transaction support.
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
 */

namespace Wikimedia\FileBackend\FileOps;

use StatusValue;
use Wikimedia\FileBackend\FileBackend;

/**
 * Move a file from one storage path to another in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class MoveFileOp extends FileOp {
	/** @inheritDoc */
	protected function allowedParams() {
		return [
			[ 'src', 'dst' ],
			[ 'overwrite', 'overwriteSame', 'ignoreMissingSource', 'headers' ],
			[ 'src', 'dst' ]
		];
	}

	/** @inheritDoc */
	protected function doPrecheck(
		FileStatePredicates $opPredicates,
		FileStatePredicates $batchPredicates
	) {
		$status = StatusValue::newGood();

		// Check source file existence
		$srcExists = $this->resolveFileExistence( $this->params['src'], $opPredicates );
		if ( $srcExists === false ) {
			if ( $this->getParam( 'ignoreMissingSource' ) ) {
				$this->noOp = true; // no-op
				// Update file existence predicates (cache 404s)
				$batchPredicates->assumeFileDoesNotExist( $this->params['src'] );

				return $status; // nothing to do
			} else {
				$status->fatal( 'backend-fail-notexists', $this->params['src'] );

				return $status;
			}
		} elseif ( $srcExists === FileBackend::EXISTENCE_ERROR ) {
			$status->fatal( 'backend-fail-stat', $this->params['src'] );

			return $status;
		}
		// Check if an incompatible destination file exists
		$srcSize = function () use ( $opPredicates ) {
			static $size = null;
			$size ??= $this->resolveFileSize( $this->params['src'], $opPredicates );
			return $size;
		};
		$srcSha1 = function () use ( $opPredicates ) {
			static $sha1 = null;
			$sha1 ??= $this->resolveFileSha1Base36( $this->params['src'], $opPredicates );
			return $sha1;
		};
		$status->merge( $this->precheckDestExistence( $opPredicates, $srcSize, $srcSha1 ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()

		// Update file existence predicates if the operation is expected to be allowed to run
		if ( $status->isOK() ) {
			$batchPredicates->assumeFileExists( $this->params['dst'], $srcSize, $srcSha1 );
			if ( $this->params['src'] !== $this->params['dst'] ) {
				$batchPredicates->assumeFileDoesNotExist( $this->params['src'] );
			}
		}

		return $status; // safe to call attempt()
	}

	/** @inheritDoc */
	protected function doAttempt() {
		if ( $this->overwriteSameCase ) {
			if ( $this->params['src'] === $this->params['dst'] ) {
				// Do nothing to the destination (which is also the source)
				$status = StatusValue::newGood();
			} else {
				// Just delete the source as the destination file needs no changes
				$status = $this->backend->deleteInternal( $this->setFlags(
					[ 'src' => $this->params['src'] ]
				) );
			}
		} elseif ( $this->params['src'] === $this->params['dst'] ) {
			// Just update the destination file headers
			$headers = $this->getParam( 'headers' ) ?: [];
			$status = $this->backend->describeInternal( $this->setFlags(
				[ 'src' => $this->params['dst'], 'headers' => $headers ]
			) );
		} else {
			// Move the file to the destination
			$status = $this->backend->moveInternal( $this->setFlags( $this->params ) );
		}

		return $status;
	}

	/** @inheritDoc */
	public function storagePathsRead() {
		return [ $this->params['src'] ];
	}

	/** @inheritDoc */
	public function storagePathsChanged() {
		return [ $this->params['src'], $this->params['dst'] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MoveFileOp::class, 'MoveFileOp' );
