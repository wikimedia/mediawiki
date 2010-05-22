<?php

$optionsWithArgs = array( 'fake-job' );

require( dirname( __FILE__ ) . '/../commandLine.inc' );
require( dirname( __FILE__ ) . '/gearman.inc' );

if ( !$args ) {
	$args = array( 'localhost' );
}
$client = new Net_Gearman_Client( $args );
$batchSize = 1000;

$dbr = wfGetDB( DB_SLAVE );
$startId = 0;
$endId = $dbr->selectField( 'page', 'MAX(page_id)', false, __METHOD__ );
while ( true ) {
	$res = $dbr->select(
		'page',
		array( 'page_namespace', 'page_title', 'page_id' ),
		array( 'page_id > ' . intval( $startId ) ),
		__METHOD__,
		array( 'LIMIT' => $batchSize )
	);

	if ( $res->numRows() == 0 ) {
		break;
	}
	$set = new Net_Gearman_Set;
	foreach ( $res as $row ) {
		$startId = $row->page_id;
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$params = array(
			'wiki' => wfWikiID(),
			'title' => $title->getPrefixedDBkey(),
			'command' => 'refreshLinks',
			'params' => false,
		);
		$task = new Net_Gearman_Task( 'mw_job', $params );
		$set->addTask( $task );
	}
	$client->runSet( $set );
	print "$startId / $endId\n";
}

