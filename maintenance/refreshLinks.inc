<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
define( "REPORTING_INTERVAL", 100 );

function refreshLinks( $start, $newOnly = false, $maxLag = false ) {
	global $wgUser, $wgParser, $wgUseImageResize;

	$fname = 'refreshLinks';
	$dbr =& wfGetDB( DB_SLAVE );
	$dbw =& wfGetDB( DB_MASTER );
	$start = intval( $start );
	
	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption("math", 3);

	# Don't generate extension images (e.g. Timeline)
	$wgParser->mTagHooks = array();
	
	# Don't generate thumbnail images
	$wgUseImageResize = false;

	if ( $newOnly ) {
		print "Refreshing links from ";
		$res = $dbr->select( 'page', array( 'page_id' ), 
		  array( 'page_is_new' => 1, "page_id > $start" ), $fname );
		$num = $dbr->numRows( $res );
		print "$num new articles...\n";
		
		$i = 0;
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !( ++$i % REPORTING_INTERVAL ) ) {
				print "$i\n";
				wfWaitForSlaves( $maxLag );
			}

			fixLinksFromArticle( $row->page_id );
		}
	} else {
		print "Refreshing link table.\n";
		$end = $dbr->selectField( 'page', 'max(page_id)', false );
		print("Starting from page_id $start of $end.\n");

		for ($id = $start; $id <= $end; $id++) {
			
			if ( !($id % REPORTING_INTERVAL) ) {
				print "$id\n";
				wfWaitForSlaves( $maxLag );
			}
			fixLinksFromArticle( $id );
		}
		

	}
}

function fixLinksFromArticle( $id ) {
	global $wgTitle, $wgArticle, $wgLinkCache, $wgOut;
	
	$wgTitle = Title::newFromID( $id );
	$dbw =& wfGetDB( DB_MASTER );
	
	if ( is_null( $wgTitle ) ) {
		return;
	}
	$dbw->begin();

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
	$wgOut->transformBuffer();
	$wgOut->clearHTML();

	$linksUpdate = new LinksUpdate( $id, $wgTitle->getPrefixedDBkey() );
	$linksUpdate->doDumbUpdate();
	$dbw->immediateCommit();
}

function deleteLinksFromNonexistent( $maxLag = 0 ) {
	$fname = 'deleteLinksFromNonexistent';
	
	wfWaitForSlaves( $maxLag );

	$dbw =& wfGetDB( DB_WRITE );
	
	$linksTables = array( 
		'pagelinks' => 'pl_from',
		'imagelinks' => 'il_from',
		'categorylinks' => 'cl_from',
	);

	$page = $dbw->tableName( 'page' );


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
		global $wgDBmysql4, $wgDBtype;
		if( $wgDBmysql4 || $wgDBtype != 'mysql' ) {
			$sql = "DELETE $pTable FROM $pTable LEFT JOIN $page ON page_id=$field WHERE page_id IS NULL";
		} else {
			# Hack-around for MySQL 3.x, which lacks support
			# for multi-table deletes.
			
			$sql = "SELECT DISTINCT $field AS id FROM $pTable LEFT JOIN $page ON page_id=$field WHERE page_id IS NULL";
			echo "Looking in $table from non-existent articles...";
			$result = $dbw->query( $sql );
			$ids = array();
			while( $row = $dbw->fetchObject( $result ) ) {
				$ids[] = $row->id;
			}
			$dbw->freeResult( $result );
			
			if( empty( $ids ) ) {
				echo " none.\n";
				continue;
			}
			echo " found.\n";
			$sql = "DELETE FROM $pTable WHERE $field IN (" . implode( ",", $ids ) . ")";
		}
		
		print "Deleting $table from non-existent articles...";
		$dbw->query( $sql, $fname );
		print " fixed " .$dbw->affectedRows() . " row(s)\n";
	}
}

?>
