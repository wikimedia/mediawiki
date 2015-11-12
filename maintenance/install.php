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

// Checking for old versions of PHP is done in Maintenance.php
// We need to use dirname( __FILE__ ) here cause __DIR__ is PHP5.3+
// @codingStandardsIgnoreStart MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/Maintenance.php';
// @codingStandardsIgnoreEnd

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );
define( 'MEDIAWIKI_INSTALL', true );

/**
 * Maintenance script to install and configure MediaWiki
 *
 * Default values for the options are defined in DefaultSettings.php
 * (see the mapping in CliInstaller.php)
 * Default for --dbpath (SQLite-specific) is defined in SqliteInstaller::getGlobalDefaults
 *
 * @ingroup Maintenance
 */
class CommandLineInstaller extends Maintenance {
	function __construct() {
		parent::__construct();
		global $IP;

		$this->addDescription( "CLI-based MediaWiki installation and configuration.\n" .
			"Default options are indicated in parentheses." );

		$this->addArg( 'name', 'The name of the wiki (MediaWiki)', false );

		$this->addArg( 'admin', 'The username of the wiki administrator.' );
		$this->addOption( 'pass', 'The password for the wiki administrator.', false, true );
		$this->addOption(
			'passfile',
			'An alternative way to provide pass option, as the contents of this file',
			false,
			true
		);
		/* $this->addOption( 'email', 'The email for the wiki administrator', false, true ); */
		$this->addOption(
			'scriptpath',
			'The relative path of the wiki in the web server (/wiki)',
			false,
			true
		);

		$this->addOption( 'lang', 'The language to use (en)', false, true );
		/* $this->addOption( 'cont-lang', 'The content language (en)', false, true ); */

		$this->addOption( 'dbtype', 'The type of database (mysql)', false, true );
		$this->addOption( 'dbserver', 'The database host (localhost)', false, true );
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
			. 'PostgreSQL/Microsoft SQL Server (mediawiki)', false, true );
		/*
		$this->addOption( 'namespace', 'The project namespace (same as the "name" argument)',
			false, true );
		*/
		$this->addOption( 'env-checks', "Run environment checks only, don't change anything" );
	}

	function execute() {
		global $IP;

		$siteName = $this->getArg( 0, 'MediaWiki' ); // Will not be set if used with --env-checks
		$adminName = $this->getArg( 1 );

		$dbpassfile = $this->getOption( 'dbpassfile' );
		if ( $dbpassfile !== null ) {
			if ( $this->getOption( 'dbpass' ) !== null ) {
				$this->error( 'WARNING: You have provided the options "dbpass" and "dbpassfile". '
					. 'The content of "dbpassfile" overrides "dbpass".' );
			}
			MediaWiki\suppressWarnings();
			$dbpass = file_get_contents( $dbpassfile ); // returns false on failure
			MediaWiki\restoreWarnings();
			if ( $dbpass === false ) {
				$this->error( "Couldn't open $dbpassfile", true );
			}
			$this->mOptions['dbpass'] = trim( $dbpass, "\r\n" );
		}

		$passfile = $this->getOption( 'passfile' );
		if ( $passfile !== null ) {
			if ( $this->getOption( 'pass' ) !== null ) {
				$this->error( 'WARNING: You have provided the options "pass" and "passfile". '
					. 'The content of "passfile" overrides "pass".' );
			}
			MediaWiki\suppressWarnings();
			$pass = file_get_contents( $passfile ); // returns false on failure
			MediaWiki\restoreWarnings();
			if ( $pass === false ) {
				$this->error( "Couldn't open $passfile", true );
			}
			$this->mOptions['pass'] = trim( $pass, "\r\n" );
		} elseif ( $this->getOption( 'pass' ) === null ) {
			$this->error( 'You need to provide the option "pass" or "passfile"', true );
		}

		$installer = InstallerOverrides::getCliInstaller( $siteName, $adminName, $this->mOptions );

		$status = $installer->doEnvironmentChecks();
		if ( $status->isGood() ) {
			$installer->showMessage( 'config-env-good' );
		} else {
			$installer->showStatusMessage( $status );

			return;
		}
		if ( !$this->hasOption( 'env-checks' ) ) {
			$installer->execute();
			$installer->writeConfigurationFile( $this->getOption( 'confpath', $IP ) );
		}
	}

	function validateParamsAndArgs() {
		if ( !$this->hasOption( 'env-checks' ) ) {
			parent::validateParamsAndArgs();
		}
	}
}

$maintClass = 'CommandLineInstaller';

require_once RUN_MAINTENANCE_IF_MAIN;
