<?

include_once ( "LogPage.php" ) ;

function wfSpecialWantedpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = "wfSpecialWantedpages";

	# Cache
	$vsp = $wgLang->getValidSpecialPages() ;
	$log = new LogPage( $vsp["Wantedpages"] );
	$log->mUpdateRecentChanges = false;

	$wgOut->setRobotpolicy( "noindex,nofollow" );
	global $wgMiserMode;
	if ( $wgMiserMode ) {
		$log->showAsDisabledPage();
		return;
	}

	list( $limit, $offset ) = wfCheckLimits();

	$cache = "" ; # To be saved, eventually

	$sql = "SELECT bl_to, COUNT( DISTINCT bl_from ) as nlinks " .
	  "FROM brokenlinks GROUP BY bl_to HAVING nlinks > 1 " .
	  "ORDER BY nlinks DESC LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, DB_READ, $fname );

	$sk = $wgUser->getSkin();

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialpage( "Wantedpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$nt = Title::newFromDBkey( $obj->bl_to );

		$plink = $sk->makeBrokenLink( $nt->getPrefixedText(), "" );
		$nl = wfMsg( "nlinks", $obj->nlinks );
		$nlink = $sk->makeKnownLink( $wgLang->specialPage(
		  "Whatlinkshere" ), $nl, "target=" . $nt->getPrefixedURL() );

		$cache .= "* [[".$nt->getPrefixedText()."]] ({$nl})\n" ;

		$s .= "<li>{$plink} ({$nlink})</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );

	# Saving cache
	if ( $offset > 0 OR $limit < 50 ) return ; #Not suitable
	$log->replaceContent( $s );
}

?>
