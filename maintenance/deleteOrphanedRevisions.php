<?php

/**
 * Maintenance script to delete revisions which refer to a nonexisting page
 * Sometimes manual deletion done in a rush leaves crap in the database
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
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @todo More efficient cleanup of text records
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DeleteOrphanedRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Maintenance script to delete revisions which refer to a nonexisting page";
		$this->addOption( 'report', 'Prints out a count of affected revisions but doesn\'t delete them' );
	}

	public function execute() {
		$this->output( "Delete Orphaned Revisions\n" );

		$report = $this->hasOption( 'report' );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		list( $page, $revision ) = $dbw->tableNamesN( 'page', 'revision' );

		# Find all the orphaned revisions
		$this->output( "Checking for orphaned revisions..." );
		$sql = "SELECT rev_id FROM {$revision} LEFT JOIN {$page} ON rev_page = page_id WHERE page_namespace IS NULL";
		$res = $dbw->query( $sql, 'deleteOrphanedRevisions' );
	
		# Stash 'em all up for deletion (if needed)
		$revisions = array();
		foreach ( $res as $row )
			$revisions[] = $row->rev_id;
		$dbw->freeResult( $res );
		$count = count( $revisions );
		$this->output( "found {$count}.\n" );
	
		# Nothing to do?
		if ( $report || $count == 0 ) {
			$dbw->commit();
			exit( 0 );
		}
	
		# Delete each revision
		$this->output( "Deleting..." );
		$this->deleteRevs( $revisions, $dbw );
		$this->output( "done.\n" );
	
		# Close the transaction and call the script to purge unused text records
		$dbw->commit();
		$this->purgeRedundantText( true );
	}
	
	/**
	 * Delete one or more revisions from the database
	 * Do this inside a transaction
	 *
	 * @param $id Array of revision id values
	 * @param $dbw Database class (needs to be a master)
	 */
	private function deleteRevs( $id, &$dbw ) {
		if ( !is_array( $id ) )
			$id = array( $id );
		$dbw->delete( 'revision', array( 'rev_id' => $id ), __METHOD__ );
	}
}

$maintClass = "DeleteOrphanedRevisions";
require_once( DO_MAINTENANCE );

