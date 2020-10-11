<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * @experimental
 * @unstable
 */
class DoctrineSchemaBuilderFactory {

	/**
	 * @param string $platform one of strings 'mysql', 'postgres' or 'sqlite'
	 * @return DoctrineSchemaBuilder
	 */
	public function getSchemaBuilder( string $platform ) {
		return new DoctrineSchemaBuilder( $this->getPlatform( $platform ) );
	}

	/**
	 * @param string $platform one of strings 'mysql', 'postgres' or 'sqlite'
	 * @return DoctrineSchemaChangeBuilder
	 */
	public function getSchemaChangeBuilder( $platform ) {
		return new DoctrineSchemaChangeBuilder( $this->getPlatform( $platform ) );
	}

	private function getPlatform( string $platform ) {
		if ( $platform === 'mysql' ) {
			$platformObject = new MySqlPlatform();
		} elseif ( $platform === 'postgres' ) {
			$platformObject = new MWPostgreSqlPlatform();
		} elseif ( $platform === 'sqlite' ) {
			$platformObject = new SqlitePlatform();
		} else {
			throw new InvalidArgumentException( 'Unknown platform: ' . $platform );
		}

		if ( !Type::hasType( TimestampType::TIMESTAMP ) ) {
			Type::addType( TimestampType::TIMESTAMP, TimestampType::class );
		}
		$platformObject->registerDoctrineTypeMapping( 'Timestamp', TimestampType::TIMESTAMP );

		if ( !Type::hasType( TinyIntType::TINYINT ) ) {
			Type::addType( TinyIntType::TINYINT, TinyIntType::class );
		}
		$platformObject->registerDoctrineTypeMapping( 'Tinyint', TinyIntType::TINYINT );

		return $platformObject;
	}

}
