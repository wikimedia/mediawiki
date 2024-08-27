<?php

/**
 * Show the cached statistics.
 * Give out the same output as [[Special:Statistics]]
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
 * @author Antoine Musso <hashar at free dot fr>
 * Based on initSiteStats.php by:
 * @author Brooke Vibber
 * @author Rob Church <robchur@gmail.com>
 *
 * @license GPL-2.0-or-later
 */

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
