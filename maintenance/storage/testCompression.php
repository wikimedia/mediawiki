<?php

$optionsWithArgs = array( 'start', 'limit', 'type' );
require( dirname( __FILE__ ) . '/../commandLine.inc' );

if ( !isset( $args[0] )  ) {
	echo "Usage: php testCompression.php [--type=<type>] [--start=<start-date>] [--limit=<num-revs>] <page-title>\n";
	exit( 1 );
}

$title = Title::newFromText( $args[0] );
if ( isset( $options['start'] ) ) {
	$start = wfTimestamp( TS_MW, strtotime( $options['start'] ) );
	echo "Starting from " . $wgLang->timeanddate( $start ) . "\n";
} else {
	$start = '19700101000000';
}
if ( isset( $options['limit'] ) ) {
	$limit = $options['limit'];
	$untilHappy = false;
} else {
	$limit = 1000;
	$untilHappy = true;
}
$type = isset( $options['type'] ) ? $options['type'] : 'ConcatenatedGzipHistoryBlob';


$dbr = wfGetDB( DB_SLAVE );
$res = $dbr->select(
	array( 'page', 'revision', 'text' ),
	'*',
	array(
		'page_namespace' => $title->getNamespace(),
		'page_title' => $title->getDBkey(),
		'page_id=rev_page',
		'rev_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( $start ) ),
		'rev_text_id=old_id'
	), __FILE__, array( 'LIMIT' => $limit )
);

$blob = new $type;
$hashes = array();
$keys = array();
$uncompressedSize = 0;
$t = -microtime( true );
foreach ( $res as $row ) {
	$revision = new Revision( $row );
	$text = $revision->getText();
	$uncompressedSize += strlen( $text );
	$hashes[$row->rev_id] = md5( $text );
	$keys[$row->rev_id] = $blob->addItem( $text );
	if ( $untilHappy && !$blob->isHappy() ) {
		break;
	}
}

$serialized = serialize( $blob );
$t += microtime( true );
# print_r( $blob->mDiffMap );

printf( "%s\nCompression ratio for %d revisions: %5.2f, %s -> %d\n",
	$type,
	count( $hashes ),
	$uncompressedSize / strlen( $serialized ),
	$wgLang->formatSize( $uncompressedSize ),
	strlen( $serialized )
);
printf( "Compression time: %5.2f ms\n", $t * 1000 );

$t = -microtime( true );
$blob = unserialize( $serialized );
foreach ( $keys as $id => $key ) {
	$text = $blob->getItem( $key );
	if ( md5( $text ) != $hashes[$id] ) {
		echo "Content hash mismatch for rev_id $id\n";
		# var_dump( $text );
	}
}
$t += microtime( true );
printf( "Decompression time: %5.2f ms\n", $t * 1000 );

