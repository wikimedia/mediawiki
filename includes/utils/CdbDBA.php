<?php
/**
 * DBA-based CDB reader/writer
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

/**
 * Reader class which uses the DBA extension
 */
class CdbReaderDBA extends CdbReader {
	public function __construct( $fileName ) {
		$this->handle = dba_open( $fileName, 'r-', 'cdb' );
		if ( !$this->handle ) {
			throw new CdbException( 'Unable to open CDB file "' . $fileName . '"' );
		}
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			dba_close( $this->handle );
		}
		unset( $this->handle );
	}

	public function get( $key ) {
		return dba_fetch( $key, $this->handle );
	}
}

/**
 * Writer class which uses the DBA extension
 */
class CdbWriterDBA extends CdbWriter {
	public function __construct( $fileName ) {
		$this->realFileName = $fileName;
		$this->tmpFileName = $fileName . '.tmp.' . mt_rand( 0, 0x7fffffff );
		$this->handle = dba_open( $this->tmpFileName, 'n', 'cdb_make' );
		if ( !$this->handle ) {
			throw new CdbException( 'Unable to open CDB file for write "' . $fileName . '"' );
		}
	}

	public function set( $key, $value ) {
		return dba_insert( $key, $value, $this->handle );
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			dba_close( $this->handle );
		}
		if ( $this->isWindows() ) {
			unlink( $this->realFileName );
		}
		if ( !rename( $this->tmpFileName, $this->realFileName ) ) {
			throw new CdbException( 'Unable to move the new CDB file into place.' );
		}
		unset( $this->handle );
	}
}
