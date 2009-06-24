<?php

require "commandLine.inc";

echo "Fetching redirects...\n";
$dbr = wfGetDB( DB_SLAVE );
$result = $dbr->select(
	array( 'page' ),
	array( 'page_namespace','page_title', 'page_latest' ),
	array( 'page_is_redirect' => 1 ) );

$count = $result->numRows();
echo "Found $count total redirects.\n";
echo "Looking for bad redirects:\n";
echo "\n";

foreach( $result as $row ) {
	$title = Title::makeTitle( $row->page_namespace, $row->page_title );
	$rev = Revision::newFromId( $row->page_latest );
	if( $rev ) {
		$target = Title::newFromRedirect( $rev->getText() );
		if( !$target ) {
			echo $title->getPrefixedText();
			echo "\n";
		}
	}
}

echo "\n";
echo "done.\n";
