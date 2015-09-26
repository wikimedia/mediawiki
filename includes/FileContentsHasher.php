<?php
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

	/** @var BagOStuff */
	protected $cache;

	/** @var MessageCache */
	private static $instance;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->cache = ObjectCache::newAccelerator( CACHE_ANYTHING );
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
	 * @private
	 * @param string $filePath Full path to the file.
	 * @param string $algo Name of selected hashing algorithm.
	 * @return string|bool Hash of file contents, or false if the file could not be read.
	 */
	public function getFileContentsHashInternal( $filePath, $algo = 'md4' ) {
		$mtime = MediaWiki\quietCall( 'filemtime', $filePath );
		if ( $mtime === false ) {
			return false;
		}

		$cacheKey = wfGlobalCacheKey( __CLASS__, $filePath, $algo );
		$cachedHash = $this->cache->get( $cacheKey );

		if ( isset( $cachedHash['mtime'] ) && $cachedHash['mtime'] >= $mtime ) {
			return $cachedHash['hash'];
		}

		$contents = MediaWiki\quietCall( 'file_get_contents', $filePath );
		if ( $contents === false ) {
			return false;
		}

		$hash = hash( $algo, $contents );
		$this->cache->set( $cacheKey, array(
			'mtime' => $mtime,
			'hash'  => $hash
		), 60 * 60 * 24 );  // 86400 seconds, or 24 hours.

		return $hash;
	}

	/**
	 * Get a hash of the combined contents of one or more files, either by
	 * retrieving a previously-computed hash from the cache, or by computing
	 * a hash from the files.
	 *
	 * @param string|string[] $filePaths One or more file paths.
	 * @param string $algo Name of selected hashing algorithm.
	 * @return string|bool Hash of files' contents, or false if no file could not be read.
	 */
	public static function getFileContentsHash( $filePaths, $algo = 'md4' ) {
		$instance = self::singleton();

		if ( !is_array( $filePaths ) ) {
			$filePaths = (array) $filePaths;
		}

		if ( count( $filePaths ) === 1 ) {
			return $instance->getFileContentsHashInternal( $filePaths[0], $algo );
		}

		sort( $filePaths );
		$hashes = array_map( function ( $filePath ) use ( $instance, $algo ) {
			return $instance->getFileContentsHashInternal( $filePath, $algo ) ?: '';
		}, $filePaths );

		$hashes = implode( '', $hashes );
		return $hashes ? hash( $algo, $hashes ) : false;
	}
}
