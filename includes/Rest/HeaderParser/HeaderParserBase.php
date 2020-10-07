<?php

namespace MediaWiki\Rest\HeaderParser;

/**
 * @internal
 */
class HeaderParserBase {
	/**
	 * @var string The input string being processed
	 */
	protected $input;

	/**
	 * @var int The position within $input
	 */
	protected $pos;

	/**
	 * @var int The length of $input
	 */
	protected $inputLength;

	/**
	 * Set the input, and derived convenience properties
	 *
	 * @param string $input
	 */
	protected function setInput( $input ) {
		$this->input = $input;
		$this->pos = 0;
		$this->inputLength = strlen( $input );
	}

	/**
	 * Consume a specified string, or throw an exception.
	 *
	 * @param string $s
	 * @throws HeaderParserError
	 */
	protected function consumeString( $s ) {
		if ( $this->pos >= $this->inputLength ) {
			$this->error( "Expected \"$s\" but got end of string" );
		}
		if ( substr_compare( $this->input, $s, $this->pos, strlen( $s ) ) === 0 ) {
			$this->pos += strlen( $s );
		} else {
			$this->error( "Expected \"$s\"" );
		}
	}

	/**
	 * Skip whitespace at the input position (OWS)
	 */
	protected function skipWhitespace() {
		$this->pos += strspn( $this->input, " \t", $this->pos );
	}

	/**
	 * Throw an exception to indicate a parse error
	 *
	 * @param string $message
	 * @throws HeaderParserError
	 */
	protected function error( $message ) {
		throw new HeaderParserError( "$message at {$this->pos}" );
	}

	/**
	 * Consume a specified number of digits, or throw an exception
	 *
	 * @param int $numDigits
	 * @return string
	 * @throws HeaderParserError
	 */
	protected function consumeFixedDigits( $numDigits ) {
		$digits = substr( $this->input, $this->pos, $numDigits );
		if ( strlen( $digits ) !== $numDigits || !preg_match( '/^[0-9]*$/', $digits ) ) {
			$this->error( "expected $numDigits digits" );
		}
		$this->pos += $numDigits;
		return $digits;
	}

	/**
	 * If the position is not at the end of the input string, raise an error,
	 * complaining of trailing characters.
	 *
	 * @throws HeaderParserError
	 */
	protected function assertEnd() {
		if ( $this->pos !== $this->inputLength ) {
			$this->error( "trailing characters" );
		}
	}
}
