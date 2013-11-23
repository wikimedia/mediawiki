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
	/** @var array */
	protected $params = array();

	/** @var FileBackendStore */
	protected $backend;

	/** @var int */
	protected $state = self::STATE_NEW;

	/** @var bool */
	protected $failed = false;

	/** @var bool */
	protected $async = false;

	/** @var string */
	protected $batchId;

	/** @var bool Operation is not a no-op */
	protected $doOperation = true;

	/** @var string */
	protected $sourceSha1;

	/** @var bool */
	protected $overwriteSameCase;

	/** @var bool */
	protected $destExists;

	/* Object life-cycle */
	const STATE_NEW = 1;
	const STATE_CHECKED = 2;
	const STATE_ATTEMPTED = 3;

	/**
	 * Build a new batch file operation transaction
	 *
	 * @param FileBackendStore $backend
	 * @param array $params
	 * @throws FileBackendError
	 */
	final public function __construct( FileBackendStore $backend, array $params ) {
		$this->backend = $backend;
		list( $required, $optional, $paths ) = $this->allowedParams();
		foreach ( $required as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			} else {
				throw new FileBackendError( "File operation missing parameter '$name'." );
			}
		}
		foreach ( $optional as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			}
		}
		foreach ( $paths as $name ) {
			if ( isset( $this->params[$name] ) ) {
				// Normalize paths so the paths to the same file have the same string
				$this->params[$name] = self::normalizeIfValidStoragePath( $this->params[$name] );
			}
		}
	}

	/**
	 * Normalize a string if it is a valid storage path
	 *
	 * @param string $path
	 * @return string
	 */
	protected static function normalizeIfValidStoragePath( $path ) {
		if ( FileBackend::isStoragePath( $path ) ) {
			$res = FileBackend::normalizeStoragePath( $path );

			return ( $res !== null ) ? $res : $path;
		}

		return $path;
	}

	/**
	 * Set the batch UUID this operation belongs to
	 *
	 * @param string $batchId
	 */
	final public function setBatchId( $batchId ) {
		$this->batchId = $batchId;
	}

	/**
	 * Get the value of the parameter with the given name
	 *
	 * @param string $name
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
	 * @return array
	 */
	final public static function newPredicates() {
		return array( 'exists' => array(), 'sha1' => array() );
	}

	/**
	 * Get a new empty dependency tracking array for paths read/written to
	 *
	 * @return array
	 */
	final public static function newDependencies() {
		return array( 'read' => array(), 'write' => array() );
	}

	/**
	 * Update a dependency tracking array to account for this operation
	 *
	 * @param array $deps Prior path reads/writes; format of FileOp::newPredicates()
	 * @return array
	 */
	final public function applyDependencies( array $deps ) {
		$deps['read'] += array_fill_keys( $this->storagePathsRead(), 1 );
		$deps['write'] += array_fill_keys( $this->storagePathsChanged(), 1 );

		return $deps;
	}

	/**
	 * Check if this operation changes files listed in $paths
	 *
	 * @param array $deps Prior path reads/writes; format of FileOp::newPredicates()
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
	 * @param array $oPredicates Pre-op info about files (format of FileOp::newPredicates)
	 * @param array $nPredicates Post-op info about files (format of FileOp::newPredicates)
	 * @return array
	 */
	final public function getJournalEntries( array $oPredicates, array $nPredicates ) {
		if ( !$this->doOperation ) {
			return array(); // this is a no-op
		}
		$nullEntries = array();
		$updateEntries = array();
		$deleteEntries = array();
		$pathsUsed = array_merge( $this->storagePathsRead(), $this->storagePathsChanged() );
		foreach ( array_unique( $pathsUsed ) as $path ) {
			$nullEntries[] = array( // assertion for recovery
				'op' => 'null',
				'path' => $path,
				'newSha1' => $this->fileSha1( $path, $oPredicates )
			);
		}
		foreach ( $this->storagePathsChanged() as $path ) {
			if ( $nPredicates['sha1'][$path] === false ) { // deleted
				$deleteEntries[] = array(
					'op' => 'delete',
					'path' => $path,
					'newSha1' => ''
				);
			} else { // created/updated
				$updateEntries[] = array(
					'op' => $this->fileExists( $path, $oPredicates ) ? 'update' : 'create',
					'path' => $path,
					'newSha1' => $nPredicates['sha1'][$path]
				);
			}
		}

		return array_merge( $nullEntries, $updateEntries, $deleteEntries );
	}

	/**
	 * Check preconditions of the operation without writing anything.
	 * This must update $predicates for each path that the op can change
	 * except when a failing status object is returned.
	 *
	 * @param array $predicates
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
	 * @param array $predicates
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
		if ( $this->doOperation ) {
			$status = $this->doAttempt();
			if ( !$status->isOK() ) {
				$this->failed = true;
				$this->logFailure( 'attempt' );
			}
		} else { // no-op
			$status = Status::newGood();
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
	 * @return array (required params list, optional params list, list of params that are paths)
	 */
	protected function allowedParams() {
		return array( array(), array(), array() );
	}

	/**
	 * Adjust params to FileBackendStore internal file calls
	 *
	 * @param array $params
	 * @return array (required params list, optional params list)
	 */
	protected function setFlags( array $params ) {
		return array( 'async' => $this->async ) + $params;
	}

	/**
	 * Get a list of storage paths read from for this operation
	 *
	 * @return array
	 */
	public function storagePathsRead() {
		return array();
	}

	/**
	 * Get a list of storage paths written to for this operation
	 *
	 * @return array
	 */
	public function storagePathsChanged() {
		return array();
	}

	/**
	 * Check for errors with regards to the destination file already existing.
	 * Also set the destExists, overwriteSameCase and sourceSha1 member variables.
	 * A bad status will be returned if there is no chance it can be overwritten.
	 *
	 * @param array $predicates
	 * @return Status
	 */
	protected function precheckDestExistence( array $predicates ) {
		$status = Status::newGood();
		// Get hash of source file/string and the destination file
		$this->sourceSha1 = $this->getSourceSha1Base36(); // FS file or data string
		if ( $this->sourceSha1 === null ) { // file in storage?
			$this->sourceSha1 = $this->fileSha1( $this->params['src'], $predicates );
		}
		$this->overwriteSameCase = false;
		$this->destExists = $this->fileExists( $this->params['dst'], $predicates );
		if ( $this->destExists ) {
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
					$this->overwriteSameCase = true; // OK
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
	 * Subclasses should overwride this if the source is not in storage.
	 *
	 * @return string|bool Returns false on failure
	 */
	protected function getSourceSha1Base36() {
		return null; // N/A
	}

	/**
	 * Check if a file will exist in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param array $predicates
	 * @return bool
	 */
	final protected function fileExists( $source, array $predicates ) {
		if ( isset( $predicates['exists'][$source] ) ) {
			return $predicates['exists'][$source]; // previous op assures this
		} else {
			$params = array( 'src' => $source, 'latest' => true );

			return $this->backend->fileExists( $params );
		}
	}

	/**
	 * Get the SHA-1 of a file in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param array $predicates
	 * @return string|bool False on failure
	 */
	final protected function fileSha1( $source, array $predicates ) {
		if ( isset( $predicates['sha1'][$source] ) ) {
			return $predicates['sha1'][$source]; // previous op assures this
		} elseif ( isset( $predicates['exists'][$source] ) && !$predicates['exists'][$source] ) {
			return false; // previous op assures this
		} else {
			$params = array( 'src' => $source, 'latest' => true );

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
	 * @param string $action
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
 * Create a file in the backend with the given content.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class CreateFileOp extends FileOp {
	protected function allowedParams() {
		return array(
			array( 'content', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'headers' ),
			array( 'dst' )
		);
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source data is too big
		if ( strlen( $this->getParam( 'content' ) ) > $this->backend->maxFileSizeInternal() ) {
			$status->fatal( 'backend-fail-maxsize',
				$this->params['dst'], $this->backend->maxFileSizeInternal() );
			$status->fatal( 'backend-fail-create', $this->params['dst'] );

			return $status;
		// Check if a file can be placed/changed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-create', $this->params['dst'] );

			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( !$this->overwriteSameCase ) {
			// Create the file at the destination
			return $this->backend->createInternal( $this->setFlags( $this->params ) );
		}

		return Status::newGood();
	}

	protected function getSourceSha1Base36() {
		return wfBaseConvert( sha1( $this->params['content'] ), 16, 36, 31 );
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Store a file into the backend from a file on the file system.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class StoreFileOp extends FileOp {
	protected function allowedParams() {
		return array(
			array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'headers' ),
			array( 'src', 'dst' )
		);
	}

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
		// Check if a file can be placed/changed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-store', $this->params['src'], $this->params['dst'] );

			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( !$this->overwriteSameCase ) {
			// Store the file at the destination
			return $this->backend->storeInternal( $this->setFlags( $this->params ) );
		}

		return Status::newGood();
	}

	protected function getSourceSha1Base36() {
		wfSuppressWarnings();
		$hash = sha1_file( $this->params['src'] );
		wfRestoreWarnings();
		if ( $hash !== false ) {
			$hash = wfBaseConvert( $hash, 16, 36, 31 );
		}

		return $hash;
	}

	public function storagePathsChanged() {
		return array( $this->params['dst'] );
	}
}

/**
 * Copy a file from one storage path to another in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class CopyFileOp extends FileOp {
	protected function allowedParams() {
		return array(
			array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'ignoreMissingSource', 'headers' ),
			array( 'src', 'dst' )
		);
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			if ( $this->getParam( 'ignoreMissingSource' ) ) {
				$this->doOperation = false; // no-op
				// Update file existence predicates (cache 404s)
				$predicates['exists'][$this->params['src']] = false;
				$predicates['sha1'][$this->params['src']] = false;

				return $status; // nothing to do
			} else {
				$status->fatal( 'backend-fail-notexists', $this->params['src'] );

				return $status;
			}
			// Check if a file can be placed/changed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-copy', $this->params['src'], $this->params['dst'] );

			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( $this->overwriteSameCase ) {
			$status = Status::newGood(); // nothing to do
		} elseif ( $this->params['src'] === $this->params['dst'] ) {
			// Just update the destination file headers
			$headers = $this->getParam( 'headers' ) ?: array();
			$status = $this->backend->describeInternal( $this->setFlags( array(
				'src' => $this->params['dst'], 'headers' => $headers
			) ) );
		} else {
			// Copy the file to the destination
			$status = $this->backend->copyInternal( $this->setFlags( $this->params ) );
		}

		return $status;
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
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class MoveFileOp extends FileOp {
	protected function allowedParams() {
		return array(
			array( 'src', 'dst' ),
			array( 'overwrite', 'overwriteSame', 'ignoreMissingSource', 'headers' ),
			array( 'src', 'dst' )
		);
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			if ( $this->getParam( 'ignoreMissingSource' ) ) {
				$this->doOperation = false; // no-op
				// Update file existence predicates (cache 404s)
				$predicates['exists'][$this->params['src']] = false;
				$predicates['sha1'][$this->params['src']] = false;

				return $status; // nothing to do
			} else {
				$status->fatal( 'backend-fail-notexists', $this->params['src'] );

				return $status;
			}
		// Check if a file can be placed/changed at the destination
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['dst'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['dst'] );
			$status->fatal( 'backend-fail-move', $this->params['src'], $this->params['dst'] );

			return $status;
		}
		// Check if destination file exists
		$status->merge( $this->precheckDestExistence( $predicates ) );
		$this->params['dstExists'] = $this->destExists; // see FileBackendStore::setFileCache()
		if ( $status->isOK() ) {
			// Update file existence predicates
			$predicates['exists'][$this->params['src']] = false;
			$predicates['sha1'][$this->params['src']] = false;
			$predicates['exists'][$this->params['dst']] = true;
			$predicates['sha1'][$this->params['dst']] = $this->sourceSha1;
		}

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		if ( $this->overwriteSameCase ) {
			if ( $this->params['src'] === $this->params['dst'] ) {
				// Do nothing to the destination (which is also the source)
				$status = Status::newGood();
			} else {
				// Just delete the source as the destination file needs no changes
				$status = $this->backend->deleteInternal( $this->setFlags(
					array( 'src' => $this->params['src'] )
				) );
			}
		} elseif ( $this->params['src'] === $this->params['dst'] ) {
			// Just update the destination file headers
			$headers = $this->getParam( 'headers' ) ?: array();
			$status = $this->backend->describeInternal( $this->setFlags(
				array( 'src' => $this->params['dst'], 'headers' => $headers )
			) );
		} else {
			// Move the file to the destination
			$status = $this->backend->moveInternal( $this->setFlags( $this->params ) );
		}

		return $status;
	}

	public function storagePathsRead() {
		return array( $this->params['src'] );
	}

	public function storagePathsChanged() {
		return array( $this->params['src'], $this->params['dst'] );
	}
}

/**
 * Delete a file at the given storage path from the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DeleteFileOp extends FileOp {
	protected function allowedParams() {
		return array( array( 'src' ), array( 'ignoreMissingSource' ), array( 'src' ) );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			if ( $this->getParam( 'ignoreMissingSource' ) ) {
				$this->doOperation = false; // no-op
				// Update file existence predicates (cache 404s)
				$predicates['exists'][$this->params['src']] = false;
				$predicates['sha1'][$this->params['src']] = false;

				return $status; // nothing to do
			} else {
				$status->fatal( 'backend-fail-notexists', $this->params['src'] );

				return $status;
			}
		// Check if a file can be placed/changed at the source
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['src'] );
			$status->fatal( 'backend-fail-delete', $this->params['src'] );

			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['src']] = false;
		$predicates['sha1'][$this->params['src']] = false;

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		// Delete the source file
		return $this->backend->deleteInternal( $this->setFlags( $this->params ) );
	}

	public function storagePathsChanged() {
		return array( $this->params['src'] );
	}
}

/**
 * Change metadata for a file at the given storage path in the backend.
 * Parameters for this operation are outlined in FileBackend::doOperations().
 */
class DescribeFileOp extends FileOp {
	protected function allowedParams() {
		return array( array( 'src' ), array( 'headers' ), array( 'src' ) );
	}

	protected function doPrecheck( array &$predicates ) {
		$status = Status::newGood();
		// Check if the source file exists
		if ( !$this->fileExists( $this->params['src'], $predicates ) ) {
			$status->fatal( 'backend-fail-notexists', $this->params['src'] );

			return $status;
		// Check if a file can be placed/changed at the source
		} elseif ( !$this->backend->isPathUsableInternal( $this->params['src'] ) ) {
			$status->fatal( 'backend-fail-usable', $this->params['src'] );
			$status->fatal( 'backend-fail-describe', $this->params['src'] );

			return $status;
		}
		// Update file existence predicates
		$predicates['exists'][$this->params['src']] =
			$this->fileExists( $this->params['src'], $predicates );
		$predicates['sha1'][$this->params['src']] =
			$this->fileSha1( $this->params['src'], $predicates );

		return $status; // safe to call attempt()
	}

	protected function doAttempt() {
		// Update the source file's metadata
		return $this->backend->describeInternal( $this->setFlags( $this->params ) );
	}

	public function storagePathsChanged() {
		return array( $this->params['src'] );
	}
}

/**
 * Placeholder operation that has no params and does nothing
 */
class NullFileOp extends FileOp {
}
