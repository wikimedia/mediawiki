<?php
/**
 * Sends dump output via the p7zip compressor.
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
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

use MediaWiki\Shell\Shell;

/**
 * @ingroup Dump
 */
class Dump7ZipOutput extends DumpPipeOutput {
	/**
	 * @var int
	 */
	protected $compressionLevel;

	/**
	 * @param string $file
	 * @param int $cmpLevel Compression level passed to 7za command's -mx
	 */
	public function __construct( $file, $cmpLevel = 4 ) {
		$this->compressionLevel = $cmpLevel;
		$command = $this->setup7zCommand( $file );
		parent::__construct( $command );
		$this->filename = $file;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function setup7zCommand( $file ) {
		$command = "7za a -bd -si -mx=";
		$command .= Shell::escape( $this->compressionLevel ) . ' ';
		$command .= Shell::escape( $file );
		// Suppress annoying useless crap from p7zip
		// Unfortunately this could suppress real error messages too
		$command .= ' >' . wfGetNull() . ' 2>&1';
		return $command;
	}

	/**
	 * @inheritDoc
	 */
	public function closeAndRename( $newname, $open = false ) {
		$newname = $this->checkRenameArgCount( $newname );
		if ( $newname ) {
			fclose( $this->handle );
			proc_close( $this->procOpenResource );
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->setup7zCommand( $this->filename );
				$this->startCommand( $command );
			}
		}
	}
}
