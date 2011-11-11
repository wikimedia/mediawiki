<?php
/**
 * Run all updaters.
 *
 * This is used when the database schema is modified and we need to apply patches.
 * It is kept compatible with php 4 parsing so that it can give out a meaningful error.
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

if ( !function_exists( 'version_compare' ) || ( version_compare( phpversion(), '5.2.3' ) < 0 ) ) {
	echo "You are using PHP version " . phpversion() . " but MediaWiki needs PHP 5.2.3 or higher. ABORTING.\n" .
	"Check if you have a newer php executable with a different name, such as php5.\n";
	die( 1 );
}

$wgUseMasterForMaintenance = true;
require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateMediaWiki extends Maintenance {

	function __construct() {
		parent::__construct();
		$this->mDescription = "MediaWiki database updater";
		$this->addOption( 'skip-compat-checks', 'Skips compatibility checks, mostly for developers' );
		$this->addOption( 'quick', 'Skip 5 second countdown before starting' );
		$this->addOption( 'doshared', 'Also update shared tables' );
		$this->addOption( 'nopurge', 'Do not purge the objectcache table after updates' );
		$this->addOption( 'force', 'Override when $wgAllowSchemaUpdates disables this script' );
	}

	function getDbType() {
		/* If we used the class constant PHP4 would give a parser error here */
		return 2 /* Maintenance::DB_ADMIN */;
	}

	function compatChecks() {
		$test = new PhpXmlBugTester();
		if ( !$test->ok ) {
			$this->error(
				"Your system has a combination of PHP and libxml2 versions which is buggy\n" .
				"and can cause hidden data corruption in MediaWiki and other web apps.\n" .
				"Upgrade to PHP 5.2.9 or later and libxml2 2.7.3 or later!\n" .
				"ABORTING (see http://bugs.php.net/bug.php?id=45996).\n",
				true );
		}

		$test = new PhpRefCallBugTester;
		$test->execute();
		if ( !$test->ok ) {
			$ver = phpversion();
			$this->error(
				"PHP $ver is not compatible with MediaWiki due to a bug involving\n" .
				"reference parameters to __call. Upgrade to PHP 5.3.2 or higher, or \n" .
				"downgrade to PHP 5.3.0 to fix this.\n" .
				"ABORTING (see http://bugs.php.net/bug.php?id=50394 for details)\n",
				true );
		}
	}

	function execute() {
		global $wgVersion, $wgTitle, $wgLang, $wgAllowSchemaUpdates;

		if( !$wgAllowSchemaUpdates && !$this->hasOption( 'force' ) ) {
			$this->error( "Do not run update.php on this wiki. If you're seeing this you should\n"
				. "probably ask for some help in performing your schema updates.\n\n"
				. "If you know what you are doing, you can continue with --force", true );
		}

		$wgLang = Language::factory( 'en' );
		$wgTitle = Title::newFromText( "MediaWiki database updater" );

		$this->output( "MediaWiki {$wgVersion} Updater\n\n" );

		wfWaitForSlaves( 5 ); // let's not kill databases, shall we? ;) --tor

		if ( !$this->hasOption( 'skip-compat-checks' ) ) {
			$this->compatChecks();
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

		$updates = array( 'core', 'extensions', 'stats' );
		if( !$this->hasOption('nopurge') ) {
			$updates[] = 'purge';
		}

		$updater = DatabaseUpdater::newForDb( $db, $shared, $this );
		$updater->doUpdates( $updates );

		foreach( $updater->getPostDatabaseUpdateMaintenance() as $maint ) {
			if ( $updater->updateRowExists( $maint ) ) {
				continue;
			}
			$child = $this->runChild( $maint );
			$child->execute();
			$updater->insertUpdateRow( $maint );
		}

		$this->output( "\nDone.\n" );
	}

	function afterFinalSetup() {
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
require_once( RUN_MAINTENANCE_IF_MAIN );
