<?php
# Move "custom messages" from the MediaWiki namespace to the Template namespace

chdir( ".." );
include_once( "commandLine.inc" );

# Compose DB key array
global $wgAllMessagesEn;
$dbkeys = array();

foreach ( $wgAllMessagesEn as $key => $enValue )
{
	$title = Title::newFromText( $key );
	$dbkeys[$title->getDBkey()] = 1;
}

$sql = "SELECT cur_id, cur_title FROM cur WHERE cur_namespace= " . NS_MEDIAWIKI;
$res = wfQuery( $sql, DB_READ );
$first = true;
while ( $row = wfFetchObject( $res ) ) {
	$partial = $row->cur_title;
	print "$partial...";
	if ( !array_key_exists( $partial, $dbkeys ) ) {
		$ot = Title::makeTitle( NS_MEDIAWIKI, $partial );
		$nt = Title::makeTitle( NS_TEMPLATE, $partial );
		if ( $ot->moveNoAuth( $nt ) === true ) {
			print "moved\n";
		} else {
			print "not moved\n";
		}
		# Clear deferred updates
		while ( count( $wgDeferredUpdateList ) ) {
			$up = array_pop( $wgDeferredUpdateList );
			$up->doUpdate();
		}
		$first = false;
	} else {
		print "internal\n";
	}
}
if ( $first ) {
	print "Nothing to move\n";
}

?>
