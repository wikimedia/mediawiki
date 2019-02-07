<?php
/**
 * Helper class for update.php.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 */

/**
 * Look for duplicate user table entries and optionally prune them.
 *
 * This is still used by our MysqlUpdater at:
 * includes/installer/MysqlUpdater.php
 *
 * @ingroup Maintenance
 */
class UserDupes {
	private $db;
	private $reassigned;
	private $trimmed;
	private $failed;
	private $outputCallback;

	function __construct( &$database, $outputCallback ) {
		$this->db = $database;
		$this->outputCallback = $outputCallback;
	}

	/**
	 * Output some text via the output callback provided
	 * @param string $str Text to print
	 */
	private function out( $str ) {
		call_user_func( $this->outputCallback, $str );
	}

	/**
	 * Check if this database's user table has already had a unique
	 * user_name index applied.
	 * @return bool
	 */
	function hasUniqueIndex() {
		$info = $this->db->indexInfo( 'user', 'user_name', __METHOD__ );
		if ( !$info ) {
			$this->out( "WARNING: doesn't seem to have user_name index at all!\n" );

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
	 * @param bool $doDelete Pass true to actually remove things
	 *   from the database; false to just check.
	 * @return bool
	 */
	function checkDupes( $doDelete = false ) {
		if ( $this->hasUniqueIndex() ) {
			echo wfWikiID() . " already has a unique index on its user table.\n";

			return true;
		}

		$this->lock();

		$this->out( "Checking for duplicate accounts...\n" );
		$dupes = $this->getDupes();
		$count = count( $dupes );

		$this->out( "Found $count accounts with duplicate records on " . wfWikiID() . ".\n" );
		$this->trimmed = 0;
		$this->reassigned = 0;
		$this->failed = 0;
		foreach ( $dupes as $name ) {
			$this->examine( $name, $doDelete );
		}

		$this->unlock();

		$this->out( "\n" );

		if ( $this->reassigned > 0 ) {
			if ( $doDelete ) {
				$this->out( "$this->reassigned duplicate accounts had edits "
					. "reassigned to a canonical record id.\n" );
			} else {
				$this->out( "$this->reassigned duplicate accounts need to have edits reassigned.\n" );
			}
		}

		if ( $this->trimmed > 0 ) {
			if ( $doDelete ) {
				$this->out( "$this->trimmed duplicate user records were deleted from "
					. wfWikiID() . ".\n" );
			} else {
				$this->out( "$this->trimmed duplicate user accounts were found on "
					. wfWikiID() . " which can be removed safely.\n" );
			}
		}

		if ( $this->failed > 0 ) {
			$this->out( "Something terribly awry; $this->failed duplicate accounts were not removed.\n" );

			return false;
		}

		if ( $this->trimmed == 0 || $doDelete ) {
			$this->out( "It is now safe to apply the unique index on user_name.\n" );

			return true;
		} else {
			$this->out( "Run this script again with the --fix option to automatically delete them.\n" );

			return false;
		}
	}

	/**
	 * We don't want anybody to mess with our stuff...
	 * @private
	 */
	function lock() {
		$set = [ 'user', 'revision' ];
		$names = array_map( [ $this, 'lockTable' ], $set );
		$tables = implode( ',', $names );

		$this->db->query( "LOCK TABLES $tables", __METHOD__ );
	}

	function lockTable( $table ) {
		return $this->db->tableName( $table ) . ' WRITE';
	}

	/**
	 * @private
	 */
	function unlock() {
		$this->db->query( "UNLOCK TABLES", __METHOD__ );
	}

	/**
	 * Grab usernames for which multiple records are present in the database.
	 * @return array
	 * @private
	 */
	function getDupes() {
		$user = $this->db->tableName( 'user' );
		$result = $this->db->query(
			"SELECT user_name,COUNT(*) AS n
				FROM $user
			GROUP BY user_name
				HAVING n > 1", __METHOD__ );

		$list = [];
		foreach ( $result as $row ) {
			$list[] = $row->user_name;
		}

		return $list;
	}

	/**
	 * Examine user records for the given name. Try to see which record
	 * will be the one that actually gets used, then check remaining records
	 * for edits. If the dupes have no edits, we can safely remove them.
	 * @param string $name
	 * @param bool $doDelete
	 * @private
	 */
	function examine( $name, $doDelete ) {
		$result = $this->db->select( 'user',
			[ 'user_id' ],
			[ 'user_name' => $name ],
			__METHOD__ );

		$firstRow = $this->db->fetchObject( $result );
		$firstId = $firstRow->user_id;
		$this->out( "Record that will be used for '$name' is user_id=$firstId\n" );

		foreach ( $result as $row ) {
			$dupeId = $row->user_id;
			$this->out( "... dupe id $dupeId: " );
			$edits = $this->editCount( $dupeId );
			if ( $edits > 0 ) {
				$this->reassigned++;
				$this->out( "has $edits edits! " );
				if ( $doDelete ) {
					$this->reassignEdits( $dupeId, $firstId );
					$newEdits = $this->editCount( $dupeId );
					if ( $newEdits == 0 ) {
						$this->out( "confirmed cleaned. " );
					} else {
						$this->failed++;
						$this->out( "WARNING! $newEdits remaining edits for $dupeId; NOT deleting user.\n" );
						continue;
					}
				} else {
					$this->out( "(will need to reassign edits on fix)" );
				}
			} else {
				$this->out( "ok, no edits. " );
			}
			$this->trimmed++;
			if ( $doDelete ) {
				$this->trimAccount( $dupeId );
			}
			$this->out( "\n" );
		}
	}

	/**
	 * Count the number of edits attributed to this user.
	 * Does not currently check log table or other things
	 * where it might show up...
	 * @param int $userid
	 * @return int
	 * @private
	 */
	function editCount( $userid ) {
		return intval( $this->db->selectField(
			'revision',
			'COUNT(*)',
			[ 'rev_user' => $userid ],
			__METHOD__ ) );
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @private
	 */
	function reassignEdits( $from, $to ) {
		$this->out( 'reassigning... ' );
		$this->db->update( 'revision',
			[ 'rev_user' => $to ],
			[ 'rev_user' => $from ],
			__METHOD__ );
		$this->out( "ok. " );
	}

	/**
	 * Remove a user account line.
	 * @param int $userid
	 * @private
	 */
	function trimAccount( $userid ) {
		$this->out( "deleting..." );
		$this->db->delete( 'user', [ 'user_id' => $userid ], __METHOD__ );
		$this->out( " ok" );
	}
}
