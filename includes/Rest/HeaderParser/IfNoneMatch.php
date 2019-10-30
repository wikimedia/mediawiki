<?php

namespace MediaWiki\Rest\HeaderParser;

/**
 * A class to assist with the parsing of If-None-Match, If-Match and ETag headers
 */
class IfNoneMatch extends HeaderParserBase {
	private $results = [];

	/** @var string|null */
	private $lastError;

	/**
	 * Parse an If-None-Match or If-Match header list as returned by
	 * RequestInterface::getHeader().
	 *
	 * The return value is an array of tag info arrays. Each tag info array is
	 * an associative array with the following keys:
	 *
	 *   - weak: True if the tag is weak, false otherwise
	 *   - contents: The contents of the double-quoted opaque-tag, not
	 *     including the quotes.
	 *   - whole: The whole ETag, including weak indicator and quoted opaque-tag
	 *
	 * In the case of a wildcard header like "If-Match: *", there cannot be any
	 * other tags. The return value is an array with a single tag info array with
	 * 'whole' => '*'.
	 *
	 * If the header was invalid, an empty array will be returned. Further
	 * information about why the parsing failed can be found by calling
	 * getLastError().
	 *
	 * @param string[] $headerList
	 * @return array[]
	 */
	public function parseHeaderList( $headerList ) {
		$this->lastError = null;
		if ( count( $headerList ) === 1 && $headerList[0] === '*' ) {
			return [ [
				'weak' => true,
				'contents' => null,
				'whole' => '*'
			] ];
		}
		$this->results = [];
		try {
			foreach ( $headerList as $header ) {
				$this->parseHeader( $header );
			}
			return $this->results;
		} catch ( HeaderParserError $e ) {
			$this->lastError = $e->getMessage();
			return [];
		}
	}

	/**
	 * Parse an entity-tag such as might be found in an ETag response header.
	 * The result is an associative array in the same format returned by
	 * parseHeaderList().
	 *
	 * If parsing failed, null is returned.
	 *
	 * @param string $eTag
	 * @return array|null
	 */
	public function parseETag( $eTag ) {
		$this->setInput( $eTag );
		$this->results = [];

		try {
			$this->consumeTag();
			$this->assertEnd();
		} catch ( HeaderParserError $e ) {
			$this->lastError = $e->getMessage();
			return null;
		}
		/* @phan-suppress-next-line PhanTypeInvalidDimOffset */
		return $this->results[0];
	}

	/**
	 * Get the last parse error message, or null if there was none
	 *
	 * @return string|null
	 */
	public function getLastError() {
		return $this->lastError;
	}

	/**
	 * Parse a single header
	 *
	 * @param string $header
	 * @throws HeaderParserError
	 */
	private function parseHeader( $header ) {
		$this->setInput( $header );
		$this->consumeTagList();
		$this->assertEnd();
	}

	/**
	 * Consume a comma-separated list of entity-tags
	 *
	 * @throws HeaderParserError
	 */
	private function consumeTagList() {
		while ( true ) {
			$this->skipWhitespace();
			$this->consumeTag();
			$this->skipWhitespace();
			if ( $this->pos === $this->inputLength ) {
				break;
			} else {
				$this->consumeString( ',' );
			}
		}
	}

	/**
	 * Consume a single entity-tag and append to the result array
	 *
	 * @throws HeaderParserError
	 */
	private function consumeTag() {
		$weak = false;
		$whole = '';
		if ( substr( $this->input, $this->pos, 2 ) === 'W/' ) {
			$weak = true;
			$whole .= 'W/';
			$this->pos += 2;
		}
		$this->consumeString( '"' );
		if ( !preg_match( '/[!#-~\x80-\xff]*/A', $this->input, $m, 0, $this->pos ) ) {
			$this->error( 'unexpected regex failure' );
		}
		$contents = $m[0];
		$this->pos += strlen( $contents );
		$this->consumeString( '"' );
		$whole .= '"' . $contents . '"';
		$this->results[] = [
			'weak' => $weak,
			'contents' => $contents,
			'whole' => $whole
		];
	}
}
