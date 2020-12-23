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
		if ( $platform === 'mysql' ) {
			// T270740 - HACK: In doctrine/dbal 3.0.0, they renamed MySql -> MySQL
			// https://github.com/doctrine/dbal/commit/885bf615a5820c56ddee60a8fbd3d7ce973587ed
			// So this looks for the old case (in dbal < 3.0.0) and uses that, else uses
			// the new case... When we drop support for dbal < 3.0.0 (which can be done
			// after we drop PHP 7.2 support in master - T261872; would be fine in MW 1.35),
			// this can be cleaned up to just use MySQLPlatform and be imported at the top.
			if ( class_exists( \Doctrine\DBAL\Platforms\MySqlPlatform::class ) ) {
				$platformObject = new \Doctrine\DBAL\Platforms\MySqlPlatform();
			} else {
				$platformObject = new \Doctrine\DBAL\Platforms\MySQLPlatform();
			}
		} elseif ( $platform === 'postgres' ) {
			$platformObject = new MWPostgreSqlPlatform();
		} elseif ( $platform === 'sqlite' ) {
			$platformObject = new SqlitePlatform();
		} else {
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
