<?php
/**
 * 7z stream wrapper
 *
 * @file
 * @ingroup Maintenance
 */

/**
 * Stream wrapper around 7za filter program.
 * Required since we can't pass an open file resource to XMLReader->open()
 * which is used for the text prefetch.
 *
 * @ingroup Maintenance
 */
class SevenZipStream {
	var $stream;

	private function stripPath( $path ) {
		$prefix = 'mediawiki.compress.7z://';
		return substr( $path, strlen( $prefix ) );
	}

	function stream_open( $path, $mode, $options, &$opened_path ) {
		if ( $mode[0] == 'r' ) {
			$options = 'e -bd -so';
		} elseif ( $mode[0] == 'w' ) {
			$options = 'a -bd -si';
		} else {
			return false;
		}
		$arg = wfEscapeShellArg( $this->stripPath( $path ) );
		$command = "7za $options $arg";
		if ( !wfIsWindows() ) {
			// Suppress the stupid messages on stderr
			$command .= ' 2>/dev/null';
		}
		$this->stream = popen( $command, $mode[0] ); // popen() doesn't like two-letter modes
		return ( $this->stream !== false );
	}

	function url_stat( $path, $flags ) {
		return stat( $this->stripPath( $path ) );
	}

	// This is all so lame; there should be a default class we can extend

	function stream_close() {
		return fclose( $this->stream );
	}

	function stream_flush() {
		return fflush( $this->stream );
	}

	function stream_read( $count ) {
		return fread( $this->stream, $count );
	}

	function stream_write( $data ) {
		return fwrite( $this->stream, $data );
	}

	function stream_tell() {
		return ftell( $this->stream );
	}

	function stream_eof() {
		return feof( $this->stream );
	}

	function stream_seek( $offset, $whence ) {
		return fseek( $this->stream, $offset, $whence );
	}
}
stream_wrapper_register( 'mediawiki.compress.7z', 'SevenZipStream' );
