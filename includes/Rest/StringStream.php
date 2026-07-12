<?php

namespace MediaWiki\Rest;

use InvalidArgumentException;
use Stringable;

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
class StringStream implements Stringable, CopyableStreamInterface {

	/** @var int */
	private $offset = 0;

	/**
	 * Construct a StringStream with the given contents.
	 *
	 * The offset will start at 0, ready for reading. If appending to the
	 * given string is desired, you should first seek to the end.
	 */
	public function __construct(
		private string $contents = '',
	) {
	}

	/** @inheritDoc */
	public function copyToStream( $stream ) {
		fwrite( $stream, $this->getContents() );
	}

	public function __toString(): string {
		return $this->contents;
	}

	public function close(): void {
	}

	/** @inheritDoc */
	public function detach() {
		return null;
	}

	/** @inheritDoc */
	public function getSize(): ?int {
		return strlen( $this->contents );
	}

	/** @inheritDoc */
	public function tell(): int {
		return $this->offset;
	}

	/** @inheritDoc */
	public function eof(): bool {
		return $this->offset >= strlen( $this->contents );
	}

	/** @inheritDoc */
	public function isSeekable(): bool {
		return true;
	}

	/** @inheritDoc */
	public function seek( int $offset, int $whence = SEEK_SET ): void {
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
				throw new InvalidArgumentException( "Invalid value for \$whence" );
		}
		if ( $this->offset > strlen( $this->contents ) ) {
			throw new InvalidArgumentException( "Cannot seek beyond the end of a StringStream" );
		}
		if ( $this->offset < 0 ) {
			throw new InvalidArgumentException( "Cannot seek before the start of a StringStream" );
		}
	}

	/** @inheritDoc */
	public function rewind(): void {
		$this->offset = 0;
	}

	/** @inheritDoc */
	public function isWritable(): bool {
		return true;
	}

	/** @inheritDoc */
	public function write( string $string ): int {
		if ( $this->offset === strlen( $this->contents ) ) {
			$this->contents .= $string;
		} else {
			$this->contents = substr_replace( $this->contents, $string,
				$this->offset, strlen( $string ) );
		}
		$this->offset += strlen( $string );
		return strlen( $string );
	}

	/** @inheritDoc */
	public function isReadable(): bool {
		return true;
	}

	/** @inheritDoc */
	public function read( int $length ): string {
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

	/** @inheritDoc */
	public function getContents(): string {
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

	/** @inheritDoc */
	public function getMetadata( ?string $key = null ) {
		return null;
	}
}
