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
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgTitle, $wgRequest;
	$fname = 'wfSpecialRecentchangeslinked';

	$days = $wgRequest->getInt( 'days' );
	$target = $wgRequest->getText( 'target' );
	$hideminor = $wgRequest->getBool( 'hideminor' ) ? 1 : 0;
	
	$wgOut->setPagetitle( wfMsg( "recentchanges" ) );
	$sk = $wgUser->getSkin();

	if( $par ) {
		$target = $par;
	}
	if ( $target == '') {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}
	$nt = Title::newFromURL( $target );
	if( !$nt ) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}
	$id = $nt->getArticleId();
	
	$wgOut->setSubtitle( wfMsg( 'rclsub', $nt->getPrefixedText() ) );

	if ( ! $days ) {
		$days = $wgUser->getOption( 'rcdays' );
		if ( ! $days ) { $days = 7; }
	}
	$days = (int)$days;
	list( $limit, $offset ) = wfCheckLimits( 100, 'rclimit' );

	$dbr =& wfGetDB( DB_SLAVE );
	$cutoff = $dbr->timestamp( time() - ( $days * 86400 ) );

	$hideminor = ($hideminor ? 1 : 0);
	if ( $hideminor ) {
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( 'Recentchangeslinked' ),
	  	  WfMsg( 'show' ), 'target=' . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=0" );
	} else {
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( "Recentchangeslinked" ),
	  	  WfMsg( "hide" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&days={$days}&limit={$limit}&hideminor=1" );
	}
	if ( $hideminor ) {
		$cmq = 'AND rev_minor_edit=0';
	} else { $cmq = ''; }

	// If target is a Category, use categorylinks and invert from and to
	if( $nt->getNamespace() == NS_CATEGORY ) {
		$catkey = $dbr->addQuotes( $nt->getDBKey() );
		$sql = "SELECT page_id,page_namespace,page_title,rev_user,rev_comment," .
		  "rev_user_text,rev_timestamp,rev_minor_edit,page_is_new FROM $categorylinks, $revision, $page " .
		  "WHERE rev_timestamp > '{$cutoff}' {$cmq} AND cl_from=page_id AND cl_to=$catkey " .
			  "GROUP BY page_id,page_namespace,page_title,rev_user,rev_comment,rev_user_text," .
		  "rev_timestamp,rev_minor_edit,page_is_new,inverse_timestamp ORDER BY inverse_timestamp LIMIT {$limit}";
	} else {
		$sql = "SELECT page_id,page_namespace,page_title,rev_user,rev_comment," .
		  "rev_user_text,rev_timestamp,rev_minor_edit,page_is_new FROM $links, $revision, $page " .
		  "WHERE rev_timestamp > '{$cutoff}' {$cmq} AND l_to=page_id AND l_from=$id " .
			  "GROUP BY page_id,page_namespace,page_title,rev_user,rev_comment,rev_user_text," .
		  "rev_timestamp,rev_minor_edit,page_is_new,inverse_timestamp ORDER BY inverse_timestamp LIMIT {$limit}";
	}
	$res = $dbr->query( $sql, $fname );

	$wgOut->addHTML("&lt; ".$sk->makeKnownLinkObj($nt, "", "redirect=no" )."<br />\n");
	$note = wfMsg( "rcnote", $limit, $days );
	$wgOut->addHTML( "<hr />\n{$note}\n<br />" );

	$note = rcDayLimitlinks( $days, $limit, "Recentchangeslinked",
                                 "target=" . $nt->getPrefixedURL() . "&hideminor={$hideminor}",
                                 false, $mlink );

	$wgOut->addHTML( $note."\n" );

	$list =& new ChangesList( $sk );
	$s = $list->beginRecentChangesList();
	$count = $dbr->numRows( $res );
	
	$counter = 1;
	while ( $limit ) {
		if ( 0 == $count ) { break; }
		$obj = $dbr->fetchObject( $res );
		--$count;

		$rc = RecentChange::newFromCurRow( $obj );
		$rc->counter = $counter++;
		$s .= $list->recentChangesLine( $rc );
		--$limit;
	}
	$s .= $list->endRecentChangesList();

	$dbr->freeResult( $res );
	$wgOut->addHTML( $s );
}

?>
