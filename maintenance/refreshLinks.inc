<?php
/**
 * @todo document
 * @addtogroup Maintenance
 */

/** */
define( "REPORTING_INTERVAL", 100 );
#define( "REPORTING_INTERVAL", 1 );

function refreshLinks( $start, $newOnly = false, $maxLag = false, $end = 0, $redirectsOnly = false ) {
	global $wgUser, $wgParser, $wgUseImageResize, $wgUseTidy;

	$fname = 'refreshLinks';
	$dbr = wfGetDB( DB_SLAVE );
	$start = intval( $start );

	# Don't generate TeX PNGs (lack of a sensible current directory causes errors anyway)
	$wgUser->setOption('math', MW_MATH_SOURCE);

	# Don't generate extension images (e.g. Timeline)
	$wgParser->mTagHooks = array();

	# Don't generate thumbnail images
	$wgUseImageResize = false;
	$wgUseTidy = false;

	$what = ($redirectsOnly)? "redirects" : "links";

	if ( $newOnly ) {
		print "Refreshing $what from ";
		$res = $dbr->select( 'page',
			array( 'page_id' ),
			array(
				'page_is_new' => 1,
				"page_id > $start" ),
			$fname
		);
		$num = $dbr->numRows( $res );
		print "$num new articles...\n";

		$i = 0;
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !( ++$i % REPORTING_INTERVAL ) ) {
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

			if ( !($id % REPORTING_INTERVAL) ) {
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

function deleteLinksFromNonexistent( $maxLag = 0 ) {
	$fname = 'deleteLinksFromNonexistent';

	wfWaitForSlaves( $maxLag );

	$dbw = wfGetDB( DB_WRITE );

	$linksTables = array(
		'pagelinks' => 'pl_from',
		'imagelinks' => 'il_from',
		'categorylinks' => 'cl_from',
		'templatelinks' => 'tl_from',
		'externallinks' => 'el_from',
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
		$sql = "DELETE $pTable FROM $pTable LEFT JOIN $page ON page_id=$field WHERE page_id IS NULL";

		print "Deleting $table from non-existent articles...";
		$dbw->query( $sql, $fname );
		print " fixed " .$dbw->affectedRows() . " row(s)\n";
	}
}

?>
