<?php

namespace MediaWiki\Rest;

/**
 * This is a container for storing headers. The header names are case-insensitive,
 * but the case is preserved for methods that return headers in bulk. The
 * header values are a comma-separated list, or equivalently, an array of strings.
 *
 * Unlike PSR-7, the container is mutable.
 */
class HeaderContainer {
	private $headerLists = [];
	private $headerLines = [];
	private $headerNames = [];

	/**
	 * Erase any existing headers and replace them with the specified
	 * header arrays or values.
	 *
	 * @param array $headers
	 */
	public function resetHeaders( $headers = [] ) {
		$this->headerLines = [];
		$this->headerLists = [];
		$this->headerNames = [];
		foreach ( $headers as $name => $value ) {
			$this->headerNames[ strtolower( $name ) ] = $name;
			list( $valueParts, $valueLine ) = $this->convertToListAndString( $value );
			$this->headerLines[$name] = $valueLine;
			$this->headerLists[$name] = $valueParts;
		}
	}

	/**
	 * Take an input header value, which may either be a string or an array,
	 * and convert it to an array of header values and a header line.
	 *
	 * The return value is an array where element 0 has the array of header
	 * values, and element 1 has the header line.
	 *
	 * Theoretically, if the input is a string, this could parse the string
	 * and split it on commas. Doing this is complicated, because some headers
	 * can contain double-quoted strings containing commas. The User-Agent
	 * header allows commas in comments delimited by parentheses. So it is not
	 * just explode(",", $value), we would need to parse a grammar defined by
	 * RFC 7231 appendix D which depends on header name.
	 *
	 * It's unclear how much it would help handlers to have fully spec-aware
	 * HTTP header handling just to split on commas. They would probably be
	 * better served by an HTTP header parsing library which provides the full
	 * parse tree.
	 *
	 * @param string|string[] $value The input header value
	 * @return array
	 */
	private function convertToListAndString( $value ) {
		if ( is_array( $value ) ) {
			return [ array_values( $value ), implode( ', ', $value ) ];
		} else {
			return [ [ $value ], $value ];
		}
	}

	/**
	 * Set or replace a header
	 *
	 * @param string $name
	 * @param string|string[] $value
	 */
	public function setHeader( $name, $value ) {
		list( $valueParts, $valueLine ) = $this->convertToListAndString( $value );
		$lowerName = strtolower( $name );
		$origName = $this->headerNames[$lowerName] ?? null;
		if ( $origName !== null ) {
			unset( $this->headerLines[$origName] );
			unset( $this->headerLists[$origName] );
		}
		$this->headerNames[$lowerName] = $name;
		$this->headerLines[$name] = $valueLine;
		$this->headerLists[$name] = $valueParts;
	}

	/**
	 * Set a header or append to an existing header
	 *
	 * @param string $name
	 * @param string|string[] $value
	 */
	public function addHeader( $name, $value ) {
		list( $valueParts, $valueLine ) = $this->convertToListAndString( $value );
		$lowerName = strtolower( $name );
		$origName = $this->headerNames[$lowerName] ?? null;
		if ( $origName === null ) {
			$origName = $name;
			$this->headerNames[$lowerName] = $origName;
			$this->headerLines[$origName] = $valueLine;
			$this->headerLists[$origName] = $valueParts;
		} else {
			$this->headerLines[$origName] .= ', ' . $valueLine;
			$this->headerLists[$origName] = array_merge( $this->headerLists[$origName],
				$valueParts );
		}
	}

	/**
	 * Remove a header
	 *
	 * @param string $name
	 */
	public function removeHeader( $name ) {
		$lowerName = strtolower( $name );
		$origName = $this->headerNames[$lowerName] ?? null;
		if ( $origName !== null ) {
			unset( $this->headerNames[$lowerName] );
			unset( $this->headerLines[$origName] );
			unset( $this->headerLists[$origName] );
		}
	}

	/**
	 * Get header arrays indexed by original name
	 *
	 * @return string[][]
	 */
	public function getHeaders() {
		return $this->headerLists;
	}

	/**
	 * Get the header with a particular name, or an empty array if there is no
	 * such header.
	 *
	 * @param string $name
	 * @return string[]
	 */
	public function getHeader( $name ) {
		$headerName = $this->headerNames[ strtolower( $name ) ] ?? null;
		if ( $headerName === null ) {
			return [];
		}
		return $this->headerLists[$headerName];
	}

	/**
	 * Return true if the header exists, false otherwise
	 * @param string $name
	 * @return bool
	 */
	public function hasHeader( $name ) {
		return isset( $this->headerNames[ strtolower( $name ) ] );
	}

	/**
	 * Get the specified header concatenated into a comma-separated string.
	 * If the header does not exist, an empty string is returned.
	 *
	 * @param string $name
	 * @return string
	 */
	public function getHeaderLine( $name ) {
		$headerName = $this->headerNames[ strtolower( $name ) ] ?? null;
		if ( $headerName === null ) {
			return '';
		}
		return $this->headerLines[$headerName];
	}

	/**
	 * Get all header lines
	 *
	 * @return string[]
	 */
	public function getHeaderLines() {
		return $this->headerLines;
	}

	/**
	 * Get an array of strings of the form "Name: Value", suitable for passing
	 * directly to header() to set response headers. The PHP manual describes
	 * these strings as "raw HTTP headers", so we adopt that terminology.
	 *
	 * @return string[] Header list (integer indexed)
	 */
	public function getRawHeaderLines() {
		$lines = [];
		foreach ( $this->headerNames as $lowerName => $name ) {
			if ( $lowerName === 'set-cookie' ) {
				// As noted by RFC 7230 section 3.2.2, Set-Cookie is the only
				// header for which multiple values cannot be concatenated into
				// a single comma-separated line.
				foreach ( $this->headerLists[$name] as $value ) {
					$lines[] = "$name: $value";
				}
			} else {
				$lines[] = "$name: " . $this->headerLines[$name];
			}
		}
		return $lines;
	}
}
