<?

function wfSpecialShortpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	global $limit, $offset; # From query string
	$fname = "wfSpecialShortpages";

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

	$sql = "SELECT cur_title, LENGTH(cur_text) AS len FROM cur " .
	  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY " .
	  "LENGTH(cur_text) LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, $fname );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Shortpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$sk = $wgUser->getSkin();
	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$nb = str_replace( "$1", $obj->len, wfMsg( "nbytes" ) );
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$link} ({$nb})</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
