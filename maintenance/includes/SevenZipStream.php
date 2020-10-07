<?php
/**
 * 7z stream wrapper
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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
 * @ingroup Maintenance
 */

use MediaWiki\Shell\Shell;

/**
 * Stream wrapper around 7za filter program.
 * Required since we can't pass an open file resource to XMLReader->open()
 * which is used for the text prefetch.
 *
 * @ingroup Maintenance
 */
class SevenZipStream {
	protected $stream;

	private function stripPath( $path ) {
		$prefix = 'mediawiki.compress.7z://';

		return substr( $path, strlen( $prefix ) );
	}

	public function stream_open( $path, $mode, $options, &$opened_path ) {
		if ( $mode[0] == 'r' ) {
			$options = 'e -bd -so';
		} elseif ( $mode[0] == 'w' ) {
			$options = 'a -bd -si';
		} else {
			return false;
		}
		$arg = Shell::escape( $this->stripPath( $path ) );
		$command = "7za $options $arg";
		if ( !wfIsWindows() ) {
			// Suppress the stupid messages on stderr
			$command .= ' 2>/dev/null';
		}
		// popen() doesn't like two-letter modes
		$this->stream = popen( $command, $mode[0] );
		return ( $this->stream !== false );
	}

	public function url_stat( $path, $flags ) {
		return stat( $this->stripPath( $path ) );
	}

	public function stream_close() {
		return fclose( $this->stream );
	}

	public function stream_flush() {
		return fflush( $this->stream );
	}

	public function stream_read( $count ) {
		return fread( $this->stream, $count );
	}

	public function stream_write( $data ) {
		return fwrite( $this->stream, $data );
	}

	public function stream_tell() {
		return ftell( $this->stream );
	}

	public function stream_eof() {
		return feof( $this->stream );
	}

	public function stream_seek( $offset, $whence ) {
		return fseek( $this->stream, $offset, $whence );
	}
}

stream_wrapper_register( 'mediawiki.compress.7z', SevenZipStream::class );
