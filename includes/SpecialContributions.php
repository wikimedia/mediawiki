<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Special page "user contributions".
 * Shows a list of the contributions of a user.
 *
 * @return	none
 * @param	string	$par	(optional) user name of the user for which to show the contributions
 */
function wfSpecialContributions( $par = '' ) {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgRequest;
	$fname = 'wfSpecialContributions';

	if( $par )
		$target = $par;
	else
		$target = $wgRequest->getVal( 'target' );

	if ( '' == $target ) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}

	# FIXME: Change from numeric offsets to date offsets
	list( $limit, $offset ) = wfCheckLimits( 50, '' );
	$offlimit = $limit + $offset;
	$querylimit = $offlimit + 1;
	$hideminor = ($wgRequest->getVal( 'hideminor' ) ? 1 : 0);
	$sk = $wgUser->getSkin();
	$dbr =& wfGetDB( DB_SLAVE );
	$userCond = "";
	$namespace = $wgRequest->getVal( 'namespace', '' );
	if( $namespace != '' ) {
		$namespace = IntVal( $namespace );
	} else {
		$namespace = NULL;
	}

	$nt = Title::newFromURL( $target );
	if ( !$nt ) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}
	$nt =& Title::makeTitle( NS_USER, $nt->getDBkey() );

	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		$userCond = '=' . $id;
	}
	$talk = $nt->getTalkPage();
	if( $talk ) {
		$ul .= ' (' . $sk->makeLinkObj( $talk, $wgLang->getNsText(Namespace::getTalk(0)) ) . ')';
	}


	if ( $target == 'newbies' ) {
		# View the contributions of all recently created accounts
		$max = $dbr->selectField( 'user', 'max(user_id)', false, $fname );
		$userCond = '>' . ($max - $max / 100);
		$ul = wfMsg ( 'newbies' );
		$id = 0;
	}

	$wgOut->setSubtitle( wfMsg( 'contribsub', $ul ) );

	if ( $hideminor ) {
		$cmq = 'AND cur_minor_edit=0';
		$omq = 'AND old_minor_edit=0';
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
	  	  WfMsg( 'show' ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=0&namespace={$namespace}" );
	} else {
		$cmq = $omq = '';
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( "Contributions" ),
	  	  WfMsg( 'hide' ), 'target=' . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=1&namespace={$namespace}" );
	}
	
	if( !is_null($namespace) ) {
		$cmq .= " AND cur_namespace = {$namespace}";
		$omq .= " AND old_namespace = {$namespace}";
	}
	
	# We may have to force the index, as some options will cause
	# MySQL to incorrectly pick eg the namespace index.
	list( $useIndex, $tailOpts ) = $dbr->makeSelectOptions( array(
		'USE INDEX' => 'usertext_timestamp',
		'LIMIT' => $querylimit ) );
	
	extract( $dbr->tableNames( 'old', 'cur' ) );
	if ( $userCond == '' ) {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM $cur $useIndex " .
		  "WHERE cur_user_text='" . $dbr->strencode( $nt->getText() ) . "' {$cmq} " .
		  "ORDER BY inverse_timestamp $tailOpts";
		$res1 = $dbr->query( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text,old_id FROM $old $useIndex " .
		  "WHERE old_user_text='" . $dbr->strencode( $nt->getText() ) . "' {$omq} " .
		  "ORDER BY inverse_timestamp $tailOpts";
		$res2 = $dbr->query( $sql, $fname );
	} else {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment,cur_minor_edit,cur_is_new,cur_user_text FROM $cur $useIndex " .
		  "WHERE cur_user {$userCond} {$cmq} ORDER BY inverse_timestamp $tailOpts";
		$res1 = $dbr->query( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment,old_minor_edit,old_user_text,old_id FROM $old $useIndex " .
		  "WHERE old_user {$userCond} {$omq} ORDER BY inverse_timestamp $tailOpts";
		$res2 = $dbr->query( $sql, $fname );
	}
	$nCur = $dbr->numRows( $res1 );
	$nOld = $dbr->numRows( $res2 );

	$wgOut->addHTML( namespaceForm( $target, $hideminor, $namespace ) );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgContLang->specialpage( 'Contributions' ),
	  "hideminor={$hideminor}&namespace={$namespace}&target=" . wfUrlEncode( $target ),
	  ($nCur + $nOld) <= $offlimit);

        $shm = wfMsg( 'showhideminor', $mlink );
	$wgOut->addHTML( "<br />{$sl} ($shm)</p>\n");


	if ( 0 == $nCur && 0 == $nOld ) {
		$wgOut->addHTML( "\n<p>" . wfMsg( 'nocontribs' ) . "</p>\n" );
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
			$oldid = 0;
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
			ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, ( $me > 0), $isnew, $usertext, $oldid, $id );
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

	$wgOut->addHTML( "<br />{$sl} ($shm)</p>\n");
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
function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, $isminor, $isnew, $target, $oldid, $id ) {
	$fname = 'ucListEdit';
	wfProfileIn( $fname );
	
	global $wgLang, $wgOut, $wgUser, $wgRequest;
	global $wgStylePath, $wgAllowEditComments, $wgUserEditCommentTimeout;
	
	static $messages;
	if( !isset( $messages ) ) {
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg ) {
			$messages[$msg] = wfMsg( $msg );
		}
	}
	
	$page =& Title::makeTitle( $ns, $t );
	$link = $sk->makeKnownLinkObj( $page, '' );
	$difftext = $topmarktext = '';
	if($topmark) {
		$topmarktext .= '<strong>' . $messages['uctop'] . '</strong>';
		if(!$isnew) {
			$difftext .= $sk->makeKnownLinkObj( $page, '(' . $messages['diff'] . ')', 'diff=0' );
		} else {
			$difftext .= $messages['newarticle'];
		}
		
		if( $wgUser->isAllowed('rollback') ) {
			$extraRollback = $wgRequest->getBool( 'bot' ) ? '&bot=1' : '';
			# $target = $wgRequest->getText( 'target' );
			$topmarktext .= ' ['. $sk->makeKnownLinkObj( $page,
			  	$messages['rollbacklink'],
			  	'action=rollback&from=' . urlencode( $target ) . $extraRollback ) .']';
		}

	}
	if ( $oldid ) {
		$difftext= $sk->makeKnownLinkObj( $page, '(' . $messages['diff'].')', 'diff=prev&oldid='.$oldid );
	} 
	$histlink='('.$sk->makeKnownLinkObj( $page, $messages['hist'], 'action=history' ) . ')';

	if( $comment ) {
		$comment = '<em>(' . $sk->formatComment( $comment, $page ) . ')</em> ';
	}
	$d = $wgLang->timeanddate( $ts, true );

	if ($isminor) {
		$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
	} else {
		$mflag = '';
	}
	
	# Show an edit user comments icon if conditions are met.
	# Option must be on, user logged in, user these contributions are for or a sysop,
	# and the article must be recent enough.
	if ( $wgAllowEditComments && $wgUser->getID() &&
		 ($id == $wgUser->getID() || $wgUser->isAllowed(EDIT_COMMENT_ALL)) &&
		 ($wgUser->isAllowed(EDIT_COMMENT_ALL) || $wgUserEditCommentTimeout < 0 || 
		  $ts >= wfTimestampPlus( wfTimestampNow(), -$wgUserEditCommentTimeout * 60)) ) {
		
		$tooltip = wfMsg( "ectooltip" );
		$rt = $sk->makeKnownLinkObj( $page, "<img src=\"".$wgStylePath."/common/images/editcomment_icon.gif\" alt=\"{$tooltip}\" />",
		  "action=editcomment&oldid={$oldid}&returnto=Special:Contributions&returntotarget=$target" );
		
		# ***** Kludge ****
		# Swap out the tool tip created by makeKnownLink() with one appropriate for this link.
		$rt = preg_replace( '/title\=\"[^\"]*\"/', 'title="' . $tooltip . '"', $rt);
	}
	else {
		$rt = '';
	}

	$wgOut->addHTML( "<li>{$d} {$histlink} {$difftext} {$link} {$rt} {$mflag} {$comment} {$topmarktext}</li>\n" );
	wfProfileOut( $fname );
}

/**
 *
 */
function ucCountLink( $lim, $d ) {
	global $wgUser, $wgContLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( "Contributions" ),
	  "{$lim}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

/**
 *
 */
function ucDaysLink( $lim, $d ) {
	global $wgUser, $wgContLang, $wgRequest;

	$target = $wgRequest->getText( 'target' );
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
	  "{$d}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

/**
 * Generates a form used to restrict display of contributions
 * to a specific namespace
 *
 * @return	none
 * @param	string	$target	target user to show contributions for
 * @param	string	$hideminor whether minor contributions are hidden
 * @param	string	$namespace currently selected namespace, NULL for show all
 */
function namespaceForm ( $target, $hideminor, $namespace ) {
	global $wgContLang, $wgScript;

	$namespaceselect = '<form><select name="namespace">';
	$namespaceselect .= '<option value="" '.(is_null($namespace) ? ' selected="selected"' : '').'>'.wfMsg( 'all' ).'</option>';
	$arr = $wgContLang->getNamespaces();
	foreach( array_keys( $arr ) as $i ) {
		if( $i < 0 ) {
			continue;
		}
		$namespacename = str_replace ( "_", " ", $arr[$i] );
		$n = ($i == 0) ? wfMsg ( 'articlenamespace' ) : $namespacename;
		$sel = ($i === $namespace) ? ' selected="selected"' : '';
		$namespaceselect .= "<option value='{$i}'{$sel}>{$n}</option>";
	}
	$namespaceselect .= '</select>';

	$submitbutton = '<input type="submit" value="' . wfMsg( 'allpagessubmit' ) . '" />';

	$out = "<div class='namespaceselector'><form method='get' action='{$wgScript}'>";
	$out .= '<input type="hidden" name="title" value="'.$wgContLang->specialpage( 'Contributions' ).'" />';
	$out .= '<input type="hidden" name="target" value="'.htmlspecialchars( $target ).'" />';
	$out .= '<input type="hidden" name="hideminor" value="'.$hideminor.'" />';	
	$out .= wfMsg ( 'allpagesformtext2', $namespaceselect, $submitbutton );
	$out .= '</form></div>';
	return $out;
}
?>
