<?

function wfSpecialAncientpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = "wfSpecialAncientpages";

	list( $limit, $offset ) = wfCheckLimits();

	$sql = "SELECT cur_title,cur_user,cur_user_text,cur_comment," .
	  "cur_timestamp FROM cur USE INDEX (cur_timestamp) " .
	  "WHERE cur_namespace=0 AND cur_is_redirect=0 " .
	  " ORDER BY cur_timestamp LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, DB_READ, $fname );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Ancientpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$sk = $wgUser->getSkin();
	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$u = $obj->cur_user;
		$ut = $obj->cur_user_text;
		$c = wfEscapeHTML( $obj->cur_comment );
		if ( 0 == $u ) { $ul = $ut; }
		else { $ul = $sk->makeLink( $wgLang->getNsText(2).":{$ut}", $ut ); }

		$d = $wgLang->timeanddate( $obj->cur_timestamp, true );
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$d} {$link} . . {$ul}";

		if ( "" != $c && "*" != $c ) { $s .= " <em>({$c})</em>"; }
		$s .= "</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
