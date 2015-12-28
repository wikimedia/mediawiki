<?php
/**
 * Stream outputter to send data to a file.
 *
 * Copyright � 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @ingroup Dump
 */
class DumpFileOutput extends DumpOutput {
	protected $handle = false, $filename;

	/**
	 * @param string $file
	 */
	function __construct( $file ) {
		$this->handle = fopen( $file, "wt" );
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->handle ) {
			fclose( $this->handle );
			$this->handle = false;
		}
	}

	/**
	 * @param string $string
	 */
	function write( $string ) {
		fputs( $this->handle, $string );
	}

	/**
	 * @param string $newname
	 */
	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
	}

	/**
	 * @param string $newname
	 * @throws MWException
	 */
	function renameOrException( $newname ) {
			if ( !rename( $this->filename, $newname ) ) {
				throw new MWException( __METHOD__ . ": rename of file {$this->filename} to $newname failed\n" );
			}
	}

	/**
	 * @param array $newname
	 * @return string
	 * @throws MWException
	 */
	function checkRenameArgCount( $newname ) {
		if ( is_array( $newname ) ) {
			if ( count( $newname ) > 1 ) {
				throw new MWException( __METHOD__ . ": passed multiple arguments for rename of single file\n" );
			} else {
				$newname = $newname[0];
			}
		}
		return $newname;
	}

	/**
	 * @param string $newname
	 * @param bool $open
	 */
	function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			if ( $this->handle ) {
				fclose( $this->handle );
				$this->handle = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$this->handle = fopen( $this->filename, "wt" );
			}
		}
	}

	/**
	 * @return string|null
	 */
	function getFilenames() {
		return $this->filename;
	}
}
