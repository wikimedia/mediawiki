<?php

# Remove spurious brokenlinks
require_once( "commandLine.inc" );
require_once( "./rebuildrecentchanges.inc" );
$wgTitle = Title::newFromText( "Rebuild brokenlinks script" );

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;


# That above is common code and should be hidden away :(

$n = 0;

echo "Checking for broken brokenlinks...\n";

$sql = "SELECT cur_namespace,cur_title,cur_id FROM cur";
$res = wfQuery( $sql, DB_WRITE );
while( $s = wfFetchObject( $res ) ) {
	$n++;
	if(($n % 500) == 0) {
		echo "$n\n";
	}
	$title = Title::makeTitle( $s->cur_namespace, $s->cur_title );
	if($title) {
		$t = $title->getPrefixedDBKey();
		$tt = wfStrencode( $t );
		$any = false;
		$sql2 = "SELECT bl_from,cur_id,cur_namespace,cur_title FROM brokenlinks,cur WHERE bl_to='$tt' AND cur_id=bl_from";
		$res2 = wfQuery( $sql2, DB_WRITE );
		while( $s = wfFetchObject( $res2 ) ) {
			$from = Title::makeTitle( $s->cur_namespace, $s->cur_title );
			$xt = $from->getPrefixedText();
			echo "Found bad brokenlink to [[$t]] from page #$s->cur_id [[$xt]]!\n";
			$any = true;
		}
		wfFreeResult( $res2 );
		if($any) {
			echo "Removing brokenlinks to [[$t]]...\n";
			$sql3 = "DELETE FROM brokenlinks WHERE bl_to='$tt'";
			$res3 = wfQuery( $sql3, DB_WRITE );
			#echo "-- $sql3\n";
		}
	} else {
		echo "Illegal title?! Namespace $s->cur_namespace, title '$s->cur_title'\n";
	}
}
echo "Done at $n.\n\n";

echo "Clearing linkscc table...\n";
$sql4 = "DELETE FROM linkscc";
wfQuery( $sql4, DB_WRITE );

?>
