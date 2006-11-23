<?php
/**
 * This is to display changes made to all articles linked in an article.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'SpecialRecentchanges.php' );

/**
 * Entrypoint
 * @param string $par parent page we will look at
 */
function wfSpecialRecentchangeslinked( $par = NULL ) {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgRequest;
	$fname = 'wfSpecialRecentchangeslinked';

	$days = $wgRequest->getInt( 'days' );
	$target = isset($par) ? $par : $wgRequest->getText( 'target' );
	$hideminor = $wgRequest->getBool( 'hideminor' ) ? 1 : 0;

	$wgOut->setPagetitle( wfMsg( 'recentchangeslinked' ) );
	$sk = $wgUser->getSkin();

	# Validate the title
	$nt = Title::newFromURL( $target );
	if( !is_object( $nt ) ) {
		$wgOut->errorPage( 'notargettitle', 'notargettext' );
		return;
	}
	
	# Check for existence
	# Do a quiet redirect back to the page itself if it doesn't
	if( !$nt->exists() ) {
		$wgOut->redirect( $nt->getLocalUrl() );
		return;
	}

	$id = $nt->getArticleId();

	$wgOut->setSubtitle( htmlspecialchars( wfMsg( 'rclsub', $nt->getPrefixedText() ) ) );

	if ( ! $days ) {
		$days = $wgUser->getOption( 'rcdays' );
		if ( ! $days ) { $days = 7; }
	}
	$days = (int)$days;
	list( $limit, /* offset */ ) = wfCheckLimits( 100, 'rclimit' );

	$dbr =& wfGetDB( DB_SLAVE );
	$cutoff = $dbr->timestamp( time() - ( $days * 86400 ) );

	$hideminor = ($hideminor ? 1 : 0);
	if ( $hideminor ) {
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( 'Recentchangeslinked' ),
	  	  wfMsg( 'show' ), 'target=' . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=0" );
	} else {
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( "Recentchangeslinked" ),
	  	  wfMsg( "hide" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=1" );
	}
	if ( $hideminor ) {
		$cmq = 'AND rc_minor=0';
	} else { $cmq = ''; }

	extract( $dbr->tableNames( 'recentchanges', 'categorylinks', 'pagelinks', 'revision', 'page' , "watchlist" ) );

	$uid = $wgUser->getID();

	$GROUPBY = "
	GROUP BY rc_cur_id,rc_namespace,rc_title,
		rc_user,rc_comment,rc_user_text,rc_timestamp,rc_minor,
		rc_new, rc_id, rc_this_oldid, rc_last_oldid, rc_bot, rc_patrolled, rc_type
" . ($uid ? ",wl_user" : "") . "
		ORDER BY rc_timestamp DESC
	LIMIT {$limit}";

	// If target is a Category, use categorylinks and invert from and to
	if( $nt->getNamespace() == NS_CATEGORY ) {
		$catkey = $dbr->addQuotes( $nt->getDBKey() );
		$sql = "SELECT /* wfSpecialRecentchangeslinked */
				rc_id,
				rc_cur_id,
				rc_namespace,
				rc_title,
				rc_this_oldid,
				rc_last_oldid,
				rc_user,
				rc_comment,
				rc_user_text,
				rc_timestamp,
				rc_minor,
				rc_bot,
				rc_new,
				rc_patrolled,
				rc_type
" . ($uid ? ",wl_user" : "") . "
	    FROM $categorylinks, $recentchanges
" . ($uid ? "LEFT OUTER JOIN $watchlist ON wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace " : "") . "
	   WHERE rc_timestamp > '{$cutoff}'
	     {$cmq}
	     AND cl_from=rc_cur_id
	     AND cl_to=$catkey
$GROUPBY
 ";
	} else {
		$sql =
"SELECT /* wfSpecialRecentchangeslinked */
			rc_id,
			rc_cur_id,
			rc_namespace,
			rc_title,
			rc_user,
			rc_comment,
			rc_user_text,
			rc_this_oldid,
			rc_last_oldid,
			rc_timestamp,
			rc_minor,
			rc_bot,
			rc_new,
			rc_patrolled,
			rc_type
" . ($uid ? ",wl_user" : "") . "
   FROM $pagelinks, $recentchanges
" . ($uid ? " LEFT OUTER JOIN $watchlist ON wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace " : "") . "
   WHERE rc_timestamp > '{$cutoff}'
	{$cmq}
     AND pl_namespace=rc_namespace
     AND pl_title=rc_title
     AND pl_from=$id
$GROUPBY
";
	}
	$res = $dbr->query( $sql, $fname );

	$wgOut->addHTML("&lt; ".$sk->makeKnownLinkObj($nt, "", "redirect=no" )."<br />\n");
	$note = wfMsg( "rcnote", $limit, $days, $wgLang->timeAndDate( wfTimestampNow(), true ) );
	$wgOut->addHTML( "<hr />\n{$note}\n<br />" );

	$note = rcDayLimitlinks( $days, $limit, "Recentchangeslinked",
				 "target=" . $nt->getPrefixedURL() . "&hideminor={$hideminor}",
				 false, $mlink );

	$wgOut->addHTML( $note."\n" );

	$list = ChangesList::newFromUser( $wgUser );
	$s = $list->beginRecentChangesList();
	$count = $dbr->numRows( $res );

	$counter = 1;
	while ( $limit ) {
		if ( 0 == $count ) { break; }
		$obj = $dbr->fetchObject( $res );
		--$count;
		$rc = RecentChange::newFromRow( $obj );
		$rc->counter = $counter++;
		$s .= $list->recentChangesLine( $rc , !empty( $obj->wl_user) );
		--$limit;
	}
	$s .= $list->endRecentChangesList();

	$dbr->freeResult( $res );
	$wgOut->addHTML( $s );
}

?>
