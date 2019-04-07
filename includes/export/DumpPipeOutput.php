<?php
/**
 * Stream outputter to send data to a file via some filter program.
 * Even if compression is available in a library, using a separate
 * program can allow us to make use of a multi-processor system.
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
class DumpPipeOutput extends DumpFileOutput {
	protected $command, $filename;
	protected $procOpenResource = false;

	/**
	 * @param string $command
	 * @param string|null $file
	 */
	function __construct( $command, $file = null ) {
		if ( !is_null( $file ) ) {
			$command .= " > " . Shell::escape( $file );
		}

		$this->startCommand( $command );
		$this->command = $command;
		$this->filename = $file;
	}

	/**
	 * @param string $string
	 */
	function writeCloseStream( $string ) {
		parent::writeCloseStream( $string );
		if ( $this->procOpenResource ) {
			proc_close( $this->procOpenResource );
			$this->procOpenResource = false;
		}
	}

	/**
	 * @param string $command
	 */
	function startCommand( $command ) {
		$spec = [
			0 => [ "pipe", "r" ],
		];
		$pipes = [];
		$this->procOpenResource = proc_open( $command, $spec, $pipes );
		$this->handle = $pipes[0];
	}

	/**
	 * @param string $newname
	 */
	function closeRenameAndReopen( $newname ) {
		$this->closeAndRename( $newname, true );
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
			if ( $this->procOpenResource ) {
				proc_close( $this->procOpenResource );
				$this->procOpenResource = false;
			}
			$this->renameOrException( $newname );
			if ( $open ) {
				$command = $this->command;
				$command .= " > " . Shell::escape( $this->filename );
				$this->startCommand( $command );
			}
		}
	}
}
