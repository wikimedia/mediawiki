<?php

require_once( "commandLine.inc" );
# print "DB name: $wgDBname\n";
# print "DB user: $wgDBuser\n";
# print "DB password: $wgDBpassword\n";

print "This is an example command-line maintenance script.\n";

$dbr =& wfGetDB( DB_SLAVE );
$cur = $dbr->tableName( 'cur' );
$res = $dbr->query( "SELECT MAX(cur_id) as m FROM $cur" );
$row = $dbr->fetchObject( $res );
print "Max cur_id: {$row->m}\n";

?>
