<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
define( "REPORTING_INTERVAL", 50 );

function refreshLinks( $start, $newOnly = false, $maxLag = false ) {
	global $wgUser, $wgLoadBalancer;

	$fname = 'refreshLinks';
	$dbr =& wfGetDB( DB_SLAVE );
	$dbw =& wfGetDB( DB_MASTER );
	$start = intval( $start );
	
	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption("math", 3);

	if ( $newOnly ) {
		print "Refreshing links from ";
		$res = $dbr->select( 'cur', array( 'cur_id' ), 
		  array( 'cur_is_new' => 1, "cur_id > $start" ), $fname );
		$num = $dbr->numRows( $res );
		print "$num new articles...\n";
		
		$i = 0;
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !( $i++ % REPORTING_INTERVAL ) ) {
				print "$i\n";
			}
			waitForSlaves( $maxLag );

			fixLinksFromArticle( $row->cur_id );
		}
	} else {
		print "Refreshing link table.\n";
		$end = $dbr->selectField( 'cur', 'max(cur_id)', false );
		print("Starting from cur_id $start of $end.\n");

		for ($id = $start; $id <= $end; $id++) {
			
			if ( !($id % REPORTING_INTERVAL) ) {
				print "$id\n";
			}
			waitForSlaves( $maxLag );

			fixLinksFromArticle( $id );
		}
		

	}
}

function waitForSlaves( $maxLag ) {
	if ( $maxLag ) {
		list( $host, $lag ) = $wgLoadBalancer->getMaxLag();
		while ( $lag > $maxLag ) {
			print "Waiting for $host (lagged $lag seconds)...\n";
			list( $host, $lag ) = $wgLoadBalancer->getMaxLag();
		}
	}
}

function fixLinksFromArticle( $id ) {
	global $wgUser, $wgTitle, $wgArticle, $wgEnablePersistentLC, $wgLinkCache, $wgOut;
	
	$wgTitle = Title::newFromID( $id );
	$dbw =& wfGetDB( DB_MASTER );
	
	if ( is_null( $wgTitle ) ) {
		continue;
	}
	$dbw->query("BEGIN");

	$wgArticle = new Article( $wgTitle );
	$text = $wgArticle->getContent( true );
	$wgLinkCache = new LinkCache;
	$wgLinkCache->forUpdate( true );
	
	global $wgLinkHolders;
	$wgLinkHolders = array(
		'namespaces' => array(),
		'dbkeys' => array(),
		'queries' => array(),
		'texts' => array(),
		'titles' => array()
	);


	# Parse the text and replace links with placeholders
	$wgOut->addWikiText( $text );
	
	# Look up the links in the DB and add them to the link cache
	$wgOut->transformBuffer( RLH_FOR_UPDATE );
	$wgOut->clearHTML();

	if ( $wgEnablePersistentLC ) {
		$wgLinkCache->saveToLinkscc( $id, $dbw->strencode( $wgTitle->getPrefixedDBkey() ) );
	}
	$linksUpdate = new LinksUpdate( $id, $wgTitle->getPrefixedDBkey() );
	$linksUpdate->doDumbUpdate();
	$linksUpdate->fixBrokenLinks();
	$dbw->query("COMMIT");
}

function deleteLinksFromNonexistent() {
	$fname = 'deleteLinksFromNonexistent';
	
	$dbw =& wfGetDB( DB_WRITE );
	
	$linksTables = array( 
		'links' => 'l_from',
		'brokenlinks' => 'bl_from',
		'imagelinks' => 'il_from',
		'categorylinks' => 'cl_from',
	);

	$cur = $dbw->tableName( 'cur' );

	foreach ( $linksTables as $table => $field ) {
		if ( !$dbw->ping() ) {
			print "DB disconnected, reconnecting...";
			while ( !$dbw->ping() ) {
				print ".";
				sleep(10);
			}
			print "\n";
		}

		$pTable = $dbw->tableName( $table );
		$sql = "DELETE $pTable FROM $pTable LEFT JOIN $cur ON cur_id=$field WHERE cur_id IS NULL";
		
		print "Deleting $table from non-existent articles...";
		$dbw->query( $sql, $fname );
		print " fixed " .$dbw->affectedRows() . " row(s)\n";
	}
}

?>
