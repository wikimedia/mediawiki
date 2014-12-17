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

	/** @var array Active temp files to purge on shutdown */
	protected static $instances = array();

	/** @var array Map of (path => 1) for paths to delete on shutdown */
	protected static $pathsCollect = null;

	public function __construct( $path ) {
		parent::__construct( $path );

		if ( self::$pathsCollect === null ) {
			self::$pathsCollect = array();
			register_shutdown_function( array( __CLASS__, 'purgeAllOnShutdown' ) );
		}
	}

	/**
	 * Make a new temporary file on the file system.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @param string $prefix
	 * @param string $extension
	 * @return TempFSFile|null
	 */
	public static function factory( $prefix, $extension = '' ) {
		$base = wfTempDir() . '/' . $prefix . wfRandomString( 12 );
		$ext = ( $extension != '' ) ? ".{$extension}" : "";
		for ( $attempt = 1; true; $attempt++ ) {
			$path = "{$base}-{$attempt}{$ext}";
			wfSuppressWarnings();
			$newFileHandle = fopen( $path, 'x' );
			wfRestoreWarnings();
			if ( $newFileHandle ) {
				fclose( $newFileHandle );
				break; // got it
			}
			if ( $attempt >= 5 ) {

				return null; // give up
			}
		}
		$tmpFile = new self( $path );
		$tmpFile->autocollect(); // safely instantiated

		return $tmpFile;
	}

	/**
	 * Purge this file off the file system
	 *
	 * @return bool Success
	 */
	public function purge() {
		$this->canDelete = false; // done
		wfSuppressWarnings();
		$ok = unlink( $this->path );
		wfRestoreWarnings();

		unset( self::$pathsCollect[$this->path] );

		return $ok;
	}

	/**
	 * Clean up the temporary file only after an object goes out of scope
	 *
	 * @param stdClass $object
	 * @return TempFSFile This object
	 */
	public function bind( $object ) {
		if ( is_object( $object ) ) {
			if ( !isset( $object->tempFSFileReferences ) ) {
				// Init first since $object might use __get() and return only a copy variable
				$object->tempFSFileReferences = array();
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
			wfSuppressWarnings();
			unlink( $path );
			wfRestoreWarnings();
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
