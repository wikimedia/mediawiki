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
		wfProfileIn( __METHOD__ );
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
				wfProfileOut( __METHOD__ );
				return null; // give up
			}
		}
		$tmpFile = new self( $path );
		$tmpFile->canDelete = true; // safely instantiated
		wfProfileOut( __METHOD__ );
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
	 * Set flag clean up after the temporary file
	 *
	 * @return void
	 */
	public function autocollect() {
		$this->canDelete = true;
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
