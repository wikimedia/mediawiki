<?php

namespace MediaWiki\Rest;

/**
 * A stream class which uses a string as the underlying storage. Surprisingly,
 * Guzzle does not appear to have one of these. BufferStream does not do what
 * we want.
 *
 * The normal use of this class should be to first write to the stream, then
 * rewind, then read back the whole buffer with getContents().
 *
 * Seeking is supported, however seeking past the end of the string does not
 * fill with null bytes as in a real file, it throws an exception instead.
 */
class StringStream implements CopyableStreamInterface {
	private $contents = '';
	private $offset = 0;

	/**
	 * Construct a StringStream with the given contents.
	 *
	 * The offset will start at 0, ready for reading. If appending to the
	 * given string is desired, you should first seek to the end.
	 *
	 * @param string $contents
	 */
	public function __construct( $contents = '' ) {
		$this->contents = $contents;
	}

	public function copyToStream( $stream ) {
		fwrite( $stream, $this->getContents() );
	}

	public function __toString() {
		return $this->contents;
	}

	public function close() {
	}

	public function detach() {
		return null;
	}

	public function getSize() {
		return strlen( $this->contents );
	}

	public function tell() {
		return $this->offset;
	}

	public function eof() {
		return $this->offset >= strlen( $this->contents );
	}

	public function isSeekable() {
		return true;
	}

	public function seek( $offset, $whence = SEEK_SET ) {
		switch ( $whence ) {
			case SEEK_SET:
				$this->offset = $offset;
				break;

			case SEEK_CUR:
				$this->offset += $offset;
				break;

			case SEEK_END:
				$this->offset = strlen( $this->contents ) + $offset;
				break;

			default:
				throw new \InvalidArgumentException( "Invalid value for \$whence" );
		}
		if ( $this->offset > strlen( $this->contents ) ) {
			throw new \InvalidArgumentException( "Cannot seek beyond the end of a StringStream" );
		}
		if ( $this->offset < 0 ) {
			throw new \InvalidArgumentException( "Cannot seek before the start of a StringStream" );
		}
	}

	public function rewind() {
		$this->offset = 0;
	}

	public function isWritable() {
		return true;
	}

	public function write( $string ) {
		if ( $this->offset === strlen( $this->contents ) ) {
			$this->contents .= $string;
		} else {
			$this->contents = substr_replace( $this->contents, $string,
				$this->offset, strlen( $string ) );
		}
		$this->offset += strlen( $string );
		return strlen( $string );
	}

	public function isReadable() {
		return true;
	}

	public function read( $length ) {
		if ( $this->offset === 0 && $length >= strlen( $this->contents ) ) {
			$ret = $this->contents;
		} elseif ( $this->offset >= strlen( $this->contents ) ) {
			$ret = '';
		} else {
			$ret = substr( $this->contents, $this->offset, $length );
		}
		$this->offset += strlen( $ret );
		return $ret;
	}

	public function getContents() {
		if ( $this->offset === 0 ) {
			$ret = $this->contents;
		} elseif ( $this->offset >= strlen( $this->contents ) ) {
			$ret = '';
		} else {
			$ret = substr( $this->contents, $this->offset );
		}
		$this->offset = strlen( $this->contents );
		return $ret;
	}

	public function getMetadata( $key = null ) {
		return null;
	}
}
