<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );
# print "DB name: $wgDBname\n";
# print "DB user: $wgDBuser\n";
# print "DB password: $wgDBpassword\n";

print "This is an example command-line maintenance script.\n";

$dbr =& wfGetDB( DB_SLAVE );
$page = $dbr->tableName( 'page' );
$res = $dbr->query( "SELECT MAX(page_id) as m FROM $page" );
$row = $dbr->fetchObject( $res );
print "Max page_id: {$row->m}\n";

?>
