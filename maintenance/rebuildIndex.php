<?

# Rebuild the fulltext search indexes. This may take a while
# depending on the database size and server configuration.

global $IP;
include_once( "../LocalSettings.php" );
include_once( "$IP/Setup.php" );
include_once( "$IP/SearchUpdate.php" );
set_time_limit(0);

$wgDBuser			= "wikiadmin";
$wgDBpassword		= $wgDBadminpassword;

# May run faster if you drop the index; but will break attempts to search
# while it's running if you're online.
#echo "Dropping index...\n";
##$sql = "ALTER TABLE searchindex DROP INDEX si_title, DROP INDEX si_text";
#$res = wfQuery($sql);

$sql = "SELECT COUNT(*) AS count FROM cur";
$res = wfQuery($sql);
$s = wfFetchObject($res);
echo "Rebuilding index fields for {$s->count} pages...\n";
$n = 0;

$sql = "SELECT cur_id, cur_namespace, cur_title, cur_text FROM cur";
$res = wfQuery($sql);
while( $s = wfFetchObject($res)) {
	$u = new SearchUpdate( $s->cur_id, $s->cur_title, $s->cur_text );
	$u->doUpdate();
	if ( ( (++$n) % 500) == 0) {
		echo "$n\n";
	}
}
wfFreeResult( $res );

#echo "Rebuild the index...\n";
##$sql = "ALTER TABLE searchindex ADD FULLTEXT si_title (si_title),
##  ADD FULLTEXT si_text (si_text)";
#$res = wfQuery($sql);

print "Done.\n";
exit();

?>
