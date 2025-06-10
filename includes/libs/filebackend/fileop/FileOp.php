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
 */

namespace Wikimedia\FileBackend\FileOps;

use Closure;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use StatusValue;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FileBackendStore;
use Wikimedia\RequestTimeout\TimeoutException;

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
	/** @var FileBackendStore */
	protected $backend;
	/** @var LoggerInterface */
	protected $logger;

	/** @var array */
	protected $params = [];

	/** @var int Stage in the operation life-cycle */
	protected $state = self::STATE_NEW;
	/** @var bool Whether the operation pre-check or attempt stage failed */
	protected $failed = false;
	/** @var bool Whether the operation is part of a concurrent sub-batch of operation */
	protected $async = false;
	/** @var bool Whether the operation pre-check stage marked the attempt stage as a no-op */
	protected $noOp = false;

	/** @var bool|null */
	protected $overwriteSameCase;
	/** @var bool|null */
	protected $destExists;

	/** Operation has not yet been pre-checked nor run */
	private const STATE_NEW = 1;
	/** Operation has been pre-checked but not yet attempted */
	private const STATE_CHECKED = 2;
	/** Operation has been attempted */
	private const STATE_ATTEMPTED = 3;

	/**
	 * Build a new batch file operation transaction
	 *
	 * @param FileBackendStore $backend
	 * @param array $params
	 * @param LoggerInterface $logger PSR logger instance
	 */
	final public function __construct(
		FileBackendStore $backend, array $params, LoggerInterface $logger
	) {
		$this->backend = $backend;
		$this->logger = $logger;
		[ $required, $optional, $paths ] = $this->allowedParams();
		foreach ( $required as $name ) {
			if ( isset( $params[$name] ) ) {
				$this->params[$name] = $params[$name];
			} else {
				throw new InvalidArgumentException( "File operation missing parameter '$name'." );
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

			return $res ?? $path;
		}

		return $path;
	}

	/**
	 * Get the value of the parameter with the given name
	 *
	 * @param string $name
	 * @return mixed Returns null if the parameter is not set
	 */
	final public function getParam( $name ) {
		return $this->params[$name] ?? null;
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
	 * Get a new empty dependency tracking array for paths read/written to
	 *
	 * @return array
	 */
	final public static function newDependencies() {
		return [ 'read' => [], 'write' => [] ];
	}

	/**
	 * Update a dependency tracking array to account for this operation
	 *
	 * @param array $deps Prior path reads/writes; format of FileOp::newDependencies()
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
	 * @param array $deps Prior path reads/writes; format of FileOp::newDependencies()
	 * @return bool
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
	 * Do a dry-run precondition check of the operation in the context of op batch
	 *
	 * Updates the batch predicates for all paths this op can change if an OK status is returned
	 *
	 * @param FileStatePredicates $predicates Counterfactual file states for the op batch
	 * @return StatusValue
	 */
	final public function precheck( FileStatePredicates $predicates ) {
		if ( $this->state !== self::STATE_NEW ) {
			return StatusValue::newFatal( 'fileop-fail-state', self::STATE_NEW, $this->state );
		}
		$this->state = self::STATE_CHECKED;

		$status = StatusValue::newGood();
		foreach ( $this->storagePathsReadOrChanged() as $path ) {
			if ( !$this->backend->isPathUsableInternal( $path ) ) {
				$status->fatal( 'backend-fail-usable', $path );
			}
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		$opPredicates = $predicates->snapshot( $this->storagePathsReadOrChanged() );
		$status = $this->doPrecheck( $opPredicates, $predicates );
		if ( !$status->isOK() ) {
			$this->failed = true;
		}

		return $status;
	}

	/**
	 * Do a dry-run precondition check of the operation in the context of op batch
	 *
	 * Updates the batch predicates for all paths this op can change if an OK status is returned
	 *
	 * @param FileStatePredicates $opPredicates Counterfactual file states for op paths at op start
	 * @param FileStatePredicates $batchPredicates Counterfactual file states for the op batch
	 * @return StatusValue
	 */
	protected function doPrecheck(
		FileStatePredicates $opPredicates,
		FileStatePredicates $batchPredicates
	) {
		return StatusValue::newGood();
	}

	/**
	 * Attempt the operation
	 *
	 * @return StatusValue
	 */
	final public function attempt() {
		if ( $this->state !== self::STATE_CHECKED ) {
			return StatusValue::newFatal( 'fileop-fail-state', self::STATE_CHECKED, $this->state );
		} elseif ( $this->failed ) { // failed precheck
			return StatusValue::newFatal( 'fileop-fail-attempt-precheck' );
		}
		$this->state = self::STATE_ATTEMPTED;
		if ( $this->noOp ) {
			$status = StatusValue::newGood(); // no-op
		} else {
			$status = $this->doAttempt();
			if ( !$status->isOK() ) {
				$this->failed = true;
				$this->logFailure( 'attempt' );
			}
		}

		return $status;
	}

	/**
	 * @return StatusValue
	 */
	protected function doAttempt() {
		return StatusValue::newGood();
	}

	/**
	 * Attempt the operation in the background
	 *
	 * @return StatusValue
	 */
	final public function attemptAsync() {
		$this->async = true;
		$result = $this->attempt();
		$this->async = false;

		return $result;
	}

	/**
	 * Attempt the operation without regards to prechecks
	 *
	 * @return StatusValue
	 */
	final public function attemptQuick() {
		$this->state = self::STATE_CHECKED; // bypassed

		return $this->attempt();
	}

	/**
	 * Attempt the operation in the background without regards to prechecks
	 *
	 * @return StatusValue
	 */
	final public function attemptAsyncQuick() {
		$this->state = self::STATE_CHECKED; // bypassed

		return $this->attemptAsync();
	}

	/**
	 * Get the file operation parameters
	 *
	 * @return array (required params list, optional params list, list of params that are paths)
	 */
	protected function allowedParams() {
		return [ [], [], [] ];
	}

	/**
	 * Adjust params to FileBackendStore internal file calls
	 *
	 * @param array $params
	 * @return array (required params list, optional params list)
	 */
	protected function setFlags( array $params ) {
		return [ 'async' => $this->async ] + $params;
	}

	/**
	 * Get a list of storage paths read from for this operation
	 *
	 * @return array
	 */
	public function storagePathsRead() {
		return [];
	}

	/**
	 * Get a list of storage paths written to for this operation
	 *
	 * @return array
	 */
	public function storagePathsChanged() {
		return [];
	}

	/**
	 * Get a list of storage paths read from or written to for this operation
	 *
	 * @return array
	 */
	final public function storagePathsReadOrChanged() {
		return array_values( array_unique(
			array_merge( $this->storagePathsRead(), $this->storagePathsChanged() )
		) );
	}

	/**
	 * Check for errors with regards to the destination file already existing
	 *
	 * Also set the destExists and overwriteSameCase member variables.
	 * A bad StatusValue will be returned if there is no chance it can be overwritten.
	 *
	 * @param FileStatePredicates $opPredicates Counterfactual storage path states for this op
	 * @param int|false|Closure $sourceSize Source size or idempotent function yielding the size
	 * @param string|Closure $sourceSha1 Source hash, or, idempotent function yielding the hash
	 * @return StatusValue
	 */
	protected function precheckDestExistence(
		FileStatePredicates $opPredicates,
		$sourceSize,
		$sourceSha1
	) {
		$status = StatusValue::newGood();
		// Record the existence of destination file
		$this->destExists = $this->resolveFileExistence( $this->params['dst'], $opPredicates );
		// Check if an incompatible file exists at the destination
		$this->overwriteSameCase = false;
		if ( $this->destExists ) {
			if ( $this->getParam( 'overwrite' ) ) {
				return $status; // OK, no conflict
			} elseif ( $this->getParam( 'overwriteSame' ) ) {
				// Operation does nothing other than return an OK or bad status
				$sourceSize = ( $sourceSize instanceof Closure ) ? $sourceSize() : $sourceSize;
				$sourceSha1 = ( $sourceSha1 instanceof Closure ) ? $sourceSha1() : $sourceSha1;
				$dstSha1 = $this->resolveFileSha1Base36( $this->params['dst'], $opPredicates );
				$dstSize = $this->resolveFileSize( $this->params['dst'], $opPredicates );
				// Check if hashes are valid and match each other...
				if ( !strlen( $sourceSha1 ) || !strlen( $dstSha1 ) ) {
					$status->fatal( 'backend-fail-hashes' );
				} elseif ( !is_int( $sourceSize ) || !is_int( $dstSize ) ) {
					$status->fatal( 'backend-fail-sizes' );
				} elseif ( $sourceSha1 !== $dstSha1 || $sourceSize !== $dstSize ) {
					// Give an error if the files are not identical
					$status->fatal( 'backend-fail-notsame', $this->params['dst'] );
				} else {
					$this->overwriteSameCase = true; // OK
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $this->params['dst'] );
			}
		} elseif ( $this->destExists === FileBackend::EXISTENCE_ERROR ) {
			$status->fatal( 'backend-fail-stat', $this->params['dst'] );
		}

		return $status;
	}

	/**
	 * Check if a file will exist in storage when this operation is attempted
	 *
	 * Ideally, the file stat entry should already be preloaded via preloadFileStat().
	 * Otherwise, this will query the backend.
	 *
	 * @param string $source Storage path
	 * @param FileStatePredicates $opPredicates Counterfactual storage path states for this op
	 * @return bool|null Whether the file will exist or null on error
	 */
	final protected function resolveFileExistence( $source, FileStatePredicates $opPredicates ) {
		return $opPredicates->resolveFileExistence(
			$source,
			function ( $path ) {
				return $this->backend->fileExists( [ 'src' => $path, 'latest' => true ] );
			}
		);
	}

	/**
	 * Get the size a file in storage will have when this operation is attempted
	 *
	 * Ideally, file the stat entry should already be preloaded via preloadFileStat() and
	 * the backend tracks hashes as extended attributes. Otherwise, this will query the backend.
	 * Get the size of a file in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param FileStatePredicates $opPredicates Counterfactual storage path states for this op
	 * @return int|false False on failure
	 */
	final protected function resolveFileSize( $source, FileStatePredicates $opPredicates ) {
		return $opPredicates->resolveFileSize(
			$source,
			function ( $path ) {
				return $this->backend->getFileSize( [ 'src' => $path, 'latest' => true ] );
			}
		);
	}

	/**
	 * Get the SHA-1 of a file in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param FileStatePredicates $opPredicates Counterfactual storage path states for this op
	 * @return string|false The SHA-1 hash the file will have or false if non-existent or on error
	 */
	final protected function resolveFileSha1Base36( $source, FileStatePredicates $opPredicates ) {
		return $opPredicates->resolveFileSha1Base36(
			$source,
			function ( $path ) {
				return $this->backend->getFileSha1Base36( [ 'src' => $path, 'latest' => true ] );
			}
		);
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
		try {
			$this->logger->error( static::class . ' failed: ' . $action,
				[ 'params' => $this->params ]
			);
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception ) {
			// bad config? debug log error?
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileOp::class, 'FileOp' );
