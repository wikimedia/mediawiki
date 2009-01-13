<?php
/**
 * @todo document
 * @file
 * @ingroup Maintenance
 */

function refreshLinks( $start, $newOnly = false, $maxLag = false, $end = 0, $redirectsOnly = false, $oldRedirectsOnly = false ) {
	global $wgUser, $wgParser, $wgUseTidy;

	$reportingInterval = 100;
	$fname = 'refreshLinks';
	$dbr = wfGetDB( DB_SLAVE );
	$start = intval( $start );

	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption('math', MW_MATH_SOURCE);

	# Don't generate extension images (e.g. Timeline)
	if( method_exists( $wgParser, "clearTagHooks" ) ) {
		$wgParser->clearTagHooks();
	}

	# Don't use HTML tidy
	$wgUseTidy = false;

	$what = $redirectsOnly ? "redirects" : "links";

	if( $oldRedirectsOnly ) {
		# This entire code path is cut-and-pasted from below.  Hurrah.
		$res = $dbr->query(
			"SELECT page_id ".
			"FROM page ".
			"LEFT JOIN redirect ON page_id=rd_from ".
			"WHERE page_is_redirect=1 AND rd_from IS NULL AND ".
			($end == 0 ? "page_id >= $start"
			           : "page_id BETWEEN $start AND $end"),
			$fname
		);
		$num = $dbr->numRows( $res );
		print "Refreshing $num old redirects from $start...\n";

		while( $row = $dbr->fetchObject( $res ) ) {
			if ( !( ++$i % $reportingInterval ) ) {
				print "$i\n";
				wfWaitForSlaves( $maxLag );
			}
			fixRedirect( $row->page_id );
		}
	} elseif( $newOnly ) {
		print "Refreshing $what from ";
		$res = $dbr->select( 'page',
			array( 'page_id' ),
			array(
				'page_is_new' => 1,
				"page_id >= $start" ),
			$fname
		);
		$num = $dbr->numRows( $res );
		print "$num new articles...\n";

		$i = 0;
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !( ++$i % $reportingInterval ) ) {
				print "$i\n";
				wfWaitForSlaves( $maxLag );
			}
			if($redirectsOnly)
				fixRedirect( $row->page_id );
			else
				fixLinksFromArticle( $row->page_id );
		}
	} else {
		print "Refreshing $what table.\n";
		if ( !$end ) {
			$end = $dbr->selectField( 'page', 'max(page_id)', false );
		}
		print("Starting from page_id $start of $end.\n");

		for ($id = $start; $id <= $end; $id++) {

			if ( !($id % $reportingInterval) ) {
				print "$id\n";
				wfWaitForSlaves( $maxLag );
			}
			if($redirectsOnly)
				fixRedirect( $id );
			else
				fixLinksFromArticle( $id );
		}
	}
}

function fixRedirect( $id ){
	global $wgTitle, $wgArticle;

	$wgTitle = Title::newFromID( $id );
	$dbw = wfGetDB( DB_MASTER );

	if ( is_null( $wgTitle ) ) {
		return;
	}
	$wgArticle = new Article($wgTitle);

	$rt = $wgArticle->followRedirect();

	if($rt == false || !is_object($rt))
		return;

	$wgArticle->updateRedirectOn($dbw,$rt);
}

function fixLinksFromArticle( $id ) {
	global $wgTitle, $wgParser;

	$wgTitle = Title::newFromID( $id );
	$dbw = wfGetDB( DB_MASTER );

	$linkCache =& LinkCache::singleton();
	$linkCache->clear();

	if ( is_null( $wgTitle ) ) {
		return;
	}
	$dbw->begin();

	$revision = Revision::newFromTitle( $wgTitle );
	if ( !$revision ) {
		return;
	}

	$options = new ParserOptions;
	$parserOutput = $wgParser->parse( $revision->getText(), $wgTitle, $options, true, true, $revision->getId() );
	$update = new LinksUpdate( $wgTitle, $parserOutput, false );
	$update->doUpdate();
	$dbw->immediateCommit();
}

/*
 * Removes non-existing links from pages from pagelinks, imagelinks,
 * categorylinks, templatelinks and externallinks tables.
 *
 * @param $maxLag
 * @param $batchSize The size of deletion batches
 *
 * @author Merlijn van Deen <valhallasw@arctus.nl>
 */
function deleteLinksFromNonexistent( $maxLag = 0, $batchSize = 100 ) {
	wfWaitForSlaves( $maxLag );
	
	$dbw = wfGetDB( DB_MASTER );

	$lb = wfGetLBFactory()->newMainLB();
	$dbr = $lb->getConnection( DB_SLAVE );
	$dbr->bufferResults( false );
	
	$linksTables = array( // table name => page_id field
		'pagelinks' => 'pl_from',
		'imagelinks' => 'il_from',
		'categorylinks' => 'cl_from',
		'templatelinks' => 'tl_from',
		'externallinks' => 'el_from',
	);
	
	foreach ( $linksTables as $table => $field ) {
		print "Retrieving illegal entries from $table... ";
		
		// SELECT DISTINCT( $field ) FROM $table LEFT JOIN page ON $field=page_id WHERE page_id IS NULL;
		$results = $dbr->select( array( $table, 'page' ),
		              $field,
		              array('page_id' => null ),
		              __METHOD__,
		              'DISTINCT',
		              array( 'page' => array( 'LEFT JOIN', "$field=page_id"))
		);
		
		$counter = 0;
		$list = array();
		print "0..";
		
		foreach( $results as $row ) {
			$counter++;
			$list[] = $row->$field;
			if ( ( $counter % $batchSize ) == 0 ) {
				wfWaitForSlaves(5);
				$dbw->delete( $table, array( $field => $list ), __METHOD__ );
				
				print $counter . "..";
				$list = array();
			}
		}
		
		print $counter;
		if (count($list) > 0) {
			$dbw->delete( $table, array( $field => $list ), __METHOD__ );
		}
		
		print "\n";
	}
	
	$lb->closeAll();
}
