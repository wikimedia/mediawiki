<?php
# Move "custom messages" from the MediaWiki namespace to the Template namespace
# Usage: php moveCustomMessages.php [<lang>] [skipredir]


chdir( ".." );
include_once( "commandLine.inc" );

if ( @$argv[2] == "1" ) {
	$doRedirects = true;
	$doMove = false;
} elseif ( @$argv[2] == "2" ) {	
	$doRedirects = false;
	$doMove = true;
} else {
	$doRedirects = true;
	$doMove = true;
}

$wgUser = User::newFromName( "Template namespace initialisation script" );

# Compose DB key array
global $wgAllMessagesEn;
$dbkeys = array();

foreach ( $wgAllMessagesEn as $key => $enValue ) {
	$title = Title::newFromText( $key );
	$dbkeys[$title->getDBkey()] = 1;
}

$sql = "SELECT cur_id, cur_title FROM cur WHERE cur_namespace= " . NS_MEDIAWIKI;
$res = wfQuery( $sql, DB_READ );

# Compile target array
$targets = array();
while ( $row = wfFetchObject( $res ) ) {
	if ( !array_key_exists( $row->cur_title, $dbkeys ) ) {
		$targets[] = $row->cur_title;
	}
}
wfFreeResult( $res );

# Create redirects from destination to source
if ( $doRedirects ) {
	foreach ( $targets as $partial ) {
		print "$partial...";
		$nt = Title::makeTitle( NS_TEMPLATE, $partial );
		$ot = Title::makeTitle( NS_MEDIAWIKI, $partial );

		if ( $nt->createRedirect( $ot, "" ) ) {
			print "redirected\n";
		} else {
			print "not redirected\n";
		}
	}
	if ( $doMove ) {
		print "\nRedirects created. Update live script files now.\nPress ENTER to continue.\n\n";
		readconsole();
	}
}

# Move pages
if ( $doMove ) {
	foreach ( $targets as $partial ) {
		$ot = Title::makeTitle( NS_MEDIAWIKI, $partial );
		$nt = Title::makeTitle( NS_TEMPLATE, $partial );
		print "$partial...";

		if ( $ot->moveNoAuth( $nt ) === true ) {
			print "moved\n";
		} else {
			print "not moved\n";
		}
		# Do deferred updates
		while ( count( $wgDeferredUpdateList ) ) {
			$up = array_pop( $wgDeferredUpdateList );
			$up->doUpdate();
		}
	}
}

?>
