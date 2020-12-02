<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class MWPostgreSqlPlatform extends PostgreSqlPlatform {

	/**
	 * Handles Postgres unique timestamp format
	 * @inheritDoc
	 *
	 * @param mixed[] $column The column definition array.
	 * @return string Postgres specific SQL code portion needed to set a default value.
	 */
	public function getDefaultValueDeclarationSQL( $column ) {
		$type = $column['type'];
		$default = $column['default'] ?? null;

		if ( $type instanceof TimestampType && $default ) {
			$timestamp = new ConvertibleTimestamp( $default );
			$pgTimestamp = $timestamp->getTimestamp( TS_POSTGRES );

			return " DEFAULT '$pgTimestamp' ";
		}

		return parent::getDefaultValueDeclarationSQL( $column );
	}

	/**
	 * @inheritDoc
	 * @suppress PhanParamSignatureRealMismatchHasNoParamType // Parameter widened by parent
	 * phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
	 */
	protected function _getCreateTableSQL( $name, $columns, array $options = [] ) {
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
	public function getFloatDeclarationSQL( array $column ) {
		return 'FLOAT';
	}
}
