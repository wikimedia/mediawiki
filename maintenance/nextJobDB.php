<?php

$user = 'wikiuser';
$password = `/home/wikipedia/bin/wikiuser_pass`;
$availableDBs = array_map( 'trim', file( "/home/wikipedia/common/pmtpa.dblist" ) );
shuffle( $availableDBs );

$dbs = array();

# Connect to enwiki
mysql_connect( 'db4', $user, $password ) || myerror();
mysql_select_db( 'enwiki' ) || myerror();
( $res = mysql_query( 'SELECT 1 FROM job LIMIT 1' ) ) || myerror();
$enwikiHasJobs = ( mysql_num_rows( $res ) != 0 );
mysql_free_result( $res );
mysql_close();


# Now try the rest
mysql_connect( 'ixia', $user, $password ) || myerror();

$sql = "(SELECT '-------------------------------------------')";
foreach ( $availableDBs as $db ) {
	if ( $db == 'enwiki' ) {
		continue;
	}
	if ( $sql != '' ) {
		$sql .= ' UNION ';
	}
	$sql .= "(SELECT '$db' FROM `$db`.job)";
}
$sql .= ' LIMIT 1,1';
( $res = mysql_query( $sql ) ) || myerror();
$row = mysql_fetch_row( $res );
if ( $row ) {
	$db = $row[0];
} else {
	$db = false;
}

mysql_free_result( $res );
mysql_close();


if ( $enwikiHasJobs ) {
	if ( $db ) {
		# Choose enwiki with arbitrary constant probability
		if ( mt_rand( 0, 4 ) == 0 ) {
			$db = 'enwiki';
		}
	} else {
		$db = 'enwiki';
	}
}

if ( $db ) {
	echo $db;
}

function myerror() {
	$f = fopen( 'php://stderr', 'w' );
	fwrite( $f, mysql_error() . "\n" );
	exit(1);
}
?>
