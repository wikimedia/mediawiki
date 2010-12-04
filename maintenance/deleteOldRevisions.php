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
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DeleteOldRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Delete old (non-current) revisions from the database";
		$this->addOption( 'delete', 'Actually perform the deletion' );
		$this->addOption( 'page_id', 'List of page ids to work on', false );
	}

	public function execute() {
		$this->output( "Delete old revisions\n\n" );
		$this->doDelete( $this->hasOption( 'delete' ), $this->mArgs );
	}

	function doDelete( $delete = false, $args = array() ) {

		# Data should come off the master, wrapped in a transaction
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_pag = $dbw->tableName( 'page' );
		$tbl_rev = $dbw->tableName( 'revision' );

		$pageIdClause = '';
		$revPageClause = '';

		# If a list of page_ids was provided, limit results to that set of page_ids
		if ( sizeof( $args ) > 0 ) {
			$pageIdList = implode( ',', $args );
			$pageIdClause = " WHERE page_id IN ({$pageIdList})";
			$revPageClause = " AND rev_page IN ({$pageIdList})";
			$this->output( "Limiting to {$tbl_pag}.page_id IN ({$pageIdList})\n" );
		}

		# Get "active" revisions from the page table
		$this->output( "Searching for active revisions..." );
		$res = $dbw->query( "SELECT page_latest FROM $tbl_pag{$pageIdClause}" );
		foreach ( $res as $row ) {
			$cur[] = $row->page_latest;
		}
		$this->output( "done.\n" );

		# Get all revisions that aren't in this set
		$old = array();
		$this->output( "Searching for inactive revisions..." );
		$set = implode( ', ', $cur );
		$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_id NOT IN ( $set ){$revPageClause}" );
		foreach ( $res as $row ) {
			$old[] = $row->rev_id;
		}
		$this->output( "done.\n" );

		# Inform the user of what we're going to do
		$count = count( $old );
		$this->output( "$count old revisions found.\n" );

		# Delete as appropriate
		if ( $delete && $count ) {
			$this->output( "Deleting..." );
			$set = implode( ', ', $old );
			$dbw->query( "DELETE FROM $tbl_rev WHERE rev_id IN ( $set )" );
			$this->output( "done.\n" );
		}

		# This bit's done
		# Purge redundant text records
		$dbw->commit();
		if ( $delete ) {
			$this->purgeRedundantText( true );
		}
	}
}

$maintClass = "DeleteOldRevisions";
require_once( DO_MAINTENANCE );

