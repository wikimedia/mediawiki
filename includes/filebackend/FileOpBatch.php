<?php
/**
 * Helper class for representing batch file operations.
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
 * @author Aaron Schulz
 */

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
	const MAX_BATCH_SIZE = 1000; // integer

	/**
	 * Attempt to perform a series of file operations.
	 * Callers are responsible for handling file locking.
	 *
	 * $opts is an array of options, including:
	 *   - force        : Errors that would normally cause a rollback do not.
	 *                    The remaining operations are still attempted if any fail.
	 *   - allowStale   : Don't require the latest available data.
	 *                    This can increase performance for non-critical writes.
	 *                    This has no effect unless the 'force' flag is set.
	 *   - nonJournaled : Don't log this operation batch in the file journal.
	 *   - concurrency  : Try to do this many operations in parallel when possible.
	 *
	 * The resulting Status will be "OK" unless:
	 *   - a) unexpected operation errors occurred (network partitions, disk full...)
	 *   - b) significant operation errors occurred and 'force' was not set
	 *
	 * @param $performOps Array List of FileOp operations
	 * @param $opts Array Batch operation options
	 * @param $journal FileJournal Journal to log operations to
	 * @return Status
	 */
	public static function attempt( array $performOps, array $opts, FileJournal $journal ) {
		wfProfileIn( __METHOD__ );
		$status = Status::newGood();

		$n = count( $performOps );
		if ( $n > self::MAX_BATCH_SIZE ) {
			$status->fatal( 'backend-fail-batchsize', $n, self::MAX_BATCH_SIZE );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		$batchId = $journal->getTimestampedUUID();
		$allowStale = !empty( $opts['allowStale'] );
		$ignoreErrors = !empty( $opts['force'] );
		$journaled = empty( $opts['nonJournaled'] );
		$maxConcurrency = isset( $opts['concurrency'] ) ? $opts['concurrency'] : 1;

		$entries = array(); // file journal entry list
		$predicates = FileOp::newPredicates(); // account for previous ops in prechecks
		$curBatch = array(); // concurrent FileOp sub-batch accumulation
		$curBatchDeps = FileOp::newDependencies(); // paths used in FileOp sub-batch
		$pPerformOps = array(); // ordered list of concurrent FileOp sub-batches
		$lastBackend = null; // last op backend name
		// Do pre-checks for each operation; abort on failure...
		foreach ( $performOps as $index => $fileOp ) {
			$backendName = $fileOp->getBackend()->getName();
			$fileOp->setBatchId( $batchId ); // transaction ID
			$fileOp->allowStaleReads( $allowStale ); // consistency level
			// Decide if this op can be done concurrently within this sub-batch
			// or if a new concurrent sub-batch must be started after this one...
			if ( $fileOp->dependsOn( $curBatchDeps )
				|| count( $curBatch ) >= $maxConcurrency
				|| ( $backendName !== $lastBackend && count( $curBatch ) )
			) {
				$pPerformOps[] = $curBatch; // push this batch
				$curBatch = array(); // start a new sub-batch
				$curBatchDeps = FileOp::newDependencies();
			}
			$lastBackend = $backendName;
			$curBatch[$index] = $fileOp; // keep index
			// Update list of affected paths in this batch
			$curBatchDeps = $fileOp->applyDependencies( $curBatchDeps );
			// Simulate performing the operation...
			$oldPredicates = $predicates;
			$subStatus = $fileOp->precheck( $predicates ); // updates $predicates
			$status->merge( $subStatus );
			if ( $subStatus->isOK() ) {
				if ( $journaled ) { // journal log entries
					$entries = array_merge( $entries,
						$fileOp->getJournalEntries( $oldPredicates, $predicates ) );
				}
			} else { // operation failed?
				$status->success[$index] = false;
				++$status->failCount;
				if ( !$ignoreErrors ) {
					wfProfileOut( __METHOD__ );
					return $status; // abort
				}
			}
		}
		// Push the last sub-batch
		if ( count( $curBatch ) ) {
			$pPerformOps[] = $curBatch;
		}

		// Log the operations in the file journal...
		if ( count( $entries ) ) {
			$subStatus = $journal->logChangeBatch( $entries, $batchId );
			if ( !$subStatus->isOK() ) {
				wfProfileOut( __METHOD__ );
				return $subStatus; // abort
			}
		}

		if ( $ignoreErrors ) { // treat precheck() fatals as mere warnings
			$status->setResult( true, $status->value );
		}

		// Attempt each operation (in parallel if allowed and possible)...
		if ( count( $pPerformOps ) < count( $performOps ) ) {
			self::runBatchParallel( $pPerformOps, $status );
		} else {
			self::runBatchSeries( $performOps, $status );
		}

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Attempt a list of file operations in series.
	 * This will abort remaining ops on failure.
	 *
	 * @param $performOps Array
	 * @param $status Status
	 * @return bool Success
	 */
	protected static function runBatchSeries( array $performOps, Status $status ) {
		foreach ( $performOps as $index => $fileOp ) {
			if ( $fileOp->failed() ) {
				continue; // nothing to do
			}
			$subStatus = $fileOp->attempt();
			$status->merge( $subStatus );
			if ( $subStatus->isOK() ) {
				$status->success[$index] = true;
				++$status->successCount;
			} else {
				$status->success[$index] = false;
				++$status->failCount;
				// We can't continue (even with $ignoreErrors) as $predicates is wrong.
				// Log the remaining ops as failed for recovery...
				for ( $i = ($index + 1); $i < count( $performOps ); $i++ ) {
					$performOps[$i]->logFailure( 'attempt_aborted' );
				}
				return false; // bail out
			}
		}
		return true;
	}

	/**
	 * Attempt a list of file operations sub-batches in series.
	 *
	 * The operations *in* each sub-batch will be done in parallel.
	 * The caller is responsible for making sure the operations
	 * within any given sub-batch do not depend on each other.
	 * This will abort remaining ops on failure.
	 *
	 * @param $pPerformOps Array
	 * @param $status Status
	 * @return bool Success
	 */
	protected static function runBatchParallel( array $pPerformOps, Status $status ) {
		$aborted = false;
		foreach ( $pPerformOps as $performOpsBatch ) {
			if ( $aborted ) { // check batch op abort flag...
				// We can't continue (even with $ignoreErrors) as $predicates is wrong.
				// Log the remaining ops as failed for recovery...
				foreach ( $performOpsBatch as $i => $fileOp ) {
					$performOpsBatch[$i]->logFailure( 'attempt_aborted' );
				}
				continue;
			}
			$statuses = array();
			$opHandles = array();
			// Get the backend; all sub-batch ops belong to a single backend
			$backend = reset( $performOpsBatch )->getBackend();
			// If attemptAsync() returns synchronously, it was either an
			// error Status or the backend just doesn't support async ops.
			foreach ( $performOpsBatch as $i => $fileOp ) {
				if ( !$fileOp->failed() ) { // failed => already has Status
					$subStatus = $fileOp->attemptAsync();
					if ( $subStatus->value instanceof FileBackendStoreOpHandle ) {
						$opHandles[$i] = $subStatus->value; // deferred
					} else {
						$statuses[$i] = $subStatus; // done already
					}
				}
			}
			// Try to do all the operations concurrently...
			$statuses = $statuses + $backend->executeOpHandlesInternal( $opHandles );
			// Marshall and merge all the responses (blocking)...
			foreach ( $performOpsBatch as $i => $fileOp ) {
				if ( !$fileOp->failed() ) { // failed => already has Status
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
		return $status;
	}
}
