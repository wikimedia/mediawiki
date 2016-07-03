<?php
/**
 * Re-initialise or update the site statistics table.
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
 * @author Brion Vibber
 * @author Rob Church <robchur@gmail.com>
 */

require_once __DIR__ . '/Maintenance.php';

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
		$this->addOption( 'use-master', 'Count using the master database' );
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
			$active = SiteStatsUpdate::cacheUpdate( $this->getDB( DB_MASTER ) );
			$this->output( "{$active}\n" );
		}

		$this->output( "\nDone.\n" );
	}
}

$maintClass = "InitSiteStats";
require_once RUN_MAINTENANCE_IF_MAIN;
