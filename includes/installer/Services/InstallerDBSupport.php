<?php

/**
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
 *
 * @author Art Baltai
 */

declare( strict_types = 1 );
namespace MediaWiki\Installer\Services;

use DatabaseInstaller;
use DatabaseUpdater;
use InvalidArgumentException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MysqlInstaller;
use MysqlUpdater;
use PostgresInstaller;
use PostgresUpdater;
use Psr\Log\LoggerInterface;
use SqliteInstaller;
use SqliteUpdater;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseMysqli;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * @since 1.35
 */
class InstallerDBSupport {
	public const EXTENSION_TYPE_DATABASE = 'database';

	/**
	 * @var InstallerDBSupport $instance
	 */
	private static $instance;

	/**
	 * Known database types. These correspond to the class names <type>Installer,
	 * and are also MediaWiki database types valid for $wgDBtype.
	 *
	 * To add a new type, create a <type>Installer class and a Database<type>
	 * class, and add a config-type-<type> message to MessagesEn.php.
	 *
	 * @var array<string, array>
	 */
	private $databaseInfo = [
		'mysql' => [
			'installer' => MysqlInstaller::class,
			'updater' => MysqlUpdater::class,
			'driver' => DatabaseMysqli::class,
			'extension' => null
		],
		'postgres' => [
			'installer' => PostgresInstaller::class,
			'updater' => PostgresUpdater::class,
			'driver' => DatabasePostgres::class,
			'extension' => null
		],
		'sqlite' => [
			'installer' => SqliteInstaller::class,
			'updater' => SqliteUpdater::class,
			'driver' => DatabaseSqlite::class,
			'extension' => null
		]
	];

	/**
	 * @var InstallerExtensionRegistration
	 */
	private $extensionRegistration;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public static function getInstance(): self {
		global $IP, $wgExtensionDirectory;
		if ( !isset( self::$instance ) ) {
			$extensionDir = $wgExtensionDirectory ?: "$IP/extensions";
			$installerRegistration = new InstallerExtensionRegistration(
				$extensionDir,
				MediaWikiServices::getInstance()->getLocalisationCache()
			);
			self::$instance = new InstallerDBSupport(
				$installerRegistration,
				LoggerFactory::getInstance( 'Installer' )
			);
			self::$instance->registerDbExtensions(
				( new InstallerExtensionSelector( $extensionDir ) )
					->getExtOptionsByType( self::EXTENSION_TYPE_DATABASE )
			);
		}

		return self::$instance;
	}

	private function __construct(
		InstallerExtensionRegistration $extensionRegistration,
		LoggerInterface $logger
	) {
		$this->extensionRegistration = $extensionRegistration;
		$this->logger = $logger;
	}

	private function registerDbExtensions( iterable $extDbOptionsByType ): bool {
		$registered = false;
		foreach ( $extDbOptionsByType as $extensionName => $extDbOptions ) {
			$result = $this->registerDbExtension(
				$extensionName,
				$extDbOptions
			);
			$registered = $result || $registered;
		}

		return $registered;
	}

	private function registerDbExtension(
		string $extensionName,
		array $extJsonOptions
	): bool {
		if ( !isset( $extJsonOptions['Providers']['Databases'] ) ) {
			return false;
		}
		$newDatabases = $extJsonOptions['Providers']['Databases'];
		$isRegistred = false;
		foreach ( $newDatabases as $database => $options ) {
			if ( is_numeric( $database ) ) {
				continue;
			}
			if ( $isRegistred === false ) {
				$this->extensionRegistration->register(
					$extensionName,
					$extJsonOptions
				);
				$isRegistred = true;
			}

			$this->registerDatabase(
				$database,
				$options['Installer'] ?? $this->getInstallerClassAuto( $database ),
				$options['Updater'] ?? $this->getUpdaterClassAuto( $database ),
				$options['Driver'] ?? $this->getDriverClassAuto( $database ),
				$extensionName
			);
		}

		return $isRegistred;
	}

	private function isDatabaseInfoValid(
		string $dbInstaller,
		string $dbUpdater,
		string $dbDriver
	): bool {
		$isValid = true;
		if ( !is_subclass_of( $dbInstaller, DatabaseInstaller::class ) ) {
			$this->logger->warning( 'Database `Installer` should be a subclass of '
				. DatabaseInstaller::class );
			$isValid = false;
		}

		if ( !is_subclass_of( $dbUpdater, DatabaseUpdater::class ) ) {
			$this->logger->warning( 'Database `Updater` should be a subclass of '
				. DatabaseUpdater::class );
			$isValid = false;
		}

		if ( !is_subclass_of( $dbDriver, Database::class ) ) {
			$this->logger->warning( 'Database `Driver` should be a subclass of ' . Database::class );
			$isValid = false;
		}
		return $isValid;
	}

	/**
	 * @param string $database
	 * @param string $dbInstaller
	 * @param string $dbUpdater
	 * @param string $dbDriver
	 * @param string $extensionName
	 */
	private function registerDatabase(
		string $database,
		string $dbInstaller,
		string $dbUpdater,
		string $dbDriver,
		string $extensionName
	): void {
		if ( !$this->isDatabaseInfoValid( $dbInstaller, $dbUpdater, $dbDriver ) ) {
			return;
		}

		$this->databaseInfo[ strtolower( $database ) ] = [
			'installer' => $dbInstaller,
			'updater' => $dbUpdater,
			'driver' => $dbDriver,
			'extension' => $extensionName,
		];
	}

	/**
	 * Get a list of known DB types.
	 *
	 * @return string[]
	 */
	public function getDatabases(): array {
		return array_keys( $this->databaseInfo );
	}

	/**
	 * Checks wheather given database type is registered
	 * @param string $database
	 *
	 * @return bool
	 */
	public function hasDatabase( string $database ): bool {
		return array_key_exists( $database, $this->databaseInfo );
	}

	/**
	 * Get the database installer class for given database type, throws an
	 * InvalidArgumentException if no given database registerred
	 *
	 * @param string $database
	 * @return string Class name
	 * @throws InvalidArgumentException
	 */
	public function getDBInstallerClass( string $database ): string {
		if ( !isset( $this->databaseInfo[strtolower( $database )] ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no registered database found for type '$database'" );
		}
		return $this->databaseInfo[strtolower( $database )]['installer'];
	}

	/**
	 * Get the database updater class for given database type, throws an
	 * InvalidArgumentException if no given database registerred
	 *
	 * @param string $database
	 * @return string class name
	 * @throws InvalidArgumentException
	 */
	public function getDBUpdaterClass( string $database ): string {
		if ( !isset( $this->databaseInfo[strtolower( $database )] ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no registered database found for type '$database'" );
		}
		return $this->databaseInfo[strtolower( $database )]['updater'];
	}

	/**
	 * Get the database driver class for given database type, throws an
	 * InvalidArgumentException if no given database registerred
	 *
	 * @param string $database
	 * @return string class name
	 * @throws InvalidArgumentException
	 */
	public function getDBDriverClass( string $database ): string {
		if ( !isset( $this->databaseInfo[strtolower( $database )] ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no registered database found for type '$database'" );
		}
		return $this->databaseInfo[strtolower( $database )]['driver'];
	}

	/**
	 * Gets extension name that implements classes for given database type, throws an
	 * InvalidArgumentException if no given database registerred
	 *
	 * @param string $database
	 * @return string|null
	 * @throws InvalidArgumentException
	 */
	public function getExtensionNameForDatabase(
		string $database
	): ?string {
		if ( !isset( $this->databaseInfo[strtolower( $database )] ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				" no registered database found for type '$database'" );
		}
		return $this->databaseInfo[strtolower( $database )]['extension'];
	}

	/**
	 * Generate default name for database Installer
	 *
	 * @param string $type database type ($wgDBtype)
	 * @return string Class name
	 */
	private function getInstallerClassAuto( string $type ): string {
		return ucfirst( $type ) . 'Installer';
	}

	/**
	 * Generate default name for database Updater
	 *
	 * @param string $type database type ($wgDBtype)
	 * @return string Class name
	 */
	private function getUpdaterClassAuto( string $type ): string {
		return ucfirst( $type ) . 'Updater';
	}

	/**
	 * Generate default name for database Driver
	 *
	 * @param string $type database type ($wgDBtype)
	 * @return string Class name
	 */
	private function getDriverClassAuto( string $type ): string {
		return 'Database' . ucfirst( $type );
	}
}
