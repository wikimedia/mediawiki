<?php

function wfSpecialContributions( $par = "" )
{
	global $wgUser, $wgOut, $wgLang, $wgRequest, $wgIsPg;
	$fname = "wfSpecialContributions";
	$sysop = $wgUser->isSysop();

	if( $par )
		$target = $par;
	else
		$target = $wgRequest->getVal( 'target' );

	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	
	# FIXME: Change from numeric offsets to date offsets
	list( $limit, $offset ) = wfCheckLimits( 50, "" );
	$offlimit = $limit + $offset;
	$querylimit = $offlimit + 1;
	$hideminor = ($wgRequest->getVal( 'hideminor' ) ? 1 : 0);
	$sk = $wgUser->getSkin();

	$userCond = "";

	$nt = Title::newFromURL( $target );
	$nt->setNamespace( Namespace::getUser() );

	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		$userCond = "=" . $id;
	}
	$talk = $nt->getTalkPage();
	if( $talk )
		$ul .= " (" . $sk->makeLinkObj( $talk, $wgLang->getNsText(Namespace::getTalk(0)) ) . ")";
	else
		$ul .= "brrrp";

	if ( $target == 'newbies' ) {
		# View the contributions of all recently created accounts
		$row = wfGetArray("user",array("max(user_id) as m"),false);
		$userCond = ">" . ($row->m - $row->m / 100);
		$ul = "";
		$id = 0;
	}

	$wgOut->setSubtitle( wfMsg( "contribsub", $ul ) );

	if ( $hideminor ) {
		$cmq = "AND cur_minor_edit=0";
		$omq = "AND old_minor_edit=0";
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  	  WfMsg( "show" ), "target=" . wfEscapeHTML( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=0" );
	} else {
		$cmq = $omq = "";
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  	  WfMsg( "hide" ), "target=" . wfEscapeHTML( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=1" );
	}

	$oldtable=$wgIsPg?'"old"':'old';
	if ( $userCond == "" ) {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM cur " .
		  "WHERE cur_user_text='" . wfStrencode( $nt->getText() ) . "' {$cmq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = wfQuery( $sql, DB_READ, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text FROM $oldtable " .
		  "WHERE old_user_text='" . wfStrencode( $nt->getText() ) . "' {$omq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res2 = wfQuery( $sql, DB_READ, $fname );
	} else {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM cur " .
		  "WHERE cur_user {$userCond} {$cmq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = wfQuery( $sql, DB_READ, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text FROM $oldtable " .
		  "WHERE old_user {$userCond} {$omq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res2 = wfQuery( $sql, DB_READ, $fname );
	}
	$nCur = wfNumRows( $res1 );
	$nOld = wfNumRows( $res2 );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialpage( "Contributions" ),
	  "hideminor={$hideminor}&target=" . wfUrlEncode( $target ),
	  ($nCur + $nOld) <= $offlimit);

        $shm = wfMsg( "showhideminor", $mlink );
	$wgOut->addHTML( "<br />{$sl} ($shm)</p>\n");


	if ( 0 == $nCur && 0 == $nOld ) {
		$wgOut->addHTML( "\n<p>" . wfMsg( "nocontribs" ) . "</p>\n" );
		return;
	}
	if ( 0 != $nCur ) { $obj1 = wfFetchObject( $res1 ); }
	if ( 0 != $nOld ) { $obj2 = wfFetchObject( $res2 ); }

	$wgOut->addHTML( "<ul>\n" );
	for( $n = 0; $n < $offlimit; $n++ ) {
		if ( 0 == $nCur && 0 == $nOld ) { break; }

		if ( ( 0 == $nOld ) ||
		  ( ( 0 != $nCur ) &&
		  ( $obj1->cur_timestamp >= $obj2->old_timestamp ) ) ) {
			$ns = $obj1->cur_namespace;
			$t = $obj1->cur_title;
			$ts = $obj1->cur_timestamp;
			$comment =$obj1->cur_comment;
			$me = $obj1->cur_minor_edit;
			$isnew = $obj1->cur_is_new;
			$usertext = $obj1->cur_user_text;
			
			$obj1 = wfFetchObject( $res1 );
			$topmark = true;
			--$nCur;
		} else {
			$ns = $obj2->old_namespace;
			$t = $obj2->old_title;
			$ts = $obj2->old_timestamp;
			$comment =$obj2->old_comment;
			$me = $obj2->old_minor_edit;
			$usertext = $obj2->old_user_text;

			$obj2 = wfFetchObject( $res2 );
			$topmark = false;
			$isnew = false;
			--$nOld;
		}
		if( $n >= $offset )
			ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, ( $me > 0), $isnew, $usertext );
	}
	$wgOut->addHTML( "</ul>\n" );
}


/*

Generates each row in the contributions list.

Contributions which are marked "top" are currently on top of the history.
For these contributions, a [rollback] link is shown for users with sysop
privileges. The rollback link restores the most recent version that was not
written by the target user.

If the contributions page is called with the parameter &bot=1, all rollback
links also get that parameter. It causes the edit itself and the rollback
to be marked as "bot" edits. Bot edits are hidden by default from recent
changes, so this allows sysops to combat a busy vandal without bothering
other users.

TODO: This would probably look a lot nicer in a table.

*/
function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, $isminor, $isnew, $target )
{
	global $wgLang, $wgOut, $wgUser, $wgRequest;
	$page = Title::makeName( $ns, $t );
	$link = $sk->makeKnownLink( $page, "" );
	$topmarktext="";
	if($topmark) {
		if(!$isnew) {
			$topmarktext .= $sk->makeKnownLink( $page, wfMsg("uctop"), "diff=0" );
		} else {
			$topmarktext .= wfMsg("newarticle");
		}
		$sysop = $wgUser->isSysop();
		if($sysop ) {
			$extraRollback = $wgRequest->getBool( "bot" ) ? '&bot=1' : '';
			# $target = $wgRequest->getText( 'target' );
			$topmarktext .= " [". $sk->makeKnownLink( $page,
		  	wfMsg( "rollbacklink" ),
		  	"action=rollback&from=" . urlencode( $target ) . $extraRollback ) ."]";
		}

	}
	$histlink="(".$sk->makeKnownLink($page,wfMsg("hist"),"action=history").")";

	if($comment) {

		$comment="<em>(". $sk->formatComment($comment ) .")</em> ";

	}
	$d = $wgLang->timeanddate( $ts, true );

	if ($isminor) {
		$mflag = "<strong>" . wfMsg( "minoreditletter" ) . "</strong> ";
	} else {
		$mflag = "";
	}

	$wgOut->addHTML( "<li>{$d} {$histlink} {$mflag} {$link} {$comment}{$topmarktext}</li>\n" );
}

function ucCountLink( $lim, $d )
{
	global $wgUser, $wgLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$lim}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

function ucDaysLink( $lim, $d )
{
	global $wgUser, $wgLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$d}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}
?>
