<?php
/**
 * Helper class for representing batch file operations.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend;

use StatusValue;
use Wikimedia\FileBackend\FileOpHandle\FileBackendStoreOpHandle;
use Wikimedia\FileBackend\FileOps\FileOp;
use Wikimedia\FileBackend\FileOps\FileStatePredicates;

/**
 * Helper class for representing batch file operations.
 * Do not use this class from places outside FileBackend.
 *
 * Methods should avoid throwing exceptions at all costs.
 *
 * @ingroup FileBackend
 * @since 1.20
 */
class FileOpBatch {
	/* Timeout related parameters */
	private const MAX_BATCH_SIZE = 1000; // integer

	/**
	 * Attempt to perform a series of file operations.
	 * Callers are responsible for handling file locking.
	 *
	 * $opts is an array of options, including:
	 *   - force        : Errors that would normally cause a rollback do not.
	 *                    The remaining operations are still attempted if any fail.
	 *   - concurrency  : Try to do this many operations in parallel when possible.
	 *
	 * The resulting StatusValue will be "OK" unless:
	 *   - a) unexpected operation errors occurred (network partitions, disk full...)
	 *   - b) predicted operation errors occurred and 'force' was not set
	 *
	 * @param FileOp[] $performOps List of FileOp operations
	 * @param array $opts Batch operation options
	 * @return StatusValue
	 */
	public static function attempt( array $performOps, array $opts ) {
		$status = StatusValue::newGood();

		$n = count( $performOps );
		if ( $n > self::MAX_BATCH_SIZE ) {
			$status->fatal( 'backend-fail-batchsize', $n, self::MAX_BATCH_SIZE );

			return $status;
		}

		$ignoreErrors = !empty( $opts['force'] );
		$maxConcurrency = $opts['concurrency'] ?? 1;

		$predicates = new FileStatePredicates(); // account for previous ops in prechecks
		$curBatch = []; // concurrent FileOp sub-batch accumulation
		$curBatchDeps = FileOp::newDependencies(); // paths used in FileOp sub-batch
		$pPerformOps = []; // ordered list of concurrent FileOp sub-batches
		$lastBackend = null; // last op backend name
		// Do pre-checks for each operation; abort on failure...
		foreach ( $performOps as $index => $fileOp ) {
			$backendName = $fileOp->getBackend()->getName();
			// Decide if this op can be done concurrently within this sub-batch
			// or if a new concurrent sub-batch must be started after this one...
			if ( $fileOp->dependsOn( $curBatchDeps )
				|| count( $curBatch ) >= $maxConcurrency
				|| ( $backendName !== $lastBackend && count( $curBatch ) )
			) {
				$pPerformOps[] = $curBatch; // push this batch
				$curBatch = []; // start a new sub-batch
				$curBatchDeps = FileOp::newDependencies();
			}
			$lastBackend = $backendName;
			$curBatch[$index] = $fileOp; // keep index
			// Update list of affected paths in this batch
			$curBatchDeps = $fileOp->applyDependencies( $curBatchDeps );
			// Simulate performing the operation...
			$subStatus = $fileOp->precheck( $predicates ); // updates $predicates
			$status->merge( $subStatus );
			if ( !$subStatus->isOK() ) {
				// operation failed?
				$status->success[$index] = false;
				++$status->failCount;
				if ( !$ignoreErrors ) {
					return $status; // abort
				}
			}
		}
		// Push the last sub-batch
		if ( count( $curBatch ) ) {
			$pPerformOps[] = $curBatch;
		}

		if ( $ignoreErrors ) { // treat precheck() fatals as mere warnings
			$status->setResult( true, $status->value );
		}

		// Attempt each operation (in parallel if allowed and possible)...
		self::runParallelBatches( $pPerformOps, $status );

		return $status;
	}

	/**
	 * Attempt a list of file operations sub-batches in series.
	 *
	 * The operations *in* each sub-batch will be done in parallel.
	 * The caller is responsible for making sure the operations
	 * within any given sub-batch do not depend on each other.
	 * This will abort remaining ops on failure.
	 *
	 * @param FileOp[][] $pPerformOps Batches of file ops (batches use original indexes)
	 * @param StatusValue $status
	 */
	protected static function runParallelBatches( array $pPerformOps, StatusValue $status ) {
		$aborted = false; // set to true on unexpected errors
		foreach ( $pPerformOps as $performOpsBatch ) {
			if ( $aborted ) { // check batch op abort flag...
				// We can't continue (even with $ignoreErrors) as $predicates is wrong.
				// Log the remaining ops as failed for recovery...
				foreach ( $performOpsBatch as $i => $fileOp ) {
					$status->success[$i] = false;
					++$status->failCount;
					$fileOp->logFailure( 'attempt_aborted' );
				}
				continue;
			}
			/** @var StatusValue[] $statuses */
			$statuses = [];
			$opHandles = [];
			// Get the backend; all sub-batch ops belong to a single backend
			/** @var FileBackendStore $backend */
			$backend = reset( $performOpsBatch )->getBackend();
			// Get the operation handles or actually do it if there is just one.
			// If attemptAsync() returns a StatusValue, it was either due to an error
			// or the backend does not support async ops and did it synchronously.
			foreach ( $performOpsBatch as $i => $fileOp ) {
				if ( !isset( $status->success[$i] ) ) { // didn't already fail in precheck()
					// Parallel ops may be disabled in config due to missing dependencies,
					// (e.g. needing popen()). When they are, $performOpsBatch has size 1.
					$subStatus = ( count( $performOpsBatch ) > 1 )
						? $fileOp->attemptAsync()
						: $fileOp->attempt();
					if ( $subStatus->value instanceof FileBackendStoreOpHandle ) {
						$opHandles[$i] = $subStatus->value; // deferred
					} else {
						$statuses[$i] = $subStatus; // done already
					}
				}
			}
			// Try to do all the operations concurrently...
			$statuses += $backend->executeOpHandlesInternal( $opHandles );
			// Marshall and merge all the responses (blocking)...
			foreach ( $performOpsBatch as $i => $fileOp ) {
				if ( !isset( $status->success[$i] ) ) { // didn't already fail in precheck()
					$subStatus = $statuses[$i];
					$status->merge( $subStatus );
					if ( $subStatus->isOK() ) {
						$status->success[$i] = true;
						++$status->successCount;
					} else {
						$status->success[$i] = false;
						++$status->failCount;
						$aborted = true; // set abort flag; we can't continue
					}
				}
			}
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileOpBatch::class, 'FileOpBatch' );
