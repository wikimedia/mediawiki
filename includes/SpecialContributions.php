<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialContributions( $par = '' ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest;
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
	$dbr =& wfGetDB( DB_SLAVE );
	$userCond = "";

	$nt = Title::newFromURL( $target );
	if ( !$nt ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$nt->setNamespace( Namespace::getUser() );

	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		$userCond = "=" . $id;
	}
	$talk = $nt->getTalkPage();
	if( $talk ) {
		$ul .= " (" . $sk->makeLinkObj( $talk, $wgLang->getNsText(Namespace::getTalk(0)) ) . ")";
	}

	if ( $target == 'newbies' ) {
		# View the contributions of all recently created accounts
		$max = $dbr->selectField( 'user', 'max(user_id)', false, $fname );
		$userCond = ">" . ($max - $max / 100);
		$ul = wfMsg ( 'newbies' );
		$id = 0;
	}

	$wgOut->setSubtitle( wfMsg( "contribsub", $ul ) );

	if ( $hideminor ) {
		$cmq = "AND cur_minor_edit=0";
		$omq = "AND old_minor_edit=0";
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  	  WfMsg( "show" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=0" );
	} else {
		$cmq = $omq = "";
		$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  	  WfMsg( "hide" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=1" );
	}

	extract( $dbr->tableNames( 'old', 'cur' ) );
	if ( $userCond == "" ) {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM $cur " .
		  "WHERE cur_user_text='" . $dbr->strencode( $nt->getText() ) . "' {$cmq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = $dbr->query( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text,old_id FROM $old " .
		  "WHERE old_user_text='" . $dbr->strencode( $nt->getText() ) . "' {$omq} " .
		  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res2 = $dbr->query( $sql, $fname );
	} else {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM $cur " .
		  "WHERE cur_user {$userCond} {$cmq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res1 = $dbr->query( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text,old_id FROM $old " .
		  "WHERE old_user {$userCond} {$omq} ORDER BY inverse_timestamp LIMIT {$querylimit}";
		$res2 = $dbr->query( $sql, $fname );
	}
	$nCur = $dbr->numRows( $res1 );
	$nOld = $dbr->numRows( $res2 );

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
	if ( 0 != $nCur ) { $obj1 = $dbr->fetchObject( $res1 ); }
	if ( 0 != $nOld ) { $obj2 = $dbr->fetchObject( $res2 ); }

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

			$obj1 = $dbr->fetchObject( $res1 );
			$topmark = true;
			$oldid = false;
			--$nCur;
		} else {
			$ns = $obj2->old_namespace;
			$t = $obj2->old_title;
			$ts = $obj2->old_timestamp;
			$comment =$obj2->old_comment;
			$me = $obj2->old_minor_edit;
			$usertext = $obj2->old_user_text;
			$oldid = $obj2->old_id;

			$obj2 = $dbr->fetchObject( $res2 );
			$topmark = false;
			$isnew = false;
			--$nOld;
		}
		if( $n >= $offset )
			ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, ( $me > 0), $isnew, $usertext, $oldid );
	}
	$wgOut->addHTML( "</ul>\n" );

	# Validations
	global $wgUseValidation;
	if( $wgUseValidation ) {
		require_once( 'SpecialValidate.php' );
		$val = new Validation ;
		$val = $val->countUserValidations ( $id ) ;
		$wgOut->addHTML( wfMsg ( 'val_user_validations', $val ) );
	}
}


/**
 * Generates each row in the contributions list.
 *
 * Contributions which are marked "top" are currently on top of the history.
 * For these contributions, a [rollback] link is shown for users with sysop
 * privileges. The rollback link restores the most recent version that was not
 * written by the target user.
 * 
 * If the contributions page is called with the parameter &bot=1, all rollback
 * links also get that parameter. It causes the edit itself and the rollback
 * to be marked as "bot" edits. Bot edits are hidden by default from recent
 * changes, so this allows sysops to combat a busy vandal without bothering
 * other users.
 * 
 * @todo This would probably look a lot nicer in a table.
 */
function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, $isminor, $isnew, $target, $oldid ) {
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
	if ( $oldid ) {
		$oldtext= $sk->makeKnownLink( $page, '('.wfMsg('diff').')', 'diff=prev&oldid='.$oldid );
	} else { $oldtext=''; }
	$histlink="(".$sk->makeKnownLink($page,wfMsg("hist"),"action=history").")";

	if($comment) {

		$comment="<em>(". $sk->formatComment($comment, Title::newFromText($t) ) .")</em> ";

	}
	$d = $wgLang->timeanddate( $ts, true );

	if ($isminor) {
		$mflag = '<span class="minor">'.wfMsg( "minoreditletter" ).'</span> ';
	} else {
		$mflag = "";
	}

	$wgOut->addHTML( "<li>{$d} {$histlink} {$mflag} {$link} {$comment}{$topmarktext}{$oldtext}</li>\n" );
}

/**
 *
 */
function ucCountLink( $lim, $d ) {
	global $wgUser, $wgLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$lim}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

/**
 *
 */
function ucDaysLink( $lim, $d ) {
	global $wgUser, $wgLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$d}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}
?>
