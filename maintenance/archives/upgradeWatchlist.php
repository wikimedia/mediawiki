<?
# Convert watchlists to new format

global $IP;
include_once( "../LocalSettings.php" );
include_once( "$IP/Setup.php" );

$wgTitle = Title::newFromText( "Rebuild links script" );
set_time_limit(0);

$wgDBuser			= "wikiadmin";
$wgDBpassword		= $wgDBadminpassword;

$sql = "DROP TABLE IF EXISTS watchlist";
wfQuery( $sql );
$sql = "CREATE TABLE watchlist (
  wl_user int(5) unsigned NOT NULL,
  wl_page int(8) unsigned NOT NULL,
  UNIQUE KEY (wl_user, wl_page)
) TYPE=MyISAM PACK_KEYS=1";
wfQuery( $sql );

$lc = new LinkCache;

# Now, convert!
$sql = "SELECT user_id,user_watch FROM user";
$res = wfQuery( $sql );
$nu = wfNumRows( $res );
$sql = "INSERT into watchlist (wl_user,wl_page) VALUES ";
$i = $n = 0;
while( $row = wfFetchObject( $res ) ) {
	$list = explode( "\n", $row->user_watch );
	$bits = array();
	foreach( $list as $title ) {
		if( $id = $lc->addLink( $title ) and ! $bits[$id]++) {
			$sql .= ($i++ ? "," : "") . "({$row->user_id},{$id})";
		}
	}
	if( ($n++ % 100) == 0 ) echo "$n of $nu users done...\n";
}
echo "$n users done.\n";
if( $i ) {
	wfQuery( $sql );
}


# Add index
# is this necessary?
$sql = "ALTER TABLE watchlist
  ADD INDEX wl_user (wl_user),
  ADD INDEX wl_page (wl_page)";
#wfQuery( $sql );

?>
