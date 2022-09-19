<?php

namespace Wikimedia\Rdbms;

/**
 * @phan-file-suppress PhanCommentAbstractOnInheritedMethod T298571
 */

/**
 * @suppress PhanRedefinedExtendedClass
 */
class MWMySQLPlatform extends MWMySQLPlatformCompat {

	/**
	 * @inheritDoc
	 */
	public function getFloatDeclarationSQL( array $column ) {
		$double = $column['doublePrecision'] ?? false;
		$unsigned = $column['unsigned'] ?? false;

		$sql = $double ? 'DOUBLE PRECISION' : 'FLOAT';

		return $sql . ( $unsigned ? ' UNSIGNED' : '' );
	}
}
