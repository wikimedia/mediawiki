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
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Look for duplicate user table entries and optionally prune them.
 */
class UserDupes {
	var $db;
	var $unresolvable;
	var $trimmed;
	
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
	 * May return false if there are unresolvable problems.
	 * Status information will be echo'd to stdout.
	 *
	 * @param bool $doDelete pass true to actually remove things
	 *                       from the database; false to just check.
	 * @return bool
	 */
	function checkDupes( $doDelete=false ) {
		global $wgDBname;
		
		$this->db =& wfGetDB( DB_MASTER );
		$this->lock();
		
		echo "Checking for duplicate accounts...\n";
		$dupes = $this->getDupes();
		$count = count( $dupes );
		
		echo "Found $count accounts with duplicate records on $wgDBname.\n";
		$this->trimmed      = 0;
		$this->unresolvable = 0;
		foreach( $dupes as $name ) {
			$this->examine( $name, $doDelete );
		}
		
		$this->unlock();
		
		if( $this->trimmed > 0 ) {
			echo "\n";
			if( $doDelete ) {
				echo "$this->trimmed duplicate user records were deleted from $wgDBname.\n";
				if( $this->unresolvable == 0 ) {
					echo "It is now safe to apply the unique index on user_name.\n";
				}
			} else {
				echo "$this->trimmed duplicate user accounts were found on $wgDBname which can be removed safely.\n";
				echo "Run this script again with the --fix option to automatically delete them.\n";
			}
		}
		
		if( $this->unresolvable > 0 ) {
			echo "\n";
			echo "There were $this->unresolvable unresolvable accounts on $wgDBname.\n";
			echo "These accounts have edits credited to duplicate records,\n";
			echo "and need to be cleaned up manually before the unique index\n";
			echo "for user_name can be applied.\n";
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * We don't want anybody to mess with our stuff...
	 * @access private
	 */
	function lock() {
		$fname = 'UserDupes::lock';
		global $wgVersion;
		if( version_compare( $wgVersion, '1.5', 'gte' ) ) {
			$set = array( 'user', 'cur', 'old' );
		} else {
			$set = array( 'user', 'revision' );
		}
		$names = array_map( array( $this, 'lockTable' ), $set );
		$tables = implode( ',', $names );
		
		$result = $this->db->query( "LOCK TABLES $tables", $fname );
	}
	
	function lockTable( $table ) {
		return $this->db->tableName( $table ) . ' WRITE';
	}
	
	/**
	 * @access private
	 */
	function unlock() {
		$fname = 'UserDupes::unlock';
		$result = $this->db->query( "UNLOCK TABLES", $fname );
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
		while( $row = $this->db->fetchObject( $result ) ) {
			$list[] = $row->user_name;
		}
		$this->db->freeResult( $result );
		
		return $list;
	}
	
	/**
	 * Examine user records for the given name. Try to see which record
	 * will be the one that actually gets used, then check remaining records
	 * for edits. If the dupes have no edits, we can safely remove them.
	 * @param string $name
	 * @param bool $doDelete
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
		echo "Record that will be used for '$name' is user_id=$firstId\n";
		
		while( $row = $this->db->fetchObject( $result ) ) {
			$dupeId = $row->user_id;
			echo "... dupe id $dupeId: ";
			$edits = $this->editCount( $dupeId );
			if( $edits > 0 ) {
				$this->unresolvable++;
				echo "has $edits edits! MANUAL INTERVENTION REQUIRED.\n";
				continue;
			} else {
				$this->trimmed++;
				echo "ok, no edits. ";
				if( $doDelete ) {
					$this->trimAccount( $dupeId );
				}
				echo "\n";
			}
		}
		$this->db->freeResult( $result );
	}
	
	/**
	 * Count the number of edits attributed to this user.
	 * Does not currently check log table or other things
	 * where it might show up...
	 * @param int $userid
	 * @return int
	 * @access private
	 */
	function editCount( $userid ) {
		global $wgVersion;
		if( version_compare( $wgVersion, '1.5alpha', 'ge' ) ) {
			return $this->editCountOn( 'revision', 'rev_user', $userid );
		} else {
			return $this->editCountOn( 'cur', 'cur_user', $userid ) +
				$this->editCountOn( 'old', 'old_user', $userid );
		}
	}
	
	/**
	 * Count the number of hits on a given table for this account.
	 * @param string $table
	 * @param string $field
	 * @param int $userid
	 * @return int
	 * @access private
	 */
	function editCountOn( $table, $field, $userid ) {
		$fname = 'UserDupes::editCountOn';
		return IntVal( $this->db->selectField(
			$table,
			'COUNT(*)',
			array( $field => $userid ),
			$fname ) );
	}
	
	/**
	 * Remove a user account line.
	 * @param int $userid
	 * @access private
	 */
	function trimAccount( $userid ) {
		$fname = 'UserDupes::trimAccount';
		echo "deleting...";
		$this->db->delete( 'user', array( 'user_id' => $userid ), $fname );
		echo " ok";
	}
	
}


?>