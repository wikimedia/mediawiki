<?php

require_once( 'commandLine.inc' );

$dbr =& wfGetDB( DB_SLAVE );

$edits = $dbr->selectField( 'revision', 'COUNT(rev_id)', '' );
$pages = $dbr->selectField( 'page', 'COUNT(page_id)',
	array(
		'page_namespace' => 0,
		'page_is_redirect' => 0,
		'page_len > 0',
	)
); // HACK APPROXIMATION

echo "$wgDBname: setting edits $edits, pages $pages\n";

$dbw =& wfGetDB( DB_MASTER );
$dbw->insert( 'site_stats',
	array( 'ss_row_id'=> 1,
	       'ss_total_views'   => 0,
	       'ss_total_edits'   => $edits,
	       'ss_good_articles' => $pages ) );

?>