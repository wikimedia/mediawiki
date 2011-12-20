<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Helper class for representing operations with transaction support.
 * FileBackend::doOperations() will require these classes for supported operations.
 * 
 * Use of large fields should be avoided as we want to be able to support
 * potentially many FileOp classes in large arrays in memory.
 * 
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileOp {
	/** $var Array */
	protected $params = array();
	/** $var FileBackendBase */
	protected $backend;
	/** @var TempFSFile|null */
	protected $tmpSourceFile, $tmpDestFile;

	protected $state = self::STATE_NEW; // integer
	protected $failed = false; // boolean
	protected $useBackups = true; // boolean
	protected $destSameAsSource = false; // boolean
	protected $destAlreadyExists = false; // boolean

	/* Object life-cycle */
	const STATE_NEW = 1;
	const STATE_CHECKED = 2;
	const STATE_ATTEMPTED = 3;
	const STATE_DONE = 4;

	/**
	 * Build a new file operation transaction
	 *
	 * @params $backend FileBackend
	 * @params $params Array
	 */
	final public function __construct( FileBackendBase $backend, array $params ) {
		$this->backend = $backend;
		foreach ( $this->allowedParams() as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			}
		}
		$this->params = $params;
	}

	/**
	 * Disable file backups for this operation
	 *
	 * @return void
	 */
	final protected function disableBackups() {
		$this->useBackups = false;
	}

	/**
	 * Attempt a series of file operations.
	 * Callers are responsible for handling file locking.
	 * 
	 * @param $performOps Array List of FileOp operations
	 * @param $opts Array Batch operation options
	 * @return Status 
	 */
	final public static function attemptBatch( array $performOps, array $opts ) {
		$status = Status::newGood();

		$ignoreErrors = isset( $opts['ignoreErrors'] ) && $opts['ignoreErrors'];
		$predicates = FileOp::newPredicates(); // account for previous op in prechecks
		// Do pre-checks for each operation; abort on failure...
		foreach ( $performOps as $index => $fileOp ) {
			$status->merge( $fileOp->precheck( $predicates ) );
			if ( !$status->isOK() ) { // operation failed?
				if ( $ignoreErrors ) {
					++$status->failCount;
					$status->success[$index] = false;
				} else {
					return $status;
				}
			}
		}

		// Attempt each operation; abort on failure...
		foreach ( $performOps as $index => $fileOp ) {
			if ( $fileOp->failed() ) {
				continue; // nothing to do
			} elseif ( $ignoreErrors ) {
				$fileOp->disableBackups(); // no chance of revert() calls
			}
			$status->merge( $fileOp->attempt() );
			if ( !$status->isOK() ) { // operation failed?
				if ( $ignoreErrors ) {
					++$status->failCount;
					$status->success[$index] = false;
				} else {
					// Revert everything done so far and abort.
					// Do this by reverting each previous operation in reverse order.
					$pos = $index - 1; // last one failed; no need to revert()
					while ( $pos >= 0 ) {
						if ( !$performOps[$pos]->failed() ) {
							$status->merge( $performOps[$pos]->revert() );
						}
						$pos--;
					}
					return $status;
				}
			}
		}

		// Finish each operation...
		foreach ( $performOps as $index => $fileOp ) {
			if ( $fileOp->failed() ) {
				continue; // nothing to do
			}
			$subStatus = $fileOp->finish();
			if ( $subStatus->isOK() ) {
				++$status->successCount;
				$status->success[$index] = true;
			} else {
				++$status->failCount;
				$status->success[$index] = false;
			}
			$status->merge( $subStatus );
		}

		// Make sure status is OK, despite any finish() fatals
		$status->setResult( true, $status->value );

		return $status;
	}

	/**
	 * Get the value of the parameter with the given name.
	 * Returns null if the parameter is not set.
	 * 
	 * @param $name string
	 * @return mixed
	 */
	final public function getParam( $name ) {
		return isset( $this->params[$name] ) ? $this->params[$name] : null;
	}

	/**
	 * Check if this operation failed precheck() or attempt()
	 * @return type 
	 */
	final public function failed() {
		return $this->failed;
	}

	/**
	 * Get a new empty predicates array for precheck()
	 *
	 * @return Array 
	 */
	final public static function newPredicates() {
		return array( 'exists' => array() );
	}

	/**
	 * Check preconditions of the operation without writing anything
	 *
	 * @param $predicates Array
	 * @return Status
	 */
	final public function precheck( array &$predicates ) {
		if ( $this->state !== self::STATE_NEW ) {
			return Status::newFatal( 'fileop-fail-state', self::STATE_NEW, $this->state );
		}
		$this->state = self::STATE_CHECKED;
		$status = $this->doPrecheck( $predicates );
		if ( !$status->isOK() ) {
			$this->failed = true;
		}
		return $status;
	}

	/**
	 * Attempt the operation, backing up files as needed; this must be reversible
	 *
	 * @return Status
	 */
	final public function attempt() {
		if ( $this->state !== self::STATE_CHECKED ) {
			return Status::newFatal( 'fileop-fail-state', self::STATE_CHECKED, $this->state );
		} elseif ( $this->failed ) { // failed precheck
			return Status::newFatal( 'fileop-fail-attempt-precheck' );
		}
		$this->state = self::STATE_ATTEMPTED;
		$status = $this->doAttempt();
		if ( !$status->isOK() ) {
			$this->failed = true;
			$this->logFailure( 'attempt' );
		}
		return $status;
	}

	/**
	 * Revert the operation; affected files are restored
	 *
	 * @return Status
	 */
	final public function revert() {
		if ( $this->state !== self::STATE_ATTEMPTED ) {
			return Status::newFatal( 'fileop-fail-state', self::STATE_ATTEMPTED, $this->state );
		}
		$this->state = self::STATE_DONE;
		if ( $this->failed ) {
			$status = Status::newGood(); // nothing to revert
		} else {
			$status = $this->doRevert();
			if ( !$status->isOK() ) {
				$this->logFailure( 'revert' );
			}
		}
		return $status;
	}

	/**
	 * Finish the operation; this may be irreversible
	 *
	 * @return Status
	 */
	final public function finish() {
		if ( $this->state !== self::STATE_ATTEMPTED ) {
			return Status::newFatal( 'fileop-fail-state', self::STATE_ATTEMPTED, $this->state );
		}
		$this->state = self::STATE_DONE;
		if ( $this->failed ) {
			$status = Status::newGood(); // nothing to finish
		} else {
			$status = $this->doFinish();
		}
		return $status;
	}

	/**
	 * Get a list of storage paths read from for this operation
	 *
	 * @return Array
	 */
	public function storagePathsRead() {
		return array();
	}

	/**
	 * Get a list of storage paths written to for this operation
	 *
	 * @return Array
	 */
	public function storagePathsChanged() {
		return array();
	}

	/**
	 * @return Array List of allowed parameters
	 */
	protected function allowedParams() {
		return array();
	}

	/**
	 * @return Status
	 */
	protected function doPrecheck( array &$predicates ) {
		return Status::newGood();
	}

	/**
	 * @return Status
	 */
	abstract protected function doAttempt();

	/**
	 * @return Status
	 */
	abstract protected function doRevert();

	/**
	 * @return Status
	 */
	protected function doFinish() {
		return Status::newGood();
	}

	/**
	 * Check if the destination file exists and update the
	 * destAlreadyExists member variable. A bad status will
	 * be returned if there is no chance it can be overwritten.
	 * 
	 * @param $predicates Array
	 * @return Status
	 */
	protected function precheckDestExistence( array $predicates ) {
		$status = Status::newGood();
		if ( $this->fileExists( $this->params['dst'], $predicates ) ) {
			$this->destAlreadyExists = true;
			if ( !$this->getParam( 'overwriteDest' ) && !$this->getParam( 'overwriteSame' ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $this->params['dst'] );
				return $status;
			}
		} else {
			$this->destAlreadyExists = false;
		}
		return $status;
	}

	/**
	 * Backup any file at the source to a temporary file
	 *
	 * @return Status
	 */
	protected function backupSource() {
		$status = Status::newGood();
		if ( $this->useBackups ) {
			// Check if a file already exists at the source...
			$params = array( 'src' => $this->params['src'] );
			if ( $this->backend->fileExists( $params ) ) {
				// Create a temporary backup copy...
				$this->tmpSourcePath = $this->backend->getLocalCopy( $params );
				if ( $this->tmpSourcePath === null ) {
					$status->fatal( 'backend-fail-backup', $this->params['src'] );
					return $status;
				}
			}
		}
		return $status;
	}

	/**
	 * Backup the file at the destination to a temporary file.
	 * Don't bother backing it up unless we might overwrite the file.
	 * This assumes that the destination is in the backend and that
	 * the source is either in the backend or on the file system.
	 * This also handles the 'overwriteSame' check logic and updates
	 * the destSameAsSource member variable.
	 *
	 * @return Status
	 */
	protected function checkAndBackupDest() {
		$status = Status::newGood();
		$this->destSameAsSource = false;

		if ( $this->getParam( 'overwriteDest' ) ) {
			if ( $this->useBackups ) {
				// Create a temporary backup copy...
				$params = array( 'src' => $this->params['dst'] );
				$this->tmpDestFile = $this->backend->getLocalCopy( $params );
				if ( !$this->tmpDestFile ) {
					$status->fatal( 'backend-fail-backup', $this->params['dst'] );
					return $status;
				}
			}
		} elseif ( $this->getParam( 'overwriteSame' ) ) {
			$shash = $this->getSourceSha1Base36();
			// If there is a single source, then we can do some checks already.
			// For things like concatenate(), we would need to build a temp file
			// first and thus don't support 'overwriteSame' ($shash is null).
			if ( $shash !== null ) {
				$dhash = $this->getFileSha1Base36( $this->params['dst'] );
				if ( !strlen( $shash ) || !strlen( $dhash ) ) {
					$status->fatal( 'backend-fail-hashes' );
				} elseif ( $shash !== $dhash ) {
					// Give an error if the files are not identical
					$status->fatal( 'backend-fail-notsame', $this->params['dst'] );
				} else {
					$this->destSameAsSource = true;
				}
				return $status; // do nothing; either OK or bad status
			}
		} else {
			$status->fatal( 'backend-fail-alreadyexists', $this->params['dst'] );
			return $status;
		}

		return $status;
	}

	/**
	 * checkAndBackupDest() helper function to get the source file Sha1.
	 * Returns false on failure and null if there is no single source.
	 *
	 * @return string|false|null
	 */
	protected function getSourceSha1Base36() {
		return null; // N/A
	}

	/**
	 * checkAndBackupDest() helper function to get the Sha1 of a file.
	 *
	 * @return string|false False on failure
	 */
	protected function getFileSha1Base36( $path ) {
		// Source file is in backend
		if ( FileBackend::isStoragePath( $path ) ) {
			// For some backends (e.g. Swift, Azure) we can get
			// standard hashes to use for this types of comparisons.
			$hash = $this->backend->getFileSha1Base36( array( 'src' => $path ) );
		// Source file is on file system
		} else {
			wfSuppressWarnings();
			$hash = sha1_file( $path );
			wfRestoreWarnings();
			if ( $hash !== false ) {
				$hash = wfBaseConvert( $hash, 16, 36, 31 );
			}
		}
		return $hash;
	}

	/**
	 * Restore any temporary source backup file
	 *
	 * @return Status
	 */
	protected function restoreSource() {
		$status = Status::newGood();
		// Restore any file that was at the destination
		if ( $this->tmpSourcePath !== null ) {
			$params = array(
				'src'           => $this->tmpSourcePath,
				'dst'           => $this->params['src'],
				'overwriteDest' => true
			);
			$status = $this->backend->store( $params );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		return $status;
	}

	/**
	 * Restore any temporary destination backup file
	 *
	 * @return Status
	 */
	protected function restoreDest() {
		$status = Status::newGood();
		// Restore any file that was at the destination
		if ( $this->tmpDestFile ) {
			$params = array(
				'src'           => $this->tmpDestFile->getPath(),
				'dst'           => $this->params['dst'],
				'overwriteDest' => true
			);
			$status = $this->backend->store( $params );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		return $status;
	}

	/**
	 * Check if a file will exist in storage when this operation is attempted
	 * 
	 * @param $source string Storage path
	 * @param $predicates Array
	 * @return bool 
	 */
	final protected function fileExists( $source, array $predicates ) {
		if ( isset( $predicates['exists'][$source] ) ) {
			return $predicates['exists'][$source]; // previous op assures this
		} else {
			return $this->backend->fileExists( array( 'src' => $source ) );
		}
	}

	/**
	 * Log a file operation failure and preserve any temp files
	 * 
	 * @param $fileOp FileOp
	 * @return void
	 */
	final protected function logFailure( $action ) {
		$params = $this->params;
		$params['failedAction'] = $action;
		// Preserve backup files just in case (for recovery)
		if ( $this->tmpSourceFile ) {
			$this->tmpSourceFile->preserve(); // don't purge
			$params['srcBackupPath'] = $this->tmpSourceFile->getPath();
		}
		if ( $this->tmpDestFile ) {
			$this->tmpDestFile->preserve(); // don't purge
			$params['dstBackupPath'] = $this->tmpDestFile->getPath();
		}
		try {
			wfDebugLog( 'FileOperation',
				get_class( $this ) . ' failed:' . serialize( $params ) );
		} catch ( Exception $e ) {
			// bad config? debug log error?
		}
	}
}

/**
 * Store a file into the backend from a file on the file system.
 * Parameters similar to FileBackend::store(), which include:
 *     src           : source path on file system
 *     dst           : destination storage path
 *     overwriteDest : do nothing and pass if an identical file exists at destination
 *     overwriteSame : override any existing file at destination
 */
class StoreFileOp extends FileOp {
	protected function allowedParams() {
		return array( 'src', 'dst', 'overwriteDest', 'overwriteSame' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Check if the source file exists on the file system
		if ( !is_file( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['dst']] = true;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		// Create a destination backup copy as needed
		if ( $this->destAlreadyExists ) {
			$status->merge( $this->checkAndBackupDest() );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		// Store the file at the destination
		if ( !$this->destSameAsSource ) {
			$status->merge( $this->backend->store( $this->params ) );
		}
		return $status;
	}

	protected function doRevert() {
		$status = Status::newGood();
		if ( !$this->destSameAsSource ) {
			// Restore any file that was at the destination,
			// overwritting what was put there in attempt()
			$status->merge( $this->restoreDest() );
		}
		return $status;
	}

	protected function getSourceSha1Base36() {
		return $this->getFileSha1Base36( $this->params['src'] );
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Create a file in the backend with the given content.
 * Parameters similar to FileBackend::create(), which include:
 *     content       : a string of raw file contents
 *     dst           : destination storage path
 *     overwriteDest : do nothing and pass if an identical file exists at destination
 *     overwriteSame : override any existing file at destination
 */
class CreateFileOp extends FileOp {
	protected function allowedParams() {
		return array( 'content', 'dst', 'overwriteDest', 'overwriteSame' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['dst']] = true;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		// Create a destination backup copy as needed
		if ( $this->destAlreadyExists ) {
			$status->merge( $this->checkAndBackupDest() );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		// Create the file at the destination
		if ( !$this->destSameAsSource ) {
			$status->merge( $this->backend->create( $this->params ) );
		}
		return $status;
	}

	protected function doRevert() {
		$status = Status::newGood();
		if ( !$this->destSameAsSource ) {
			// Restore any file that was at the destination,
			// overwritting what was put there in attempt()
			$status->merge( $this->restoreDest() );
		}
		return $status;
	}

	protected function getSourceSha1Base36() {
		return wfBaseConvert( sha1( $this->params['content'] ), 16, 36, 31 );
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Copy a file from one storage path to another in the backend.
 * Parameters similar to FileBackend::copy(), which include:
 *     src           : source storage path
 *     dst           : destination storage path
 *     overwriteDest : do nothing and pass if an identical file exists at destination
 *     overwriteSame : override any existing file at destination
 */
class CopyFileOp extends FileOp {
	protected function allowedParams() {
		return array( 'src', 'dst', 'overwriteDest', 'overwriteSame' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['dst']] = true;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		// Create a destination backup copy as needed
		if ( $this->destAlreadyExists ) {
			$status->merge( $this->checkAndBackupDest() );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		// Copy the file into the destination
		if ( !$this->destSameAsSource ) {
			$status->merge( $this->backend->copy( $this->params ) );
		}
		return $status;
	}

	protected function doRevert() {
		$status = Status::newGood();
		if ( !$this->destSameAsSource ) {
			// Restore any file that was at the destination,
			// overwritting what was put there in attempt()
			$status->merge( $this->restoreDest() );
		}
		return $status;
	}

	protected function getSourceSha1Base36() {
		return $this->getFileSha1Base36( $this->params['src'] );
	}

	public function storagePathsRead() {
		return array( $this->params['src'] );
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Move a file from one storage path to another in the backend.
 * Parameters similar to FileBackend::move(), which include:
 *     src           : source storage path
 *     dst           : destination storage path
 *     overwriteDest : do nothing and pass if an identical file exists at destination
 *     overwriteSame : override any existing file at destination
 */
class MoveFileOp extends FileOp {
	protected function allowedParams() {
		return array( 'src', 'dst', 'overwriteDest', 'overwriteSame' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['src']] = false;
		$predicates['exists'][$this->params['dst']] = true;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		// Create a destination backup copy as needed
		if ( $this->destAlreadyExists ) {
			$status->merge( $this->checkAndBackupDest() );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		if ( !$this->destSameAsSource ) {
			// Move the file into the destination
			$status->merge( $this->backend->move( $this->params ) );
		} else {
			// Create a source backup copy as needed
			$status->merge( $this->backupSource() );
			if ( !$status->isOK() ) {
				return $status;
			}
			// Just delete source as the destination needs no changes
			$params = array( 'src' => $this->params['src'] );
			$status->merge( $this->backend->delete( $params ) );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		return $status;
	}

	protected function doRevert() {
		$status = Status::newGood();
		if ( !$this->destSameAsSource ) {
			// Move the file back to the source
			$params = array(
				'src' => $this->params['dst'],
				'dst' => $this->params['src']
			);
			$status->merge( $this->backend->move( $params ) );
			if ( !$status->isOK() ) {
				return $status; // also can't restore any dest file
			}
			// Restore any file that was at the destination
			$status->merge( $this->restoreDest() );
		} else {
			// Restore any source file
			return $this->restoreSource();
		}

		return $status;
	}

	protected function getSourceSha1Base36() {
		return $this->getFileSha1Base36( $this->params['src'] );
	}

	public function storagePathsRead() {
		return array( $this->params['src'] );
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Combines files from severals storage paths into a new file in the backend.
 * Parameters similar to FileBackend::concatenate(), which include:
 *     srcs          : ordered source storage paths (e.g. chunk1, chunk2, ...)
 *     dst           : destination storage path
 *     overwriteDest : do nothing and pass if an identical file exists at destination
 */
class ConcatenateFileOp extends FileOp {
	protected function allowedParams() {
		return array( 'srcs', 'dst', 'overwriteDest' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Check that source files exists
		foreach ( $this->params['srcs'] as $source ) {
			if ( !$this->fileExists( $source, $predicates ) ) {
				$status->fatal( 'backend-fail-notexists', $source );
				return $status;
			}
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['dst']] = true;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		// Create a destination backup copy as needed
		if ( $this->destAlreadyExists ) {
			$status->merge( $this->checkAndBackupDest() );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		// Concatenate the file at the destination
		$status->merge( $this->backend->concatenate( $this->params ) );
		return $status;
	}

	protected function doRevert() {
		// Restore any file that was at the destination,
		// overwritting what was put there in attempt()
		return $this->restoreDest();
	}

	protected function getSourceSha1Base36() {
		return null; // defer this until we finish building the new file
	}

	public function storagePathsRead() {
		return $this->params['srcs'];
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Delete a file at the storage path.
 * Parameters similar to FileBackend::delete(), which include:
 *     src                 : source storage path
 *     ignoreMissingSource : don't return an error if the file does not exist
 */
class DeleteFileOp extends FileOp {
	protected $needsDelete = true;

	protected function allowedParams() {
		return array( 'src', 'ignoreMissingSource' );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			if ( !$this->getParam( 'ignoreMissingSource' ) ) {
				$status->fatal( 'backend-fail-notexists', $this->params['src'] );
				return $status;
			}
			$this->needsDelete = false;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['src']] = false;
		return $status;
	}

	protected function doAttempt() {
		$status = Status::newGood();
		if ( $this->needsDelete ) {
			// Create a source backup copy as needed
			$status->merge( $this->backupSource() );
			if ( !$status->isOK() ) {
				return $status;
			}
			// Delete the source file
			$status->merge( $this->backend->delete( $this->params ) );
			if ( !$status->isOK() ) {
				return $status;
			}
		}
		return $status;
	}

	protected function doRevert() {
		// Restore any source file that we deleted
		return $this->restoreSource();
	}

	public function storagePathsChanged() {
		return array( $this->params['src'] );
	}
}

/**
 * Placeholder operation that has no params and does nothing
 */
class NullFileOp extends FileOp {
	protected function doAttempt() {
		return Status::newGood();
	}

	protected function doRevert() {
		return Status::newGood();
	}
}
