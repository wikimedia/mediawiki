<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\MySQLPlatform;

class MWMySQLPlatform extends MySQLPlatform {

	/**
	 * @inheritDoc
	 */
	public function getFloatDeclarationSQL( array $column ): string {
		$double = $column['doublePrecision'] ?? false;
		$unsigned = $column['unsigned'] ?? false;

		$sql = $double ? 'DOUBLE PRECISION' : 'FLOAT';

		return $sql . ( $unsigned ? ' UNSIGNED' : '' );
	}
}
