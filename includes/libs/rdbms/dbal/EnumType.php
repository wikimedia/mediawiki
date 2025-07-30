<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * Custom handling for ENUM datatype
 *
 * NOTE: Use of this type is discouraged unless necessary. Please use
 * alternative types where possible. See T119173 for the RFC discussion
 * about this type and potential alternatives.
 *
 * @see https://phabricator.wikimedia.org/T119173
 */
class EnumType extends Type {
	public const ENUM = 'mwenum';

	/**
	 * Gets the SQL declaration snippet for an ENUM column
	 *
	 * @param mixed[] $column Column definition
	 * @param AbstractPlatform $platform
	 *
	 * @return string
	 */
	public function getSQLDeclaration( array $column, AbstractPlatform $platform ): string {
		// SQLite does not support ENUM type
		if ( $platform instanceof SQLitePlatform ) {
			return 'TEXT';
		}

		// PostgreSQL does support but needs custom handling.
		// This just returns a string name that references the
		// actual ENUM which will be created by CREATE TYPE command
		// If 'fixed' option is not passed, this field will use TEXT
		if ( $platform instanceof PostgreSQLPlatform ) {
			if ( !$column['fixed'] ) {
				return 'TEXT';
			}

			return strtoupper( $column['name'] . '_enum' );
		}

		if ( $platform instanceof MySQLPlatform ) {
			$enumValues = $this->formatValues( $column['enum_values'] );
			return "ENUM( $enumValues )";
		}
	}

	/**
	 * Gets the sql portion to create ENUM for Postgres table column
	 *
	 * @param mixed[] $column
	 * @param AbstractPlatform $platform
	 *
	 * @see MWPostgreSqlPlatform::_getCreateTableSQL()
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	public function makeEnumTypeSql( $column, $platform ): string {
		if ( !( $platform instanceof PostgreSQLPlatform ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' can only be called on Postgres platform'
			);
		}

		$enumName = strtoupper( $column['name'] . '_enum' );
		$enumValues = $this->formatValues( $column['enum_values'] );
		$typeSql = "\n\nCREATE TYPE $enumName AS ENUM( $enumValues )";

		return $typeSql;
	}

	/**
	 * Get the imploded values suitable for pushing directly into ENUM();
	 *
	 * @param string[] $values
	 * @return string
	 */
	public function formatValues( $values ): string {
		$values = implode( "','", $values );
		$enumValues = "'" . $values . "'";

		return $enumValues;
	}

	public function getName(): string {
		return self::ENUM;
	}
}
