<?php

use Wikimedia\ObjectCache\APCUBagOStuff;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * Generate hash digests of file contents to help with cache invalidation.
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
 */
class FileContentsHasher {
	private const ALGO = 'md4';

	/** @var BagOStuff */
	protected $cache;

	/** @var FileContentsHasher */
	private static $instance;

	public function __construct() {
		$this->cache = function_exists( 'apcu_fetch' ) ? new APCUBagOStuff() : new EmptyBagOStuff();
	}

	/**
	 * Get the singleton instance of this class.
	 *
	 * @return FileContentsHasher
	 */
	public static function singleton() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get a hash of a file's contents, either by retrieving a previously-
	 * computed hash from the cache, or by computing a hash from the file.
	 *
	 * @param string $filePath Full path to the file.
	 * @return string|bool Hash of file contents, or false if the file could not be read.
	 */
	private function getFileContentsHashInternal( $filePath ) {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$mtime = @filemtime( $filePath );
		if ( $mtime === false ) {
			return false;
		}

		$cacheKey = $this->cache->makeGlobalKey( __CLASS__, $filePath, $mtime, self::ALGO );
		return $this->cache->getWithSetCallback(
			$cacheKey,
			$this->cache::TTL_DAY,
			static function () use ( $filePath ) {
				// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
				$contents = @file_get_contents( $filePath );
				if ( $contents === false ) {
					// Don't cache false
					return false;
				}

				return hash( self::ALGO, $contents );
			}
		);
	}

	/**
	 * Get a hash of the combined contents of one or more files, either by
	 * retrieving a previously-computed hash from the cache, or by computing
	 * a hash from the files.
	 *
	 * @param string|string[] $filePaths One or more file paths.
	 * @return string|bool Hash of files' contents, or false if no file could not be read.
	 */
	public static function getFileContentsHash( $filePaths ) {
		$instance = self::singleton();

		if ( !is_array( $filePaths ) ) {
			$filePaths = (array)$filePaths;
		}

		if ( count( $filePaths ) === 1 ) {
			$hash = $instance->getFileContentsHashInternal( $filePaths[0] );
			return $hash;
		}

		sort( $filePaths );
		$hashes = [];
		foreach ( $filePaths as $filePath ) {
			$hashes[] = $instance->getFileContentsHashInternal( $filePath ) ?: '';
		}

		$hashes = implode( '', $hashes );
		return $hashes ? hash( self::ALGO, $hashes ) : false;
	}
}
