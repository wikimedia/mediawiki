<?php
/**
 * Delete old (non-current) revisions from the database
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
 * Maintenance script that deletes old (non-current) revisions from the database.
 *
 * @ingroup Maintenance
 */
class DeleteOldRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete old (non-current) revisions from the database' );
		$this->addOption( 'delete', 'Actually perform the deletion' );
		$this->addOption( 'page_id', 'List of page ids to work on', false );
	}

	public function execute() {
		$this->output( "Delete old revisions\n\n" );
		$this->doDelete( $this->hasOption( 'delete' ), $this->mArgs );
	}

	private function doDelete( $delete = false, $args = [] ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getDB( DB_PRIMARY );
		$this->beginTransaction( $dbw, __METHOD__ );

		$pageConds = [];
		$revConds = [];

		# If a list of page_ids was provided, limit results to that set of page_ids
		if ( count( $args ) > 0 ) {
			$pageConds['page_id'] = $args;
			$revConds['rev_page'] = $args;
			$this->output( "Limiting to page IDs " . implode( ',', $args ) . "\n" );
		}

		# Get "active" revisions from the page table
		$this->output( "Searching for active revisions..." );
		$res = $dbw->newSelectQueryBuilder()
			->select( 'page_latest' )
			->from( 'page' )
			->where( $pageConds )
			->caller( __METHOD__ )
			->fetchResultSet();
		$latestRevs = [];
		foreach ( $res as $row ) {
			$latestRevs[] = $row->page_latest;
		}
		$this->output( "done.\n" );

		# Get all revisions that aren't in this set
		$this->output( "Searching for inactive revisions..." );
		if ( count( $latestRevs ) > 0 ) {
			$revConds[] = 'rev_id NOT IN (' . $dbw->makeList( $latestRevs ) . ')';
		}
		$res = $dbw->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( $revConds )
			->caller( __METHOD__ )
			->fetchResultSet();
		$oldRevs = [];
		foreach ( $res as $row ) {
			$oldRevs[] = $row->rev_id;
		}
		$this->output( "done.\n" );

		# Inform the user of what we're going to do
		$count = count( $oldRevs );
		$this->output( "$count old revisions found.\n" );

		# Delete as appropriate
		if ( $delete && $count ) {
			$this->output( "Deleting..." );
			$dbw->delete( 'revision', [ 'rev_id' => $oldRevs ], __METHOD__ );
			$dbw->delete( 'ip_changes', [ 'ipc_rev_id' => $oldRevs ], __METHOD__ );
			$this->output( "done.\n" );
		}

		# Purge redundant text records
		$this->commitTransaction( $dbw, __METHOD__ );
		if ( $delete ) {
			$this->purgeRedundantText( true );
		}
	}
}

$maintClass = DeleteOldRevisions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
