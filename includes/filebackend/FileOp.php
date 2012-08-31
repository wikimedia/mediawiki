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
 * @author Aaron Schulz
 */

/**
 * FileBackend helper class for representing operations.
 * Do not use this class from places outside FileBackend.
 *
 * Methods called from FileOpBatch::attempt() should avoid throwing
 * exceptions at all costs. FileOp objects should be lightweight in order
 * to support large arrays in memory and serialization.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
abstract class FileOp {
	/** @var Array */
	protected $params = array();
	/** @var FileBackendStore */
	protected $backend;

	protected $state = self::STATE_NEW; // integer
	protected $failed = false; // boolean
	protected $async = false; // boolean
	protected $useLatest = true; // boolean
	protected $batchId; // string

	protected $sourceSha1; // string
	protected $destSameAsSource; // boolean

	/* Object life-cycle */
	const STATE_NEW = 1;
	const STATE_CHECKED = 2;
	const STATE_ATTEMPTED = 3;

	/**
	 * Build a new file operation transaction
	 *
	 * @param $backend FileBackendStore
	 * @param $params Array
	 * @throws MWException
	 */
	final public function __construct( FileBackendStore $backend, array $params ) {
		$this->backend = $backend;
		list( $required, $optional ) = $this->allowedParams();
		foreach ( $required as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			} else {
				throw new MWException( "File operation missing parameter '$name'." );
			}
		}
		foreach ( $optional as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			}
		}
		$this->params = $params;
	}

	/**
	 * Set the batch UUID this operation belongs to
	 *
	 * @param $batchId string
	 * @return void
	 */
	final public function setBatchId( $batchId ) {
		$this->batchId = $batchId;
	}

	/**
	 * Whether to allow stale data for file reads and stat checks
	 *
	 * @param $allowStale bool
	 * @return void
	 */
	final public function allowStaleReads( $allowStale ) {
		$this->useLatest = !$allowStale;
	}

	/**
	 * Get the value of the parameter with the given name
	 *
	 * @param $name string
	 * @return mixed Returns null if the parameter is not set
	 */
	final public function getParam( $name ) {
		return isset( $this->params[$name] ) ? $this->params[$name] : null;
	}

	/**
	 * Check if this operation failed precheck() or attempt()
	 *
	 * @return bool
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
		return array( 'exists' => array(), 'sha1' => array() );
	}

	/**
	 * Get a new empty dependency tracking array for paths read/written to
	 *
	 * @return Array
	 */
	final public static function newDependencies() {
		return array( 'read' => array(), 'write' => array() );
	}

	/**
	 * Update a dependency tracking array to account for this operation
	 *
	 * @param $deps Array Prior path reads/writes; format of FileOp::newPredicates()
	 * @return Array
	 */
	final public function applyDependencies( array $deps ) {
		$deps['read']  += array_fill_keys( $this->storagePathsRead(), 1 );
		$deps['write'] += array_fill_keys( $this->storagePathsChanged(), 1 );
		return $deps;
	}

	/**
	 * Check if this operation changes files listed in $paths
	 *
	 * @param $paths Array Prior path reads/writes; format of FileOp::newPredicates()
	 * @return boolean
	 */
	final public function dependsOn( array $deps ) {
		foreach ( $this->storagePathsChanged() as $path ) {
			if ( isset( $deps['read'][$path] ) || isset( $deps['write'][$path] ) ) {
				return true; // "output" or "anti" dependency
			}
		}
		foreach ( $this->storagePathsRead() as $path ) {
			if ( isset( $deps['write'][$path] ) ) {
				return true; // "flow" dependency
			}
		}
		return false;
	}

	/**
	 * Get the file journal entries for this file operation
	 *
	 * @param $oPredicates Array Pre-op info about files (format of FileOp::newPredicates)
	 * @param $nPredicates Array Post-op info about files (format of FileOp::newPredicates)
	 * @return Array
	 */
	final public function getJournalEntries( array $oPredicates, array $nPredicates ) {
		$nullEntries = array();
		$updateEntries = array();
		$deleteEntries = array();
		$pathsUsed = array_merge( $this->storagePathsRead(), $this->storagePathsChanged() );
		foreach ( $pathsUsed as $path ) {
			$nullEntries[] = array( // assertion for recovery
				'op'      => 'null',
				'path'    => $path,
				'newSha1' => $this->fileSha1( $path, $oPredicates )
			);
		}
		foreach ( $this->storagePathsChanged() as $path ) {
			if ( $nPredicates['sha1'][$path] === false ) { // deleted
				$deleteEntries[] = array(
					'op'      => 'delete',
					'path'    => $path,
					'newSha1' => ''
				);
			} else { // created/updated
				$updateEntries[] = array(
					'op'      => $this->fileExists( $path, $oPredicates ) ? 'update' : 'create',
					'path'    => $path,
					'newSha1' => $nPredicates['sha1'][$path]
				);
			}
		}
		return array_merge( $nullEntries, $updateEntries, $deleteEntries );
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
	 * @return Status
	 */
	protected function doPrecheck( array &$predicates ) {
		return Status::newGood();
	}

	/**
	 * Attempt the operation
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
	 * @return Status
	 */
	protected function doAttempt() {
		return Status::newGood();
	}

	/**
	 * Attempt the operation in the background
	 *
	 * @return Status
	 */
	final public function attemptAsync() {
		$this->async = true;
		$result = $this->attempt();
		$this->async = false;
		return $result;
	}

	/**
	 * Get the file operation parameters
	 *
	 * @return Array (required params list, optional params list)
	 */
	protected function allowedParams() {
		return array( array(), array() );
	}

	/**
	 * Adjust params to FileBackendStore internal file calls
	 *
	 * @param $params Array
	 * @return Array (required params list, optional params list)
	 */
	protected function setFlags( array $params ) {
		return array( 'async' => $this->async ) + $params;
	}

	/**
	 * Get a list of storage paths read from for this operation
	 *
	 * @return Array
	 */
	final public function storagePathsRead() {
		return array_map( 'FileBackend::normalizeStoragePath', $this->doStoragePathsRead() );
	}

	/**
	 * @see FileOp::storagePathsRead()
	 * @return Array
	 */
	protected function doStoragePathsRead() {
		return array();
	}

	/**
	 * Get a list of storage paths written to for this operation
	 *
	 * @return Array
	 */
	final public function storagePathsChanged() {
		return array_map( 'FileBackend::normalizeStoragePath', $this->doStoragePathsChanged() );
	}

	/**
	 * @see FileOp::storagePathsChanged()
	 * @return Array
	 */
	protected function doStoragePathsChanged() {
		return array();
	}

	/**
	 * Check for errors with regards to the destination file already existing.
	 * This also updates the destSameAsSource and sourceSha1 member variables.
	 * A bad status will be returned if there is no chance it can be overwritten.
	 *
	 * @param $predicates Array
	 * @return Status
	 */
	protected function precheckDestExistence( array $predicates ) {
		$status = Status::newGood();
		// Get hash of source file/string and the destination file
		$this->sourceSha1 = $this->getSourceSha1Base36(); // FS file or data string
		if ( $this->sourceSha1 === null ) { // file in storage?
			$this->sourceSha1 = $this->fileSha1( $this->params['src'], $predicates );
		}
		$this->destSameAsSource = false;
		if ( $this->fileExists( $this->params['dst'], $predicates ) ) {
			if ( $this->getParam( 'overwrite' ) ) {
				return $status; // OK
			} elseif ( $this->getParam( 'overwriteSame' ) ) {
				$dhash = $this->fileSha1( $this->params['dst'], $predicates );
				// Check if hashes are valid and match each other...
				if ( !strlen( $this->sourceSha1 ) || !strlen( $dhash ) ) {
					$status->fatal( 'backend-fail-hashes' );
				} elseif ( $this->sourceSha1 !== $dhash ) {
					// Give an error if the files are not identical
					$status->fatal( 'backend-fail-notsame', $this->params['dst'] );
				} else {
					$this->destSameAsSource = true; // OK
				}
				return $status; // do nothing; either OK or bad status
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $this->params['dst'] );
				return $status;
			}
		}
		return $status;
	}

	/**
	 * precheckDestExistence() helper function to get the source file SHA-1.
	 * Subclasses should overwride this iff the source is not in storage.
	 *
	 * @return string|bool Returns false on failure
	 */
	protected function getSourceSha1Base36() {
		return null; // N/A
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
			$params = array( 'src' => $source, 'latest' => $this->useLatest );
			return $this->backend->fileExists( $params );
		}
	}

	/**
	 * Get the SHA-1 of a file in storage when this operation is attempted
	 *
	 * @param $source string Storage path
	 * @param $predicates Array
	 * @return string|bool False on failure
	 */
	final protected function fileSha1( $source, array $predicates ) {
		if ( isset( $predicates['sha1'][$source] ) ) {
			return $predicates['sha1'][$source]; // previous op assures this
		} else {
			$params = array( 'src' => $source, 'latest' => $this->useLatest );
			return $this->backend->getFileSha1Base36( $params );
		}
	}

	/**
	 * Get the backend this operation is for
	 *
	 * @return FileBackendStore
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * Log a file operation failure and preserve any temp files
	 *
	 * @param $action string
	 * @return void
	 */
	final public function logFailure( $action ) {
		$params = $this->params;
		$params['failedAction'] = $action;
		try {
			wfDebugLog( 'FileOperation', get_class( $this ) .
				" failed (batch #{$this->batchId}): " . FormatJson::encode( $params ) );
		} catch ( Exception $e ) {
			// bad config? debug log error?
		}
	}
}

/**
 * Store a file into the backend from a file on the file system.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class StoreFileOp extends FileOp {
	/**
	 * @return array
	 */
	protected function allowedParams() {
		return array( array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'disposition' ) );
	}

	/**
	 * @param $predicates array
	 * @return Status
	 */
	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists on the file system
		if ( !is_file( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		// Check if the source file is too big
		} elseif ( filesize( $this->params['src'] ) > $this->backend->maxFileSizeInternal() ) {
			$status->fatal( 'backend-fail-maxsize',
				$this->params['dst'], $this->backend->maxFileSizeInternal() );
			$status->fatal( 'backend-fail-store', $this->params['src'], $this->params['dst'] );
			return $status;
		// Check if a file can be placed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-store', $this->params['src'], $this->params['dst'] );
			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}
		return $status; // safe to call attempt()
	}

	/**
	 * @return Status
	 */
	protected function doAttempt() {
		// Store the file at the destination
		if ( !$this->destSameAsSource ) {
			return $this->backend->storeInternal( $this->setFlags( $this->params ) );
		}
		return Status::newGood();
	}

	/**
	 * @return bool|string
	 */
	protected function getSourceSha1Base36() {
		wfSuppressWarnings();
		$hash = sha1_file( $this->params['src'] );
		wfRestoreWarnings();
		if ( $hash !== false ) {
			$hash = wfBaseConvert( $hash, 16, 36, 31 );
		}
		return $hash;
	}

	protected function doStoragePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Create a file in the backend with the given content.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class CreateFileOp extends FileOp {
	protected function allowedParams() {
		return array( array( 'content', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'disposition' ) );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source data is too big
		if ( strlen( $this->getParam( 'content' ) ) > $this->backend->maxFileSizeInternal() ) {
			$status->fatal( 'backend-fail-maxsize',
				$this->params['dst'], $this->backend->maxFileSizeInternal() );
			$status->fatal( 'backend-fail-create', $this->params['dst'] );
			return $status;
		// Check if a file can be placed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-create', $this->params['dst'] );
			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}
		return $status; // safe to call attempt()
	}

	/**
	 * @return Status
	 */
	protected function doAttempt() {
		if ( !$this->destSameAsSource ) {
			// Create the file at the destination
			return $this->backend->createInternal( $this->setFlags( $this->params ) );
		}
		return Status::newGood();
	}

	/**
	 * @return bool|String
	 */
	protected function getSourceSha1Base36() {
		return wfBaseConvert( sha1( $this->params['content'] ), 16, 36, 31 );
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Copy a file from one storage path to another in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class CopyFileOp extends FileOp {
	/**
	 * @return array
	 */
	protected function allowedParams() {
		return array( array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'disposition' ) );
	}

	/**
	 * @param $predicates array
	 * @return Status
	 */
	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		// Check if a file can be placed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-copy', $this->params['src'], $this->params['dst'] );
			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}
		return $status; // safe to call attempt()
	}

	/**
	 * @return Status
	 */
	protected function doAttempt() {
		// Do nothing if the src/dst paths are the same
		if ( $this->params['src'] !== $this->params['dst'] ) {
			// Copy the file into the destination
			if ( !$this->destSameAsSource ) {
				return $this->backend->copyInternal( $this->setFlags( $this->params ) );
			}
		}
		return Status::newGood();
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsRead() {
		return array( $this->params['src'] );
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Move a file from one storage path to another in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class MoveFileOp extends FileOp {
	/**
	 * @return array
	 */
	protected function allowedParams() {
		return array( array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'disposition' ) );
	}

	/**
	 * @param $predicates array
	 * @return Status
	 */
	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );
			return $status;
		// Check if a file can be placed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-move', $this->params['src'], $this->params['dst'] );
			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['src']] = false;
			$predicates['sha1'][$this->params['src']] = false;
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}
		return $status; // safe to call attempt()
	}

	/**
	 * @return Status
	 */
	protected function doAttempt() {
		// Do nothing if the src/dst paths are the same
		if ( $this->params['src'] !== $this->params['dst'] ) {
			if ( !$this->destSameAsSource ) {
				// Move the file into the destination
				return $this->backend->moveInternal( $this->setFlags( $this->params ) );
			} else {
				// Just delete source as the destination needs no changes
				$params = array( 'src' => $this->params['src'] );
				return $this->backend->deleteInternal( $this->setFlags( $params ) );
			}
		}
		return Status::newGood();
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsRead() {
		return array( $this->params['src'] );
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsChanged() {
		return array( $this->params['src'], $this->params['dst'] );
	}
}

/**
 * Delete a file at the given storage path from the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DeleteFileOp extends FileOp {
	/**
	 * @return array
	 */
	protected function allowedParams() {
		return array( array( 'src' ), array( 'ignoreMissingSource' ) );
	}

	protected $needsDelete = true;

	/**
	 * @param array $predicates
	 * @return Status
	 */
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
		$predicates['sha1'][$this->params['src']] = false;
		return $status; // safe to call attempt()
	}

	/**
	 * @return Status
	 */
	protected function doAttempt() {
		if ( $this->needsDelete ) {
			// Delete the source file
			return $this->backend->deleteInternal( $this->setFlags( $this->params ) );
		}
		return Status::newGood();
	}

	/**
	 * @return array
	 */
	protected function doStoragePathsChanged() {
		return array( $this->params['src'] );
	}
}

/**
 * Placeholder operation that has no params and does nothing
 */
class NullFileOp extends FileOp {}
