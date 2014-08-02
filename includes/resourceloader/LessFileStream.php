<?php

class LessFileStream {

	/* Properties */
	public $context;
	public static $replacement;

	protected $handle;
	protected $buffer = '';

	/* Methods */
	public function stream_close() { return fclose( $this->handle ); } //
	public function stream_eof() { return feof( $this->handle ); } //
	public function stream_flush() { return fflush( $this->handle ); } //

	public function stream_open( $path, $mode, $options, &$opened_path ) { //

		if ( STREAM_REPORT_ERRORS & $options ) {
			$this->handle = fopen( $this->fixPath( $path ), $mode, $options & STREAM_USE_PATH !== 0 );
		} else {
			$this->handle = @fopen( $this->fixPath( $path ), $mode, $options & STREAM_USE_PATH !== 0 );
		}

		if ( $this->handle !== false ) {
			$meta_data = stream_get_meta_data( $this->handle );
			$opened_path = 'lessfile://' . $meta_data["uri"];
			return true;
		}

		return false;
	}

	public function stream_read( $count ) { //
		$data = fread( $this->handle, $count );
		$fixedData = str_replace( '!ie', self::$replacement, $data );

		if ( $data !== $fixedData ) {
			wfDeprecated( 'the !ie hack in .less files', '1.24', false, 3 );
		}
		return $fixedData;
	}
	public function stream_stat() { return fstat( $this->handle ); } //

	public function url_stat( $path, $flags ) { //
		if ( STREAM_URL_STAT_QUIET & $flags ) {
			@$arr = stat( $this->fixPath( $path ) );
		} else {
			$arr = stat( $this->fixPath( $path ) );
		}
		return $arr;
	}

	public static function fixPath ( $path ) { return strpos( $path, 'lessfile://' ) === 0? substr( $path, 11): $path; }
}
