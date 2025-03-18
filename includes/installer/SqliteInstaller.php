<?php

/**
 * Sqlite-specific installer.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * Class for setting up the MediaWiki database using SQLLite.
 *
 * @ingroup Installer
 * @since 1.17
 */
class SqliteInstaller extends DatabaseInstaller {

	/** @inheritDoc */
	public static $minimumVersion = '3.31.0';
	/** @inheritDoc */
	protected static $notMinimumVersionMessage = 'config-outdated-sqlite';

	/**
	 * @var DatabaseSqlite
	 */
	public $db;

	/** @inheritDoc */
	protected $globalNames = [
		'wgDBname',
		'wgSQLiteDataDir',
	];

	public function getName() {
		return 'sqlite';
	}

	public function isCompiled() {
		return self::checkExtension( 'pdo_sqlite' );
	}

	public function getConnectForm( WebInstaller $webInstaller ): DatabaseConnectForm {
		return new SqliteConnectForm( $webInstaller, $this );
	}

	public function getSettingsForm( WebInstaller $webInstaller ): DatabaseSettingsForm {
		return new DatabaseSettingsForm( $webInstaller, $this );
	}

	/**
	 * @return Status
	 */
	public function checkPrerequisites() {
		// Bail out if SQLite is too old
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$result = static::meetsMinimumRequirement( $db );
		// Check for FTS3 full-text search module
		if ( DatabaseSqlite::getFulltextSearchModule() != 'FTS3' ) {
			$result->warning( 'config-no-fts3' );
		}

		return $result;
	}

	public function getGlobalDefaults() {
		global $IP;
		$defaults = parent::getGlobalDefaults();
		if ( !empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
			$path = dirname( $_SERVER['DOCUMENT_ROOT'] );
		} else {
			// We use $IP when unable to get $_SERVER['DOCUMENT_ROOT']
			$path = $IP;
		}
		$defaults['wgSQLiteDataDir'] = str_replace(
			[ '/', '\\' ],
			DIRECTORY_SEPARATOR,
			$path . '/data'
		);
		return $defaults;
	}

	/**
	 * @param string $type
	 * @return ConnectionStatus
	 */
	public function openConnection( string $type ) {
		$status = new ConnectionStatus;
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );

		// Don't implicitly create the file
		$file = DatabaseSqlite::generateFileName( $dir, $dbName );
		if ( !file_exists( $file ) ) {
			$status->fatal( 'config-sqlite-connection-error',
				'file does not exist' );
			return $status;
		}

		try {
			$db = MediaWikiServices::getInstance()->getDatabaseFactory()->create(
				'sqlite', [ 'dbname' => $dbName, 'dbDirectory' => $dir ]
			);
			$status->setDB( $db );
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * @return string
	 */
	public function getLocalSettings() {
		$dir = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgSQLiteDataDir' ) );
		// These tables have frequent writes and are thus split off from the main one.
		// Since the code using these tables only uses transactions for writes, then set
		// them to using BEGIN IMMEDIATE. This avoids frequent lock errors on the first write action.
		return "# SQLite-specific settings
\$wgSQLiteDataDir = \"{$dir}\";
\$wgObjectCaches[CACHE_DB] = [
	'class' => SqlBagOStuff::class,
	'loggroup' => 'SQLBagOStuff',
	'server' => [
		'type' => 'sqlite',
		'dbname' => 'wikicache',
		'tablePrefix' => '',
		'variables' => [ 'synchronous' => 'NORMAL' ],
		'dbDirectory' => \$wgSQLiteDataDir,
		'trxMode' => 'IMMEDIATE',
		'flags' => 0
	]
];
\$wgLocalisationCacheConf['storeServer'] = [
	'type' => 'sqlite',
	'dbname' => \"{\$wgDBname}_l10n_cache\",
	'tablePrefix' => '',
	'variables' => [ 'synchronous' => 'NORMAL' ],
	'dbDirectory' => \$wgSQLiteDataDir,
	'trxMode' => 'IMMEDIATE',
	'flags' => 0
];
\$wgJobTypeConf['default'] = [
	'class' => 'JobQueueDB',
	'claimTTL' => 3600,
	'server' => [
		'type' => 'sqlite',
		'dbname' => \"{\$wgDBname}_jobqueue\",
		'tablePrefix' => '',
		'variables' => [ 'synchronous' => 'NORMAL' ],
		'dbDirectory' => \$wgSQLiteDataDir,
		'trxMode' => 'IMMEDIATE',
		'flags' => 0
	]
];
";
	}
}
