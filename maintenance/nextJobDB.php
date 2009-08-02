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
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

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
		if( !$pendingDBs ) {
			$pendingDBs = $this->getPendingDbs( $type );
			$wgMemc->get( $mckey, $pendingDBs, 300 );
		}
		# If we've got a pending job in a db, display it. 
		if ( $pendingDBs ) {
			$this->output( $pendingDBs[mt_rand(0, count( $pendingDBs ) - 1)] );
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
			$dbsByMaster[$lb->getServerName(0)][] = $db;
		}
	
		foreach ( $dbsByMaster as $master => $dbs ) {
			$dbConn = wfGetDB( DB_MASTER, array(), $dbs[0] );
			$stype = $dbConn->addQuotes($type);
			$jobTable = $dbConn->getTable( 'job' );
	
			# Padding row for MySQL bug
			$sql = "(SELECT '-------------------------------------------')";
			foreach ( $dbs as $dbName ) {
				if ( $sql != '' ) {
					$sql .= ' UNION ';
				}
				if ($type === false)
					$sql .= "(SELECT '$dbName' FROM `$dbName`.$jobTable LIMIT 1)";
				else
					$sql .= "(SELECT '$dbName' FROM `$dbName`.$jobTable WHERE job_cmd=$stype LIMIT 1)";
			}
			$res = $dbConn->query( $sql, __METHOD__ );
			$row = $dbConn->fetchRow( $res ); // discard padding row
			while ( $row = $dbConn->fetchRow( $res ) ) {
				$pendingDBs[] = $row[0];
			}
		}
	}
}

$maintClass = "nextJobDb";
require_once( DO_MAINTENANCE );
