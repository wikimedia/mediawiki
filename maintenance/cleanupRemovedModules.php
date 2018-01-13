<?php
/**
 * Remove cache entries for removed ResourceLoader modules from the database.
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
 * @author Roan Kattouw
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to remove cache entries for removed ResourceLoader modules
 * from the database.
 *
 * @ingroup Maintenance
 */
class CleanupRemovedModules extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Remove cache entries for removed ResourceLoader modules from the database' );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$this->output( "Cleaning up module_deps table...\n" );

		$dbw = $this->getDB( DB_MASTER );
		$rl = new ResourceLoader( MediaWikiServices::getInstance()->getMainConfig() );
		$moduleNames = $rl->getModuleNames();
		$res = $dbw->select( 'module_deps',
			[ 'md_module', 'md_skin' ],
			$moduleNames ? 'md_module NOT IN (' . $dbw->makeList( $moduleNames ) . ')' : '1=1',
			__METHOD__
		);
		$rows = iterator_to_array( $res, false );

		$modDeps = $dbw->tableName( 'module_deps' );
		$i = 1;
		foreach ( array_chunk( $rows, $this->getBatchSize() ) as $chunk ) {
			// WHERE ( mod=A AND skin=A ) OR ( mod=A AND skin=B) ..
			$conds = array_map( function ( stdClass $row ) use ( $dbw ) {
				return $dbw->makeList( (array)$row, IDatabase::LIST_AND );
			}, $chunk );
			$conds = $dbw->makeList( $conds, IDatabase::LIST_OR );

			$this->beginTransaction( $dbw, __METHOD__ );
			$dbw->query( "DELETE FROM $modDeps WHERE $conds", __METHOD__ );
			$numRows = $dbw->affectedRows();
			$this->output( "Batch $i: $numRows rows\n" );
			$this->commitTransaction( $dbw, __METHOD__ );

			$i++;
		}

		$this->output( "Done\n" );
	}
}

$maintClass = CleanupRemovedModules::class;
require_once RUN_MAINTENANCE_IF_MAIN;
