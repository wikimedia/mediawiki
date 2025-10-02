<?php
/**
 * Provide a better count of the number of articles
 * and update the site statistics table, if desired.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\SiteStats\SiteStatsInit;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to provide a better count of the number of articles
 * and update the site statistics table, if desired.
 *
 * @ingroup Maintenance
 */
class UpdateArticleCount extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Count of the number of articles and update the site statistics table' );
		$this->addOption( 'update', 'Update the site_stats table with the new count' );
		$this->addOption( 'use-master', 'Count using the primary database' );
	}

	public function execute() {
		$this->output( "Counting articles..." );

		if ( $this->hasOption( 'use-master' ) ) {
			$dbr = $this->getPrimaryDB();
		} else {
			$dbr = $this->getDB( DB_REPLICA, 'vslow' );
		}
		$counter = new SiteStatsInit( $dbr );
		$result = $counter->articles();

		$this->output( "found {$result}.\n" );
		if ( $this->hasOption( 'update' ) ) {
			$this->output( "Updating site statistics table..." );
			$dbw = $this->getPrimaryDB();
			$dbw->newUpdateQueryBuilder()
				->update( 'site_stats' )
				->set( [ 'ss_good_articles' => $result ] )
				->where( [ 'ss_row_id' => 1 ] )
				->caller( __METHOD__ )
				->execute();
			$this->output( "done.\n" );
		} else {
			$this->output( "To update the site statistics table, run the script "
				. "with the --update option.\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = UpdateArticleCount::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
