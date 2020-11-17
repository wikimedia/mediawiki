<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

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
	public function getSQLDeclaration( array $column, AbstractPlatform $platform ) {
		// SQLite does not support ENUM type
		if ( $platform->getName() == 'sqlite' ) {
			return 'TEXT';
		}

		$values = implode( "','", $column['enum_values'] );
		$enumValues = "'" . $values . "'";

		return "ENUM( $enumValues )";
	}

	public function getName() {
		return self::ENUM;
	}
}
