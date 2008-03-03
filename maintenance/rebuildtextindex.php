<?php
/**
 * Rebuild search index table from scratch.  This takes several
 * hours, depending on the database size and server configuration.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );
require_once( "rebuildtextindex.inc" );
$wgTitle = Title::newFromText( "Rebuild text index script" );

$database = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );

dropTextIndex( $database );
rebuildTextIndex( $database );
createTextIndex( $database );

print "Done.\n";
exit();

?>
