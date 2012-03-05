<?php
# Copyright (C) 2005 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * @file
 * @ingroup Maintenance
 */

/**
 * Look for duplicate user table entries and optionally prune them.
 * @ingroup Maintenance
 */
class UserDupes {
	var $db;
	var $reassigned;
	var $trimmed;
	var $failed;

	function UserDupes( &$database ) {
		$this->db =& $database;
	}

	/**
	 * Check if this database's user table has already had a unique
	 * user_name index applied.
	 * @return bool
	 */
	function hasUniqueIndex() {
		$fname = 'UserDupes::hasUniqueIndex';
		$info = $this->db->indexInfo( 'user', 'user_name', $fname );
		if ( !$info ) {
			wfOut( "WARNING: doesn't seem to have user_name index at all!\n" );
			return false;
		}

		# Confusingly, 'Non_unique' is 0 for *unique* indexes,
		# and 1 for *non-unique* indexes. Pass the crack, MySQL,
		# it's obviously some good stuff!
		return ( $info[0]->Non_unique == 0 );
	}

	/**
	 * Checks the database for duplicate user account records
	 * and remove them in preparation for application of a unique
	 * index on the user_name field. Returns true if the table is
	 * clean or if duplicates have been resolved automatically.
	 *
	 * May return false if there are unresolvable problems.
	 * Status information will be echo'd to stdout.
	 *
	 * @return bool
	 */
	function clearDupes() {
		return $this->checkDupes( true );
	}

	/**
	 * Checks the database for duplicate user account records
	 * in preparation for application of a unique index on the
	 * user_name field. Returns true if the table is clean or
	 * if duplicates can be resolved automatically.
	 *
	 * Returns false if there are duplicates and resolution was
	 * not requested. (If doing resolution, edits may be reassigned.)
	 * Status information will be echo'd to stdout.
	 *
	 * @param $doDelete bool: pass true to actually remove things
	 *                  from the database; false to just check.
	 * @return bool
	 */
	function checkDupes( $doDelete = false ) {
		if ( $this->hasUniqueIndex() ) {
			echo wfWikiID() . " already has a unique index on its user table.\n";
			return true;
		}

		$this->lock();

		wfOut( "Checking for duplicate accounts...\n" );
		$dupes = $this->getDupes();
		$count = count( $dupes );

		wfOut( "Found $count accounts with duplicate records on " . wfWikiID() . ".\n" );
		$this->trimmed    = 0;
		$this->reassigned = 0;
		$this->failed     = 0;
		foreach ( $dupes as $name ) {
			$this->examine( $name, $doDelete );
		}

		$this->unlock();

		wfOut( "\n" );

		if ( $this->reassigned > 0 ) {
			if ( $doDelete ) {
				wfOut( "$this->reassigned duplicate accounts had edits reassigned to a canonical record id.\n" );
			} else {
				wfOut( "$this->reassigned duplicate accounts need to have edits reassigned.\n" );
			}
		}

		if ( $this->trimmed > 0 ) {
			if ( $doDelete ) {
				wfOut( "$this->trimmed duplicate user records were deleted from " . wfWikiID() . ".\n" );
			} else {
				wfOut( "$this->trimmed duplicate user accounts were found on " . wfWikiID() . " which can be removed safely.\n" );
			}
		}

		if ( $this->failed > 0 ) {
			wfOut( "Something terribly awry; $this->failed duplicate accounts were not removed.\n" );
			return false;
		}

		if ( $this->trimmed == 0 || $doDelete ) {
			wfOut( "It is now safe to apply the unique index on user_name.\n" );
			return true;
		} else {
			wfOut( "Run this script again with the --fix option to automatically delete them.\n" );
			return false;
		}
	}

	/**
	 * We don't want anybody to mess with our stuff...
	 * @access private
	 */
	function lock() {
		$fname = 'UserDupes::lock';
		if ( $this->newSchema() ) {
			$set = array( 'user', 'revision' );
		} else {
			$set = array( 'user', 'cur', 'old' );
		}
		$names = array_map( array( $this, 'lockTable' ), $set );
		$tables = implode( ',', $names );

		$this->db->query( "LOCK TABLES $tables", $fname );
	}

	function lockTable( $table ) {
		return $this->db->tableName( $table ) . ' WRITE';
	}

	/**
	 * @return bool
	 * @access private
	 */
	function newSchema() {
		return class_exists( 'Revision' );
	}

	/**
	 * @access private
	 */
	function unlock() {
		$fname = 'UserDupes::unlock';
		$this->db->query( "UNLOCK TABLES", $fname );
	}

	/**
	 * Grab usernames for which multiple records are present in the database.
	 * @return array
	 * @access private
	 */
	function getDupes() {
		$fname = 'UserDupes::listDupes';
		$user = $this->db->tableName( 'user' );
		$result = $this->db->query(
			 "SELECT user_name,COUNT(*) AS n
			    FROM $user
			GROUP BY user_name
			  HAVING n > 1", $fname );

		$list = array();
		while ( $row = $this->db->fetchObject( $result ) ) {
			$list[] = $row->user_name;
		}
		return $list;
	}

	/**
	 * Examine user records for the given name. Try to see which record
	 * will be the one that actually gets used, then check remaining records
	 * for edits. If the dupes have no edits, we can safely remove them.
	 * @param $name string
	 * @param $doDelete bool
	 * @access private
	 */
	function examine( $name, $doDelete ) {
		$fname = 'UserDupes::listDupes';
		$result = $this->db->select( 'user',
			array( 'user_id' ),
			array( 'user_name' => $name ),
			$fname );

		$firstRow = $this->db->fetchObject( $result );
		$firstId  = $firstRow->user_id;
		wfOut( "Record that will be used for '$name' is user_id=$firstId\n" );

		while ( $row = $this->db->fetchObject( $result ) ) {
			$dupeId = $row->user_id;
			wfOut( "... dupe id $dupeId: " );
			$edits = $this->editCount( $dupeId );
			if ( $edits > 0 ) {
				$this->reassigned++;
				wfOut( "has $edits edits! " );
				if ( $doDelete ) {
					$this->reassignEdits( $dupeId, $firstId );
					$newEdits = $this->editCount( $dupeId );
					if ( $newEdits == 0 ) {
						wfOut( "confirmed cleaned. " );
					} else {
						$this->failed++;
						wfOut( "WARNING! $newEdits remaining edits for $dupeId; NOT deleting user.\n" );
						continue;
					}
				} else {
					wfOut( "(will need to reassign edits on fix)" );
				}
			} else {
				wfOut( "ok, no edits. " );
			}
			$this->trimmed++;
			if ( $doDelete ) {
				$this->trimAccount( $dupeId );
			}
			wfOut( "\n" );
		}
	}

	/**
	 * Count the number of edits attributed to this user.
	 * Does not currently check log table or other things
	 * where it might show up...
	 * @param $userid int
	 * @return int
	 * @access private
	 */
	function editCount( $userid ) {
		if ( $this->newSchema() ) {
			return $this->editCountOn( 'revision', 'rev_user', $userid );
		} else {
			return $this->editCountOn( 'cur', 'cur_user', $userid ) +
				$this->editCountOn( 'old', 'old_user', $userid );
		}
	}

	/**
	 * Count the number of hits on a given table for this account.
	 * @param $table string
	 * @param $field string
	 * @param $userid int
	 * @return int
	 * @access private
	 */
	function editCountOn( $table, $field, $userid ) {
		$fname = 'UserDupes::editCountOn';
		return intval( $this->db->selectField(
			$table,
			'COUNT(*)',
			array( $field => $userid ),
			$fname ) );
	}

	/**
	 * @param $from int
	 * @param $to int
	 * @access private
	 */
	function reassignEdits( $from, $to ) {
		$set = $this->newSchema()
			? array( 'revision' => 'rev_user' )
			: array( 'cur' => 'cur_user', 'old' => 'old_user' );
		foreach ( $set as $table => $field ) {
			$this->reassignEditsOn( $table, $field, $from, $to );
		}
	}

	/**
	 * @param $table string
	 * @param $field string
	 * @param $from int
	 * @param $to int
	 * @access private
	 */
	function reassignEditsOn( $table, $field, $from, $to ) {
		$fname = 'UserDupes::reassignEditsOn';
		wfOut( "reassigning on $table... " );
		$this->db->update( $table,
			array( $field => $to ),
			array( $field => $from ),
			$fname );
		wfOut( "ok. " );
	}

	/**
	 * Remove a user account line.
	 * @param $userid int
	 * @access private
	 */
	function trimAccount( $userid ) {
		$fname = 'UserDupes::trimAccount';
		wfOut( "deleting..." );
		$this->db->delete( 'user', array( 'user_id' => $userid ), $fname );
		wfOut( " ok" );
	}

}
