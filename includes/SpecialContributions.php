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
		$minorQuery = "AND rev_minor_edit=0";
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( "Contributions" ),
	  	  WfMsg( "show" ), "target=" . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=0&namespace={$namespace}" );
	} else {
		$minorQuery = "";
		$mlink = $sk->makeKnownLink( $wgContLang->specialPage( "Contributions" ),
	  	  WfMsg( 'hide' ), 'target=' . htmlspecialchars( $nt->getPrefixedURL() ) .
		  "&offset={$offset}&limit={$limit}&hideminor=1&namespace={$namespace}" );
	}
	
	if( !is_null($namespace) ) {
		$minorQuery .= " AND page_namespace = {$namespace}";
	}
	
	extract( $dbr->tableNames( 'old', 'cur' ) );
	if ( $userCond == "" ) {
		$condition = "rev_user_text=" . $dbr->addQuotes( $nt->getText() );
		$index = 'usertext_timestamp';
	} else {
		$condition = "rev_user {$userCond}";
		$index = 'user_timestamp';
	}
	$page = $dbr->tableName( 'page' );
	$revision = $dbr->tableName( 'revision' );
	$sql = "SELECT
		page_namespace,page_title,page_is_new,page_latest,
		rev_id,rev_timestamp,rev_comment,rev_minor_edit,rev_user_text
		FROM $page,$revision USE INDEX($index)
		WHERE page_id=rev_page AND $condition $minorQuery " .
	  "ORDER BY inverse_timestamp LIMIT {$querylimit}";
	$res = $dbr->query( $sql, $fname );
	$numRows = $dbr->numRows( $res );

	$wgOut->addHTML( namespaceForm( $target, $hideminor, $namespace ) );

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgContLang->specialpage( "Contributions" ),
	  "hideminor={$hideminor}&namespace={$namespace}&target=" . wfUrlEncode( $target ),
	  ($numRows) <= $offlimit);

	$shm = wfMsg( "showhideminor", $mlink );
	$wgOut->addHTML( "<br />{$sl} ($shm)</p>\n");


	if ( 0 == $numRows ) {
		$wgOut->addHTML( "\n<p>" . wfMsg( "nocontribs" ) . "</p>\n" );
		return;
	}

	$wgOut->addHTML( "<ul>\n" );
	while( $obj = $dbr->fetchObject( $res ) ) {
		ucListEdit( $sk,
			$obj->page_namespace,
			$obj->page_title,
			$obj->rev_timestamp,
			($obj->rev_id == $obj->page_latest),
			$obj->rev_comment,
			($obj->rev_minor_edit),
			$obj->page_is_new,
			$obj->rev_user_text,
			$obj->rev_id );
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
function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment, $isminor, $isnew, $target, $oldid ) {
	$fname = 'ucListEdit';
	wfProfileIn( $fname );
	
	global $wgLang, $wgOut, $wgUser, $wgRequest;
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

	$wgOut->addHTML( "<li>{$d} {$histlink} {$difftext} {$mflag} {$link} {$comment} {$topmarktext}</li>\n" );
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
