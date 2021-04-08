<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\PhpIntegerMappingType;
use Doctrine\DBAL\Types\Type;

/**
 * Handling smallest integer data type
 */
class TinyIntType extends Type implements PhpIntegerMappingType {
	public const TINYINT = 'mwtinyint';

	public function getSQLDeclaration( array $fieldDeclaration, AbstractPlatform $platform ) {
		if ( $platform->getName() == 'mysql' ) {
			if ( !empty( $fieldDeclaration['length'] ) && is_numeric( $fieldDeclaration['length'] ) ) {
				$length = $fieldDeclaration['length'];
				return "TINYINT($length)" . $this->getCommonIntegerTypeDeclarationForMySQL( $fieldDeclaration );
			}
			return 'TINYINT' . $this->getCommonIntegerTypeDeclarationForMySQL( $fieldDeclaration );
		}

		return $platform->getSmallIntTypeDeclarationSQL( $fieldDeclaration );
	}

	protected function getCommonIntegerTypeDeclarationForMySQL( array $columnDef ) {
		$autoinc = '';
		if ( !empty( $columnDef['autoincrement'] ) ) {
			$autoinc = ' AUTO_INCREMENT';
		}

		return !empty( $columnDef['unsigned'] ) ? ' UNSIGNED' . $autoinc : $autoinc;
	}

	public function getName() {
		return self::TINYINT;
	}
}
