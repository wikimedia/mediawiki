<?

function wfSpecialLonelypages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	global $limit, $offset; # From query string
	$fname = "wfSpecialLonelypages";

	global $wgMiserMode;
	if ( $wgMiserMode ) {
		$wgOut->addWikiText( wfMsg( "perfdisabled" ) );
		return;
	}

	if ( ! $limit ) {
		$limit = $wgUser->getOption( "rclimit" );
		if ( ! $limit ) { $limit = 50; }
	}
	if ( ! $offset ) { $offset = 0; }

	$sql = "SELECT cur_title FROM cur LEFT JOIN links ON " .
	  "cur_id=l_to WHERE l_to IS NULL AND cur_namespace=0 AND " .
	  "cur_is_redirect=0 ORDER BY cur_title LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, $fname );

	$sk = $wgUser->getSkin();

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Lonelypages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$link}</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
