<?php
require_once( "SpecialRecentchanges.php" );

function wfSpecialRecentchangeslinked( $par = NULL )
{
	global $wgUser, $wgOut, $wgLang, $wgTitle, $wgRequest;
	$fname = "wfSpecialRecentchangeslinked";

	$days = $wgRequest->getInt( 'days' );
	$target = $wgRequest->getText( 'target' );
	$hideminor = $wgRequest->getBool( 'hideminor' ) ? 1 : 0;
	
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
	if( !$nt ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$id = $nt->getArticleId();
	
	$wgOut->setSubtitle( wfMsg( "rclsub", $nt->getPrefixedText() ) );

	if ( ! $days ) {
		$days = $wgUser->getOption( "rcdays" );
		if ( ! $days ) { $days = 7; }
	}
	$days = (int)$days;
	list( $limit, $offset ) = wfCheckLimits( 100, "rclimit" );

	$dbr =& wfGetDB( DB_SLAVE );
	$cutoff = $dbr->timestamp( time() - ( $days * 86400 ) );

	$hideminor = ($hideminor ? 1 : 0);
	if ( $hideminor ) {
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Recentchangeslinked" ),
	  	  WfMsg( "show" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=0" );
	} else {
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Recentchangeslinked" ),
	  	  WfMsg( "hide" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=1" );
	}
	if ( $hideminor ) {
		$cmq = "AND cur_minor_edit=0";
	} else { $cmq = ""; }

	extract( $dbr->tableNames( 'cur', 'links' ) );

	$sql = "SELECT cur_id,cur_namespace,cur_title,cur_user,cur_comment," .
	  "cur_user_text,cur_timestamp,cur_minor_edit,cur_is_new FROM $links, $cur " .
	  "WHERE cur_timestamp > '{$cutoff}' {$cmq} AND l_to=cur_id AND l_from=$id " .
          "GROUP BY cur_id,cur_namespace,cur_title,cur_user,cur_comment,cur_user_text," .
	  "cur_timestamp,cur_minor_edit,cur_is_new,inverse_timestamp ORDER BY inverse_timestamp LIMIT {$limit}";
	$res = $dbr->query( $sql, $fname );

	$wgOut->addHTML("&lt; ".$sk->makeKnownLinkObj($nt, "", "redirect=no" )."<br />\n");
	$note = wfMsg( "rcnote", $limit, $days );
	$wgOut->addHTML( "<hr />\n{$note}\n<br />" );

	$note = rcDayLimitlinks( $days, $limit, "Recentchangeslinked",
                                 "target=" . $nt->getPrefixedURL() . "&hideminor={$hideminor}",
                                 false, $mlink );

	$wgOut->addHTML( "{$note}\n" );

	$s = $sk->beginRecentChangesList();
	$count = $dbr->numRows( $res );
	
	$counter = 1;
	while ( $limit ) {
		if ( 0 == $count ) { break; }
		$obj = $dbr->fetchObject( $res );
		--$count;

		$rc = RecentChange::newFromCurRow( $obj );
		$rc->counter = $counter++;
		$s .= $sk->recentChangesLine( $rc );
		--$limit;
	}
	$s .= $sk->endRecentChangesList();

	$dbr->freeResult( $res );
	$wgOut->addHTML( $s );
}

?>
