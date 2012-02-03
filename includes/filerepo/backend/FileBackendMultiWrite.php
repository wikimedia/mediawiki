<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * This class defines a multi-write backend. Multiple backends can be
 * registered to this proxy backend and it will act as a single backend.
 * Use this when all access to those backends is through this proxy backend.
 * At least one of the backends must be declared the "master" backend.
 *
 * Only use this class when transitioning from one storage system to another.
 *
 * Read operations are only done on the 'master' backend for consistency.
 * Write operations are performed on all backends, in the order defined.
 * If an operation fails on one backend it will be rolled back from the others.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FileBackendMultiWrite extends FileBackend {
	/** @var Array Prioritized list of FileBackendStore objects */
	protected $backends = array(); // array of (backend index => backends)
	protected $masterIndex = -1; // integer; index of master backend
	protected $syncChecks = 0; // integer bitfield

	/* Possible internal backend consistency checks */
	const CHECK_SIZE = 1;
	const CHECK_TIME = 2;

	/**
	 * Construct a proxy backend that consists of several internal backends.
	 * Additional $config params include:
	 *     'backends'    : Array of backend config and multi-backend settings.
	 *                     Each value is the config used in the constructor of a
	 *                     FileBackendStore class, but with these additional settings:
	 *                         'class'         : The name of the backend class
	 *                         'isMultiMaster' : This must be set for one backend.
	 *     'syncChecks'  : Integer bitfield of internal backend sync checks to perform.
	 *                     Possible bits include self::CHECK_SIZE and self::CHECK_TIME.
	 *                     The checks are done before allowing any file operations.
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$namesUsed = array();
		// Construct backends here rather than via registration
		// to keep these backends hidden from outside the proxy.
		foreach ( $config['backends'] as $index => $config ) {
			$name = $config['name'];
			if ( isset( $namesUsed[$name] ) ) { // don't break FileOp predicates
				throw new MWException( "Two or more backends defined with the name $name." );
			}
			$namesUsed[$name] = 1;
			if ( !isset( $config['class'] ) ) {
				throw new MWException( 'No class given for a backend config.' );
			}
			$class = $config['class'];
			$this->backends[$index] = new $class( $config );
			if ( !empty( $config['isMultiMaster'] ) ) {
				if ( $this->masterIndex >= 0 ) {
					throw new MWException( 'More than one master backend defined.' );
				}
				$this->masterIndex = $index;
			}
		}
		if ( $this->masterIndex < 0 ) { // need backends and must have a master
			throw new MWException( 'No master backend defined.' );
		}
		$this->syncChecks = isset( $config['syncChecks'] )
			? $config['syncChecks']
			: self::CHECK_SIZE;
	}

	/**
	 * @see FileBackend::doOperationsInternal()
	 */
	final protected function doOperationsInternal( array $ops, array $opts ) {
		$status = Status::newGood();

		$performOps = array(); // list of FileOp objects
		$filesRead = $filesChanged = array(); // storage paths used
		// Build up a list of FileOps. The list will have all the ops
		// for one backend, then all the ops for the next, and so on.
		// These batches of ops are all part of a continuous array.
		// Also build up a list of files read/changed...
		foreach ( $this->backends as $index => $backend ) {
			$backendOps = $this->substOpBatchPaths( $ops, $backend );
			// Add on the operation batch for this backend
			$performOps = array_merge( $performOps, $backend->getOperations( $backendOps ) );
			if ( $index == 0 ) { // first batch
				// Get the files used for these operations. Each backend has a batch of
				// the same operations, so we only need to get them from the first batch.
				foreach ( $performOps as $fileOp ) {
					$filesRead = array_merge( $filesRead, $fileOp->storagePathsRead() );
					$filesChanged = array_merge( $filesChanged, $fileOp->storagePathsChanged() );
				}
				// Get the paths under the proxy backend's name
				$filesRead = $this->unsubstPaths( $filesRead );
				$filesChanged = $this->unsubstPaths( $filesChanged );
			}
		}

		// Try to lock those files for the scope of this function...
		if ( empty( $opts['nonLocking'] ) ) {
			$filesLockSh = array_diff( $filesRead, $filesChanged ); // optimization
			$filesLockEx = $filesChanged;
			// Get a shared lock on the parent directory of each path changed
			$filesLockSh = array_merge( $filesLockSh, array_map( 'dirname', $filesLockEx ) );
			// Try to lock those files for the scope of this function...
			$scopeLockS = $this->getScopedFileLocks( $filesLockSh, LockManager::LOCK_UW, $status );
			$scopeLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}

		// Clear any cache entries (after locks acquired)
		$this->clearCache();

		// Do a consistency check to see if the backends agree
		if ( count( $this->backends ) > 1 ) {
			$status->merge( $this->consistencyCheck( array_merge( $filesRead, $filesChanged ) ) );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}

		// Actually attempt the operation batch...
		$subStatus = FileOp::attemptBatch( $performOps, $opts );

		$success = array();
		$failCount = $successCount = 0;
		// Make 'success', 'successCount', and 'failCount' fields reflect
		// the overall operation, rather than all the batches for each backend.
		// Do this by only using success values from the master backend's batch.
		$batchStart = $this->masterIndex * count( $ops );
		$batchEnd = $batchStart + count( $ops ) - 1;
		for ( $i = $batchStart; $i <= $batchEnd; $i++ ) {
			if ( !isset( $subStatus->success[$i] ) ) {
				break; // failed out before trying this op
			} elseif ( $subStatus->success[$i] ) {
				++$successCount;
			} else {
				++$failCount;
			}
			$success[] = $subStatus->success[$i];
		}
		$subStatus->success = $success;
		$subStatus->successCount = $successCount;
		$subStatus->failCount = $failCount;

		// Merge errors into status fields
		$status->merge( $subStatus );
		$status->success = $subStatus->success; // not done in merge()

		return $status;
	}

	/**
	 * Check that a set of files are consistent across all internal backends
	 *
	 * @param $paths Array
	 * @return Status
	 */
	public function consistencyCheck( array $paths ) {
		$status = Status::newGood();
		if ( $this->syncChecks == 0 ) {
			return $status; // skip checks
		}

		$mBackend = $this->backends[$this->masterIndex];
		foreach ( array_unique( $paths ) as $path ) {
			$params = array( 'src' => $path, 'latest' => true );
			// Stat the file on the 'master' backend
			$mStat = $mBackend->getFileStat( $this->substOpPaths( $params, $mBackend ) );
			// Check of all clone backends agree with the master...
			foreach ( $this->backends as $index => $cBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}
				$cStat = $cBackend->getFileStat( $this->substOpPaths( $params, $cBackend ) );
				if ( $mStat ) { // file is in master
					if ( !$cStat ) { // file should exist
						$status->fatal( 'backend-fail-synced', $path );
						continue;
					}
					if ( $this->syncChecks & self::CHECK_SIZE ) {
						if ( $cStat['size'] != $mStat['size'] ) { // wrong size
							$status->fatal( 'backend-fail-synced', $path );
							continue;
						}
					}
					if ( $this->syncChecks & self::CHECK_TIME ) {
						$mTs = wfTimestamp( TS_UNIX, $mStat['mtime'] );
						$cTs = wfTimestamp( TS_UNIX, $cStat['mtime'] );
						if ( abs( $mTs - $cTs ) > 30 ) { // outdated file somewhere
							$status->fatal( 'backend-fail-synced', $path );
							continue;
						}
					}
				} else { // file is not in master
					if ( $cStat ) { // file should not exist
						$status->fatal( 'backend-fail-synced', $path );
					}
				}
			}
		}

		return $status;
	}

	/**
	 * Substitute the backend name in storage path parameters
	 * for a set of operations with that of a given internal backend.
	 * 
	 * @param $ops Array List of file operation arrays
	 * @param $backend FileBackendStore
	 * @return Array
	 */
	protected function substOpBatchPaths( array $ops, FileBackendStore $backend ) {
		$newOps = array(); // operations
		foreach ( $ops as $op ) {
			$newOp = $op; // operation
			foreach ( array( 'src', 'srcs', 'dst', 'dir' ) as $par ) {
				if ( isset( $newOp[$par] ) ) { // string or array
					$newOp[$par] = $this->substPaths( $newOp[$par], $backend );
				}
			}
			$newOps[] = $newOp;
		}
		return $newOps;
	}

	/**
	 * Same as substOpBatchPaths() but for a single operation
	 * 
	 * @param $op File operation array
	 * @param $backend FileBackendStore
	 * @return Array
	 */
	protected function substOpPaths( array $ops, FileBackendStore $backend ) {
		$newOps = $this->substOpBatchPaths( array( $ops ), $backend );
		return $newOps[0];
	}

	/**
	 * Substitute the backend of storage paths with an internal backend's name
	 * 
	 * @param $paths Array|string List of paths or single string path
	 * @param $backend FileBackendStore
	 * @return Array|string
	 */
	protected function substPaths( $paths, FileBackendStore $backend ) {
		return preg_replace(
			'!^mwstore://' . preg_quote( $this->name ) . '/!',
			StringUtils::escapeRegexReplacement( "mwstore://{$backend->getName()}/" ),
			$paths // string or array
		);
	}

	/**
	 * Substitute the backend of internal storage paths with the proxy backend's name
	 * 
	 * @param $paths Array|string List of paths or single string path
	 * @return Array|string
	 */
	protected function unsubstPaths( $paths ) {
		return preg_replace(
			'!^mwstore://([^/]+)!',
			StringUtils::escapeRegexReplacement( "mwstore://{$this->name}" ),
			$paths // string or array
		);
	}

	/**
	 * @see FileBackend::doPrepare()
	 */
	public function doPrepare( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->doPrepare( $realParams ) );
		}
		return $status;
	}

	/**
	 * @see FileBackend::doSecure()
	 */
	public function doSecure( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->doSecure( $realParams ) );
		}
		return $status;
	}

	/**
	 * @see FileBackend::doClean()
	 */
	public function doClean( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->doClean( $realParams ) );
		}
		return $status;
	}

	/**
	 * @see FileBackend::getFileList()
	 */
	public function concatenate( array $params ) {
		// We are writing to an FS file, so we don't need to do this per-backend
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->concatenate( $realParams );
	}

	/**
	 * @see FileBackend::fileExists()
	 */
	public function fileExists( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->fileExists( $realParams );
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 */
	public function getFileTimestamp( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileTimestamp( $realParams );
	}

	/**
	 * @see FileBackend::getFileSize()
	 */
	public function getFileSize( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileSize( $realParams );
	}

	/**
	 * @see FileBackend::getFileStat()
	 */
	public function getFileStat( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileStat( $realParams );
	}

	/**
	 * @see FileBackend::getFileContents()
	 */
	public function getFileContents( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileContents( $realParams );
	}

	/**
	 * @see FileBackend::getFileSha1Base36()
	 */
	public function getFileSha1Base36( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileSha1Base36( $realParams );
	}

	/**
	 * @see FileBackend::getFileProps()
	 */
	public function getFileProps( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileProps( $realParams );
	}

	/**
	 * @see FileBackend::streamFile()
	 */
	public function streamFile( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->streamFile( $realParams );
	}

	/**
	 * @see FileBackend::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getLocalReference( $realParams );
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 */
	public function getLocalCopy( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getLocalCopy( $realParams );
	}

	/**
	 * @see FileBackend::getFileList()
	 */
	public function getFileList( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileList( $realParams );
	}

	/**
	 * @see FileBackend::clearCache()
	 */
	public function clearCache( array $paths = null ) {
		foreach ( $this->backends as $backend ) {
			$realPaths = is_array( $paths ) ? $this->substPaths( $paths, $backend ) : null;
			$backend->clearCache( $realPaths );
		}
	}
}
