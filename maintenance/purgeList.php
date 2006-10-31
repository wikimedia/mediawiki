<?php

require_once( "commandLine.inc" );

$stdin = fopen( "php://stdin", "rt" );
$urls = array();

while( !feof( $stdin ) ) {
	$page = trim( fgets( $stdin ) );
	if( $page !== '' ) {
		$title = Title::newFromText( $page );
		if( $title ) {
			$url = $title->getFullUrl();
			echo "$url\n";
			$urls[] = $url;
		} else {
			echo "(Invalid title '$page')\n";
		}
	}
}

echo "Purging " . count( $urls ) . " urls...\n";
$u = new SquidUpdate( $urls );
$u->doUpdate();

echo "Done!\n";

?>