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
 * Delete a file at the given storage path from the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DeleteFileOp extends FileOp {
	/** @inheritDoc */
	protected function allowedParams() {
		return [ [ 'src' ], [ 'ignoreMissingSource' ], [ 'src' ] ];
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

		// Update file existence predicates since the operation is expected to be allowed to run
		$batchPredicates->assumeFileDoesNotExist( $this->params['src'] );

		return $status; // safe to call attempt()
	}

	/** @inheritDoc */
	protected function doAttempt() {
		// Delete the source file
		return $this->backend->deleteInternal( $this->setFlags( $this->params ) );
	}

	/** @inheritDoc */
	public function storagePathsChanged() {
		return [ $this->params['src'] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( DeleteFileOp::class, 'DeleteFileOp' );
