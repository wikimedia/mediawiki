<?php

function wfSpecialContributions( $par = "" )
{
	global $wgUser, $wgOut, $wgLang, $target, $hideminor;
	$fname = "wfSpecialContributions";
	$sysop = $wgUser->isSysop();

	if( $par )
		$target = $par;
	else
		$target = wfCleanQueryVar( $target );

	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	list( $limit, $offset ) = wfCheckLimits( 50, "" );
	$offlimit = $limit + $offset;
	$querylimit = $offlimit + 1;
	$hideminor = ($hideminor ? 1 : 0);

	$nt = Title::newFromURL( $target );
	$nt->setNamespace( Namespace::getUser() );

	$sk = $wgUser->getSkin();
	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeKnownLink( $nt->getPrefixedText(), $nt->getText() );
	}
	$talk = $nt->getTalkPage();
	if( $talk )
		$ul .= " (" . $sk->makeLinkObj( $talk, $wgLang->getNsText(Namespace::getTalk(0)) ) . ")";
	else
		$ul .= "brrrp";
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

	if ( 0 == $id ) {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit FROM cur " .
		  "WHERE cur_user_text='" . wfStrencode( $nt->getText() ) . "' {$cmq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = wfQuery( $sql, DB_READ, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit FROM old " .
		  "WHERE old_user_text='" . wfStrencode( $nt->getText() ) . "' {$omq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res2 = wfQuery( $sql, DB_READ, $fname );
	} else {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit FROM cur " .
		  "WHERE cur_user={$id} {$cmq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = wfQuery( $sql, DB_READ, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit FROM old " .
		  "WHERE old_user={$id} {$omq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
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
	$wgOut->addHTML( "<br>{$sl} ($shm) \n");
	

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

			$obj1 = wfFetchObject( $res1 );
			$topmark = true;			
			--$nCur;
		} else {
			$ns = $obj2->old_namespace;
			$t = $obj2->old_title;
			$ts = $obj2->old_timestamp;
			$comment =$obj2->old_comment;
			$me = $obj2->old_minor_edit;

			$obj2 = wfFetchObject( $res2 );
			$topmark = false;
			--$nOld;
		}
		if( $n >= $offset )
			ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, ( $me > 0) );
	}
	$wgOut->addHTML( "</ul>\n" );
}

function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, $isminor )
{
	global $wgLang, $wgOut, $wgUser, $target;
	$page = Title::makeName( $ns, $t );
	$link = $sk->makeKnownLink( $page, "" );
	$topmarktext = $topmark ? wfMsg ( "uctop" ) : "";
	$sysop = $wgUser->isSysop();

	$extraRollback = $_REQUEST['bot'] ? '&bot=1' : '';	
	if($sysop && $topmark ) {
		$topmarktext .= " [". $sk->makeKnownLink( $page,
		  wfMsg( "rollbacklink" ), 
		  "action=rollback&from=" . urlencode( $target ) . $extraRollback ) ."]";
	}
	if($comment) {
	
		$comment="<em>(". htmlspecialchars( $comment ) .")</em> ";
	
	}
	$d = $wgLang->timeanddate( $ts, true );

        if ($isminor) {
          $mflag = "<strong>" . wfMsg( "minoreditletter" ) . "</strong> ";
        }

	$wgOut->addHTML( "<li>{$d} {$mflag}{$link} {$comment}{$topmarktext}</li>\n" );
}

function ucCountLink( $lim, $d )
{
	global $wgUser, $wgLang, $target;

	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$lim}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

function ucDaysLink( $lim, $d )
{
	global $wgUser, $wgLang, $target;

	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$d}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}
?>
