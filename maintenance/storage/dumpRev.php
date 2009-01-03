<?php
/**
 * @file
 * @ingroup Maintenance ExternalStorage
 */

require_once( dirname(__FILE__) . '/../commandLine.inc' );

$wgDebugLogFile = '/dev/stdout';


$dbr = wfGetDB( DB_SLAVE );
$row = $dbr->selectRow( 
	array( 'text', 'revision' ), 
	array( 'old_flags', 'old_text' ), 
	array( 'old_id=rev_text_id', 'rev_id' => $args[0] )
);
if ( !$row ) {
	print "Row not found\n";
	exit;
}

$flags = explode( ',',  $row->old_flags );
$text = $row->old_text;
if ( in_array( 'external', $flags ) ) {
	print "External $text\n";
	if ( preg_match( '!^DB://(\w+)/(\w+)/(\w+)$!', $text, $m ) ) {
		$es = ExternalStore::getStoreObject( 'DB' );
		$blob = $es->fetchBlob( $m[1], $m[2], $m[3] );
		if ( strtolower( get_class( $blob ) ) == 'concatenatedgziphistoryblob' ) {
			print "Found external CGZ\n";
			$blob->uncompress();
			print "Items: (" . implode( ', ', array_keys( $blob->mItems ) ) . ")\n";
			$text = $blob->getItem( $m[3] );
		} else {
			print "CGZ expected at $text, got " . gettype( $blob ) . "\n";
			$text = $blob;
		}
	} else {
		print "External plain $text\n";
		$text = ExternalStore::fetchFromURL( $text );
	}
}
if ( in_array( 'gzip', $flags ) ) {
	$text = gzinflate( $text );
}
if ( in_array( 'object', $flags ) ) {
	$text = unserialize( $text );
}

if ( is_object( $text ) ) {
	print "Unexpectedly got object of type: " . get_class( $text ) .  "\n";
} else {
	print "Text length: " . strlen( $text ) ."\n";
	print substr( $text, 0, 100 ) . "\n";
}
