<?php

define( "REPORTING_INTERVAL", 50 );
define( "PAUSE_INTERVAL", 50 );

function refreshLinks( $start ) {
	global $wgUser, $wgTitle, $wgArticle, $wgEnablePersistentLC, $wgLinkCache, $wgOut;

	$dbw =& wfGetDB( DB_MASTER );
	
	$end = $dbw->selectField( 'cur', 'max(cur_id)', false );

	print("Refreshing link table. Starting from cur_id $start of $end.\n");

	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption("math", 3);
	
	# Turn on link cache in skin
	$sk =& $wgUser->getSkin();
	$sk->postParseLinkColour( false );

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
		$wgLinkCache->forUpdate( true );
		$wgOut->addWikiText( $text );

		if ( $wgEnablePersistentLC ) {
			$wgLinkCache->saveToLinkscc( $id, $dbw->strencode( $wgTitle->getPrefixedDBkey() ) );
		}

		$linksUpdate = new LinksUpdate( $id, $wgTitle->getPrefixedDBkey() );
		$linksUpdate->doDumbUpdate();
		$linksUpdate->fixBrokenLinks();
	}
}
?>
