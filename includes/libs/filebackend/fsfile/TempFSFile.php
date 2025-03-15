<?php

/**
 * Location holder of files stored temporarily
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

namespace Wikimedia\FileBackend\FSFile;

use RuntimeException;
use WeakMap;
use Wikimedia\AtEase\AtEase;

/**
 * This class is used to hold the location and do limited manipulation
 * of files stored temporarily (this will be whatever wfTempDir() returns)
 *
 * @ingroup FileBackend
 */
class TempFSFile extends FSFile {
	/** @var bool Garbage collect the temp file */
	protected $canDelete = false;

	/** @var array Map of (path => 1) for paths to delete on shutdown */
	protected static $pathsCollect = null;

	/**
	 * A WeakMap where the key is an object which depends on the file, and the
	 * value is a TempFSFile responsible for deleting the file. This keeps each
	 * TempFSFile alive until all associated objects have been destroyed.
	 * @var WeakMap|null
	 */
	private static $references;

	/**
	 * Do not call directly. Use TempFSFileFactory
	 *
	 * @param string $path
	 */
	public function __construct( $path ) {
		parent::__construct( $path );

		if ( self::$pathsCollect === null ) {
			// @codeCoverageIgnoreStart
			self::$pathsCollect = [];
			register_shutdown_function( [ self::class, 'purgeAllOnShutdown' ] );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Make a new temporary file on the file system.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @deprecated since 1.34, use TempFSFileFactory directly
	 *
	 * @param string $prefix
	 * @param string $extension Optional file extension
	 * @param string|null $tmpDirectory Optional parent directory
	 * @return TempFSFile|null
	 */
	public static function factory( $prefix, $extension = '', $tmpDirectory = null ) {
		return ( new TempFSFileFactory( $tmpDirectory ) )->newTempFSFile( $prefix, $extension );
	}

	/**
	 * @todo Is there any useful way to test this? Would it be useful to make this non-static on
	 * TempFSFileFactory?
	 *
	 * @return string Filesystem path to a temporary directory
	 * @throws RuntimeException if no writable temporary directory can be found
	 */
	public static function getUsableTempDirectory() {
		$tmpDir = array_map( 'getenv', [ 'TMPDIR', 'TMP', 'TEMP' ] );
		$tmpDir[] = sys_get_temp_dir();
		$tmpDir[] = ini_get( 'upload_tmp_dir' );
		foreach ( $tmpDir as $tmp ) {
			if ( $tmp != '' && is_dir( $tmp ) && is_writable( $tmp ) ) {
				return $tmp;
			}
		}

		// PHP on Windows will detect C:\Windows\Temp as not writable even though PHP can write to
		// it so create a directory within that called 'mwtmp' with a suffix of the user running
		// the current process.
		// The user is included as if various scripts are run by different users they will likely
		// not be able to access each others temporary files.
		if ( PHP_OS_FAMILY === 'Windows' ) {
			$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mwtmp-' . get_current_user();
			if ( !is_dir( $tmp ) ) {
				mkdir( $tmp );
			}
			if ( is_dir( $tmp ) && is_writable( $tmp ) ) {
				return $tmp;
			}
		}

		throw new RuntimeException(
			'No writable temporary directory could be found. ' .
			'Please explicitly specify a writable directory in configuration.' );
	}

	/**
	 * Purge this file off the file system
	 *
	 * @return bool Success
	 */
	public function purge() {
		$this->canDelete = false; // done
		AtEase::suppressWarnings();
		$ok = unlink( $this->path );
		AtEase::restoreWarnings();

		unset( self::$pathsCollect[$this->path] );

		return $ok;
	}

	/**
	 * Clean up the temporary file only after an object goes out of scope
	 *
	 * @param mixed $object
	 * @return TempFSFile This object
	 */
	public function bind( $object ) {
		if ( is_object( $object ) ) {
			// Use a WeakMap to avoid dynamic property creation (T324894)
			if ( self::$references === null ) {
				self::$references = new WeakMap;
			}
			self::$references[$object] = $this;
		}

		return $this;
	}

	/**
	 * Set flag to not clean up after the temporary file
	 *
	 * @return TempFSFile This object
	 */
	public function preserve() {
		$this->canDelete = false;

		unset( self::$pathsCollect[$this->path] );

		return $this;
	}

	/**
	 * Set flag clean up after the temporary file
	 *
	 * @return TempFSFile This object
	 */
	public function autocollect() {
		$this->canDelete = true;

		self::$pathsCollect[$this->path] = 1;

		return $this;
	}

	/**
	 * Try to make sure that all files are purged on error
	 *
	 * This method should only be called internally
	 *
	 * @codeCoverageIgnore
	 */
	public static function purgeAllOnShutdown() {
		foreach ( self::$pathsCollect as $path => $unused ) {
			AtEase::suppressWarnings();
			unlink( $path );
			AtEase::restoreWarnings();
		}
	}

	/**
	 * Cleans up after the temporary file by deleting it
	 */
	public function __destruct() {
		if ( $this->canDelete ) {
			$this->purge();
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( TempFSFile::class, 'TempFSFile' );
