<?php
/**
 * Remove all cache entries for ResourceLoader modules from the database.
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
 * @author Timo Tijhof
 */

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to purge the module_deps database cache table.
 *
 * @ingroup Maintenance
 */
class PurgeModuleDeps extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Remove all cache entries for ResourceLoader modules from the database' );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );
		$limit = (int)$this->mBatchSize;

		$this->output( "Cleaning up module_deps table...\n" );
		$i = 1;
		$modDeps = $dbw->tableName( 'module_deps' );
		do {
			$dbw->query( "DELETE FROM $modDeps WHERE LIMIT $limit", __METHOD__ );
			$numRows = $dbw->affectedRows();
			$this->output( "Batch $i: $numRows rows\n" );
			$i++;
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
		} while ( $numRows > 0 );
		$this->output( "done\n" );
	}
}

$maintClass = 'PurgeModuleDeps';
require_once RUN_MAINTENANCE_IF_MAIN;
