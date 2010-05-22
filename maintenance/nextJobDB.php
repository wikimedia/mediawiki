<?php
/**
 * Pick a database that has pending jobs
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
 * @todo Make this work on PostgreSQL and maybe other database servers
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class nextJobDB extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Pick a database that has pending jobs";
		$this->addOption( 'type', "The type of job to search for", false, true );
	}

	public function execute() {
		global $wgMemc;
		$type = $this->getOption( 'type', false );
		$mckey = $type === false
					? "jobqueue:dbs"
					: "jobqueue:dbs:$type";
		$pendingDBs = $wgMemc->get( $mckey );

		# If we didn't get it from the cache
		if ( !$pendingDBs ) {
			$pendingDBs = $this->getPendingDbs( $type );
			$wgMemc->get( $mckey, $pendingDBs, 300 );
		}
		# If we've got a pending job in a db, display it.
		if ( $pendingDBs ) {
			$this->output( $pendingDBs[mt_rand( 0, count( $pendingDBs ) - 1 )] );
		}
	}

	/**
	 * Get all databases that have a pending job
	 * @param $type String Job type
	 * @return array
	 */
	private function getPendingDbs( $type ) {
		global $wgLocalDatabases;
		$pendingDBs = array();
		# Cross-reference DBs by master DB server
		$dbsByMaster = array();
		foreach ( $wgLocalDatabases as $db ) {
			$lb = wfGetLB( $db );
			$dbsByMaster[$lb->getServerName( 0 )][] = $db;
		}

		foreach ( $dbsByMaster as $master => $dbs ) {
			$dbConn = wfGetDB( DB_MASTER, array(), $dbs[0] );
			$stype = $dbConn->addQuotes( $type );

			# Padding row for MySQL bug
			$sql = "(SELECT '-------------------------------------------' as db)";
			foreach ( $dbs as $wikiId ) {
				if ( $sql != '' ) {
					$sql .= ' UNION ';
				}

				list( $dbName, $tablePrefix ) = wfSplitWikiID( $wikiId );
				$dbConn->tablePrefix( $tablePrefix );
				$jobTable = $dbConn->tableName( 'job' );

				if ( $type === false )
					$sql .= "(SELECT '$wikiId' as db FROM $dbName.$jobTable LIMIT 1)";
				else
					$sql .= "(SELECT '$wikiId' as db FROM $dbName.$jobTable WHERE job_cmd=$stype LIMIT 1)";
			}
			$res = $dbConn->query( $sql, __METHOD__ );
			$first = true;
			foreach ( $res as $row ) {
				if ( $first ) {
					// discard padding row
					$first = false;
					continue;
				}
				$pendingDBs[] = $row->db;
			}
		}
		return $pendingDBs;
	}
}

$maintClass = "nextJobDb";
require_once( DO_MAINTENANCE );
