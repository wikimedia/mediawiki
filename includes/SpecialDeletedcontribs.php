<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** @package MediaWiki */
class DeletedContribsFinder extends ContribsFinder {
	var $username, $offset, $limit, $namespace;
	var $dbr;

	function getEditLimit( $dir ) {
		list( $index, $usercond ) = $this->getUserCond();
		$nscond = $this->getNamespaceCond();
		$use_index = $this->dbr->useIndexClause( $index );
		list( $archive ) = $this->dbr->tableNamesN( 'archive' );
		$sql =	"SELECT ar_timestamp " .
			" FROM $archive $use_index " .
			" WHERE $usercond $nscond" .
			" ORDER BY ar_timestamp $dir LIMIT 1";

		$res = $this->dbr->query( $sql, __METHOD__ );
		$row = $this->dbr->fetchObject( $res );
		if ( $row ) {
			return $row->ar_timestamp;
		} else {
			return false;
		}
	}

	function getUserCond() {
		$condition = ' ar_user_text=' . $this->dbr->addQuotes( $this->username );
		$index = 'usertext_timestamp';
		return array( $index, $condition );
	}

	function getNamespaceCond() {
		if ( $this->namespace !== false )
			return ' AND ar_namespace = ' . (int)$this->namespace;
		return '';
	}

	# The following functions share a lot of code with each other and with
	# their counterparts in ContribsFinder.  Some refactoring should help.

	function getPreviousOffsetForPaging() {
		list( $index, $usercond ) = $this->getUserCond();
		$nscond = $this->getNamespaceCond();

		$use_index = $this->dbr->useIndexClause( $index );
		list( $archive ) = $this->dbr->tableNamesN( 'archive' );

		$sql =	"SELECT ar_timestamp FROM $archive $use_index " .
			"WHERE ar_timestamp > '" . $this->offset . "' AND " .
			$usercond . $nscond;
		$sql .=	" ORDER BY ar_timestamp ASC";
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		$res = $this->dbr->query( $sql );
		
		$numRows = $this->dbr->numRows( $res );
		if ( $numRows ) {
			$this->dbr->dataSeek( $res, $numRows - 1 );
			$row = $this->dbr->fetchObject( $res );
			$offset = $row->ar_timestamp;
		} else {
			$offset = false;
		}
		$this->dbr->freeResult( $res );
		return $offset;
	}

	function getFirstOffsetForPaging() {
		list( $index, $usercond ) = $this->getUserCond();
		$use_index = $this->dbr->useIndexClause( $index );
		list( $archive ) = $this->dbr->tableNamesN( 'archive' );
		$nscond = $this->getNamespaceCond();
		$sql =	"SELECT ar_timestamp FROM $archive $use_index " .
			"WHERE " .
			$usercond . $nscond;
		$sql .=	" ORDER BY ar_timestamp ASC";
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		$res = $this->dbr->query( $sql );
		
		$numRows = $this->dbr->numRows( $res );
		if ( $numRows ) {
			$this->dbr->dataSeek( $res, $numRows - 1 );
			$row = $this->dbr->fetchObject( $res );
			$offset = $row->ar_timestamp;
		} else {
			$offset = false;
		}
		$this->dbr->freeResult( $res );
		return $offset;
	}

	/* private */ function makeSql() {
		$offsetQuery = '';

		list( $archive ) = $this->dbr->tableNamesN( 'archive' );
		list( $index, $userCond ) = $this->getUserCond();

		if ( $this->offset )
			$offsetQuery = "AND ar_timestamp <= '{$this->offset}'";

		$nscond = $this->getNamespaceCond();
		$use_index = $this->dbr->useIndexClause( $index );
		$sql = "SELECT
			ar_namespace,ar_title,
			ar_rev_id,ar_text_id,ar_timestamp,ar_comment,ar_minor_edit,ar_user,ar_user_text
			FROM $archive $use_index
			WHERE $userCond $nscond $offsetQuery
		 	ORDER BY ar_timestamp DESC";
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		return $sql;
	}
};

/**
 * Special page "deleted contributions".
 * Shows a list of the deleted contributions of a user.
 *
 * XXX This is almost and exact duplicate of the code in
 * XXX SpecialContributions.php and should be merged with it.
 *
 * @return	none
 * @param	$par	String: (optional) user name of the user for which to show the contributions
 */
function wfSpecialDeletedcontribs( $par = null ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest;

	$target = isset( $par ) ? $par : $wgRequest->getVal( 'target' );
	if ( !strlen( $target ) ) {
		$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
		return;
	}

	$nt = Title::newFromURL( $target );
	if ( !$nt ) {
		$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
		return;
	}

	$options = array();

	list( $options['limit'], $options['offset']) = wfCheckLimits();
	$options['offset'] = $wgRequest->getVal( 'offset' );
	/* Offset must be an integral. */
	if ( !strlen( $options['offset'] ) || !preg_match( '/^[0-9]+$/', $options['offset'] ) )
		$options['offset'] = '';

	$title = SpecialPage::getTitleFor( 'Deletedcontribs' );
	$options['target'] = $target;

	$nt =& Title::makeTitle( NS_USER, $nt->getDBkey() );
	$finder = new DeletedContribsFinder( $nt->getText() );
	$finder->setLimit( $options['limit'] );
	$finder->setOffset( $options['offset'] );

	if ( ( $ns = $wgRequest->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
		$options['namespace'] = intval( $ns );
		$finder->setNamespace( $options['namespace'] );
	} else {
		$options['namespace'] = '';
	}

	if ( $wgRequest->getText( 'go' ) == 'prev' ) {
		$offset = $finder->getPreviousOffsetForPaging();
		if ( $offset !== false ) {
			$options['offset'] = $offset;
			$prevurl = $title->getLocalURL( wfArrayToCGI( $options ) );
			$wgOut->redirect( $prevurl );
			return;
		}
	}

	if ( $wgRequest->getText( 'go' ) == 'first' ) {
		$offset = $finder->getFirstOffsetForPaging();
		if ( $offset !== false ) {
			$options['offset'] = $offset;
			$prevurl = $title->getLocalURL( wfArrayToCGI( $options ) );
			$wgOut->redirect( $prevurl );
			return;
		}
	}

	$wgOut->setSubtitle( wfMsgHtml( 'contribsub', contributionsSub( $nt ) ) );

	$id = User::idFromName( $nt->getText() );
	wfRunHooks( 'SpecialDeletedcontribsBeforeMainOutput', $id );

	$wgOut->addHTML( contributionsForm( $options) );

	$contribs = $finder->find();

	if ( count( $contribs ) == 0) {
		$wgOut->addWikiText( wfMsg( 'nocontribs' ) );
		return;
	}

	list( $early, $late ) = $finder->getEditLimits();
	$lastts = count( $contribs ) ? $contribs[count( $contribs ) - 1]->ar_timestamp : 0;
	$atstart = ( !count( $contribs ) || $late == $contribs[0]->ar_timestamp );
	$atend = ( !count( $contribs ) || $early == $lastts );

	// These four are defaults
	$newestlink = wfMsgHtml( 'sp-contributions-newest' );
	$oldestlink = wfMsgHtml( 'sp-contributions-oldest' );
	$newerlink  = wfMsgHtml( 'sp-contributions-newer', $options['limit'] );
	$olderlink  = wfMsgHtml( 'sp-contributions-older', $options['limit'] );

	if ( !$atstart ) {
		$stuff = $title->escapeLocalURL( wfArrayToCGI( array( 'offset' => '' ), $options ) );
		$newestlink = "<a href=\"$stuff\">$newestlink</a>";
		$stuff = $title->escapeLocalURL( wfArrayToCGI( array( 'go' => 'prev' ), $options ) );
		$newerlink = "<a href=\"$stuff\">$newerlink</a>";
	}

	if ( !$atend ) {
		$stuff = $title->escapeLocalURL( wfArrayToCGI( array( 'go' => 'first' ), $options ) );
		$oldestlink = "<a href=\"$stuff\">$oldestlink</a>";
		$stuff = $title->escapeLocalURL( wfArrayToCGI( array( 'offset' => $lastts ), $options ) );
		$olderlink = "<a href=\"$stuff\">$olderlink</a>";
	}

	$firstlast = "($newestlink | $oldestlink)";

	$urls = array();
	foreach ( array( 20, 50, 100, 250, 500 ) as $num ) {
		$stuff = $title->escapeLocalURL( wfArrayToCGI( array( 'limit' => $num ), $options ) );
		$urls[] = "<a href=\"$stuff\">".$wgLang->formatNum( $num )."</a>";
	}
	$bits = implode( $urls, ' | ' );

	$prevnextbits = $firstlast .' '. wfMsgHtml( 'viewprevnext', $newerlink, $olderlink, $bits );

	$wgOut->addHTML( "<p>{$prevnextbits}</p>\n" );

	$wgOut->addHTML( "<ul>\n" );

	$sk = $wgUser->getSkin();
	foreach ( $contribs as $contrib )
		$wgOut->addHTML( dcListEdit( $sk, $contrib ) );

	$wgOut->addHTML( "</ul>\n" );
	$wgOut->addHTML( "<p>{$prevnextbits}</p>\n" );
}


/**
 * Generates each row in the deleted contributions list.
 *
 * @todo This would probably look a lot nicer in a table.
 */
function dcListEdit( $sk, $row ) {
	$fname = 'dcListEdit';
	wfProfileIn( $fname );

	global $wgLang, $wgUser, $wgRequest;
	static $messages, $undelete;
	if( !isset( $messages ) ) {
		foreach( explode( ' ', 'undel minoreditletter' ) as $msg ) {
			$messages[$msg] = wfMsgExt( $msg, array( 'escape') );
		}
	}
	if( !isset( $undelete ) ) {
		$undelete =& SpecialPage::getTitleFor( 'Undelete' );
	}

	$page = Title::makeTitle( $row->ar_namespace, $row->ar_title )->getPrefixedText();

	$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
	$d = $wgLang->timeanddate( $ts, true );

	$link = $sk->makeKnownLinkObj( $undelete, $page, "target=$page&timestamp=$ts" );
	$undellink = '(' . $sk->makeKnownLinkObj( $undelete, $messages['undel'], "target=$page" ) . ')';

	if( $row->ar_minor_edit ) {
		$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
	} else {
		$mflag = '';
	}

	$comment = $sk->commentBlock( $row->ar_comment );

	$ret = "<li>{$d}{$mflag} {$link} {$undellink} {$comment}</li>";
	wfProfileOut( $fname );
	return $ret;
}

?>
