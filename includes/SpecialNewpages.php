<?

function wfSpecialNewpages()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = "wfSpecialNewpages";

	list( $limit, $offset ) = wfCheckLimits();

	$sql = "SELECT rc_title AS cur_title,rc_user AS cur_user,rc_user_text AS cur_user_text,rc_comment as cur_comment," .
	  "rc_timestamp AS cur_timestamp,length(cur_text) as cur_length FROM recentchanges,cur " .
	  "WHERE rc_cur_id=cur_id AND rc_new=1 AND rc_namespace=0 AND cur_is_redirect=0 " .
	  "ORDER BY rc_timestamp DESC LIMIT {$offset}, {$limit}";
	$res = wfQuery( $sql, $fname );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Newpages" ) );
	$wgOut->addHTML( "<br>{$sl}\n" );

	$sk = $wgUser->getSkin();
	$s = "<ol start=" . ( $offset + 1 ) . ">";
	while ( $obj = wfFetchObject( $res ) ) {
		$u = $obj->cur_user;
		$ut = $obj->cur_user_text;
		$length= wfmsg("nbytes",$obj->cur_length);
		$c = wfEscapeHTML( $obj->cur_comment );
		if ( 0 == $u ) { $ul = $ut; }
		else { $ul = $sk->makeLink( $wgLang->getNsText(2).":{$ut}", $ut ); }

		$d = $wgLang->timeanddate( $obj->cur_timestamp, true );
		$link = $sk->makeKnownLink( $obj->cur_title, "" );
		$s .= "<li>{$d} {$link} ({$length}) . . {$ul}";

		if ( "" != $c && "*" != $c ) { $s .= " <em>({$c})</em>"; }
		$s .= "</li>\n";
	}
	wfFreeResult( $res );
	$s .= "</ol>";
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
