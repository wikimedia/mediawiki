<?php
include_once( "SpecialRecentchanges.php" );

function wfSpecialRecentchangeslinked( $par = NULL )
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	global $days, $target, $hideminor; # From query string
	$fname = "wfSpecialRecentchangeslinked";

	$wgOut->setPagetitle( wfMsg( "recentchanges" ) );
	$sk = $wgUser->getSkin();

	if( $par ) {
		$target = $par;
	}
	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$nt = Title::newFromURL( $target );
	$wgOut->setSubtitle( wfMsg( "rclsub", $nt->getPrefixedText() ) );

	if ( ! $days ) {
		$days = $wgUser->getOption( "rcdays" );
		if ( ! $days ) { $days = 7; }
	}
	$days = (int)$days;
	list( $limit, $offset ) = wfCheckLimits( 100, "rclimit" );
	$cutoff = wfUnix2Timestamp( time() - ( $days * 86400 ) );

	$hideminor = ($hideminor ? 1 : 0);
	if ( $hideminor ) {
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Recentchangeslinked" ),
	  	  WfMsg( "show" ), "target=" . wfEscapeHTML( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=0" );
	} else {
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Recentchangeslinked" ),
	  	  WfMsg( "hide" ), "target=" . wfEscapeHTML( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=1" );
	}
	if ( $hideminor ) {
		$cmq = "AND cur_minor_edit=0";
	} else { $cmq = ""; }

	$sql = "SELECT cur_id,cur_namespace,cur_title,cur_user,cur_comment," .
	  "cur_user_text,cur_timestamp,cur_minor_edit,cur_is_new FROM links, cur " .
	  "WHERE cur_timestamp > '{$cutoff}' {$cmq} AND l_to=cur_id AND l_from='" .
      wfStrencode( $nt->getPrefixedDBkey() ) . "' GROUP BY cur_id " .
	  "ORDER BY inverse_timestamp LIMIT {$limit}";
	$res = wfQuery( $sql, DB_READ, $fname );

	$note = wfMsg( "rcnote", $limit, $days );
	$wgOut->addHTML( "<hr>\n{$note}\n<br>" );

	$note = rcDayLimitlinks( $days, $limit, "Recentchangeslinked",
                                 "target=" . $nt->getPrefixedURL() . "&hideminor={$hideminor}",
                                 false, $mlink );

	$wgOut->addHTML( "{$note}\n" );

	$s = $sk->beginRecentChangesList();
	$count = wfNumRows( $res );

	while ( $limit ) {
		if ( 0 == $count ) { break; }
		$obj = wfFetchObject( $res );
		--$count;

		$rc = RecentChange::newFromCurRow( $obj );
		$s .= $sk->recentChangesLine( $rc );
		--$limit;
	}
	$s .= $sk->endRecentChangesList();

	wfFreeResult( $res );
	$wgOut->addHTML( $s );
}

?>
