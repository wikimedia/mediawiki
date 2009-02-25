<?php

$optionsWithArgs = array( 'fake-job' );

require( dirname(__FILE__).'/../commandLine.inc' );
require( dirname(__FILE__).'/gearman.inc' );

if ( !$args ) {
	$args = array( 'localhost' );
}
$client = new Net_Gearman_Client( $args );

$dbr = wfGetDB( DB_SLAVE );
$res = $dbr->select( 'page', array( 'page_namespace', 'page_title' ), false,
	__METHOD__, array( 'LIMIT' => 2 ) );
foreach ( $res as $row ) {
	$title = Title::makeTitle( $row->page_namespace, $row->page_title );
	$params = array(
		'wiki' => wfWikiID(),
		'title' => $title->getPrefixedDBkey(),
		'command' => 'refreshLinks',
		'params' => false,
	);
	$client->mw_job( $params );
}

