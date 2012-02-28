<?php
/**
 * @file
 * @ingroup FileBackend
 */

/**
 * This class is used to hold the location and do limited manipulation
 * of files stored temporarily (usually this will be $wgTmpDirectory)
 *
 * @ingroup FileBackend
 */
class TempFSFile extends FSFile {
	protected $canDelete = false; // bool; garbage collect the temp file

	/** @var Array of active temp files to purge on shutdown */
	protected static $instances = array();

	/**
	 * Make a new temporary file on the file system.
	 * Temporary files may be purged when the file object falls out of scope.
	 * 
	 * @param $prefix string
	 * @param $extension string
	 * @return TempFSFile|null 
	 */
	public static function factory( $prefix, $extension = '' ) {
		$base = wfTempDir() . '/' . $prefix . dechex( mt_rand( 0, 99999999 ) );
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
			if ( $attempt >= 15 ) {
				return null; // give up
			}
		}
		$tmpFile = new self( $path );
		$tmpFile->canDelete = true; // safely instantiated
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
		return $ok;
	}

	/**
	 * Clean up the temporary file only after an object goes out of scope
	 *
	 * @param $object Object
	 * @return void
	 */
	public function bind( $object ) {
		if ( is_object( $object ) ) {
			$object->tempFSFileReferences[] = $this;
		}
	}

	/**
	 * Set flag to not clean up after the temporary file
	 *
	 * @return void
	 */
	public function preserve() {
		$this->canDelete = false;
	}

	/**
	 * Cleans up after the temporary file by deleting it
	 */
	function __destruct() {
		if ( $this->canDelete ) {
			wfSuppressWarnings();
			unlink( $this->path );
			wfRestoreWarnings();
		}
	}
}
