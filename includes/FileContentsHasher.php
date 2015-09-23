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

	/**
	 * Constructor.
	 *
	 * @param BagOStuff $cache Cache object to use for storing computed hashes.
	 */
	public function __construct( BagOStuff $cache = null ) {
		$this->cache = $cache ?: ObjectCache::newAccelerator( CACHE_ANYTHING );
	}

	/**
	 * Get a hash of a file's contents, either by retrieving a previously-
	 * computed hash from the cache, or by computing a hash from the file.
	 *
	 * @param string $filePath Full path to the file.
	 * @param string $algo Name of selected hashing algorithm.
	 * @return string|bool Hash of file contents, or false if the file could not be read.
	 */
	public function getFileContentsHash( $filePath, $algo = 'md4' ) {
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
		), 60 * 60 * 24 );

		return $hash;
	}

	/**
	 * Get a hash of the combined contents of multiple files, either by
	 * retrieving a previously-computed hash from the cache, or by computing
	 * a hash from the files.
	 *
	 * @param array $filePaths Array of paths to files.
	 * @param string $algo Name of selected hashing algorithm.
	 * @return string|bool Hash of files' contents, or false if no file could not be read.
	 */
	public function getFilesContentHash( array $filePaths, $algo = 'md4' ) {
		sort( $filePaths );
		$that = $this;
		$hashes = array_map( function ( $filePath ) use ( $algo, $that ) {
			return $that->getFileContentsHash( $filePath, $algo ) ?: '';
		}, $filePaths );

		$hashes = implode( '', $hashes );
		return $hashes ? hash( $algo, $hashes ) : false;
	}
}
