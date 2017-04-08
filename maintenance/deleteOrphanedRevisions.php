<?php
/**
 * Delete revisions which refer to a nonexisting page.
 * Sometimes manual deletion done in a rush leaves crap in the database.
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
 * @todo More efficient cleanup of text records
 */

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\IDatabase;

/**
 * Maintenance script that deletes revisions which refer to a nonexisting page.
 *
 * @ingroup Maintenance
 */
class DeleteOrphanedRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Maintenance script to delete revisions which refer to a nonexisting page' );
		$this->addOption( 'report', 'Prints out a count of affected revisions but doesn\'t delete them' );
	}

	public function execute() {
		$this->output( "Delete Orphaned Revisions\n" );

		$report = $this->hasOption( 'report' );

		$dbw = $this->getDB( DB_MASTER );
		$this->beginTransaction( $dbw, __METHOD__ );
		list( $page, $revision ) = $dbw->tableNamesN( 'page', 'revision' );

		# Find all the orphaned revisions
		$this->output( "Checking for orphaned revisions..." );
		$sql = "SELECT rev_id FROM {$revision} LEFT JOIN {$page} ON rev_page = page_id "
			. "WHERE page_namespace IS NULL";
		$res = $dbw->query( $sql, 'deleteOrphanedRevisions' );

		# Stash 'em all up for deletion (if needed)
		$revisions = [];
		foreach ( $res as $row ) {
			$revisions[] = $row->rev_id;
		}
		$count = count( $revisions );
		$this->output( "found {$count}.\n" );

		# Nothing to do?
		if ( $report || $count == 0 ) {
			$this->commitTransaction( $dbw, __METHOD__ );
			exit( 0 );
		}

		# Delete each revision
		$this->output( "Deleting..." );
		$this->deleteRevs( $revisions, $dbw );
		$this->output( "done.\n" );

		# Close the transaction and call the script to purge unused text records
		$this->commitTransaction( $dbw, __METHOD__ );
		$this->purgeRedundantText( true );
	}

	/**
	 * Delete one or more revisions from the database
	 * Do this inside a transaction
	 *
	 * @param array $id Array of revision id values
	 * @param IDatabase $dbw Master DB handle
	 */
	private function deleteRevs( $id, &$dbw ) {
		if ( !is_array( $id ) ) {
			$id = [ $id ];
		}
		$dbw->delete( 'revision', [ 'rev_id' => $id ], __METHOD__ );
	}
}

$maintClass = "DeleteOrphanedRevisions";
require_once RUN_MAINTENANCE_IF_MAIN;
