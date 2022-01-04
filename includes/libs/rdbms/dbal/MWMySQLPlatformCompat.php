<?php
// phpcs:ignoreFile -- This is a very temporary file
namespace Wikimedia\Rdbms;

// T270740 - HACK: In doctrine/dbal 3.0.0, they renamed MySql -> MySQL
// https://github.com/doctrine/dbal/commit/885bf615a5820c56ddee60a8fbd3d7ce973587ed
// So this looks for the old case (in dbal < 3.0.0) and uses that, else uses
// the new case... When we drop support for dbal < 3.0.0 (which can be done
// after we drop PHP 7.2 support in master - T261872; would be fine in MW 1.35),
// this can be cleaned up to just use MySQLPlatform and be imported at the top.
if ( class_exists( \Doctrine\DBAL\Platforms\MySqlPlatform::class ) ) {
	/**
	 * @suppress PhanRedefineClass
	 */
	class MWMySQLPlatformCompat extends \Doctrine\DBAL\Platforms\MySqlPlatform {
	}
} else {
	/**
	 * @suppress PhanRedefineClass
	 */
	class MWMySQLPlatformCompat extends \Doctrine\DBAL\Platforms\MySQLPlatform {
	}
}
