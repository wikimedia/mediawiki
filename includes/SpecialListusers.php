<?php

function wfSpecialListusers()
{
	global $wgUser, $wgOut, $wgLang, $wgIsPg;

	list( $limit, $offset ) = wfCheckLimits();

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialPage( "Listusers" ) );
	$wgOut->addHTML( "<br />{$sl}</p>\n<ol start='" . ( $offset + 1 ) . "'>" );

	$usertable=$wgIsPg?'"user"':'user';
	$sql = "SELECT user_name,user_rights FROM $usertable ORDER BY user_name" .
		wfLimitResult($limit,$offset);
	$res = wfQuery( $sql, DB_READ, "wfSpecialListusers" );

	$sk = $wgUser->getSkin();
	while ( $s = wfFetchObject( $res ) ) {
		$n = $s->user_name;
		$r = $s->user_rights;

		$l = $sk->makeLink( $wgLang->getNsText(
		  Namespace::getUser() ) . ":{$n}", $n );

		if ( "" != $r ) {
			$link = $sk->makeKnownLink( wfMsg( "administrators" ), $r );
			$l .= " ({$link})";
		}
		$wgOut->addHTML( "<li>{$l}</li>\n" );
	}
	wfFreeResult( $res );
	$wgOut->addHTML( "</ol>\n<p>{$sl}</p>\n" );
}

?>
