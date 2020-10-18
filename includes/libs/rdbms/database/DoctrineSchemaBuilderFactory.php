<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
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
	public function getSchemaBuilder( $platform ) {
		if ( $platform === 'mysql' ) {
			$platformObject = new MySqlPlatform();
		} elseif ( $platform === 'postgres' ) {
			$platformObject = new MWPostgreSqlPlatform();
		} elseif ( $platform === 'sqlite' ) {
			$platformObject = new SqlitePlatform();
		} else {
			throw new InvalidArgumentException( 'Unknown platform: ' . $platform );
		}

		return new DoctrineSchemaBuilder( $platformObject );
	}
}
