<?php
/**
 * Remove spurious brokenlinks
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );
require_once( "./rebuildrecentchanges.inc" );
$wgTitle = Title::newFromText( "Rebuild brokenlinks script" );

$n = 0;

echo "Checking for broken brokenlinks...\n";

$dbw =& wfGetDB( DB_MASTER );
extract( $dbw->tableNames( 'brokenlinks', 'cur', 'linkscc' );

$res = $dbw->select( 'cur', array( 'cur_namespace', 'cur_title', 'cur_id' ), false );

while( $s = $dbw->fetchObject( $res ) ) {
	$n++;
	if(($n % 500) == 0) {
		echo "$n\n";
	}
	$title = Title::makeTitle( $s->cur_namespace, $s->cur_title );
	if($title) {
		$t = $title->getPrefixedDBKey();
		$tt = $dbw->strencode( $t );
		$any = false;
		$sql2 = "SELECT bl_from,cur_id,cur_namespace,cur_title FROM $brokenlinks,$cur " .
			"WHERE bl_to='$tt' AND cur_id=bl_from";
		$res2 = $dbw->query( $sql2 );
		while( $s = $dbw->fetchObject( $res2 ) ) {
			$from = Title::makeTitle( $s->cur_namespace, $s->cur_title );
			$xt = $from->getPrefixedText();
			echo "Found bad brokenlink to [[$t]] from page #$s->cur_id [[$xt]]!\n";
			$any = true;
		}
		$dbw->freeResult( $res2 );
		if($any) {
			echo "Removing brokenlinks to [[$t]]...\n";
			$sql3 = "DELETE FROM $brokenlinks WHERE bl_to='$tt'";
			$res3 = $dbw->query( $sql3 );
			#echo "-- $sql3\n";
		}
	} else {
		echo "Illegal title?! Namespace $s->cur_namespace, title '$s->cur_title'\n";
	}
}
echo "Done at $n.\n\n";

echo "Clearing linkscc table...\n";
$sql4 = "DELETE FROM $linkscc";
wfQuery( $sql4, DB_MASTER );

?>
