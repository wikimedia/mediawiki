<?

function wfSpecialLongpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = "wfSpecialLongpages";

	# Cache
	$vsp = $wgLang->getValidSpecialPages();
	$log = new LogPage( $vsp["Longpages"] );
	$log->mUpdateRecentChanges = false;

	global $wgMiserMode;
	if ( $wgMiserMode ) {
		$log->showAsDisabledPage();
		return;
	}

	list( $limit, $offset ) = wfCheckLimits();

	$sql = "SELECT cur_title, LENGTH(cur_text) AS len FROM cur " .
	  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY " .
	  "LENGTH(cur_text) DESC LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, DB_READ, $fname );

	$sk = $wgUser->getSkin();

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Longpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$nb = wfMsg( "nbytes", $obj->len );
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$link} ({$nb})</li>\n";
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
