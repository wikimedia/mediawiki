<?php

/*
 * Pick a database that has pending jobs
 */

require_once( 'commandLine.inc' );

$pendingDBs = $wgMemc->get( 'jobqueue:dbs' );
if ( !$pendingDBs ) {
	$pendingDBs = array();
	# Cross-reference DBs by master DB server
	$dbsByMaster = array();
	$defaultMaster = $wgAlternateMaster['DEFAULT'];
	foreach ( $wgLocalDatabases as $db ) {
		if ( isset( $wgAlternateMaster[$db] ) ) {
			$dbsByMaster[$wgAlternateMaster[$db]][] = $db;
		} else {
			$dbsByMaster[$defaultMaster][] = $db;
		}
	}

	foreach ( $dbsByMaster as $master => $dbs ) {
		$dbConn = new Database( $master, $wgDBuser, $wgDBpassword );

		# Padding row for MySQL bug
		$sql = "(SELECT '-------------------------------------------')";
		foreach ( $dbs as $dbName ) {
			if ( $sql != '' ) {
				$sql .= ' UNION ';
			}
			$sql .= "(SELECT '$dbName' FROM `$dbName`.job LIMIT 1)";
		}
		$res = $dbConn->query( $sql, 'nextJobDB.php' );
		$row = $dbConn->fetchRow( $res ); // discard padding row
		while ( $row = $dbConn->fetchRow( $res ) ) {
			$pendingDBs[] = $row[0];
		}
	}

	$wgMemc->set( 'jobqueue:dbs', $pendingDBs, 300 );
}

if ( $pendingDBs ) {
	echo $pendingDBs[mt_rand(0, count( $pendingDBs ) - 1)];
}

?>
