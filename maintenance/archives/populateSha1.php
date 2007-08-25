<?php

# Optional upgrade script to populate the img_sha1 field

$optionsWithArgs = array( 'method' );
require_once( dirname(__FILE__).'/../commandLine.inc' );
$method = isset( $args['method'] ) ? $args['method'] : 'normal';

$t = -microtime( true );
$fname = 'populateSha1.php';
$dbw = wfGetDB( DB_MASTER );
$res = $dbw->select( 'image', array( 'img_name' ), array( 'img_sha1' => '' ), $fname );
$imageTable = $dbw->tableName( 'image' );
$oldimageTable = $dbw->tableName( 'oldimage' );
$batch = array();

$cmd = 'mysql -u ' . wfEscapeShellArg( $wgDBuser ) . ' -p' . wfEscapeShellArg( $wgDBpassword, $wgDBname );
if ( $method == 'pipe' ) {
	$pipe = popen( $cmd, 'w' );
	fwrite( $pipe, "-- hello\n" );
}

foreach ( $res as $row ) {
	$file = wfLocalFile( $row->img_name );
	$sha1 = File::sha1Base36( $file->getPath() );
	if ( strval( $sha1 ) !== '' ) {
		$sql = "UPDATE $imageTable SET img_sha1=" . $dbw->addQuotes( $sha1 ) .
			" WHERE img_name=" . $dbw->addQuotes( $row->img_name );
		if ( $method == 'pipe' ) {
			fwrite( $pipe, $sql );
		} else {
			$dbw->query( $sql, $fname );
		}
	}
}
if ( $method == 'pipe' ) {
	fflush( $pipe );
	pclose( $pipe );
}
$t += microtime( true );
print "Done in $t seconds\n";

?>
