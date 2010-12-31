<?php
/**
 * Helper class for making a copy of the database, mostly for unit testing.
 *
 * Copyright © 2010 Chad Horohoe <chad@anyonecanedit.org>
 * http://www.mediawiki.org/
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
 * @ingroup Database
 */

class CloneDatabase {

	/**
	 * Table prefix for cloning
	 * @var String
	 */
	private $newTablePrefix = '';

	/**
	 * Current table prefix
	 * @var String
	 */
	private $oldTablePrefix = '';

	/**
	 * List of tables to be cloned
	 * @var Array
	 */
	private $tablesToClone = array();

	/**
	 * Should we DROP tables containing the new names?
	 * @var Bool
	 */
	private $dropCurrentTables = true;

	/**
	 * Whether to use temporary tables or not
	 * @var Bool
	 */
	private $useTemporaryTables = true;

	/**
	 * Constructor
	 *
	 * @param $db DatabaseBase A database subclass
	 * @param $tablesToClone Array An array of tables to clone, unprefixed
	 * @param $newTablePrefix String Prefix to assign to the tables
	 * @param $oldTablePrefix String Prefix on current tables, if not $wgDBprefix
	 */
	public function __construct( DatabaseBase $db, array $tablesToClone,
		$newTablePrefix = 'parsertest', $oldTablePrefix = '', $dropCurrentTables = true )
	{
		$this->db = $db;
		$this->tablesToClone = $tablesToClone;
		$this->newTablePrefix = $newTablePrefix;
		$this->oldTablePrefix = $oldTablePrefix ? $oldTablePrefix : $this->db->tablePrefix();
		$this->dropCurrentTables = $dropCurrentTables;
	}

	/**
	 * Set whether to use temporary tables or not
	 * @param $u Bool Use temporary tables when cloning the structure
	 */
	public function useTemporaryTables( $u = true ) {
		$this->useTemporaryTables = $u;
	}

	/**
	 * Clone the table structure
	 */
	public function cloneTableStructure() {
		
		foreach( $this->tablesToClone as $tbl ) {
			# Clean up from previous aborted run.  So that table escaping
			# works correctly across DB engines, we need to change the pre-
			# fix back and forth so tableName() works right.
			
			$this->changePrefix( $this->oldTablePrefix );
			$oldTableName = $this->db->tableName( $tbl );
			
			$this->changePrefix( $this->newTablePrefix );
			$newTableName = $this->db->tableName( $tbl );
			
			if( $this->dropCurrentTables && !in_array( $this->db->getType(), array( 'postgres') ) ) {
				$this->db->dropTable( $oldTableName, __METHOD__ );
				//Dropping the oldTable because the prefix was changed
			}

			# Create new table
			wfDebug( "Duplicating $oldTableName to $newTableName\n", __METHOD__ );
			$this->db->duplicateTableStructure( $oldTableName, $newTableName, $this->useTemporaryTables );
			
		}
		
	}

	/**
	 * Change the prefix back to the original.
	 * @param $dropTables bool Optionally drop the tables we created
	 */
	public function destroy( $dropTables = false ) {
		if( $dropTables ) {
			$this->changePrefix( $this->newTablePrefix );
			foreach( $this->tablesToClone as $tbl ) {
				$this->db->dropTable( $tbl );
			}
		}
		$this->changePrefix( $this->oldTablePrefix );
	}

	/**
	 * Change the table prefix on all open DB connections/
	 *
	 * @param  $prefix
	 * @return void
	 */
	protected function changePrefix( $prefix ) {
		global $wgDBprefix;
		wfGetLBFactory()->forEachLB( array( $this, 'changeLBPrefix' ), array( $prefix ) );
		$wgDBprefix = $prefix;
	}

	/**
	 * @param  $lb LoadBalancer
	 * @param  $prefix
	 * @return void
	 */
	public function changeLBPrefix( $lb, $prefix ) {
		$lb->forEachOpenConnection( array( $this, 'changeDBPrefix' ), array( $prefix ) );
	}

	/**
	 * @param  $db DatabaseBase
	 * @param  $prefix
	 * @return void
	 */
	public function changeDBPrefix( $db, $prefix ) {
		$db->tablePrefix( $prefix );
	}
}
