<?php

/**
 * Cleans page_restrictions table to delete rows where the pr_page
 * value doesn't match en actual page.
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
 * @author Sébastien Santoro aka Dereckson
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to clean page_restrictions table.
 *
 * @ingroup Maintenance
 */
class CleanupPageRestrictionsTable extends Maintenance {

	/**
	 * The table to clean up.
	 */
	const TABLE = 'page_restrictions';

	/**
	 * Initialize a new instance of
	 * the CleanupPageRestrictionsTable class.
	 */
	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Cleans page_restrictions table.' );
		$this->addOption( 'fix', 'Actually remove entries' );
	}

	///
	/// Main tasks
	///

	/**
	 * Execute the maintenance task.
	 */
	public function execute() {
		if ( $this->hasOption( 'fix' ) ) {
			$this->cleanUp();
		} else {
			$this->printReport();
		}
	}

	/**
	 * Clean up the table.
	 */
	private function cleanUp() {
		$dbw = $this->getDB( DB_MASTER );

		$dbw->delete( self::TABLE, [ 'pr_page' => 0 ], __METHOD__ );

		$count = $dbw->affectedRows();
		$this->printFinalResult( $count, "Deleted." );
	}

	/**
	 * Print a report of the maintenance task.
	 */
	private function printReport() {
		$count = $this->getRowsCount();
		$this->printFinalResult(
			$count,
			"To actually delete them, run this script with --fix.\n"
		);
	}

	///
	/// Helper methods
	///

	/**
	 * Print final maintenance task result.
	 *
	 * @param int $count The number of rows to deleteo
	 * @param string $status The task status or call for action
	 */
	private function printFinalResult( $count, $status ) {
		$this->output( "Found $count entries to delete.\n" );
		$this->output( $status );
	}

	/**
	 * Count the rows to delete.
	 *
	 * @return int
	 */
	public function getRowsCount() {
		$dbr = $this->getDB( DB_REPLICA );
		return $dbr->selectField(
			self::TABLE, 'COUNT(*)', [ 'pr_page' => 0 ], __METHOD__
		);
	}

}

$maintClass = "CleanupPageRestrictionsTable";
require_once RUN_MAINTENANCE_IF_MAIN;
