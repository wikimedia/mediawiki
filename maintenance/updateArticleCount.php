<?php
/**
 * Provide a better count of the number of articles
 * and update the site statistics table, if desired.
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
 * @author Rob Church <robchur@gmail.com>
 */

require_once __DIR__ . '/Maintenance.php';

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
		$this->addOption( 'use-master', 'Count using the master database' );
	}

	public function execute() {
		$this->output( "Counting articles..." );

		if ( $this->hasOption( 'use-master' ) ) {
			$dbr = $this->getDB( DB_MASTER );
		} else {
			$dbr = $this->getDB( DB_REPLICA, 'vslow' );
		}
		$counter = new SiteStatsInit( $dbr );
		$result = $counter->articles();

		$this->output( "found {$result}.\n" );
		if ( $this->hasOption( 'update' ) ) {
			$this->output( "Updating site statistics table... " );
			$dbw = $this->getDB( DB_MASTER );
			$dbw->update(
				'site_stats',
				[ 'ss_good_articles' => $result ],
				[ 'ss_row_id' => 1 ],
				__METHOD__
			);
			$this->output( "done.\n" );
		} else {
			$this->output( "To update the site statistics table, run the script "
				. "with the --update option.\n" );
		}
	}
}

$maintClass = UpdateArticleCount::class;
require_once RUN_MAINTENANCE_IF_MAIN;
