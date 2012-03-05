<?php
/**
 * Run all updaters.
 *
 * This is used when the database schema is modified and we need to apply patches.
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 */

$wgUseMasterForMaintenance = true;
require_once( 'Maintenance.php' );

class UpdateMediaWiki extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "MediaWiki database updater";
		$this->addOption( 'skip-compat-checks', 'Skips compatibility checks, mostly for developers' );
		$this->addOption( 'quick', 'Skip 5 second countdown before starting' );
		$this->addOption( 'doshared', 'Also update shared tables' );
		$this->addOption( 'nopurge', 'Do not purge the objectcache table after updates' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $wgVersion, $wgTitle, $wgLang;

		$wgLang = Language::factory( 'en' );
		$wgTitle = Title::newFromText( "MediaWiki database updater" );

		$this->output( "MediaWiki {$wgVersion} Updater\n\n" );

		if ( !$this->hasOption( 'skip-compat-checks' ) ) {
			install_version_checks();
		} else {
			$this->output( "Skipping compatibility checks, proceed at your own risk (Ctrl+C to abort)\n" );
			wfCountdown( 5 );
		}

		# Attempt to connect to the database as a privileged user
		# This will vomit up an error if there are permissions problems
		$db = wfGetDB( DB_MASTER );

		$this->output( "Going to run database updates for " . wfWikiID() . "\n" );
		$this->output( "Depending on the size of your database this may take a while!\n" );

		if ( !$this->hasOption( 'quick' ) ) {
			$this->output( "Abort with control-c in the next five seconds (skip this countdown with --quick) ... " );
			wfCountDown( 5 );
		}

		$shared = $this->hasOption( 'doshared' );
		$purge = !$this->hasOption( 'nopurge' );

		$updater = DatabaseUpdater::newForDb( $db, $shared );
		$updater->doUpdates( $purge );

		foreach( $updater->getPostDatabaseUpdateMaintenance() as $maint ) {
			$this->runChild( $maint )->execute();
		}

		$this->output( "\nDone.\n" );
	}

	protected function afterFinalSetup() {
		global $wgLocalisationCacheConf;

		# Don't try to access the database
		# This needs to be disabled early since extensions will try to use the l10n
		# cache from $wgExtensionFunctions (bug 20471)
		$wgLocalisationCacheConf = array(
			'class' => 'LocalisationCache',
			'storeClass' => 'LCStore_Null',
			'storeDirectory' => false,
			'manualRecache' => false,
		);
	}
}

$maintClass = 'UpdateMediaWiki';
require_once( DO_MAINTENANCE );
