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

	public function __construct( $path ) {
		parent::__construct( $path );

		if ( self::$pathsCollect === null ) {
			self::$pathsCollect = [];
			register_shutdown_function( [ __CLASS__, 'purgeAllOnShutdown' ] );
		}
	}

	/**
	 * Make a new temporary file on the file system.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @param string $prefix
	 * @param string $extension Optional file extension
	 * @param string|null $tmpDirectory Optional parent directory
	 * @return TempFSFile|null
	 */
	public static function factory( $prefix, $extension = '', $tmpDirectory = null ) {
		$ext = ( $extension != '' ) ? ".{$extension}" : '';

		$attempts = 5;
		while ( $attempts-- ) {
			$hex = sprintf( '%06x%06x', mt_rand( 0, 0xffffff ), mt_rand( 0, 0xffffff ) );
			if ( !is_string( $tmpDirectory ) ) {
				$tmpDirectory = self::getUsableTempDirectory();
			}
			$path = $tmpDirectory . '/' . $prefix . $hex . $ext;
			Wikimedia\suppressWarnings();
			$newFileHandle = fopen( $path, 'x' );
			Wikimedia\restoreWarnings();
			if ( $newFileHandle ) {
				fclose( $newFileHandle );
				$tmpFile = new self( $path );
				$tmpFile->autocollect();
				// Safely instantiated, end loop.
				return $tmpFile;
			}
		}

		// Give up
		return null;
	}

	/**
	 * @return string Filesystem path to a temporary directory
	 * @throws RuntimeException
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
		if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
			$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mwtmp-' . get_current_user();
			if ( !file_exists( $tmp ) ) {
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
		Wikimedia\suppressWarnings();
		$ok = unlink( $this->path );
		Wikimedia\restoreWarnings();

		unset( self::$pathsCollect[$this->path] );

		return $ok;
	}

	/**
	 * Clean up the temporary file only after an object goes out of scope
	 *
	 * @param object $object
	 * @return TempFSFile This object
	 */
	public function bind( $object ) {
		if ( is_object( $object ) ) {
			if ( !isset( $object->tempFSFileReferences ) ) {
				// Init first since $object might use __get() and return only a copy variable
				$object->tempFSFileReferences = [];
			}
			$object->tempFSFileReferences[] = $this;
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
	 */
	public static function purgeAllOnShutdown() {
		foreach ( self::$pathsCollect as $path ) {
			Wikimedia\suppressWarnings();
			unlink( $path );
			Wikimedia\restoreWarnings();
		}
	}

	/**
	 * Cleans up after the temporary file by deleting it
	 */
	function __destruct() {
		if ( $this->canDelete ) {
			$this->purge();
		}
	}
}
