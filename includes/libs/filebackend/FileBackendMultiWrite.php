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

namespace Wikimedia\FileBackend;

use InvalidArgumentException;
use LockManager;
use LogicException;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use Wikimedia\StringUtils\StringUtils;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->syncChecks = $config['syncChecks'] ?? self::CHECK_SIZE;
		$this->autoResync = $config['autoResync'] ?? false;
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
		$opts['preserveCache'] = true; // only locked files are cached
		// Check if the paths are valid and accessible on all backends
		$status->merge( $this->accessibilityCheck( $relevantPaths ) );
		if ( !$status->isOK() ) {
			return $status; // abort
		}
		// Do a consistency check to see if the backends are consistent
		$syncStatus = $this->consistencyCheck( $relevantPaths );
		if ( !$syncStatus->isOK() ) {
			$this->logger->error(
				"$fname: failed sync check: " . implode( ', ', $relevantPaths )
			);
			// Try to resync the clone backends to the master on the spot
			if (
				$this->autoResync === false ||
				!$this->resyncFiles( $relevantPaths, $this->autoResync )->isOK()
			) {
				$status->merge( $syncStatus );

				return $status; // abort
			}
		}
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
	 *
	 * This method should only be called if the files are locked or the backend
	 * is in read-only mode
	 *
	 * @param string[] $paths List of storage paths
	 * @return StatusValue
	 */
	public function consistencyCheck( array $paths ) {
		$status = $this->newStatus();
		if ( $this->syncChecks == 0 || count( $this->backends ) <= 1 ) {
			return $status; // skip checks
		}

		// Preload all of the stat info in as few round trips as possible
		foreach ( $this->backends as $backend ) {
			$realPaths = $this->substPaths( $paths, $backend );
			$backend->preloadFileStat( [ 'srcs' => $realPaths, 'latest' => true ] );
		}

		foreach ( $paths as $path ) {
			$params = [ 'src' => $path, 'latest' => true ];
			// Get the state of the file on the master backend
			$masterBackend = $this->backends[$this->masterIndex];
			$masterParams = $this->substOpPaths( $params, $masterBackend );
			$masterStat = $masterBackend->getFileStat( $masterParams );
			if ( $masterStat === self::STAT_ERROR ) {
				$status->fatal( 'backend-fail-stat', $path );
				continue;
			}
			if ( $this->syncChecks & self::CHECK_SHA1 ) {
				$masterSha1 = $masterBackend->getFileSha1Base36( $masterParams );
				if ( ( $masterSha1 !== false ) !== (bool)$masterStat ) {
					$status->fatal( 'backend-fail-hash', $path );
					continue;
				}
			} else {
				$masterSha1 = null; // unused
			}

			// Check if all clone backends agree with the master...
			foreach ( $this->backends as $index => $cloneBackend ) {
				if ( $index === $this->masterIndex ) {
					continue; // master
				}

				// Get the state of the file on the clone backend
				$cloneParams = $this->substOpPaths( $params, $cloneBackend );
				$cloneStat = $cloneBackend->getFileStat( $cloneParams );

				if ( $masterStat ) {
					// File exists in the master backend
					if ( !$cloneStat ) {
						// File is missing from the clone backend
						$status->fatal( 'backend-fail-synced', $path );
					} elseif (
						( $this->syncChecks & self::CHECK_SIZE ) &&
						$cloneStat['size'] !== $masterStat['size']
					) {
						// File in the clone backend is different
						$status->fatal( 'backend-fail-synced', $path );
					} elseif (
						( $this->syncChecks & self::CHECK_TIME ) &&
						abs(
							(int)ConvertibleTimestamp::convert( TS_UNIX, $masterStat['mtime'] ) -
							(int)ConvertibleTimestamp::convert( TS_UNIX, $cloneStat['mtime'] )
						) > 30
					) {
						// File in the clone backend is significantly newer or older
						$status->fatal( 'backend-fail-synced', $path );
					} elseif (
						( $this->syncChecks & self::CHECK_SHA1 ) &&
						$cloneBackend->getFileSha1Base36( $cloneParams ) !== $masterSha1
					) {
						// File in the clone backend is different
						$status->fatal( 'backend-fail-synced', $path );
					}
				} else {
					// File does not exist in the master backend
					if ( $cloneStat ) {
						// Stray file exists in the clone backend
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
	 * @param string[] $paths List of storage paths
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

		return array_values( array_unique( array_filter( $paths, [ FileBackend::class, 'isStoragePath' ] ) ) );
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
			$contents[$this->unsubstPaths( $path, $this->backends[$index] )] = $data;
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
			$fsFiles[$this->unsubstPaths( $path, $this->backends[$index] )] = $fsFile;
		}

		return $fsFiles;
	}

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

	public function getFileHttpUrl( array $params ) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );

		return $this->backends[$index]->getFileHttpUrl( $realParams );
	}

	public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params
	) {
		$index = $this->getReadIndexFromParams( $params );
		$realParams = $this->substOpPaths( $params, $this->backends[$index] );
		return $this->backends[$index]->addShellboxInputFile( $command, $boxedName, $realParams );
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

	public function getFeatures() {
		return $this->backends[$this->masterIndex]->getFeatures();
	}

	public function clearCache( ?array $paths = null ) {
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
