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
	public function __construct( DatabaseBase $db, Array $tablesToClone, 
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
		$this->useTemporaryTables = false;
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

			if( $this->dropCurrentTables ) {
				if ( $this->db->getType() == 'mysql' && $this->db->tableExists( $tbl ) ) {
					$this->db->query( "DROP TABLE IF EXISTS $newTableName" );
				} elseif ( in_array( $this->db->getType(), array( 'postgres', 'oracle' ) ) ) {
					/* DROPs wouldn't work due to Foreign Key Constraints (bug 14990, r58669)
					 * Use "DROP TABLE IF EXISTS $newTableName CASCADE" for postgres? That
					 * syntax would also work for mysql.
					 */
				} elseif ( $this->db->tableExists( $tbl ) ) {
					$this->db->query( "DROP TABLE $newTableName" );
				}
			}

			# Create new table
			$this->db->duplicateTableStructure( $oldTableName, $newTableName, $this->useTemporaryTables );
		}
	}

	/**
	 * Change the table prefix on all open DB connections/
	 */
	protected function changePrefix( $prefix ) {
		global $wgDBprefix;
		wfGetLBFactory()->forEachLB( array( $this, 'changeLBPrefix' ), array( $prefix ) );
		$wgDBprefix = $prefix;
	}

	public function changeLBPrefix( $lb, $prefix ) {
		$lb->forEachOpenConnection( array( $this, 'changeDBPrefix' ), array( $prefix ) );
	}

	public function changeDBPrefix( $db, $prefix ) {
		$db->tablePrefix( $prefix );
	}
}
