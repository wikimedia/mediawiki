<?php

/**
 * Show the cached statistics.
 * Give out the same output as [[Special:Statistics]]
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Antoine Musso <hashar at free dot fr>
 * Based on initSiteStats.php by:
 * @author Brooke Vibber
 * @author Rob Church <robchur@gmail.com>
 *
 * @license GPL-2.0-or-later
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to show the cached statistics.
 *
 * @ingroup Maintenance
 */
class ShowSiteStats extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Show the cached statistics' );
	}

	public function execute() {
		$fields = [
			'ss_total_edits' => 'Total edits',
			'ss_good_articles' => 'Number of articles',
			'ss_total_pages' => 'Total pages',
			'ss_users' => 'Number of users',
			'ss_active_users' => 'Active users',
			'ss_images' => 'Number of images',
		];

		// Get cached stats from a replica DB
		$dbr = $this->getReplicaDB();
		$stats = $dbr->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->caller( __METHOD__ )->fetchRow();

		// Get maximum size for each column
		$max_length_value = $max_length_desc = 0;
		foreach ( $fields as $field => $desc ) {
			$max_length_value = max( $max_length_value, strlen( $stats->$field ) );
			$max_length_desc = max( $max_length_desc, strlen( $desc ) );
		}

		// Show them
		foreach ( $fields as $field => $desc ) {
			$this->output( sprintf(
				"%-{$max_length_desc}s: %{$max_length_value}d\n",
				$desc,
				$stats->$field
			) );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ShowSiteStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
