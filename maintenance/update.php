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

// NO_AUTOLOAD -- due to hashbang above

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Maintenance script to run database schema updates.
 *
 * @ingroup Maintenance
 */
class UpdateMediaWiki extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'MediaWiki database updater' );
		$this->addOption( 'skip-compat-checks', 'Skips compatibility checks, mostly for developers' );
		$this->addOption( 'quick', 'Skip 5 second countdown before starting' );
		$this->addOption( 'doshared', 'Also update shared tables' );
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
		$this->addOption(
			'skip-config-validation',
			'Skips checking whether the existing configuration is valid'
		);
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	private function compatChecks() {
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
	}

	public function setup() {
		global $wgMessagesDirs;

		parent::setup();

		// T206765: We need to load the installer i18n files as some of errors come installer/updater code
		// T310378: We have to ensure we do this before execute()
		$wgMessagesDirs['MediawikiInstaller'] = dirname( __DIR__ ) . '/includes/installer/i18n';
	}

	public function execute() {
		global $wgLang, $wgAllowSchemaUpdates;

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
		if ( substr( $this->getOption( 'schema', '' ), 0, 2 ) === "--" ) {
			$this->fatalError( "The --schema option requires a file as an argument.\n" );
		} elseif ( $this->hasOption( 'schema' ) ) {
			$file = $this->getOption( 'schema' );
			$this->fileHandle = fopen( $file, "w" );
			if ( $this->fileHandle === false ) {
				$err = error_get_last();
				$this->fatalError( "Problem opening the schema file for writing: $file\n\t{$err['message']}" );
			}
		}

		// Check for warnings about settings, and abort if there are any.
		if ( !$this->hasOption( 'skip-config-validation' ) ) {
			$this->validateSettings();
		}

		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
		// Set global language to ensure localised errors are in English (T22633)
		RequestContext::getMain()->setLanguage( $lang );

		// BackCompat
		$wgLang = $lang;

		define( 'MW_UPDATER', true );

		$this->output( 'MediaWiki ' . MW_VERSION . " Updater\n\n" );

		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();

		if ( !$this->hasOption( 'skip-compat-checks' ) ) {
			$this->compatChecks();
		} else {
			$this->output( "Skipping compatibility checks, proceed at your own risk (Ctrl+C to abort)\n" );
			$this->countDown( 5 );
		}

		// Check external dependencies are up to date
		if ( !$this->hasOption( 'skip-external-dependencies' ) && !getenv( 'MW_SKIP_EXTERNAL_DEPENDENCIES' ) ) {
			$composerLockUpToDate = $this->runChild( CheckComposerLockUpToDate::class );
			$composerLockUpToDate->execute();
		} else {
			$this->output(
				"Skipping checking whether external dependencies are up to date, proceed at your own risk\n"
			);
		}

		# Attempt to connect to the database as a privileged user
		# This will vomit up an error if there are permissions problems
		$db = $this->getDB( DB_PRIMARY );

		# Check to see whether the database server meets the minimum requirements
		/** @var DatabaseInstaller $dbInstallerClass */
		$dbInstallerClass = Installer::getDBInstallerClass( $db->getType() );
		$status = $dbInstallerClass::meetsMinimumRequirement( $db );
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
				. "(skip this countdown with --quick) ..." );
			$this->countDown( 5 );
		}

		$time1 = microtime( true );

		$shared = $this->hasOption( 'doshared' );

		$updates = [ 'core', 'extensions' ];
		if ( !$this->hasOption( 'schema' ) ) {
			if ( $this->hasOption( 'noschema' ) ) {
				$updates[] = 'noschema';
			}
			$updates[] = 'stats';
		}

		$updater = DatabaseUpdater::newForDB( $db, $shared, $this );

		// Avoid upgrading from versions older than 1.31
		// Using an implicit marker (slots table didn't exist until 1.31)
		// TODO: Use an explicit marker
		// See T259771
		if ( !$updater->tableExists( 'slots' ) ) {
			$this->fatalError(
				"Can not upgrade from versions older than 1.31, please upgrade to that version or later first."
			);
		}

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

		$updater->purgeCache();

		$time2 = microtime( true );

		$timeDiff = $lang->formatTimePeriod( $time2 - $time1 );
		$this->output( "\nDone in $timeDiff.\n" );
	}

	protected function afterFinalSetup() {
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
		$this->getHookRunner()->onMaintenanceUpdateAddParams( $params );

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

	private function formatWarnings( array $warnings ) {
		$text = '';
		foreach ( $warnings as $warning ) {
			$warning = wordwrap( $warning, 75, "\n  " );
			$text .= "* $warning\n";
		}
		return $text;
	}

	private function validateSettings() {
		global $wgSettings;

		$warnings = [];
		if ( $wgSettings->getWarnings() ) {
			$warnings = $wgSettings->getWarnings();
		}

		$status = $wgSettings->validate();
		if ( !$status->isOk() ) {
			foreach ( $status->getErrorsByType( 'error' ) as $msg ) {
				$msg = wfMessage( $msg['message'], ...$msg['params'] );
				$warnings[] = $msg->text();
			}
		}

		$deprecations = $wgSettings->detectDeprecatedConfig();
		foreach ( $deprecations as $key => $msg ) {
			$warnings[] = "$key is deprecated: $msg";
		}

		if ( $warnings ) {
			$this->fatalError( "Some of your configuration settings caused a warning:\n\n"
				. $this->formatWarnings( $warnings ) . "\n"
				. "Please correct the issue before running update.php again.\n"
				. "If you know what you are doing, you can bypass this check\n"
				. "using --skip-config-validation.\n" );
		}
	}
}

$maintClass = UpdateMediaWiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
