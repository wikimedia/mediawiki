<?php
/**
 * Helper class for representing operations with transaction support.
 *
 * @license GPL-2.0-or-later
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
