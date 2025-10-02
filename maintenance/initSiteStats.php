<?php
/**
 * Re-initialise or update the site statistics table.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Brooke Vibber
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\SiteStats\SiteStatsInit;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to re-initialise or update the site statistics table
 *
 * @ingroup Maintenance
 */
class InitSiteStats extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Re-initialise the site statistics tables' );
		$this->addOption( 'update', 'Update the existing statistics' );
		$this->addOption( 'active', 'Also update active users count' );
		$this->addOption( 'use-master', 'Count using the primary database' );
	}

	public function execute() {
		$this->output( "Refresh Site Statistics\n\n" );
		$counter = new SiteStatsInit( $this->hasOption( 'use-master' ) );

		$this->output( "Counting total edits..." );
		$edits = $counter->edits();
		$this->output( "{$edits}\nCounting number of articles..." );

		$good = $counter->articles();
		$this->output( "{$good}\nCounting total pages..." );

		$pages = $counter->pages();
		$this->output( "{$pages}\nCounting number of users..." );

		$users = $counter->users();
		$this->output( "{$users}\nCounting number of images..." );

		$image = $counter->files();
		$this->output( "{$image}\n" );

		if ( $this->hasOption( 'update' ) ) {
			$this->output( "\nUpdating site statistics..." );
			$counter->refresh();
			$this->output( "done.\n" );
		} else {
			$this->output( "\nTo update the site statistics table, run the script "
				. "with the --update option.\n" );
		}

		if ( $this->hasOption( 'active' ) ) {
			$this->output( "\nCounting and updating active users..." );
			$active = SiteStatsUpdate::cacheUpdate( $this->getPrimaryDB() );
			$this->output( "{$active}\n" );
		}

		$this->output( "\nDone.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = InitSiteStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
