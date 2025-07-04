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
 * Change metadata for a file at the given storage path in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DescribeFileOp extends FileOp {
	/** @inheritDoc */
	protected function allowedParams() {
		return [ [ 'src' ], [ 'headers' ], [ 'src' ] ];
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
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );

			return $status;
		} elseif ( $srcExists === FileBackend::EXISTENCE_ERROR ) {
			$status->fatal( 'backend-fail-stat', $this->params['src'] );

			return $status;
		}

		// Update file existence predicates since the operation is expected to be allowed to run
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
		$batchPredicates->assumeFileExists( $this->params['src'], $srcSize, $srcSha1 );

		return $status; // safe to call attempt()
	}

	/** @inheritDoc */
	protected function doAttempt() {
		// Update the source file's metadata
		return $this->backend->describeInternal( $this->setFlags( $this->params ) );
	}

	/** @inheritDoc */
	public function storagePathsChanged() {
		return [ $this->params['src'] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( DescribeFileOp::class, 'DescribeFileOp' );
