<?php
define( "REPORTING_INTERVAL", 500 );

include_once( "commandLine.inc" );
error_reporting( E_ALL & (~E_NOTICE) );


if ($argv[2]) {
	$start = (int)$argv[2];
} else {
	$start = 1;
}

$res = wfQuery("SELECT max(cur_id) as m FROM cur", DB_READ);
$row = wfFetchObject( $res );
$end = $row->m;

print("Refreshing link table. Starting from cur_id $start of $end.\n");

$wgUser->setOption("math", 3);
for ($id = $start; $id <= $end; $id++) {
	if ( !($id % REPORTING_INTERVAL) ) {
		print "$id\n";
	}
	
	$wgTitle = Title::newFromID( $id );
	if ( is_null( $wgTitle ) ) {
		continue;
	}
	
	$wgLinkCache = new LinkCache;
	$wgArticle = new Article( $wgTitle );
	$text = $wgArticle->getContent( true );
	@$wgOut->addWikiText( $text );
	
	$linksUpdate = new LinksUpdate( $id, $wgTitle );
	$linksUpdate->doDumbUpdate();
}


exit();

?>
