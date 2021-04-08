<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Handling timestamp edge cases in mediawiki.
 * https://www.mediawiki.org/wiki/Manual:Timestamp
 */
class TimestampType extends Type {
	public const TIMESTAMP = 'mwtimestamp';

	public function getSQLDeclaration( array $fieldDeclaration, AbstractPlatform $platform ) {
		if ( $platform->getName() == 'mysql' ) {
			// "infinite" (in expiry values has to be VARBINARY)
			if ( isset( $fieldDeclaration['allowInfinite'] ) && $fieldDeclaration['allowInfinite'] == true ) {
				return 'VARBINARY(14)';
			}
			return 'BINARY(14)';
		}

		if ( $platform->getName() == 'sqlite' ) {
			return 'BLOB';
		}

		if ( $platform->getName() == 'postgresql' ) {
			return 'TIMESTAMPTZ';
		}

		return $platform->getDateTimeTzTypeDeclarationSQL( $fieldDeclaration );
	}

	public function getName() {
		return self::TIMESTAMP;
	}
}
