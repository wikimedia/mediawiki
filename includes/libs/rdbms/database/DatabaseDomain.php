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
namespace Wikimedia\Rdbms;

use InvalidArgumentException;

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
			throw new InvalidArgumentException( "Domain '$domain' has too few or too many parts." );
		}

		if ( $database === '' ) {
			$database = null;
		}

		if ( $schema === '' ) {
			$schema = null;
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
	 * @return bool Whether the domain instances are the same by value
	 */
	public function equals( $other ) {
		if ( $other instanceof self ) {
			return (
				$this->database === $other->database &&
				$this->schema === $other->schema &&
				$this->prefix === $other->prefix
			);
		}

		return ( $this->getId() === $other );
	}

	/**
	 * Check whether the domain $other meets the specifications of this domain
	 *
	 * If this instance has a null database specifier, then $other can have any database
	 * specified, including the null, and likewise if the schema specifier is null. This
	 * is not transitive like equals() since a domain that explicitly wants a certain
	 * database or schema cannot be satisfied by one of another (nor null). If the prefix
	 * is empty and the DB and schema are both null, then the entire domain is considered
	 * unspecified, and any prefix of $other is considered compatible.
	 *
	 * @param DatabaseDomain|string $other
	 * @return bool
	 * @since 1.32
	 */
	public function isCompatible( $other ) {
		if ( $this->isUnspecified() ) {
			return true; // even the prefix doesn't matter
		}

		$other = ( $other instanceof self ) ? $other : self::newFromId( $other );

		return (
			( $this->database === $other->database || $this->database === null ) &&
			( $this->schema === $other->schema || $this->schema === null ) &&
			$this->prefix === $other->prefix
		);
	}

	/**
	 * @return bool
	 * @since 1.32
	 */
	public function isUnspecified() {
		return (
			$this->database === null && $this->schema === null && $this->prefix === ''
		);
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
		if ( $this->prefix != '' || $this->schema !== null ) {
			// If there is a schema, then we need the prefix to disambiguate.
			// For engines like Postgres that use schemas, this awkwardness is hopefully
			// avoided since it is easy to have one DB per server (to avoid having many users)
			// and use schema/prefix to have wiki farms. For example, a domain schemes could be
			// wiki-<project>-<language>, e.g. "wiki-fitness-es"/"wiki-sports-fr"/"wiki-news-en".
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
				$nextChar = $encoded[$i + 1] ?? null;
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
