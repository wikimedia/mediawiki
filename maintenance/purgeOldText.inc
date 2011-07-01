<?php

/**
 * Support functions for cleaning up redundant text records
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

function PurgeRedundantText( $delete = false ) {

	# Data should come off the master, wrapped in a transaction
	$dbw = wfGetDB( DB_MASTER );
	$dbw->begin();

	$tbl_arc = $dbw->tableName( 'archive' );
	$tbl_rev = $dbw->tableName( 'revision' );
	$tbl_txt = $dbw->tableName( 'text' );

	# Get "active" text records from the revisions table
	echo( "Searching for active text records in revisions table..." );
	$res = $dbw->query( "SELECT DISTINCT rev_text_id FROM $tbl_rev" );
	foreach ( $res as $row ) {
		$cur[] = $row->rev_text_id;
	}
	echo( "done.\n" );

	# Get "active" text records from the archive table
	echo( "Searching for active text records in archive table..." );
	$res = $dbw->query( "SELECT DISTINCT ar_text_id FROM $tbl_arc" );
	$cur = array();
	foreach ( $res as $row ) {
		$cur[] = $row->ar_text_id;
	}
	echo( "done.\n" );

	# Get the IDs of all text records not in these sets
	echo( "Searching for inactive text records..." );
	$set = implode( ', ', $cur );
	$res = $dbw->query( "SELECT old_id FROM $tbl_txt WHERE old_id NOT IN ( $set )" );
	$old = array();
	foreach ( $res as $row ) {
		$old[] = $row->old_id;
	}
	echo( "done.\n" );

	# Inform the user of what we're going to do
	$count = count( $old );
	echo( "$count inactive items found.\n" );

	# Delete as appropriate
	if ( $delete && $count ) {
		echo( "Deleting..." );
		$set = implode( ', ', $old );
		$dbw->query( "DELETE FROM $tbl_txt WHERE old_id IN ( $set )" );
		echo( "done.\n" );
	}

	# Done
	$dbw->commit();

}
