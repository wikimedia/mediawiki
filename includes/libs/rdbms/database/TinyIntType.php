<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Handling smallest integer data type
 */
class TinyIntType extends Type {
	public const TINYINT = 'mwtinyint';

	public function getSQLDeclaration( array $fieldDeclaration, AbstractPlatform $platform ) {
		if ( $platform->getName() == 'mysql' ) {
			if ( !empty( $fieldDeclaration['length'] ) && is_numeric( $fieldDeclaration['length'] ) ) {
				$length = $fieldDeclaration['length'];
				return "TINYINT($length)";
			}
			return 'TINYINT';
		}

		return $platform->getSmallIntTypeDeclarationSQL( $fieldDeclaration );
	}

	public function getName() {
		return self::TINYINT;
	}
}
