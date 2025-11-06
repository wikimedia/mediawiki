<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
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

	/**
	 * @param string $platform
	 * @return AbstractPlatform
	 */
	private function getPlatform( string $platform ) {
		switch ( $platform ) {
			case 'mysql':
				$platformObject = new MWMySQLPlatform;
				break;
			case 'postgres':
				$platformObject = new MWPostgreSqlPlatform;
				break;
			case 'sqlite':
				$platformObject = new SqlitePlatform;
				break;
			default:
				throw new InvalidArgumentException( 'Unknown platform: ' . $platform );
		}

		$customTypes = [
			'Enum' => [ EnumType::class, EnumType::ENUM ],
			'Tinyint' => [ TinyIntType::class, TinyIntType::TINYINT ],
			'Timestamp' => [ TimestampType::class, TimestampType::TIMESTAMP ],
		];

		foreach ( $customTypes as $type => [ $class, $name ] ) {
			if ( !Type::hasType( $name ) ) {
				Type::addType( $name, $class );
			}
			$platformObject->registerDoctrineTypeMapping( $type, $name );
		}

		return $platformObject;
	}
}
