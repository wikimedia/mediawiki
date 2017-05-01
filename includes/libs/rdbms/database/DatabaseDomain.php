<?php
/**
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
 * @ingroup Database
 */

/**
 * Class to handle database/prefix specification for IDatabase domains
 */
class DatabaseDomain {
	/** @var string|null */
	private $database;
	/** @var string|null */
	private $schema;
	/** @var string */
	private $prefix;

	/** @var string Cache of convertToString() */
	private $equivalentString;

	/**
	 * @param string|null $database Database name
	 * @param string|null $schema Schema name
	 * @param string $prefix Table prefix
	 */
	public function __construct( $database, $schema, $prefix ) {
		if ( $database !== null && ( !is_string( $database ) || !strlen( $database ) ) ) {
			throw new InvalidArgumentException( "Database must be null or a non-empty string." );
		}
		$this->database = $database;
		if ( $schema !== null && ( !is_string( $schema ) || !strlen( $schema ) ) ) {
			throw new InvalidArgumentException( "Schema must be null or a non-empty string." );
		}
		$this->schema = $schema;
		if ( !is_string( $prefix ) ) {
			throw new InvalidArgumentException( "Prefix must be a string." );
		}
		$this->prefix = $prefix;
	}

	/**
	 * @param DatabaseDomain|string $domain Result of DatabaseDomain::toString()
	 * @return DatabaseDomain
	 */
	public static function newFromId( $domain ) {
		if ( $domain instanceof self ) {
			return $domain;
		}

		$parts = array_map( [ __CLASS__, 'decode' ], explode( '-', $domain ) );

		$schema = null;
		$prefix = '';

		if ( count( $parts ) == 1 ) {
			$database = $parts[0];
		} elseif ( count( $parts ) == 2 ) {
			list( $database, $prefix ) = $parts;
		} elseif ( count( $parts ) == 3 ) {
			list( $database, $schema, $prefix ) = $parts;
		} else {
			throw new InvalidArgumentException( "Domain has too few or too many parts." );
		}

		if ( $database === '' ) {
			$database = null;
		}

		return new self( $database, $schema, $prefix );
	}

	/**
	 * @return DatabaseDomain
	 */
	public static function newUnspecified() {
		return new self( null, null, '' );
	}

	/**
	 * @param DatabaseDomain|string $other
	 * @return bool
	 */
	public function equals( $other ) {
		if ( $other instanceof DatabaseDomain ) {
			return (
				$this->database === $other->database &&
				$this->schema === $other->schema &&
				$this->prefix === $other->prefix
			);
		}

		return ( $this->getId() === $other );
	}

	/**
	 * @return string|null Database name
	 */
	public function getDatabase() {
		return $this->database;
	}

	/**
	 * @return string|null Database schema
	 */
	public function getSchema() {
		return $this->schema;
	}

	/**
	 * @return string Table prefix
	 */
	public function getTablePrefix() {
		return $this->prefix;
	}

	/**
	 * @return string
	 */
	public function getId() {
		if ( $this->equivalentString === null ) {
			$this->equivalentString = $this->convertToString();
		}

		return $this->equivalentString;
	}

	/**
	 * @return string
	 */
	private function convertToString() {
		$parts = [ $this->database ];
		if ( $this->schema !== null ) {
			$parts[] = $this->schema;
		}
		if ( $this->prefix != '' ) {
			$parts[] = $this->prefix;
		}

		return implode( '-', array_map( [ __CLASS__, 'encode' ], $parts ) );
	}

	private static function encode( $decoded ) {
		$encoded = '';

		$length = strlen( $decoded );
		for ( $i = 0; $i < $length; ++$i ) {
			$char = $decoded[$i];
			if ( $char === '-' ) {
				$encoded .= '?h';
			} elseif ( $char === '?' ) {
				$encoded .= '??';
			} else {
				$encoded .= $char;
			}
		}

		return $encoded;
	}

	private static function decode( $encoded ) {
		$decoded = '';

		$length = strlen( $encoded );
		for ( $i = 0; $i < $length; ++$i ) {
			$char = $encoded[$i];
			if ( $char === '?' ) {
				$nextChar = isset( $encoded[$i + 1] ) ? $encoded[$i + 1] : null;
				if ( $nextChar === 'h' ) {
					$decoded .= '-';
					++$i;
				} elseif ( $nextChar === '?' ) {
					$decoded .= '?';
					++$i;
				} else {
					$decoded .= $char;
				}
			} else {
				$decoded .= $char;
			}
		}

		return $decoded;
	}

	/**
	 * @return string
	 */
	function __toString() {
		return $this->getId();
	}
}
