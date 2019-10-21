#!/usr/bin/env php
<?php
/**
 * Run all updaters.
 *
 * This is used when the database schema is modified and we need to apply patches.
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
 * @todo document
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Maintenance script to run database schema updates.
 *
 * @ingroup Maintenance
 */
class UpdateMediaWiki extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addDescription( 'MediaWiki database updater' );
		$this->addOption( 'skip-compat-checks', 'Skips compatibility checks, mostly for developers' );
		$this->addOption( 'quick', 'Skip 5 second countdown before starting' );
		$this->addOption( 'doshared', 'Also update shared tables' );
		$this->addOption( 'nopurge', 'Do not purge the objectcache table after updates' );
		$this->addOption( 'noschema', 'Only do the updates that are not done during schema updates' );
		$this->addOption(
			'schema',
			'Output SQL to do the schema updates instead of doing them. Works '
				. 'even when $wgAllowSchemaUpdates is false',
			false,
			true
		);
		$this->addOption( 'force', 'Override when $wgAllowSchemaUpdates disables this script' );
		$this->addOption(
			'skip-external-dependencies',
			'Skips checking whether external dependencies are up to date, mostly for developers'
		);
	}

	function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	function compatChecks() {
		$minimumPcreVersion = Installer::MINIMUM_PCRE_VERSION;

		$pcreVersion = explode( ' ', PCRE_VERSION, 2 )[0];
		if ( version_compare( $pcreVersion, $minimumPcreVersion, '<' ) ) {
			$this->fatalError(
				"PCRE $minimumPcreVersion or later is required.\n" .
				"Your PHP binary is linked with PCRE $pcreVersion.\n\n" .
				"More information:\n" .
				"https://www.mediawiki.org/wiki/Manual:Errors_and_symptoms/PCRE\n\n" .
				"ABORTING.\n" );
		}

		$test = new PhpXmlBugTester();
		if ( !$test->ok ) {
			$this->fatalError(
				"Your system has a combination of PHP and libxml2 versions that is buggy\n" .
				"and can cause hidden data corruption in MediaWiki and other web apps.\n" .
				"Upgrade to libxml2 2.7.3 or later.\n" .
				"ABORTING (see https://bugs.php.net/bug.php?id=45996).\n" );
		}
	}

	function execute() {
		global $wgVersion, $wgLang, $wgAllowSchemaUpdates, $wgMessagesDirs;

		if ( !$wgAllowSchemaUpdates
			&& !( $this->hasOption( 'force' )
				|| $this->hasOption( 'schema' )
				|| $this->hasOption( 'noschema' ) )
		) {
			$this->fatalError( "Do not run update.php on this wiki. If you're seeing this you should\n"
				. "probably ask for some help in performing your schema updates or use\n"
				. "the --noschema and --schema options to get an SQL file for someone\n"
				. "else to inspect and run.\n\n"
				. "If you know what you are doing, you can continue with --force\n" );
		}

		$this->fileHandle = null;
		if ( substr( $this->getOption( 'schema' ), 0, 2 ) === "--" ) {
			$this->fatalError( "The --schema option requires a file as an argument.\n" );
		} elseif ( $this->hasOption( 'schema' ) ) {
			$file = $this->getOption( 'schema' );
			$this->fileHandle = fopen( $file, "w" );
			if ( $this->fileHandle === false ) {
				$err = error_get_last();
				$this->fatalError( "Problem opening the schema file for writing: $file\n\t{$err['message']}" );
			}
		}

		// T206765: We need to load the installer i18n files as some of errors come installer/updater code
		$wgMessagesDirs['MediawikiInstaller'] = dirname( __DIR__ ) . '/includes/installer/i18n';

		$lang = Language::factory( 'en' );
		// Set global language to ensure localised errors are in English (T22633)
		RequestContext::getMain()->setLanguage( $lang );
		$wgLang = $lang; // BackCompat

		define( 'MW_UPDATER', true );

		$this->output( "MediaWiki {$wgVersion} Updater\n\n" );

		wfWaitForSlaves();

		if ( !$this->hasOption( 'skip-compat-checks' ) ) {
			$this->compatChecks();
		} else {
			$this->output( "Skipping compatibility checks, proceed at your own risk (Ctrl+C to abort)\n" );
			$this->countDown( 5 );
		}

		// Check external dependencies are up to date
		if ( !$this->hasOption( 'skip-external-dependencies' ) ) {
			$composerLockUpToDate = $this->runChild( CheckComposerLockUpToDate::class );
			$composerLockUpToDate->execute();
		} else {
			$this->output(
				"Skipping checking whether external dependencies are up to date, proceed at your own risk\n"
			);
		}

		# Attempt to connect to the database as a privileged user
		# This will vomit up an error if there are permissions problems
		$db = $this->getDB( DB_MASTER );

		# Check to see whether the database server meets the minimum requirements
		/** @var DatabaseInstaller $dbInstallerClass */
		$dbInstallerClass = Installer::getDBInstallerClass( $db->getType() );
		$status = $dbInstallerClass::meetsMinimumRequirement( $db->getServerVersion() );
		if ( !$status->isOK() ) {
			// This might output some wikitext like <strong> but it should be comprehensible
			$text = $status->getWikiText();
			$this->fatalError( $text );
		}

		$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		$this->output( "Going to run database updates for $dbDomain\n" );
		if ( $db->getType() === 'sqlite' ) {
			/** @var DatabaseSqlite $db */
			'@phan-var DatabaseSqlite $db';
			$this->output( "Using SQLite file: '{$db->getDbFilePath()}'\n" );
		}
		$this->output( "Depending on the size of your database this may take a while!\n" );

		if ( !$this->hasOption( 'quick' ) ) {
			$this->output( "Abort with control-c in the next five seconds "
				. "(skip this countdown with --quick) ... " );
			$this->countDown( 5 );
		}

		$time1 = microtime( true );

		$badPhpUnit = dirname( __DIR__ ) . '/vendor/phpunit/phpunit/src/Util/PHP/eval-stdin.php';
		if ( file_exists( $badPhpUnit ) ) {
			// Bad versions of the file are:
			// https://raw.githubusercontent.com/sebastianbergmann/phpunit/c820f915bfae34e5a836f94967a2a5ea5ef34f21/src/Util/PHP/eval-stdin.php
			// https://raw.githubusercontent.com/sebastianbergmann/phpunit/3aaddb1c5bd9b9b8d070b4cf120e71c36fd08412/src/Util/PHP/eval-stdin.php
			$md5 = md5_file( $badPhpUnit );
			if ( $md5 === '120ac49800671dc383b6f3709c25c099'
				|| $md5 === '28af792cb38fc9a1b236b91c1aad2876'
			) {
				$success = unlink( $badPhpUnit );
				if ( $success ) {
					$this->output( "Removed PHPUnit eval-stdin.php to protect against CVE-2017-9841\n" );
				} else {
					$this->error( "Unable to remove $badPhpUnit, you should manually. See CVE-2017-9841" );
				}
			}
		}

		$shared = $this->hasOption( 'doshared' );

		$updates = [ 'core', 'extensions' ];
		if ( !$this->hasOption( 'schema' ) ) {
			if ( $this->hasOption( 'noschema' ) ) {
				$updates[] = 'noschema';
			}
			$updates[] = 'stats';
		}

		$updater = DatabaseUpdater::newForDB( $db, $shared, $this );
		$updater->doUpdates( $updates );

		foreach ( $updater->getPostDatabaseUpdateMaintenance() as $maint ) {
			$child = $this->runChild( $maint );

			// LoggedUpdateMaintenance is checking the updatelog itself
			$isLoggedUpdate = $child instanceof LoggedUpdateMaintenance;

			if ( !$isLoggedUpdate && $updater->updateRowExists( $maint ) ) {
				continue;
			}

			$child->execute();
			if ( !$isLoggedUpdate ) {
				$updater->insertUpdateRow( $maint );
			}
		}

		$updater->setFileAccess();
		if ( !$this->hasOption( 'nopurge' ) ) {
			$updater->purgeCache();
		}

		$time2 = microtime( true );

		$timeDiff = $lang->formatTimePeriod( $time2 - $time1 );
		$this->output( "\nDone in $timeDiff.\n" );
	}

	function afterFinalSetup() {
		global $wgLocalisationCacheConf;

		# Don't try to access the database
		# This needs to be disabled early since extensions will try to use the l10n
		# cache from $wgExtensionFunctions (T22471)
		$wgLocalisationCacheConf = [
			'class' => LocalisationCache::class,
			'storeClass' => LCStoreNull::class,
			'storeDirectory' => false,
			'manualRecache' => false,
		];
	}

	/**
	 * @throws FatalError
	 * @throws MWException
	 * @suppress PhanPluginDuplicateConditionalNullCoalescing
	 */
	public function validateParamsAndArgs() {
		// Allow extensions to add additional params.
		$params = [];
		Hooks::run( 'MaintenanceUpdateAddParams', [ &$params ] );

		// This executes before the PHP version check, so don't use null coalesce (??).
		// Keeping this compatible with older PHP versions lets us reach the code that
		// displays a more helpful error.
		foreach ( $params as $name => $param ) {
			$this->addOption(
				$name,
				$param['desc'],
				isset( $param['require'] ) ? $param['require'] : false,
				isset( $param['withArg'] ) ? $param['withArg'] : false,
				isset( $param['shortName'] ) ? $param['shortName'] : false,
				isset( $param['multiOccurrence'] ) ? $param['multiOccurrence'] : false
			);
		}

		parent::validateParamsAndArgs();
	}
}

$maintClass = UpdateMediaWiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
