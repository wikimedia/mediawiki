<?php

require_once "commandLine.inc";

// @fixme: add replication-friendly batch mode

$dbw = wfGetDB( DB_MASTER );
$user = $dbw->tableName( 'user' );
$revision = $dbw->tableName( 'revision' );

$dbver = $dbw->getServerVersion();
if( ($dbw instanceof DatabaseMySql && version_compare( $dbver, '4.1' ) < 0)
	|| isset( $options['force-mysql4'] ) ) {
	
	echo "Warning: MySQL $dbver; using hacky MySQL 4.0 compatibility query...\n";
	$sql = "CREATE TEMPORARY TABLE temp_editcount (
	  temp_user_id INT,
	  temp_user_editcount INT
	)";
	$dbw->query( $sql );
	
	$sql = "INSERT INTO temp_editcount
	  (temp_user_id, temp_user_editcount)
	  SELECT rev_user, COUNT(rev_user)
	    FROM $revision GROUP BY rev_user";
	$dbw->query( $sql );

	$sql = "UPDATE $user
	  LEFT OUTER JOIN temp_editcount ON user_id=temp_user_id
	  SET user_editcount=IF(temp_user_editcount IS NULL,0,temp_user_editcount)";
	$dbw->query( $sql );
} else {
	// Subselect should work on modern MySQLs etc
	$sql = "UPDATE $user SET user_editcount=(SELECT COUNT(*) FROM $revision WHERE rev_user=user_id)";
	$dbw->query( $sql );
}

echo "Done!\n";

?>
