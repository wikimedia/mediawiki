<?php
/**
 * Base class for output stream; prints to stdout or buffer or wherever.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @ingroup Dump
 */
class DumpOutput {

	/**
	 * @param string $string
	 */
	public function writeOpenStream( $string ) {
		$this->write( $string );
	}

	/**
	 * @param string $string
	 */
	public function writeCloseStream( $string ) {
		$this->write( $string );
	}

	/**
	 * @param stdClass|null $page
	 * @param string $string
	 */
	public function writeOpenPage( $page, $string ) {
		$this->write( $string );
	}

	/**
	 * @param string $string
	 */
	public function writeClosePage( $string ) {
		$this->write( $string );
	}

	/**
	 * @param stdClass|null $rev
	 * @param string $string
	 */
	public function writeRevision( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * @param stdClass $rev
	 * @param string $string
	 */
	public function writeLogItem( $rev, $string ) {
		$this->write( $string );
	}

	/**
	 * Override to write to a different stream type.
	 * @param string $string
	 */
	public function write( $string ) {
		print $string;
	}

	/**
	 * Close the old file, move it to a specified name,
	 * and reopen new file with the old name. Use this
	 * for writing out a file in multiple pieces
	 * at specified checkpoints (e.g. every n hours).
	 * @param string|string[] $newname File name. May be a string or an array with one element
	 */
	public function closeRenameAndReopen( $newname ) {
	}

	/**
	 * Close the old file, and move it to a specified name.
	 * Use this for the last piece of a file written out
	 * at specified checkpoints (e.g. every n hours).
	 * @param string|string[] $newname File name. May be a string or an array with one element
	 * @param bool $open If true, a new file with the old filename will be opened
	 *   again for writing (default: false)
	 */
	public function closeAndRename( $newname, $open = false ) {
	}

	/**
	 * Returns the name of the file or files which are
	 * being written to, if there are any.
	 * @return null
	 */
	public function getFilenames() {
		return null;
	}
}
