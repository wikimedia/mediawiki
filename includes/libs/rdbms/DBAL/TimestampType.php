<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Handling timestamp edge cases in mediawiki.
 * https://www.mediawiki.org/wiki/Manual:Timestamp
 */
class TimestampType extends Type {
	public const TIMESTAMP = 'mwtimestamp';

	public function getSQLDeclaration( array $fieldDeclaration, AbstractPlatform $platform ): string {
		if ( $platform instanceof MySQLPlatform ) {
			// "infinite" (in expiry values has to be VARBINARY)
			if ( isset( $fieldDeclaration['allowInfinite'] ) && $fieldDeclaration['allowInfinite'] ) {
				return 'VARBINARY(14)';
			}
			return 'BINARY(14)';
		}

		if ( $platform instanceof SQLitePlatform ) {
			return 'BLOB';
		}

		if ( $platform instanceof PostgreSQLPlatform ) {
			return 'TIMESTAMPTZ';
		}

		return $platform->getDateTimeTzTypeDeclarationSQL( $fieldDeclaration );
	}

	public function getName(): string {
		return self::TIMESTAMP;
	}
}
