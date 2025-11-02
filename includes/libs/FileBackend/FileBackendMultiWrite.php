<?php
/**
 * Proxy backend that mirrors writes to several internal backends.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend;

use InvalidArgumentException;
use LockManager;
use LogicException;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use Wikimedia\StringUtils\StringUtils;

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
 * Except on getting list of thumbnails for write operations.
 * Write operations are performed on all backends, starting with the master.
 * This makes a best-effort to have transactional semantics.
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

	/** @var bool */
	protected $asyncWrites = false;

	/** @var int Compare file sizes among backends */
	private const CHECK_SIZE = 1;
	/** @var int Compare file mtimes among backends */
	private const CHECK_TIME = 2;
	/** @var int Compare file hashes among backends */
	private const CHECK_SHA1 = 4;

	/**
	 * Construct a proxy backend that consists of several internal backends.
	 * Locking and read-only checks are handled by the proxy backend.
	 *
	 * Additional $config params include:
	 *   - backends       : Array of backend config and multi-backend settings.
	 *                      Each value is the config used in the constructor of a
	 *                      FileBackendStore class, but with these additional settings:
	 *                        - class         : The name of the backend class
	 *                        - isMultiMaster : This must be set for one backend.
	 *                        - readAffinity  : Use this for reads without 'latest' set.
	 *   - replication    : Set to 'async' to defer file operations on the non-master backends.
	 *                      This will apply such updates post-send for web requests.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->asyncWrites = isset( $config['replication'] ) && $config['replication'] === 'async';
		// Construct backends here rather than via registration
		// to keep these backends hidden from outside the proxy.
		$namesUsed = [];
		foreach ( $config['backends'] as $index => $beConfig ) {
			$name = $beConfig['name'];
			if ( isset( $namesUsed[$name] ) ) { // don't break FileOp predicates
				throw new LogicException( "Two or more backends defined with the name $name." );
			}
			$namesUsed[$name] = 1;
			// Alter certain sub-backend settings
			unset( $beConfig['readOnly'] ); // use proxy backend setting
			unset( $beConfig['lockManager'] ); // lock under proxy backend
			$beConfig['domainId'] = $this->domainId; // use the proxy backend wiki ID
			$beConfig['logger'] = $this->logger; // use the proxy backend logger
			if ( !empty( $beConfig['isMultiMaster'] ) ) {
				if ( $this->masterIndex >= 0 ) {
					throw new LogicException( 'More than one master backend defined.' );
				}
				$this->masterIndex = $index; // this is the "master"
			}
			if ( !empty( $beConfig['readAffinity'] ) ) {
				$this->readIndex = $index; // prefer this for reads
			}
			// Create sub-backend object
			if ( !isset( $beConfig['class'] ) ) {
				throw new InvalidArgumentException( 'No class given for a backend config.' );
			}
			$class = $beConfig['class'];
			$this->backends[$index] = new $class( $beConfig );
		}
		if ( $this->masterIndex < 0 ) { // need backends and must have a master
			throw new LogicException( 'No master backend defined.' );
		}
		if ( $this->readIndex < 0 ) {
			$this->readIndex = $this->masterIndex; // default
		}
	}

	/** @inheritDoc */
	final protected function doOperationsInternal( array $ops, array $opts ) {
		$status = $this->newStatus();

		$fname = __METHOD__;
		$mbe = $this->backends[$this->masterIndex]; // convenience

		// Acquire any locks as needed
		$scopeLock = null;
		if ( empty( $opts['nonLocking'] ) ) {
			$scopeLock = $this->getScopedLocksForOps( $ops, $status );
			if ( !$status->isOK() ) {
				return $status; // abort
			}
		}
		// Get the list of paths to read/write
		$relevantPaths = $this->fileStoragePathsForOps( $ops );
		// Clear any cache entries (after locks acquired)
		$this->clearCache( $relevantPaths );
		$opts['preserveCache'] = true;
		// Actually attempt the operation batch on the master backend
		$realOps = $this->substOpBatchPaths( $ops, $mbe );
		$masterStatus = $mbe->doOperations( $realOps, $opts );
		$status->merge( $masterStatus );
		// Propagate the operations to the clone backends if there were no unexpected errors
		// and everything didn't fail due to predicted errors. If $ops only had one operation,
		// this might avoid backend sync inconsistencies.
		if ( $masterStatus->isOK() && $masterStatus->successCount > 0 ) {
			foreach ( $this->backends as $index => $backend ) {
				if ( $index === $this->masterIndex ) {
					continue; // done already
				}

				$realOps = $this->substOpBatchPaths( $ops, $backend );
				if ( $this->asyncWrites && !$this->hasVolatileSources( $ops ) ) {
					// Bind $scopeLock to the callback to preserve locks
					$this->callNowOrLater(
						function () use (
							// @phan-suppress-next-line PhanUnusedClosureUseVariable
							$backend, $realOps, $opts, $scopeLock, $relevantPaths, $fname
						) {
							$this->logger->debug(
								"$fname: '{$backend->getName()}' async replication; paths: " .
								implode( ', ', $relevantPaths )
							);
							$backend->doOperations( $realOps, $opts );
						}
					);
				} else {
					$this->logger->debug(
						"$fname: '{$backend->getName()}' sync replication; paths: " .
						implode( ', ', $relevantPaths )
					);
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
	 * and re-synchronize those files against the "multi master" if needed.
	 *
	 * This method should only be called if the files are locked
	 *
	 * @param string[] $paths List of storage paths
	 * @param string|bool $resyncMode False, True, or "conservative"; see __construct()
	 * @return StatusValue
	 */
	public function resyncFiles( array $paths, $resyncMode = true ) {
		$status = $this->newStatus();

		$fname = __METHOD__;
		foreach ( $paths as $path ) {
			$params = [ 'src' => $path, 'latest' => true ];
			// Get the state of the file on the master backend
			$masterBackend = $this->backends[$this->masterIndex];
			$masterParams = $this->substOpPaths( $params, $masterBackend );
			$masterPath = $masterParams['src'];
			$masterStat = $masterBackend->getFileStat( $masterParams );
			if ( $masterStat === self::STAT_ERROR ) {
				$status->fatal( 'backend-fail-stat', $path );
				$this->logger->error( "$fname: file '$masterPath' is not available" );
				continue;
			}
			$masterSha1 = $masterBackend->getFileSha1Base36( $masterParams );
			if ( ( $masterSha1 !== false ) !== (bool)$masterStat ) {
				$status->fatal( 'backend-fail-hash', $path );
				$this->logger->error( "$fname: file '$masterPath' hash does not match stat" );
				continue;
			}

			// Check of all clone backends agree with the master...
			foreach ( $this->backends as $index => $cloneBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}

				// Get the state of the file on the clone backend
				$cloneParams = $this->substOpPaths( $params, $cloneBackend );
				$clonePath = $cloneParams['src'];
				$cloneStat = $cloneBackend->getFileStat( $cloneParams );
				if ( $cloneStat === self::STAT_ERROR ) {
					$status->fatal( 'backend-fail-stat', $path );
					$this->logger->error( "$fname: file '$clonePath' is not available" );
					continue;
				}
				$cloneSha1 = $cloneBackend->getFileSha1Base36( $cloneParams );
				if ( ( $cloneSha1 !== false ) !== (bool)$cloneStat ) {
					$status->fatal( 'backend-fail-hash', $path );
					$this->logger->error( "$fname: file '$clonePath' hash does not match stat" );
					continue;
				}

				if ( $masterSha1 === $cloneSha1 ) {
					// File is either the same in both backends or absent from both backends
					$this->logger->debug( "$fname: file '$clonePath' matches '$masterPath'" );
				} elseif ( $masterSha1 !== false ) {
					// File is either missing from or different in the clone backend
					if (
						$resyncMode === 'conservative' &&
						$cloneStat &&
						// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
						$cloneStat['mtime'] > $masterStat['mtime']
					) {
						// Do not replace files with older ones; reduces the risk of data loss
						$status->fatal( 'backend-fail-synced', $path );
					} else {
						// Copy the master backend file to the clone backend in overwrite mode
						$fsFile = $masterBackend->getLocalReference( $masterParams );
						$status->merge( $cloneBackend->quickStore( [
							'src' => $fsFile,
							'dst' => $clonePath
						] ) );
					}
				} elseif ( $masterStat === false ) {
					// Stray file exists in the clone backend
					if ( $resyncMode === 'conservative' ) {
						// Do not delete stray files; reduces the risk of data loss
						$status->fatal( 'backend-fail-synced', $path );
						$this->logger->error( "$fname: not allowed to delete file '$clonePath'" );
					} else {
						// Delete the stay file from the clone backend
						$status->merge( $cloneBackend->quickDelete( [ 'src' => $clonePath ] ) );
					}
				}
			}
		}

		if ( !$status->isOK() ) {
			$this->logger->error( "$fname: failed to resync: " . implode( ', ', $paths ) );
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
				$paths[] = $op['src'];
			}
			if ( isset( $op['srcs'] ) ) {
				$paths = array_merge( $paths, $op['srcs'] );
			}
			if ( isset( $op['dst'] ) ) {
				$paths[] = $op['dst'];
			}
		}

		return array_values( array_unique( array_filter(
			$paths,
			[ FileBackend::class, 'isStoragePath' ]
		) ) );
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
	 * @param string[]|string $paths List of paths or single string path
	 * @param FileBackendStore $backend
	 * @return string[]|string
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
	 * @param string[]|string $paths List of paths or single string path
	 * @param FileBackendStore $backend internal storage backend
	 * @return string[]|string
	 */
	protected function unsubstPaths( $paths, FileBackendStore $backend ) {
		return preg_replace(
			'!^mwstore://' . preg_quote( $backend->getName(), '!' ) . '/!',
			StringUtils::escapeRegexReplacement( "mwstore://{$this->name}/" ),
			$paths // string or array
		);
	}

	/**
	 * @param array[] $ops File operations for FileBackend::doOperations()
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

	/** @inheritDoc */
	protected function doQuickOperationsInternal( array $ops, array $opts ) {
		$status = $this->newStatus();
		// Do the operations on the master backend; setting StatusValue fields
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
				$this->callNowOrLater(
					static function () use ( $backend, $realOps ) {
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

	/** @inheritDoc */
	protected function doPrepare( array $params ) {
		return $this->doDirectoryOp( 'prepare', $params );
	}

	/** @inheritDoc */
	protected function doSecure( array $params ) {
		return $this->doDirectoryOp( 'secure', $params );
	}

	/** @inheritDoc */
	protected function doPublish( array $params ) {
		return $this->doDirectoryOp( 'publish', $params );
	}

	/** @inheritDoc */
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
				$this->callNowOrLater(
					static function () use ( $backend, $method, $realParams ) {
						$backend->$method( $realParams );
					}
				);
			} else {
				$status->merge( $backend->$method( $realParams ) );
			}
		}

		return $status;
	}

	/** @inheritDoc */
	public function concatenate( array $params ) {
		$status = $this->newStatus();
		// We are writing to an FS file, so we don't need to do this per-backend
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$status->merge( $this->backends[$index]->concatenate( $realParams ) );

		return $status;
	}

	/** @inheritDoc */
	public function fileExists( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->fileExists( $realParams );
	}

	/** @inheritDoc */
	public function getFileTimestamp( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileTimestamp( $realParams );
	}

	/** @inheritDoc */
	public function getFileSize( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileSize( $realParams );
	}

	/** @inheritDoc */
	public function getFileStat( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileStat( $realParams );
	}

	/** @inheritDoc */
	public function getFileXAttributes( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileXAttributes( $realParams );
	}

	/** @inheritDoc */
	public function getFileContentsMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$contentsM = $this->backends[$index]->getFileContentsMulti( $realParams );

		$contents = []; // (path => FSFile) mapping using the proxy backend's name
		foreach ( $contentsM as $path => $data ) {
			$contents[$this->unsubstPaths( $path, $this->backends[$index] )] = $data;
		}

		return $contents;
	}

	/** @inheritDoc */
	public function getFileSha1Base36( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileSha1Base36( $realParams );
	}

	/** @inheritDoc */
	public function getFileProps( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileProps( $realParams );
	}

	/** @inheritDoc */
	public function streamFile( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->streamFile( $realParams );
	}

	/** @inheritDoc */
	public function getLocalReferenceMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$fsFilesM = $this->backends[$index]->getLocalReferenceMulti( $realParams );

		$fsFiles = []; // (path => FSFile) mapping using the proxy backend's name
		foreach ( $fsFilesM as $path => $fsFile ) {
			$fsFiles[$this->unsubstPaths( $path, $this->backends[$index] )] = $fsFile;
		}

		return $fsFiles;
	}

	/** @inheritDoc */
	public function getLocalCopyMulti( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		$tempFilesM = $this->backends[$index]->getLocalCopyMulti( $realParams );

		$tempFiles = []; // (path => TempFSFile) mapping using the proxy backend's name
		foreach ( $tempFilesM as $path => $tempFile ) {
			$tempFiles[$this->unsubstPaths( $path, $this->backends[$index] )] = $tempFile;
		}

		return $tempFiles;
	}

	/** @inheritDoc */
	public function getFileHttpUrl( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileHttpUrl( $realParams );
	}

	/** @inheritDoc */
	public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params
	) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );
		return $this->backends[$index]->addShellboxInputFile( $command, $boxedName, $realParams );
	}

	/** @inheritDoc */
	public function directoryExists( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );

		return $this->backends[$this->masterIndex]->directoryExists( $realParams );
	}

	/** @inheritDoc */
	public function getDirectoryList( array $params ) {
		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );

		return $this->backends[$this->masterIndex]->getDirectoryList( $realParams );
	}

	/** @inheritDoc */
	public function getFileList( array $params ) {
		if ( isset( $params['forWrite'] ) && $params['forWrite'] ) {
			return $this->getFileListForWrite( $params );
		}

		$realParams = $this->substOpPaths( $params, $this->backends[$this->masterIndex] );
		return $this->backends[$this->masterIndex]->getFileList( $realParams );
	}

	private function getFileListForWrite( array $params ): array {
		$files = [];
		// Get the list of thumbnails from all backends to allow
		// deleting all of them. Otherwise, old thumbnails existing on
		// one backend only won't get updated in reupload (T331138).
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$iterator = $backend->getFileList( $realParams );
			if ( $iterator !== null ) {
				foreach ( $iterator as $file ) {
					$files[] = $file;
				}
			}
		}

		return array_unique( $files );
	}

	/** @inheritDoc */
	public function getFeatures() {
		return $this->backends[$this->masterIndex]->getFeatures();
	}

	/** @inheritDoc */
	public function clearCache( ?array $paths = null ) {
		foreach ( $this->backends as $backend ) {
			$realPaths = is_array( $paths ) ? $this->substPaths( $paths, $backend ) : null;
			$backend->clearCache( $realPaths );
		}
	}

	/** @inheritDoc */
	public function preloadCache( array $paths ) {
		$realPaths = $this->substPaths( $paths, $this->backends[$this->readIndex] );
		$this->backends[$this->readIndex]->preloadCache( $realPaths );
	}

	/** @inheritDoc */
	public function preloadFileStat( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->preloadFileStat( $realParams );
	}

	/** @inheritDoc */
	public function getScopedLocksForOps( array $ops, StatusValue $status ) {
		$realOps = $this->substOpBatchPaths( $ops, $this->backends[$this->masterIndex] );
		$fileOps = $this->backends[$this->masterIndex]->getOperationsInternal( $realOps );
		// Get the paths to lock from the master backend
		$paths = $this->backends[$this->masterIndex]->getPathsToLockForOpsInternal( $fileOps );
		// Get the paths under the proxy backend's name
		$pbPaths = [
			LockManager::LOCK_UW => $this->unsubstPaths(
				$paths[LockManager::LOCK_UW],
				$this->backends[$this->masterIndex]
			),
			LockManager::LOCK_EX => $this->unsubstPaths(
				$paths[LockManager::LOCK_EX],
				$this->backends[$this->masterIndex]
			)
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

/** @deprecated class alias since 1.43 */
class_alias( FileBackendMultiWrite::class, 'FileBackendMultiWrite' );
