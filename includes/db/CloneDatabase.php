<?php
/**
 * Helper class for making a copy of the database, mostly for unit testing.
 *
 * Copyright © 2010 Chad Horohoe <chad@anyonecanedit.org>
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
 * @ingroup Database
 */

class CloneDatabase {
	/** @var string Table prefix for cloning */
	private $newTablePrefix = '';

	/** @var string Current table prefix */
	private $oldTablePrefix = '';

	/** @var array List of tables to be cloned */
	private $tablesToClone = array();

	/** @var bool Should we DROP tables containing the new names? */
	private $dropCurrentTables = true;

	/** @var bool Whether to use temporary tables or not */
	private $useTemporaryTables = true;

	/**
	 * Constructor
	 *
	 * @param DatabaseBase $db A database subclass
	 * @param array $tablesToClone An array of tables to clone, unprefixed
	 * @param string $newTablePrefix Prefix to assign to the tables
	 * @param string $oldTablePrefix Prefix on current tables, if not $wgDBprefix
	 * @param bool $dropCurrentTables
	 */
	public function __construct( DatabaseBase $db, array $tablesToClone,
		$newTablePrefix, $oldTablePrefix = '', $dropCurrentTables = true
	) {
		$this->db = $db;
		$this->tablesToClone = $tablesToClone;
		$this->newTablePrefix = $newTablePrefix;
		$this->oldTablePrefix = $oldTablePrefix ? $oldTablePrefix : $this->db->tablePrefix();
		$this->dropCurrentTables = $dropCurrentTables;
	}

	/**
	 * Set whether to use temporary tables or not
	 * @param bool $u Use temporary tables when cloning the structure
	 */
	public function useTemporaryTables( $u = true ) {
		$this->useTemporaryTables = $u;
	}

	/**
	 * Clone the table structure
	 */
	public function cloneTableStructure() {
		foreach ( $this->tablesToClone as $tbl ) {
			# Clean up from previous aborted run.  So that table escaping
			# works correctly across DB engines, we need to change the pre-
			# fix back and forth so tableName() works right.

			self::changePrefix( $this->oldTablePrefix );
			$oldTableName = $this->db->tableName( $tbl, 'raw' );

			self::changePrefix( $this->newTablePrefix );
			$newTableName = $this->db->tableName( $tbl, 'raw' );

			if ( $this->dropCurrentTables
				&& !in_array( $this->db->getType(), array( 'postgres', 'oracle' ) )
			) {
				$this->db->dropTable( $tbl, __METHOD__ );
				wfDebug( __METHOD__ . " dropping {$newTableName}\n" );
				//Dropping the oldTable because the prefix was changed
			}

			# Create new table
			wfDebug( __METHOD__ . " duplicating $oldTableName to $newTableName\n" );
			$this->db->duplicateTableStructure( $oldTableName, $newTableName, $this->useTemporaryTables );
		}
	}

	/**
	 * Change the prefix back to the original.
	 * @param bool $dropTables Optionally drop the tables we created
	 */
	public function destroy( $dropTables = false ) {
		if ( $dropTables ) {
			self::changePrefix( $this->newTablePrefix );
			foreach ( $this->tablesToClone as $tbl ) {
				$this->db->dropTable( $tbl );
			}
		}
		self::changePrefix( $this->oldTablePrefix );
	}

	/**
	 * Change the table prefix on all open DB connections/
	 *
	 * @param string $prefix
	 * @return void
	 */
	public static function changePrefix( $prefix ) {
		global $wgDBprefix;
		wfGetLBFactory()->forEachLB( array( 'CloneDatabase', 'changeLBPrefix' ), array( $prefix ) );
		$wgDBprefix = $prefix;
	}

	/**
	 * @param LoadBalancer $lb
	 * @param string $prefix
	 * @return void
	 */
	public static function changeLBPrefix( $lb, $prefix ) {
		$lb->forEachOpenConnection( array( 'CloneDatabase', 'changeDBPrefix' ), array( $prefix ) );
	}

	/**
	 * @param DatabaseBase $db
	 * @param string $prefix
	 * @return void
	 */
	public static function changeDBPrefix( $db, $prefix ) {
		$db->tablePrefix( $prefix );
	}
}
