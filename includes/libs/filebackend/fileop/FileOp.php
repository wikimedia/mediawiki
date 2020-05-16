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
use Psr\Log\LoggerInterface;

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

	/** @var int */
	protected $state = self::STATE_NEW;
	/** @var bool */
	protected $failed = false;
	/** @var bool */
	protected $async = false;
	/** @var string */
	protected $batchId;
	/** @var bool */
	protected $cancelled = false;

	/** @var int|bool */
	protected $sourceSize;
	/** @var string|bool */
	protected $sourceSha1;

	/** @var bool */
	protected $overwriteSameCase;

	/** @var bool */
	protected $destExists;

	/* Object life-cycle */
	private const STATE_NEW = 1;
	private const STATE_CHECKED = 2;
	private const STATE_ATTEMPTED = 3;

	protected const ASSUMED_SHA1 = 'sha1';
	protected const ASSUMED_EXISTS = 'exists';
	protected const ASSUMED_SIZE = 'size';

	/**
	 * Build a new batch file operation transaction
	 *
	 * @param FileBackendStore $backend
	 * @param array $params
	 * @param LoggerInterface $logger PSR logger instance
	 * @throws InvalidArgumentException
	 */
	final public function __construct(
		FileBackendStore $backend, array $params, LoggerInterface $logger
	) {
		$this->backend = $backend;
		$this->logger = $logger;
		list( $required, $optional, $paths ) = $this->allowedParams();
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
	 * Get a new empty predicates array for precheck()
	 *
	 * @return array
	 */
	final public static function newPredicates() {
		return [ self::ASSUMED_EXISTS => [], self::ASSUMED_SHA1 => [], self::ASSUMED_SIZE => [] ];
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
	 * Get the file journal entries for this file operation
	 *
	 * @param array $oPredicates Pre-op info about files (format of FileOp::newPredicates)
	 * @param array $nPredicates Post-op info about files (format of FileOp::newPredicates)
	 * @return array
	 */
	final public function getJournalEntries( array $oPredicates, array $nPredicates ) {
		if ( $this->cancelled ) {
			return []; // this is a no-op
		}
		$nullEntries = [];
		$updateEntries = [];
		$deleteEntries = [];
		foreach ( $this->storagePathsReadOrChanged() as $path ) {
			$nullEntries[] = [ // assertion for recovery
				'op' => 'null',
				'path' => $path,
				'newSha1' => $this->fileSha1( $path, $oPredicates )
			];
		}
		foreach ( $this->storagePathsChanged() as $path ) {
			if ( $nPredicates[self::ASSUMED_SHA1][$path] === false ) { // deleted
				$deleteEntries[] = [
					'op' => 'delete',
					'path' => $path,
					'newSha1' => ''
				];
			} else { // created/updated
				$updateEntries[] = [
					'op' => $this->fileExists( $path, $oPredicates ) ? 'update' : 'create',
					'path' => $path,
					'newSha1' => $nPredicates[self::ASSUMED_SHA1][$path]
				];
			}
		}

		return array_merge( $nullEntries, $updateEntries, $deleteEntries );
	}

	/**
	 * Check preconditions of the operation without writing anything.
	 * This must update $predicates for each path that the op can change
	 * except when a failing StatusValue object is returned.
	 *
	 * @param array &$predicates
	 * @return StatusValue
	 */
	final public function precheck( array &$predicates ) {
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

		$status = $this->doPrecheck( $predicates );
		if ( !$status->isOK() ) {
			$this->failed = true;
		}

		return $status;
	}

	/**
	 * @param array &$predicates
	 * @return StatusValue
	 */
	protected function doPrecheck( array &$predicates ) {
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
		if ( $this->cancelled ) {
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
	 * Check for errors with regards to the destination file already existing.
	 * Also set destExists, overwriteSameCase, sourceSize, and sourceSha1 member variables.
	 * A bad StatusValue will be returned if there is no chance it can be overwritten.
	 *
	 * @param array $predicates
	 * @return StatusValue
	 */
	protected function precheckDestExistence( array $predicates ) {
		$status = StatusValue::newGood();
		// Record the size of source file/string
		$this->sourceSize = $this->getSourceSize(); // FS file or data string
		if ( $this->sourceSize === null ) { // file in storage?
			$this->sourceSize = $this->fileSize( $this->params['src'], $predicates );
		}
		// Record the hash of source file/string
		$this->sourceSha1 = $this->getSourceSha1Base36(); // FS file or data string
		if ( $this->sourceSha1 === null ) { // file in storage?
			$this->sourceSha1 = $this->fileSha1( $this->params['src'], $predicates );
		}
		// Record the existence of destination file
		$this->destExists = $this->fileExists( $this->params['dst'], $predicates );
		// Check if an incompatible file exists at the destination
		$this->overwriteSameCase = false;
		if ( $this->destExists ) {
			if ( $this->getParam( 'overwrite' ) ) {
				return $status; // OK, no conflict
			} elseif ( $this->getParam( 'overwriteSame' ) ) {
				// Operation does nothing other than return an OK or bad status
				$dhash = $this->fileSha1( $this->params['dst'], $predicates );
				$dsize = $this->fileSize( $this->params['dst'], $predicates );
				// Check if hashes are valid and match each other...
				if ( !strlen( $this->sourceSha1 ) || !strlen( $dhash ) ) {
					$status->fatal( 'backend-fail-hashes' );
				} elseif ( !is_int( $this->sourceSize ) || !is_int( $dsize ) ) {
					$status->fatal( 'backend-fail-sizes' );
				} elseif ( $this->sourceSha1 !== $dhash || $this->sourceSize !== $dsize ) {
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
	 * precheckDestExistence() helper function to get the source file size.
	 * Subclasses should overwrite this if the source is not in storage.
	 *
	 * @return string|bool Returns false on failure
	 */
	protected function getSourceSize() {
		return null; // N/A
	}

	/**
	 * precheckDestExistence() helper function to get the source file SHA-1.
	 * Subclasses should overwrite this if the source is not in storage.
	 *
	 * @return string|bool Returns false on failure
	 */
	protected function getSourceSha1Base36() {
		return null; // N/A
	}

	/**
	 * Check if a file will exist in storage when this operation is attempted
	 *
	 * Ideally, the file stat entry should already be preloaded via preloadFileStat().
	 * Otherwise, this will query the backend.
	 *
	 * @param string $source Storage path
	 * @param array $predicates
	 * @return bool|null Whether the file will exist or null on error
	 */
	final protected function fileExists( $source, array $predicates ) {
		if ( isset( $predicates[self::ASSUMED_EXISTS][$source] ) ) {
			return $predicates[self::ASSUMED_EXISTS][$source]; // previous op assures this
		} else {
			$params = [ 'src' => $source, 'latest' => true ];

			return $this->backend->fileExists( $params );
		}
	}

	/**
	 * Get the size a file in storage will have when this operation is attempted
	 *
	 * Ideally, file the stat entry should already be preloaded via preloadFileStat() and
	 * the backend tracks hashes as extended attributes. Otherwise, this will query the backend.
	 * Get the size of a file in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param array $predicates
	 * @return int False on failure
	 */
	final protected function fileSize( $source, array $predicates ) {
		if ( isset( $predicates[self::ASSUMED_SIZE][$source] ) ) {
			return $predicates[self::ASSUMED_SIZE][$source]; // previous op assures this
		} elseif (
			isset( $predicates[self::ASSUMED_EXISTS][$source] ) &&
			!$predicates[self::ASSUMED_EXISTS][$source]
		) {
			return false; // previous op assures this
		} else {
			$params = [ 'src' => $source, 'latest' => true ];

			return $this->backend->getFileSize( $params );
		}
	}

	/**
	 * Get the SHA-1 of a file in storage when this operation is attempted
	 *
	 * @param string $source Storage path
	 * @param array $predicates
	 * @return string|bool The SHA-1 hash the file will have or false if non-existent or on error
	 */
	final protected function fileSha1( $source, array $predicates ) {
		if ( isset( $predicates[self::ASSUMED_SHA1][$source] ) ) {
			return $predicates[self::ASSUMED_SHA1][$source]; // previous op assures this
		} elseif (
			isset( $predicates[self::ASSUMED_EXISTS][$source] ) &&
			!$predicates[self::ASSUMED_EXISTS][$source]
		) {
			return false; // previous op assures this
		} else {
			$params = [ 'src' => $source, 'latest' => true ];

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
			$this->logger->error( static::class .
				" failed (batch #{$this->batchId}): " . FormatJson::encode( $params ) );
		} catch ( Exception $e ) {
			// bad config? debug log error?
		}
	}
}
