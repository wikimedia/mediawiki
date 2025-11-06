#!/usr/bin/env php
<?php
/**
 * Run all updaters.
 *
 * This is used when the database schema is modified and we need to apply patches.
 *
 * @license GPL-2.0-or-later
 * @file
 * @todo document
 * @ingroup Maintenance
 */

// NO_AUTOLOAD -- due to hashbang above

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Context\RequestContext;
use MediaWiki\Installer\DatabaseInstaller;
use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Installer\Installer;
use MediaWiki\Maintenance\LoggedUpdateMaintenance;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\WikiMap\WikiMap;
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
		$this->addOption( 'quick', 'Skip 5 second countdown before starting' );
		$this->addOption( 'initial',
			'Do initial updates required after manual installation using tables-generated.sql' );
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

	/** @inheritDoc */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function setup() {
		global $wgMessagesDirs;
		// T206765: We need to load the installer i18n files as some errors come from installer/updater code
		// T310378: We have to ensure we do this before execute()
		$wgMessagesDirs['MediaWikiInstaller'] = dirname( __DIR__ ) . '/includes/Installer/i18n';
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
		if ( str_starts_with( $this->getOption( 'schema', '' ), '--' ) ) {
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

		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		// Set global language to ensure localised errors are in English (T22633)
		RequestContext::getMain()->setLanguage( $lang );

		// BackCompat
		$wgLang = $lang;

		define( 'MW_UPDATER', true );

		$this->output( 'MediaWiki ' . MW_VERSION . " Updater\n\n" );

		$this->waitForReplication();

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
		$db = $this->getPrimaryDB();

		# Check to see whether the database server meets the minimum requirements
		/** @var DatabaseInstaller $dbInstallerClass */
		$dbInstallerClass = Installer::getDBInstallerClass( $db->getType() );
		$status = $dbInstallerClass::meetsMinimumRequirement( $db );
		if ( !$status->isOK() ) {
			// This might output some wikitext like <strong> but it should be comprehensible
			$this->fatalError( $status );
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
		if ( $this->hasOption( 'initial' ) ) {
			$updates[] = 'initial';
		}

		$updater = DatabaseUpdater::newForDB( $db, $shared, $this );

		// Avoid upgrading from versions older than 1.35
		// Using an implicit marker (rev_actor was introduced in 1.34)
		// TODO: Use an explicit marker
		// See T259771
		if ( !$updater->tableExists( 'linktarget' ) ) {
			$this->fatalError(
				"Can not upgrade from versions older than 1.38, please upgrade to that version or later first."
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

	private function formatWarnings( array $warnings ): string {
		$text = '';
		foreach ( $warnings as $warning ) {
			$warning = wordwrap( $warning, 75, "\n  " );
			$text .= "* $warning\n";
		}
		return $text;
	}

	private function validateSettings() {
		$settings = SettingsBuilder::getInstance();

		$warnings = [];
		if ( $settings->getWarnings() ) {
			$warnings = $settings->getWarnings();
		}

		$status = $settings->validate();
		if ( !$status->isOK() ) {
			foreach ( $status->getMessages( 'error' ) as $msg ) {
				$warnings[] = wfMessage( $msg )->text();
			}
		}

		$deprecations = $settings->detectDeprecatedConfig();
		foreach ( $deprecations as $key => $msg ) {
			$warnings[] = "$key is deprecated: $msg";
		}

		$obsolete = $settings->detectObsoleteConfig();
		foreach ( $obsolete as $key => $msg ) {
			$warnings[] = "$key is obsolete: $msg";
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

// @codeCoverageIgnoreStart
$maintClass = UpdateMediaWiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
