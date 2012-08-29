<?php
/**
 * Run a database query in batches and wait for slaves. This is used on large
 * wikis to prevent replication lag from going through the roof when executing
 * large write queries.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to run a database query in batches and wait for slaves.
 *
 * @ingroup Maintenance
 */
class BatchedQueryRunner extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run a query repeatedly until it affects 0 rows, and wait for slaves in between.\n" .
				"NOTE: You need to set a LIMIT clause yourself.";
	}

	public function execute() {
		if ( !$this->hasArg() )
			$this->error( "No query specified. Specify the query as a command line parameter.", true );

		$query = $this->getArg();
		$n = 1;
		$dbw = wfGetDB( DB_MASTER );
		do {
			$this->output( "Batch $n: " );
			$n++;
			$dbw->query( $query, __METHOD__ );
			$affected = $dbw->affectedRows();
			$this->output( "$affected rows\n" );
			wfWaitForSlaves();
		} while ( $affected > 0 );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}


$maintClass = "BatchedQueryRunner";
require_once( RUN_MAINTENANCE_IF_MAIN );
