<?php
/**
 * CLI-based MediaWiki installation and configuration.
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
 * @ingroup Maintenance
 */

use MediaWiki\Installer\Installer;
use MediaWiki\Installer\InstallerOverrides;
use MediaWiki\Installer\InstallException;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Settings\SettingsBuilder;
use Wikimedia\AtEase\AtEase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';

define( 'MW_CONFIG_CALLBACK', [ Installer::class, 'overrideConfig' ] );
define( 'MEDIAWIKI_INSTALL', true );
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to install and configure MediaWiki
 *
 * Default values for the options are defined in MainConfigSchema.php
 * (see the mapping in includes/installer/CliInstaller.php)
 * Default for --dbpath (SQLite-specific) is defined in SqliteInstaller::getGlobalDefaults
 *
 * @ingroup Maintenance
 */
class CommandLineInstaller extends Maintenance {
	public function __construct() {
		parent::__construct();
		global $IP;

		$this->addDescription( "CLI-based MediaWiki installation and configuration.\n" .
			"Default options are indicated in parentheses.\n" .
			"If no options are provided, the script will run in interactive mode." );

		$this->addArg( 'name', 'The name of the wiki', false );
		$this->addArg( 'admin', 'The username of the wiki administrator.', false );

		$this->addOption( 'pass', 'The password for the wiki administrator.', false, true );
		$this->addOption(
			'passfile',
			'An alternative way to provide pass option, as the contents of this file',
			false,
			true
		);
		$this->addOption(
			'scriptpath',
			'The relative path of the wiki in the web server (/' . basename( dirname( __DIR__ ) ) . ')',
			false,
			true
		);
		$this->addOption(
			'server',
			'The base URL of the web server the wiki will be on (http://localhost)',
			false,
			true
		);

		$this->addOption( 'lang', 'The language to use (en)', false, true );

		$this->addOption( 'dbtype', 'The type of database (mysql)', false, true );
		$this->addOption( 'dbserver', 'The database host (localhost)', false, true );
		$this->addOption( 'dbssl', 'Connect to the database over SSL' );
		$this->addOption( 'dbport', 'The database port; only for PostgreSQL (5432)', false, true );
		$this->addOption( 'dbname', 'The database name (my_wiki)', false, true );
		$this->addOption( 'dbpath', 'The path for the SQLite DB ($IP/data)', false, true );
		$this->addOption( 'dbprefix', 'Optional database table name prefix', false, true );
		$this->addOption( 'installdbuser', 'The user to use for installing (root)', false, true );
		$this->addOption( 'installdbpass', 'The password for the DB user to install as.', false, true );
		$this->addOption( 'dbuser', 'The user to use for normal operations (wikiuser)', false, true );
		$this->addOption( 'dbpass', 'The password for the DB user for normal operations', false, true );
		$this->addOption(
			'dbpassfile',
			'An alternative way to provide dbpass option, as the contents of this file',
			false,
			true
		);
		$this->addOption( 'confpath', "Path to write LocalSettings.php to ($IP)", false, true );
		$this->addOption( 'dbschema', 'The schema for the MediaWiki DB in '
			. 'PostgreSQL (mediawiki)', false, true );

		$this->addOption( 'env-checks', "Run environment checks only, don't change anything" );

		$this->addOption( 'with-extensions', "Detect and include extensions" );
		$this->addOption( 'extensions', 'Comma-separated list of extensions to install',
			false, true, false, true );
		$this->addOption( 'skins', 'Comma-separated list of skins to install (default: all)',
			false, true, false, true );
		$this->addOption( 'with-developmentsettings', 'Load DevelopmentSettings.php in LocalSettings.php' );
	}

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		parent::finalSetup( $settingsBuilder );
		Installer::overrideConfig( $settingsBuilder );
	}

	/** @inheritDoc */
	public function getDbType() {
		if ( $this->hasOption( 'env-checks' ) ) {
			return Maintenance::DB_NONE;
		}
		return parent::getDbType();
	}

	/** @inheritDoc */
	public function execute() {
		global $IP;

		if ( $this->hasOption( 'help' ) ) {
			$this->maybeHelp();
			return;
		}

		// Manually check for required arguments, as 0 arguments allows interactive mode to be used
		if ( $this->getArg( 0 ) && !$this->getArg( 1 ) ) {
			$this->fatalError( 'Argument <' . $this->getArgName( 1 ) . '> is required!' );
		}

		// No arguments, means interactive mode
		if ( !$this->getArg( 0 ) || !$this->getArg( 1 ) ) {
			$this->output( "Hello, I'm the MediaWiki installer!\n\n" );
			$this->output( "This script will guide you through the process of installing MediaWiki.\n" );
			$this->output( "If you actually want to see the help for this script, use --help.\n\n" );

			$siteName = $this->prompt( 'Enter the name of the wiki:', "Wiki" );
			$language = $this->prompt( 'Enter the language code of the wiki:', 'en' );
			$server = $this->prompt(
				'Enter the base URL of the web server the wiki will be on (without a path):',
				'http://localhost'
			);
			$adminName = $this->prompt( 'Enter the username of the MediaWiki account that will be created ' .
				'at the end of the installation and given administrator rights:', "Admin" );
			$adminPass = $this->prompt(
				'Enter the password for the wiki administrator:',
				$this->generateStrongPassword()
			);
			$dbType = $this->prompt( 'Enter the type of database (for example mysql or sqlite):', 'mysql' );
			// Assume that sqlite is the only db type that needs a path
			$dbPath = $dbType == 'sqlite' ?
				$this->prompt(
					'Enter the path for the SQLite DB (advised to be outside the web root):',
					"$IP/data"
				) :
				null;
			$dbName = $this->prompt( 'Enter the name of the database:', 'my_wiki' );
			// Assume that everything other than sqlite needs a server and credentials
			$dbUser = $dbType == 'sqlite' ? null : $this->prompt( 'Enter the database user:', 'wikiuser' );
			$dbPass = $dbType == 'sqlite' ? null : $this->prompt( 'Enter the database password:', '' );
			$dbServer = $dbType == 'sqlite' ? null : $this->prompt( 'Enter the database server:', 'localhost' );

			$this->output( "\n" );
			$this->setArg( 0, $siteName );
			$this->setArg( 1, $adminName );
			$this->setOption( 'lang', $language );
			$this->setOption( 'server', $server );
			$this->setOption( 'pass', $adminPass );
			$this->setOption( 'dbtype', $dbType );
			$this->setOption( 'dbpath', $dbPath );
			$this->setOption( 'dbname', $dbName );
			$this->setOption( 'dbuser', $dbUser );
			$this->setOption( 'dbpass', $dbPass );
			$this->setOption( 'dbserver', $dbServer );
			if ( !$this->promptYesNo( 'Do you want to continue with the installation?', true ) ) {
				$this->output( "Installation aborted.\n" );
				return false;
			}
		}

		$siteName = $this->getArg( 0, 'MediaWiki' ); // Will not be set if used with --env-checks
		$adminName = $this->getArg( 1 );
		$envChecksOnly = $this->hasOption( 'env-checks' );

		$scriptpath = $this->getOption( 'scriptpath', false );
		if ( $scriptpath === false ) {
			$this->setOption( 'scriptpath', '/' . basename( dirname( __DIR__ ) ) );
		}

		$this->setDbPassOption();
		if ( !$envChecksOnly ) {
			$this->setPassOption();
		}

		try {
			$installer = InstallerOverrides::getCliInstaller( $siteName, $adminName, $this->parameters->getOptions() );
		} catch ( InstallException $e ) {
			$this->error( $e->getStatus() );
			return false;
		}

		$status = $installer->doEnvironmentChecks();
		if ( $status->isGood() ) {
			$installer->showMessage( 'config-env-good' );
		} else {
			return false;
		}
		if ( !$envChecksOnly ) {
			$status = $installer->execute();
			if ( !$status->isGood() ) {
				return false;
			}
			$installer->writeConfigurationFile( $this->getOption( 'confpath', $IP ) );
			$installer->showMessage(
				'config-install-success',
				$installer->getVar( 'wgServer' ),
				$installer->getVar( 'wgScriptPath' )
			);
		}
		return true;
	}

	private function generateStrongPassword(): string {
		$strongPassword = '';
		$strongPasswordLength = 20;
		$strongPasswordChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}|;:,.<>?';
		$strongPasswordCharsLength = strlen( $strongPasswordChars );
		for ( $i = 0; $i < $strongPasswordLength; $i++ ) {
			$strongPassword .= $strongPasswordChars[ rand( 0, $strongPasswordCharsLength - 1 ) ];
		}
		return $strongPassword;
	}

	private function setDbPassOption() {
		$dbpassfile = $this->getOption( 'dbpassfile' );
		if ( $dbpassfile !== null ) {
			if ( $this->getOption( 'dbpass' ) !== null ) {
				$this->error( 'WARNING: You have provided the options "dbpass" and "dbpassfile". '
					. 'The content of "dbpassfile" overrides "dbpass".' );
			}
			AtEase::suppressWarnings();
			$dbpass = file_get_contents( $dbpassfile ); // returns false on failure
			AtEase::restoreWarnings();
			if ( $dbpass === false ) {
				$this->fatalError( "Couldn't open $dbpassfile" );
			}
			$this->setOption( 'dbpass', trim( $dbpass, "\r\n" ) );
		}
	}

	private function setPassOption() {
		$passfile = $this->getOption( 'passfile' );
		if ( $passfile !== null ) {
			if ( $this->getOption( 'pass' ) !== null ) {
				$this->error( 'WARNING: You have provided the option --pass or --passfile. '
					. 'The content of "passfile" overrides "pass".' );
			}
			AtEase::suppressWarnings();
			$pass = file_get_contents( $passfile ); // returns false on failure
			AtEase::restoreWarnings();
			if ( $pass === false ) {
				$this->fatalError( "Couldn't open $passfile" );
			}
			$this->setOption( 'pass', trim( $pass, "\r\n" ) );
		} elseif ( $this->getOption( 'pass' ) === null ) {
			$this->fatalError( 'You need to provide the option "pass" or "passfile"' );
		}
	}

	public function validateParamsAndArgs() {
		if ( !$this->hasOption( 'env-checks' ) ) {
			$this->parameters->validate();
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CommandLineInstaller::class;

require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
