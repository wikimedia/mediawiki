<?php

namespace Wikimedia\Rdbms;

// T270740 - HACK
if ( class_exists( \Doctrine\DBAL\Platforms\PostgreSQL94Platform::class ) ) {
	/**
	 * @suppress PhanRedefineClass
	 */
	class MWPostgreSqlPlatformCompat extends \Doctrine\DBAL\Platforms\PostgreSQL94Platform {
	}
} else {
	/**
	 * @suppress PhanRedefineClass
	 */
	class MWPostgreSqlPlatformCompat extends \Doctrine\DBAL\Platforms\PostgreSqlPlatform {
	}
}
