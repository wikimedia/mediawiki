<?php

define( "REPORTING_INTERVAL", 50 );
define( "PAUSE_INTERVAL", 50 );

function refreshLinks( $start ) {
	global $wgUser, $wgTitle, $wgArticle, $wgEnablePersistentLC, $wgLinkCache, $wgOut;

	$res = wfQuery("SELECT max(cur_id) as m FROM cur", DB_READ);
	$row = wfFetchObject( $res );
	$end = $row->m;

	print("Refreshing link table. Starting from cur_id $start of $end.\n");

	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption("math", 3);

	for ($id = $start; $id <= $end; $id++) {
		if ( !($id % REPORTING_INTERVAL) ) {
			print "$id\n";
		}

		if ( !($id % PAUSE_INTERVAL) ) {
			sleep(1);
		}
		
		$wgTitle = Title::newFromID( $id );
		if ( is_null( $wgTitle ) ) {
			continue;
		}
		
		$wgArticle = new Article( $wgTitle );
		$text = $wgArticle->getContent( true );
		$wgLinkCache = new LinkCache;
		$wgOut->addWikiText( $text );

		if ( $wgEnablePersistentLC ) {
			$wgLinkCache->saveToLinkscc( $id, wfStrencode( $wgTitle->getPrefixedDBkey() ) );
		}

		$linksUpdate = new LinksUpdate( $id, $wgTitle->getPrefixedDBkey() );
		$linksUpdate->doDumbUpdate();
		$linksUpdate->fixBrokenLinks();
	}
}
?>
