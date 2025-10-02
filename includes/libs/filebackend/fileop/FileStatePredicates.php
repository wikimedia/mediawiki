<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileOps;

use Closure;

/**
 * Helper class for tracking counterfactual file states when pre-checking file operation batches
 *
 * The file states are represented with (existence,size,sha1) triples. When combined with the
 * current state of files in the backend, this can be used to simulate how a batch of operations
 * would play out as a "dry run". This is used in FileBackend::doOperations() to bail out if any
 * failure can be predicted before modifying any data. This includes file operation batches where
 * the same file gets modified by different operations within the batch.
 *
 * @internal Only for use within FileBackend
 */
class FileStatePredicates {
	protected const EXISTS = 'exists';
	protected const SIZE = 'size';
	protected const SHA1 = 'sha1';

	/** @var array<string,array> Map of (storage path => file state map) */
	private $fileStateByPath;

	public function __construct() {
		$this->fileStateByPath = [];
	}

	/**
	 * Predicate that a file exists at the path
	 *
	 * @param string $path Storage path
	 * @param int|false|Closure $size File size or idempotent function yielding the size
	 * @param string|Closure $sha1Base36 File hash, or, idempotent function yielding the hash
	 */
	public function assumeFileExists( string $path, $size, $sha1Base36 ) {
		$this->fileStateByPath[$path] = [
			self::EXISTS => true,
			self::SIZE => $size,
			self::SHA1 => $sha1Base36
		];
	}

	/**
	 * Predicate that no file exists at the path
	 *
	 * @param string $path Storage path
	 */
	public function assumeFileDoesNotExist( string $path ) {
		$this->fileStateByPath[$path] = [
			self::EXISTS => false,
			self::SIZE => false,
			self::SHA1 => false
		];
	}

	/**
	 * Get the hypothetical existance a file given predicated and current state of files
	 *
	 * @param string $path Storage path
	 * @param Closure $curExistenceFunc Function to compute the current existence for a given path
	 * @return bool|null Whether the file exists; null on error
	 */
	public function resolveFileExistence( string $path, $curExistenceFunc ) {
		return self::resolveFileProperty( $path, self::EXISTS, $curExistenceFunc );
	}

	/**
	 * Get the hypothetical size of a file given predicated and current state of files
	 *
	 * @param string $path Storage path
	 * @param Closure $curSizeFunc Function to compute the current size for a given path
	 * @return int|false Bytes; false on error
	 */
	public function resolveFileSize( string $path, $curSizeFunc ) {
		return self::resolveFileProperty( $path, self::SIZE, $curSizeFunc );
	}

	/**
	 * Get the hypothetical SHA-1 hash of a file given predicated and current state of files
	 *
	 * @param string $path Storage path
	 * @param Closure $curSha1Func Function to compute the current SHA-1 hash for a given path
	 * @return string|false Base 36 SHA-1 hash; false on error
	 */
	public function resolveFileSha1Base36( string $path, $curSha1Func ) {
		return self::resolveFileProperty( $path, self::SHA1, $curSha1Func );
	}

	/**
	 * @param string $path Storage path
	 * @param string $property One of (self::EXISTS, self::SIZE, self::SHA1)
	 * @param Closure $curValueFunc Function to compute the current value for a given path
	 * @return mixed
	 */
	private function resolveFileProperty( $path, $property, $curValueFunc ) {
		if ( isset( $this->fileStateByPath[$path] ) ) {
			// File is predicated to have a counterfactual state
			$value = $this->fileStateByPath[$path][$property];
			if ( $value instanceof Closure ) {
				$value = $value();
				$this->fileStateByPath[$path][$property] = $value;
			}
		} else {
			// File is not predicated to have a counterfactual state; use the current state
			$value = $curValueFunc( $path );
		}

		return $value;
	}

	/**
	 * @param string[] $paths List of storage paths
	 * @return self Clone predicates limited to the given paths
	 */
	public function snapshot( array $paths ) {
		$snapshot = new self();
		foreach ( $paths as $path ) {
			if ( isset( $this->fileStateByPath[$path] ) ) {
				$snapshot->fileStateByPath[$path] = $this->fileStateByPath[$path];
			}
		}

		return $snapshot;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FileStatePredicates::class, 'FileStatePredicates' );
