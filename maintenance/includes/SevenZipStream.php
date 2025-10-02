<?php
/**
 * 7z stream wrapper
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

namespace MediaWiki\Maintenance;

use MediaWiki\Shell\Shell;

/**
 * Stream wrapper around 7za filter program.
 * Required since we can't pass an open file resource to XMLReader->open()
 * which is used for the text prefetch.
 *
 * @ingroup Maintenance
 */
class SevenZipStream {
	/** @var resource|false */
	protected $stream;

	/** @var resource|null Must exists on stream wrapper class */
	public $context;

	public static function register() {
		static $done = false;
		if ( !$done ) {
			$done = true;
			stream_wrapper_register( 'mediawiki.compress.7z', self::class );
		}
	}

	private function stripPath( string $path ): string {
		$prefix = 'mediawiki.compress.7z://';

		return substr( $path, strlen( $prefix ) );
	}

	public function stream_open( string $path, string $mode, int $options, ?string &$opened_path ): bool {
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

	public function url_stat( string $path, int $flags ): array|false {
		return stat( $this->stripPath( $path ) );
	}

	public function stream_close(): void {
		fclose( $this->stream );
	}

	public function stream_flush(): bool {
		return fflush( $this->stream );
	}

	public function stream_read( int $count ): string|false {
		return fread( $this->stream, $count );
	}

	public function stream_write( string $data ): int {
		return fwrite( $this->stream, $data );
	}

	public function stream_tell(): int {
		return ftell( $this->stream );
	}

	public function stream_eof(): bool {
		return feof( $this->stream );
	}

	public function stream_seek( int $offset, int $whence ): bool {
		return fseek( $this->stream, $offset, $whence ) === 0;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( SevenZipStream::class, 'SevenZipStream' );
