<?php
require_once( "liveCmdLine.inc" );
print "DB name: $wgDBname\n";
print "DB user: $wgDBuser\n";
print "DB password: $wgDBpassword\n";

$res = wfQuery( "SELECT MAX(cur_id) as m FROM cur", DB_READ );
$row = wfFetchObject( $res );
print "Max cur_id: {$row->m}\n";

?>
