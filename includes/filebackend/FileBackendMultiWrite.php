<?php
/**
 * Proxy backend that mirrors writes to several internal backends.
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
 * @brief Proxy backend that mirrors writes to several internal backends.
 *
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
	protected $syncChecks = 0; // integer; bitfield
	protected $autoResync = false; // boolean

	/** @var Array */
	protected $noPushDirConts = array();
	protected $noPushQuickOps = false; // boolean

	/* Possible internal backend consistency checks */
	const CHECK_SIZE = 1;
	const CHECK_TIME = 2;
	const CHECK_SHA1 = 4;

	/**
	 * Construct a proxy backend that consists of several internal backends.
	 * Locking, journaling, and read-only checks are handled by the proxy backend.
	 *
	 * Additional $config params include:
	 *   - backends       : Array of backend config and multi-backend settings.
	 *                      Each value is the config used in the constructor of a
	 *                          FileBackendStore class, but with these additional settings:
	 *                        - class         : The name of the backend class
	 *                        - isMultiMaster : This must be set for one backend.
	 *                        - template:     : If given a backend name, this will use
	 *                                          the config of that backend as a template.
	 *                                          Values specified here take precedence.
	 *   - syncChecks     : Integer bitfield of internal backend sync checks to perform.
	 *                      Possible bits include the FileBackendMultiWrite::CHECK_* constants.
	 *                      There are constants for SIZE, TIME, and SHA1.
	 *                      The checks are done before allowing any file operations.
	 *   - autoResync     : Automatically resync the clone backends to the master backend
	 *                      when pre-operation sync checks fail. This should only be used
	 *                      if the master backend is stable and not missing any files.
	 *   - noPushQuickOps : (hack) Only apply doQuickOperations() to the master backend.
	 *   - noPushDirConts : (hack) Only apply directory functions to the master backend.
	 *
	 * @param $config Array
	 * @throws MWException
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->syncChecks = isset( $config['syncChecks'] )
			? $config['syncChecks']
			: self::CHECK_SIZE;
		$this->autoResync = !empty( $config['autoResync'] );
		$this->noPushQuickOps = isset( $config['noPushQuickOps'] )
			? $config['noPushQuickOps']
			: false;
		$this->noPushDirConts = isset( $config['noPushDirConts'] )
			? $config['noPushDirConts']
			: array();
		// Construct backends here rather than via registration
		// to keep these backends hidden from outside the proxy.
		$namesUsed = array();
		foreach ( $config['backends'] as $index => $config ) {
			if ( isset( $config['template'] ) ) {
				// Config is just a modified version of a registered backend's.
				// This should only be used when that config is used only by this backend.
				$config = $config + FileBackendGroup::singleton()->config( $config['template'] );
			}
			$name = $config['name'];
			if ( isset( $namesUsed[$name] ) ) { // don't break FileOp predicates
				throw new MWException( "Two or more backends defined with the name $name." );
			}
			$namesUsed[$name] = 1;
			// Alter certain sub-backend settings for sanity
			unset( $config['readOnly'] ); // use proxy backend setting
			unset( $config['fileJournal'] ); // use proxy backend journal
			$config['wikiId'] = $this->wikiId; // use the proxy backend wiki ID
			$config['lockManager'] = 'nullLockManager'; // lock under proxy backend
			if ( !empty( $config['isMultiMaster'] ) ) {
				if ( $this->masterIndex >= 0 ) {
					throw new MWException( 'More than one master backend defined.' );
				}
				$this->masterIndex = $index; // this is the "master"
				$config['fileJournal'] = $this->fileJournal; // log under proxy backend
			}
			// Create sub-backend object
			if ( !isset( $config['class'] ) ) {
				throw new MWException( 'No class given for a backend config.' );
			}
			$class = $config['class'];
			$this->backends[$index] = new $class( $config );
		}
		if ( $this->masterIndex < 0 ) { // need backends and must have a master
			throw new MWException( 'No master backend defined.' );
		}
	}

	/**
	 * @see FileBackend::doOperationsInternal()
	 * @return Status
	 */
	final protected function doOperationsInternal( array $ops, array $opts ) {
		$status = Status::newGood();

		$mbe = $this->backends[$this->masterIndex]; // convenience

		// Get the paths to lock from the master backend
		$realOps = $this->substOpBatchPaths( $ops, $mbe );
		$paths = $mbe->getPathsToLockForOpsInternal( $mbe->getOperationsInternal( $realOps ) );
		// Get the paths under the proxy backend's name
		$paths['sh'] = $this->unsubstPaths( $paths['sh'] );
		$paths['ex'] = $this->unsubstPaths( $paths['ex'] );
		// Try to lock those files for the scope of this function...
		if ( empty( $opts['nonLocking'] ) ) {
			// Try to lock those files for the scope of this function...
			$scopeLockS = $this->getScopedFileLocks( $paths['sh'], LockManager::LOCK_UW, $status );
			$scopeLockE = $this->getScopedFileLocks( $paths['ex'], LockManager::LOCK_EX, $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}
		// Clear any cache entries (after locks acquired)
		$this->clearCache();
		$opts['preserveCache'] = true; // only locked files are cached
		// Get the list of paths to read/write...
		$relevantPaths = $this->fileStoragePathsForOps( $ops );
		// Check if the paths are valid and accessible on all backends...
		$status->merge( $this->accessibilityCheck( $relevantPaths ) );
		if ( !$status->isOK() ) {
			return $status; // abort
		}
		// Do a consistency check to see if the backends are consistent...
		$syncStatus = $this->consistencyCheck( $relevantPaths );
		if ( !$syncStatus->isOK() ) {
			wfDebugLog( 'FileOperation', get_class( $this ) .
				" failed sync check: " . FormatJson::encode( $relevantPaths ) );
			// Try to resync the clone backends to the master on the spot...
			if ( !$this->autoResync || !$this->resyncFiles( $relevantPaths )->isOK() ) {
				$status->merge( $syncStatus );
				return $status; // abort
			}
		}
		// Actually attempt the operation batch on the master backend...
		$masterStatus = $mbe->doOperations( $realOps, $opts );
		$status->merge( $masterStatus );
		// Propagate the operations to the clone backends if there were no fatal errors.
		// If $ops only had one operation, this might avoid backend inconsistencies.
		// This also avoids inconsistency for expected errors (like "file already exists").
		if ( !count( $masterStatus->getErrorsArray() ) ) {
			foreach ( $this->backends as $index => $backend ) {
				if ( $index !== $this->masterIndex ) { // not done already
					$realOps = $this->substOpBatchPaths( $ops, $backend );
					$status->merge( $backend->doOperations( $realOps, $opts ) );
				}
			}
		}
		// Make 'success', 'successCount', and 'failCount' fields reflect
		// the overall operation, rather than all the batches for each backend.
		// Do this by only using success values from the master backend's batch.
		$status->success = $masterStatus->success;
		$status->successCount = $masterStatus->successCount;
		$status->failCount = $masterStatus->failCount;

		return $status;
	}

	/**
	 * Check that a set of files are consistent across all internal backends
	 *
	 * @param $paths Array List of storage paths
	 * @return Status
	 */
	public function consistencyCheck( array $paths ) {
		$status = Status::newGood();
		if ( $this->syncChecks == 0 || count( $this->backends ) <= 1 ) {
			return $status; // skip checks
		}

		$mBackend = $this->backends[$this->masterIndex];
		foreach ( $paths as $path ) {
			$params = array( 'src' => $path, 'latest' => true );
			$mParams = $this->substOpPaths( $params, $mBackend );
			// Stat the file on the 'master' backend
			$mStat = $mBackend->getFileStat( $mParams );
			if ( $this->syncChecks & self::CHECK_SHA1 ) {
				$mSha1 = $mBackend->getFileSha1Base36( $mParams );
			} else {
				$mSha1 = false;
			}
			// Check if all clone backends agree with the master...
			foreach ( $this->backends as $index => $cBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}
				$cParams = $this->substOpPaths( $params, $cBackend );
				$cStat = $cBackend->getFileStat( $cParams );
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
					if ( $this->syncChecks & self::CHECK_SHA1 ) {
						if ( $cBackend->getFileSha1Base36( $cParams ) !== $mSha1 ) { // wrong SHA1
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
	 * Check that a set of file paths are usable across all internal backends
	 *
	 * @param $paths Array List of storage paths
	 * @return Status
	 */
	public function accessibilityCheck( array $paths ) {
		$status = Status::newGood();
		if ( count( $this->backends ) <= 1 ) {
			return $status; // skip checks
		}

		foreach ( $paths as $path ) {
			foreach ( $this->backends as $backend ) {
				$realPath = $this->substPaths( $path, $backend );
				if ( !$backend->isPathUsableInternal( $realPath ) ) {
					$status->fatal( 'backend-fail-usable', $path );
				}
			}
		}

		return $status;
	}

	/**
	 * Check that a set of files are consistent across all internal backends
	 * and re-synchronize those files againt the "multi master" if needed.
	 *
	 * @param $paths Array List of storage paths
	 * @return Status
	 */
	public function resyncFiles( array $paths ) {
		$status = Status::newGood();

		$mBackend = $this->backends[$this->masterIndex];
		foreach ( $paths as $path ) {
			$mPath  = $this->substPaths( $path, $mBackend );
			$mSha1  = $mBackend->getFileSha1Base36( array( 'src' => $mPath ) );
			$mExist = $mBackend->fileExists( array( 'src' => $mPath ) );
			// Check if the master backend is available...
			if ( $mExist === null ) {
				$status->fatal( 'backend-fail-internal', $this->name );
			}
			// Check of all clone backends agree with the master...
			foreach ( $this->backends as $index => $cBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}
				$cPath = $this->substPaths( $path, $cBackend );
				$cSha1 = $cBackend->getFileSha1Base36( array( 'src' => $cPath ) );
				if ( $mSha1 === $cSha1 ) {
					// already synced; nothing to do
				} elseif ( $mSha1 ) { // file is in master
					$fsFile = $mBackend->getLocalReference( array( 'src' => $mPath ) );
					$status->merge( $cBackend->quickStore(
						array( 'src' => $fsFile->getPath(), 'dst' => $cPath )
					) );
				} elseif ( $mExist === false ) { // file is not in master
					$status->merge( $cBackend->quickDelete( array( 'src' => $cPath ) ) );
				}
			}
		}

		return $status;
	}

	/**
	 * Get a list of file storage paths to read or write for a list of operations
	 *
	 * @param $ops Array Same format as doOperations()
	 * @return Array List of storage paths to files (does not include directories)
	 */
	protected function fileStoragePathsForOps( array $ops ) {
		$paths = array();
		foreach ( $ops as $op ) {
			if ( isset( $op['src'] ) ) {
				$paths[] = $op['src'];
			}
			if ( isset( $op['srcs'] ) ) {
				$paths = array_merge( $paths, $op['srcs'] );
			}
			if ( isset( $op['dst'] ) ) {
				$paths[] = $op['dst'];
			}
		}
		return array_unique( array_filter( $paths, 'FileBackend::isStoragePath' ) );
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
	 * @param $ops array File operation array
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
	 * @see FileBackend::doQuickOperationsInternal()
	 * @return Status
	 */
	protected function doQuickOperationsInternal( array $ops ) {
		$status = Status::newGood();
		// Do the operations on the master backend; setting Status fields...
		$realOps = $this->substOpBatchPaths( $ops, $this->backends[$this->masterIndex] );
		$masterStatus = $this->backends[$this->masterIndex]->doQuickOperations( $realOps );
		$status->merge( $masterStatus );
		// Propagate the operations to the clone backends...
		if ( !$this->noPushQuickOps ) {
			foreach ( $this->backends as $index => $backend ) {
				if ( $index !== $this->masterIndex ) { // not done already
					$realOps = $this->substOpBatchPaths( $ops, $backend );
					$status->merge( $backend->doQuickOperations( $realOps ) );
				}
			}
		}
		// Make 'success', 'successCount', and 'failCount' fields reflect
		// the overall operation, rather than all the batches for each backend.
		// Do this by only using success values from the master backend's batch.
		$status->success = $masterStatus->success;
		$status->successCount = $masterStatus->successCount;
		$status->failCount = $masterStatus->failCount;
		return $status;
	}

	/**
	 * @param $path string Storage path
	 * @return bool Path container should have dir changes pushed to all backends
	 */
	protected function replicateContainerDirChanges( $path ) {
		list( $b, $shortCont, $r ) = self::splitStoragePath( $path );
		return !in_array( $shortCont, $this->noPushDirConts );
	}

	/**
	 * @see FileBackend::doPrepare()
	 * @return Status
	 */
	protected function doPrepare( array $params ) {
		$status = Status::newGood();
		$replicate = $this->replicateContainerDirChanges( $params['dir'] );
		foreach ( $this->backends as $index => $backend ) {
			if ( $replicate || $index == $this->masterIndex ) {
				$realParams = $this->substOpPaths( $params, $backend );
				$status->merge( $backend->doPrepare( $realParams ) );
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::doSecure()
	 * @param $params array
	 * @return Status
	 */
	protected function doSecure( array $params ) {
		$status = Status::newGood();
		$replicate = $this->replicateContainerDirChanges( $params['dir'] );
		foreach ( $this->backends as $index => $backend ) {
			if ( $replicate || $index == $this->masterIndex ) {
				$realParams = $this->substOpPaths( $params, $backend );
				$status->merge( $backend->doSecure( $realParams ) );
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::doPublish()
	 * @param $params array
	 * @return Status
	 */
	protected function doPublish( array $params ) {
		$status = Status::newGood();
		$replicate = $this->replicateContainerDirChanges( $params['dir'] );
		foreach ( $this->backends as $index => $backend ) {
			if ( $replicate || $index == $this->masterIndex ) {
				$realParams = $this->substOpPaths( $params, $backend );
				$status->merge( $backend->doPublish( $realParams ) );
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::doClean()
	 * @param $params array
	 * @return Status
	 */
	protected function doClean( array $params ) {
		$status = Status::newGood();
		$replicate = $this->replicateContainerDirChanges( $params['dir'] );
		foreach ( $this->backends as $index => $backend ) {
			if ( $replicate || $index == $this->masterIndex ) {
				$realParams = $this->substOpPaths( $params, $backend );
				$status->merge( $backend->doClean( $realParams ) );
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::concatenate()
	 * @param $params array
	 * @return Status
	 */
	public function concatenate( array $params ) {
		// We are writing to an FS file, so we don't need to do this per-backend
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->concatenate( $realParams );
	}

	/**
	 * @see FileBackend::fileExists()
	 * @param $params array
	 */
	public function fileExists( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->fileExists( $realParams );
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 * @param $params array
	 * @return bool|string
	 */
	public function getFileTimestamp( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileTimestamp( $realParams );
	}

	/**
	 * @see FileBackend::getFileSize()
	 * @param $params array
	 * @return bool|int
	 */
	public function getFileSize( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileSize( $realParams );
	}

	/**
	 * @see FileBackend::getFileStat()
	 * @param $params array
	 * @return Array|bool|null
	 */
	public function getFileStat( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileStat( $realParams );
	}

	/**
	 * @see FileBackend::getFileContents()
	 * @param $params array
	 * @return bool|string
	 */
	public function getFileContents( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileContents( $realParams );
	}

	/**
	 * @see FileBackend::getFileSha1Base36()
	 * @param $params array
	 * @return bool|string
	 */
	public function getFileSha1Base36( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileSha1Base36( $realParams );
	}

	/**
	 * @see FileBackend::getFileProps()
	 * @param $params array
	 * @return Array
	 */
	public function getFileProps( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileProps( $realParams );
	}

	/**
	 * @see FileBackend::streamFile()
	 * @param $params array
	 * @return \Status
	 */
	public function streamFile( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->streamFile( $realParams );
	}

	/**
	 * @see FileBackend::getLocalReference()
	 * @param $params array
	 * @return FSFile|null
	 */
	public function getLocalReference( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getLocalReference( $realParams );
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 * @param $params array
	 * @return null|TempFSFile
	 */
	public function getLocalCopy( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getLocalCopy( $realParams );
	}

	/**
	 * @see FileBackend::directoryExists()
	 * @param $params array
	 * @return bool|null
	 */
	public function directoryExists( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->directoryExists( $realParams );
	}

	/**
	 * @see FileBackend::getSubdirectoryList()
	 * @param $params array
	 * @return Array|null|Traversable
	 */
	public function getDirectoryList( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getDirectoryList( $realParams );
	}

	/**
	 * @see FileBackend::getFileList()
	 * @param $params array
	 * @return Array|null|\Traversable
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

	/**
	 * @see FileBackend::getScopedLocksForOps()
	 */
	public function getScopedLocksForOps( array $ops, Status $status ) {
		$fileOps = $this->backends[$this->masterIndex]->getOperationsInternal( $ops );
		// Get the paths to lock from the master backend
		$paths = $this->backends[$this->masterIndex]->getPathsToLockForOpsInternal( $fileOps );
		// Get the paths under the proxy backend's name
		$paths['sh'] = $this->unsubstPaths( $paths['sh'] );
		$paths['ex'] = $this->unsubstPaths( $paths['ex'] );
		return array(
			$this->getScopedFileLocks( $paths['sh'], LockManager::LOCK_UW, $status ),
			$this->getScopedFileLocks( $paths['ex'], LockManager::LOCK_EX, $status )
		);
	}
}
