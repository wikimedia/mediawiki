<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class MWPostgreSqlPlatform extends PostgreSQLPlatform {
	/**
	 * Handles Postgres unique timestamp format
	 * @inheritDoc
	 *
	 * @param mixed[] $column The column definition array.
	 * @return string Postgres specific SQL code portion needed to set a default value.
	 */
	public function getDefaultValueDeclarationSQL( $column ): string {
		$type = $column['type'];
		$default = $column['default'] ?? null;

		if ( $type instanceof TimestampType && $default ) {
			if ( isset( $column['allowInfinite'] ) &&
				$column['allowInfinite'] &&
				$default === 'infinity'
			) {
				$pgTimestamp = $default;
			} else {
				$timestamp = new ConvertibleTimestamp( $default );
				$pgTimestamp = $timestamp->getTimestamp( TS_POSTGRES );
			}

			return " DEFAULT '$pgTimestamp' ";
		}

		return parent::getDefaultValueDeclarationSQL( $column );
	}

	/**
	 * @inheritDoc
	 * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
	 */
	protected function _getCreateTableSQL( $name, $columns, array $options = [] ): array {
		// phpcs:enable
		$tableSql = parent::_getCreateTableSQL( $name, $columns, $options );
		foreach ( $columns as $column ) {
			if ( $column['type'] instanceof EnumType && $column['fixed'] ) {
				// PostgreSQL does support ENUM datatype but they need to be
				// created severally with CREATE TYPE command for each column
				// as it's not possible to feed the values directly in the
				// column declaration as it could be done in MySQL.
				$typeSql = $column['type']->makeEnumTypeSql( $column, $this );
				array_unshift( $tableSql, $typeSql );
			}
		}

		return $tableSql;
	}

	/**
	 * @inheritDoc
	 */
	public function getBlobTypeDeclarationSQL( array $column ): string {
		// MySQL goes with varbinary for collation reasons, but postgres can't
		// properly understand BYTEA type and works just fine with TEXT type
		// FIXME: This should be fixed at some point (T257755)
		return 'TEXT';
	}

	/**
	 * @inheritDoc
	 */
	public function getBinaryTypeDeclarationSQL( array $column ): string {
		// MySQL goes with varbinary for collation reasons, but postgres can't
		// properly understand BYTEA type and works just fine with TEXT type
		// FIXME: This should be fixed at some point (T257755)
		return 'TEXT';
	}

	/**
	 * @inheritDoc
	 */
	public function getFloatDeclarationSQL( array $column ): string {
		return 'FLOAT';
	}

	/**
	 * @inheritDoc
	 */
	public function getDateTimeTzTypeDeclarationSQL( array $column ): string {
		return 'TIMESTAMPTZ';
	}
}
