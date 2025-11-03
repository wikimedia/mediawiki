<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use Stringable;

/**
 * Class to handle database/schema/prefix specifications for IDatabase
 *
 * The components of a database domain are defined as follows:
 *   - database: name of a server-side collection of schemas that is client-selectable
 *   - schema: name of a server-side collection of tables within the given database
 *   - prefix: table name prefix of an application-defined table collection
 *
 * If an RDBMS does not support server-side collections of table collections (schemas) then
 * the schema component should be null and the "database" component treated as a collection
 * of exactly one table collection (the implied schema for that "database").
 *
 * The above criteria should determine how components should map to RDBMS specific keywords
 * rather than "database"/"schema" always mapping to "DATABASE"/"SCHEMA" as used by the RDBMS.
 *
 * @ingroup Database
 */
class DatabaseDomain implements Stringable {
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
		if ( $database !== null && ( !is_string( $database ) || $database === '' ) ) {
			throw new InvalidArgumentException( 'Database must be null or a non-empty string.' );
		}
		$this->database = $database;
		if ( $schema !== null && ( !is_string( $schema ) || $schema === '' ) ) {
			throw new InvalidArgumentException( 'Schema must be null or a non-empty string.' );
		} elseif ( $database === null && $schema !== null ) {
			throw new InvalidArgumentException( 'Schema must be null if database is null.' );
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
	public static function newFromId( DatabaseDomain|string $domain ): self {
		if ( $domain instanceof self ) {
			return $domain;
		}

		$parts = explode( '-', $domain );
		foreach ( $parts as &$part ) {
			$part = strtr( $part, [ '?h' => '-', '??' => '?' ] );
		}

		$schema = null;
		$prefix = '';

		if ( count( $parts ) == 1 ) {
			$database = $parts[0];
		} elseif ( count( $parts ) == 2 ) {
			[ $database, $prefix ] = $parts;
		} elseif ( count( $parts ) == 3 ) {
			[ $database, $schema, $prefix ] = $parts;
		} else {
			throw new InvalidArgumentException( "Domain '$domain' has too few or too many parts." );
		}

		if ( $database === '' ) {
			$database = null;
		}

		if ( $schema === '' ) {
			$schema = null;
		}

		$instance = new self( $database, $schema, $prefix );
		$instance->equivalentString = $domain;

		return $instance;
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
	 * specifier, including null. This is likewise true if the schema specifier is null.
	 * This is not transitive like equals() since a domain that explicitly wants a certain
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

		$other = self::newFromId( $other );

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

	public function getId(): string {
		$this->equivalentString ??= $this->convertToString();

		return $this->equivalentString;
	}

	private function convertToString(): string {
		$parts = [ (string)$this->database ];
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

		foreach ( $parts as &$part ) {
			$part = strtr( $part, [ '-' => '?h', '?' => '??' ] );
		}
		return implode( '-', $parts );
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getId();
	}
}
