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
 * Write operations are performed on all backends, starting with the master.
 * This makes a best-effort to have transactional semantics, but since requests
 * may sometimes fail, the use of "autoResync" or background scripts to fix
 * inconsistencies is important.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FileBackendMultiWrite extends FileBackend {
	/** @var FileBackendStore[] Prioritized list of FileBackendStore objects */
	protected $backends = [];

	/** @var int Index of master backend */
	protected $masterIndex = -1;
	/** @var int Index of read affinity backend */
	protected $readIndex = -1;

	/** @var int Bitfield */
	protected $syncChecks = 0;
	/** @var string|bool */
	protected $autoResync = false;

	/** @var bool */
	protected $asyncWrites = false;

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
	 *                      FileBackendStore class, but with these additional settings:
	 *                        - class         : The name of the backend class
	 *                        - isMultiMaster : This must be set for one backend.
	 *                        - readAffinity  : Use this for reads without 'latest' set.
	 *   - syncChecks     : Integer bitfield of internal backend sync checks to perform.
	 *                      Possible bits include the FileBackendMultiWrite::CHECK_* constants.
	 *                      There are constants for SIZE, TIME, and SHA1.
	 *                      The checks are done before allowing any file operations.
	 *   - autoResync     : Automatically resync the clone backends to the master backend
	 *                      when pre-operation sync checks fail. This should only be used
	 *                      if the master backend is stable and not missing any files.
	 *                      Use "conservative" to limit resyncing to copying newer master
	 *                      backend files over older (or non-existing) clone backend files.
	 *                      Cases that cannot be handled will result in operation abortion.
	 *   - replication    : Set to 'async' to defer file operations on the non-master backends.
	 *                      This will apply such updates post-send for web requests. Note that
	 *                      any checks from "syncChecks" are still synchronous.
	 *
	 * Bogus warning
	 * @suppress PhanAccessMethodProtected
	 *
	 * @param array $config
	 * @throws FileBackendError
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->syncChecks = $config['syncChecks'] ?? self::CHECK_SIZE;
		$this->autoResync = $config['autoResync'] ?? false;
		$this->asyncWrites = isset( $config['replication'] ) && $config['replication'] === 'async';
		// Construct backends here rather than via registration
		// to keep these backends hidden from outside the proxy.
		$namesUsed = [];
		foreach ( $config['backends'] as $index => $config ) {
			$name = $config['name'];
			if ( isset( $namesUsed[$name] ) ) { // don't break FileOp predicates
				throw new LogicException( "Two or more backends defined with the name $name." );
			}
			$namesUsed[$name] = 1;
			// Alter certain sub-backend settings for sanity
			unset( $config['readOnly'] ); // use proxy backend setting
			unset( $config['fileJournal'] ); // use proxy backend journal
			unset( $config['lockManager'] ); // lock under proxy backend
			$config['domainId'] = $this->domainId; // use the proxy backend wiki ID
			if ( !empty( $config['isMultiMaster'] ) ) {
				if ( $this->masterIndex >= 0 ) {
					throw new LogicException( 'More than one master backend defined.' );
				}
				$this->masterIndex = $index; // this is the "master"
				$config['fileJournal'] = $this->fileJournal; // log under proxy backend
			}
			if ( !empty( $config['readAffinity'] ) ) {
				$this->readIndex = $index; // prefer this for reads
			}
			// Create sub-backend object
			if ( !isset( $config['class'] ) ) {
				throw new InvalidArgumentException( 'No class given for a backend config.' );
			}
			$class = $config['class'];
			$this->backends[$index] = new $class( $config );
		}
		if ( $this->masterIndex < 0 ) { // need backends and must have a master
			throw new LogicException( 'No master backend defined.' );
		}
		if ( $this->readIndex < 0 ) {
			$this->readIndex = $this->masterIndex; // default
		}
	}

	final protected function doOperationsInternal( array $ops, array $opts ) {
		$status = $this->newStatus();

		$mbe = $this->backends[$this->masterIndex]; // convenience

		// Try to lock those files for the scope of this function...
		$scopeLock = null;
		if ( empty( $opts['nonLocking'] ) ) {
			// Try to lock those files for the scope of this function...
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scopeLock = $this->getScopedLocksForOps( $ops, $status );
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
			wfDebugLog( 'FileOperation', static::class .
				" failed sync check: " . FormatJson::encode( $relevantPaths ) );
			// Try to resync the clone backends to the master on the spot...
			if ( $this->autoResync === false
				|| !$this->resyncFiles( $relevantPaths, $this->autoResync )->isOK()
			) {
				$status->merge( $syncStatus );

				return $status; // abort
			}
		}
		// Actually attempt the operation batch on the master backend...
		$realOps = $this->substOpBatchPaths( $ops, $mbe );
		$masterStatus = $mbe->doOperations( $realOps, $opts );
		$status->merge( $masterStatus );
		// Propagate the operations to the clone backends if there were no unexpected errors
		// and if there were either no expected errors or if the 'force' option was used.
		// However, if nothing succeeded at all, then don't replicate any of the operations.
		// If $ops only had one operation, this might avoid backend sync inconsistencies.
		if ( $masterStatus->isOK() && $masterStatus->successCount > 0 ) {
			foreach ( $this->backends as $index => $backend ) {
				if ( $index === $this->masterIndex ) {
					continue; // done already
				}

				$realOps = $this->substOpBatchPaths( $ops, $backend );
				if ( $this->asyncWrites && !$this->hasVolatileSources( $ops ) ) {
					// Bind $scopeLock to the callback to preserve locks
					DeferredUpdates::addCallableUpdate(
						function () use ( $backend, $realOps, $opts, $scopeLock, $relevantPaths ) {
							wfDebugLog( 'FileOperationReplication',
								"'{$backend->getName()}' async replication; paths: " .
								FormatJson::encode( $relevantPaths ) );
							$backend->doOperations( $realOps, $opts );
						}
					);
				} else {
					wfDebugLog( 'FileOperationReplication',
						"'{$backend->getName()}' sync replication; paths: " .
						FormatJson::encode( $relevantPaths ) );
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
	 * @param array $paths List of storage paths
	 * @return StatusValue
	 */
	public function consistencyCheck( array $paths ) {
		$status = $this->newStatus();
		if ( $this->syncChecks == 0 || count( $this->backends ) <= 1 ) {
			return $status; // skip checks
		}

		// Preload all of the stat info in as few round trips as possible...
		foreach ( $this->backends as $backend ) {
			$realPaths = $this->substPaths( $paths, $backend );
			$backend->preloadFileStat( [ 'srcs' => $realPaths, 'latest' => true ] );
		}

		$mBackend = $this->backends[$this->masterIndex];
		foreach ( $paths as $path ) {
			$params = [ 'src' => $path, 'latest' => true ];
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
	 * @param array $paths List of storage paths
	 * @return StatusValue
	 */
	public function accessibilityCheck( array $paths ) {
		$status = $this->newStatus();
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
	 * and re-synchronize those files against the "multi master" if needed.
	 *
	 * @param array $paths List of storage paths
	 * @param string|bool $resyncMode False, True, or "conservative"; see __construct()
	 * @return StatusValue
	 */
	public function resyncFiles( array $paths, $resyncMode = true ) {
		$status = $this->newStatus();

		$mBackend = $this->backends[$this->masterIndex];
		foreach ( $paths as $path ) {
			$mPath = $this->substPaths( $path, $mBackend );
			$mSha1 = $mBackend->getFileSha1Base36( [ 'src' => $mPath, 'latest' => true ] );
			$mStat = $mBackend->getFileStat( [ 'src' => $mPath, 'latest' => true ] );
			if ( $mStat === null || ( $mSha1 !== false && !$mStat ) ) { // sanity
				$status->fatal( 'backend-fail-internal', $this->name );
				wfDebugLog( 'FileOperation', __METHOD__
					. ': File is not available on the master backend' );
				continue; // file is not available on the master backend...
			}
			// Check of all clone backends agree with the master...
			foreach ( $this->backends as $index => $cBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}
				$cPath = $this->substPaths( $path, $cBackend );
				$cSha1 = $cBackend->getFileSha1Base36( [ 'src' => $cPath, 'latest' => true ] );
				$cStat = $cBackend->getFileStat( [ 'src' => $cPath, 'latest' => true ] );
				if ( $cStat === null || ( $cSha1 !== false && !$cStat ) ) { // sanity
					$status->fatal( 'backend-fail-internal', $cBackend->getName() );
					wfDebugLog( 'FileOperation', __METHOD__ .
						': File is not available on the clone backend' );
					continue; // file is not available on the clone backend...
				}
				if ( $mSha1 === $cSha1 ) {
					// already synced; nothing to do
				} elseif ( $mSha1 !== false ) { // file is in master
					if ( $resyncMode === 'conservative'
						&& $cStat && $cStat['mtime'] > $mStat['mtime']
					) {
						$status->fatal( 'backend-fail-synced', $path );
						continue; // don't rollback data
					}
					$fsFile = $mBackend->getLocalReference(
						[ 'src' => $mPath, 'latest' => true ] );
					$status->merge( $cBackend->quickStore(
						[ 'src' => $fsFile->getPath(), 'dst' => $cPath ]
					) );
				} elseif ( $mStat === false ) { // file is not in master
					if ( $resyncMode === 'conservative' ) {
						$status->fatal( 'backend-fail-synced', $path );
						continue; // don't delete data
					}
					$status->merge( $cBackend->quickDelete( [ 'src' => $cPath ] ) );
				}
			}
		}

		if ( !$status->isOK() ) {
			wfDebugLog( 'FileOperation', static::class .
				" failed to resync: " . FormatJson::encode( $paths ) );
		}

		return $status;
	}

	/**
	 * Get a list of file storage paths to read or write for a list of operations
	 *
	 * @param array $ops Same format as doOperations()
	 * @return array List of storage paths to files (does not include directories)
	 */
	protected function fileStoragePathsForOps( array $ops ) {
		$paths = [];
		foreach ( $ops as $op ) {
			if ( isset( $op['src'] ) ) {
				// For things like copy/move/delete with "ignoreMissingSource" and there
				// is no source file, nothing should happen and there should be no errors.
				if ( empty( $op['ignoreMissingSource'] )
					|| $this->fileExists( [ 'src' => $op['src'] ] )
				) {
					$paths[] = $op['src'];
				}
			}
			if ( isset( $op['srcs'] ) ) {
				$paths = array_merge( $paths, $op['srcs'] );
			}
			if ( isset( $op['dst'] ) ) {
				$paths[] = $op['dst'];
			}
		}

		return array_values( array_unique( array_filter( $paths, 'FileBackend::isStoragePath' ) ) );
	}

	/**
	 * Substitute the backend name in storage path parameters
	 * for a set of operations with that of a given internal backend.
	 *
	 * @param array $ops List of file operation arrays
	 * @param FileBackendStore $backend
	 * @return array
	 */
	protected function substOpBatchPaths( array $ops, FileBackendStore $backend ) {
		$newOps = []; // operations
		foreach ( $ops as $op ) {
			$newOp = $op; // operation
			foreach ( [ 'src', 'srcs', 'dst', 'dir' ] as $par ) {
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
	 * @param array $ops File operation array
	 * @param FileBackendStore $backend
	 * @return array
	 */
	protected function substOpPaths( array $ops, FileBackendStore $backend ) {
		$newOps = $this->substOpBatchPaths( [ $ops ], $backend );

		return $newOps[0];
	}

	/**
	 * Substitute the backend of storage paths with an internal backend's name
	 *
	 * @param array|string $paths List of paths or single string path
	 * @param FileBackendStore $backend
	 * @return array|string
	 */
	protected function substPaths( $paths, FileBackendStore $backend ) {
		return preg_replace(
			'!^mwstore://' . preg_quote( $this->name, '!' ) . '/!',
			StringUtils::escapeRegexReplacement( "mwstore://{$backend->getName()}/" ),
			$paths // string or array
		);
	}

	/**
	 * Substitute the backend of internal storage paths with the proxy backend's name
	 *
	 * @param array|string $paths List of paths or single string path
	 * @return array|string
	 */
	protected function unsubstPaths( $paths ) {
		return preg_replace(
			'!^mwstore://([^/]+)!',
			StringUtils::escapeRegexReplacement( "mwstore://{$this->name}" ),
			$paths // string or array
		);
	}

	/**
	 * @param array $ops File operations for FileBackend::doOperations()
	 * @return bool Whether there are file path sources with outside lifetime/ownership
	 */
	protected function hasVolatileSources( array $ops ) {
		foreach ( $ops as $op ) {
			if ( $op['op'] === 'store' && !isset( $op['srcRef'] ) ) {
				return true; // source file might be deleted anytime after do*Operations()
			}
		}

		return false;
	}

	protected function doQuickOperationsInternal( array $ops ) {
		$status = $this->newStatus();
		// Do the operations on the master backend; setting StatusValue fields...
		$realOps = $this->substOpBatchPaths( $ops, $this->backends[$this->masterIndex] );
		$masterStatus = $this->backends[$this->masterIndex]->doQuickOperations( $realOps );
		$status->merge( $masterStatus );
		// Propagate the operations to the clone backends...
		foreach ( $this->backends as $index => $backend ) {
			if ( $index === $this->masterIndex ) {
				continue; // done already
			}

			$realOps = $this->substOpBatchPaths( $ops, $backend );
			if ( $this->asyncWrites && !$this->hasVolatileSources( $ops ) ) {
				DeferredUpdates::addCallableUpdate(
					function () use ( $backend, $realOps ) {
						$backend->doQuickOperations( $realOps );
					}
				);
			} else {
				$status->merge( $backend->doQuickOperations( $realOps ) );
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

	protected function doPrepare( array $params ) {
		return $this->doDirectoryOp( 'prepare', $params );
	}

	protected function doSecure( array $params ) {
		return $this->doDirectoryOp( 'secure', $params );
	}

	protected function doPublish( array $params ) {
		return $this->doDirectoryOp( 'publish', $params );
	}

	protected function doClean( array $params ) {
		return $this->doDirectoryOp( 'clean', $params );
	}

	/**
	 * @param string $method One of (doPrepare,doSecure,doPublish,doClean)
	 * @param array $params Method arguments
	 * @return StatusValue
	 */
	protected function doDirectoryOp( $method, array $params ) {
		$status = $this->newStatus();

		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		$masterStatus = $this->backends[$this->masterIndex]->$method( $realParams );
		$status->merge( $masterStatus );

		foreach ( $this->backends as $index => $backend ) {
			if ( $index === $this->masterIndex ) {
				continue; // already done
			}

			$realParams = $this->substOpPaths( $params, $backend );
			if ( $this->asyncWrites ) {
				DeferredUpdates::addCallableUpdate(
					function () use ( $backend, $method, $realParams ) {
						$backend->$method( $realParams );
					}
				);
			} else {
				$status->merge( $backend->$method( $realParams ) );
			}
		}

		return $status;
	}

	public function concatenate( array $params ) {
		$status = $this->newStatus();
		// We are writing to an FS file, so we don't need to do this per-backend
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$status->merge( $this->backends[$index]->concatenate( $realParams ) );

		return $status;
	}

	public function fileExists( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->fileExists( $realParams );
	}

	public function getFileTimestamp( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileTimestamp( $realParams );
	}

	public function getFileSize( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileSize( $realParams );
	}

	public function getFileStat( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileStat( $realParams );
	}

	public function getFileXAttributes( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileXAttributes( $realParams );
	}

	public function getFileContentsMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$contentsM = $this->backends[$index]->getFileContentsMulti( $realParams );

		$contents = []; // (path => FSFile) mapping using the proxy backend's name
		foreach ( $contentsM as $path => $data ) {
			$contents[$this->unsubstPaths( $path )] = $data;
		}

		return $contents;
	}

	public function getFileSha1Base36( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileSha1Base36( $realParams );
	}

	public function getFileProps( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileProps( $realParams );
	}

	public function streamFile( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->streamFile( $realParams );
	}

	public function getLocalReferenceMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$fsFilesM = $this->backends[$index]->getLocalReferenceMulti( $realParams );

		$fsFiles = []; // (path => FSFile) mapping using the proxy backend's name
		foreach ( $fsFilesM as $path => $fsFile ) {
			$fsFiles[$this->unsubstPaths( $path )] = $fsFile;
		}

		return $fsFiles;
	}

	public function getLocalCopyMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$tempFilesM = $this->backends[$index]->getLocalCopyMulti( $realParams );

		$tempFiles = []; // (path => TempFSFile) mapping using the proxy backend's name
		foreach ( $tempFilesM as $path => $tempFile ) {
			$tempFiles[$this->unsubstPaths( $path )] = $tempFile;
		}

		return $tempFiles;
	}

	public function getFileHttpUrl( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileHttpUrl( $realParams );
	}

	public function directoryExists( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );

		return $this->backends[$this->masterIndex]->directoryExists( $realParams );
	}

	public function getDirectoryList( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );

		return $this->backends[$this->masterIndex]->getDirectoryList( $realParams );
	}

	public function getFileList( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );

		return $this->backends[$this->masterIndex]->getFileList( $realParams );
	}

	public function getFeatures() {
		return $this->backends[$this->masterIndex]->getFeatures();
	}

	public function clearCache( array $paths = null ) {
		foreach ( $this->backends as $backend ) {
			$realPaths = is_array( $paths ) ? $this->substPaths( $paths, $backend ) : null;
			$backend->clearCache( $realPaths );
		}
	}

	public function preloadCache( array $paths ) {
		$realPaths = $this->substPaths( $paths, $this->backends[$this->readIndex] );
		$this->backends[$this->readIndex]->preloadCache( $realPaths );
	}

	public function preloadFileStat( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->preloadFileStat( $realParams );
	}

	public function getScopedLocksForOps( array $ops, StatusValue $status ) {
		$realOps = $this->substOpBatchPaths( $ops, $this->backends[$this->masterIndex] );
		$fileOps = $this->backends[$this->masterIndex]->getOperationsInternal( $realOps );
		// Get the paths to lock from the master backend
		$paths = $this->backends[$this->masterIndex]->getPathsToLockForOpsInternal( $fileOps );
		// Get the paths under the proxy backend's name
		$pbPaths = [
			LockManager::LOCK_UW => $this->unsubstPaths( $paths[LockManager::LOCK_UW] ),
			LockManager::LOCK_EX => $this->unsubstPaths( $paths[LockManager::LOCK_EX] )
		];

		// Actually acquire the locks
		return $this->getScopedFileLocks( $pbPaths, 'mixed', $status );
	}

	/**
	 * @param array $params
	 * @return int The master or read affinity backend index, based on $params['latest']
	 */
	protected function getReadIndexFromParams( array $params ) {
		return !empty( $params['latest'] ) ? $this->masterIndex : $this->readIndex;
	}
}
