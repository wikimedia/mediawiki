<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Handles Postgres unique timestamp format
 */
class MWPostgreSqlPlatform extends PostgreSqlPlatform {

	/**
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
}
