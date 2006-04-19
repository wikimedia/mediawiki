<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$options = array( 'update' => null, 'rebuild' => null );
require_once( "commandLine.inc" );
include_once( "InitialiseMessages.inc" );

$wgTitle = Title::newFromText( "Rebuild messages script" );

if ( isset( $args[0] ) ) {
	# Retain script compatibility
	$response = array_shift( $args );
	if ( $response == "update" ) {
		$response = 1;
	} elseif ( $response == "rebuild" ) {
		$response = 2;
	}
} else {
	$response = 0;
}
if ( isset( $args[0] ) ) {
	$messages = loadLanguageFile( array_shift( $args ) );
} else {
	$messages = false;
}
if( isset( $options['update'] ) ) $response = 1;
if( isset( $options['rebuild'] ) ) $response = 2;

if ( $response == 0 ) {
	$dbr =& wfGetDB( DB_SLAVE );
	$row = $dbr->selectRow( "page", array("count(*) as c"), array("page_namespace" => NS_MEDIAWIKI) );
	print "Current namespace size: {$row->c}\n";

	print <<<END
Usage:   php rebuildMessages.php <action> [filename]

Action must be one of:
  --update   Update messages to include latest additions to MessagesXX.php
  --rebuild  Delete all messages and reinitialise namespace

If a message dump file is given, messages will be read from it to supplement
the defaults in MediaWiki's Language*.php. The file should contain a serialized
PHP associative array, as produced by dumpMessages.php.


END;
	exit(0);
}

switch ( $response ) {
	case 1:
		initialiseMessages( false, $messages );
		break;
	case 2:
		initialiseMessages( true, $messages );
		break;
}

exit();

?>
