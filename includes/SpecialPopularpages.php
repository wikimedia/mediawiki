<?

function wfSpecialPopularpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = "wfSpecialPopularpages";

	global $wgMiserMode;
	if ( $wgMiserMode ) {
		$wgOut->addWikiText( wfMsg( "perfdisabled" ) );
		return;
	}

	list( $limit, $offset ) = wfCheckLimits();

	$sql = "SELECT DISTINCT cur_title, cur_counter FROM cur " .
	  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY " .
	  "cur_counter DESC LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, $fname );

	$sk = $wgUser->getSkin();

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Popularpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$nv = wfMsg( "nviews", $obj->cur_counter );
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$link} ({$nv})</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
