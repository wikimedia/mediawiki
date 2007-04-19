<?php
/**
 * @addtogroup SpecialPage
 */

class ContribsFinder {
	var $username, $offset, $limit, $namespace;
	var $dbr;

	/**
	 * Constructor
	 * @param $username Username as a string
	*/
	function ContribsFinder( $username ) {
		$this->username = $username;
		$this->namespace = false;
		$this->dbr = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function setNamespace( $ns ) {
		$this->namespace = $ns;
	}

	function setLimit( $limit ) {
		$this->limit = $limit;
	}

	function setOffset( $offset ) {
		$this->offset = $offset;
	}

	/**
	 * Get timestamp of either first or last contribution made by the user.
	 * @todo Maybe it should be private ?
	 * @param $dir string 'ASC' or 'DESC'.
	 * @return Revision timestamp (rev_timestamp).
	*/
	function getEditLimit( $dir ) {
		list( $index, $usercond ) = $this->getUserCond();
		$nscond = $this->getNamespaceCond();
		$use_index = $this->dbr->useIndexClause( $index );
		list( $revision, $page) = $this->dbr->tableNamesN( 'revision', 'page' );
		$sql =	"SELECT rev_timestamp " .
			" FROM $page,$revision $use_index " .
			" WHERE rev_page=page_id AND $usercond $nscond" .
			" ORDER BY rev_timestamp $dir LIMIT 1";

		$res = $this->dbr->query( $sql, __METHOD__ );
		$row = $this->dbr->fetchObject( $res );
		if ( $row ) {
			return $row->rev_timestamp;
		} else {
			return false;
		}
	}

	/**
	 * Get timestamps of first and last contributions made by the user.
	 * @return Array containing first rev_timestamp and last rev_timestamp.
	*/
	function getEditLimits() {
		return array(
			$this->getEditLimit( "ASC" ),
			$this->getEditLimit( "DESC" )
		);
	}

	function getUserCond() {
		$condition = '';

		if ( $this->username == 'newbies' ) {
			$max = $this->dbr->selectField( 'user', 'max(user_id)', false, 'make_sql' );
			$condition = '>' . (int)($max - $max / 100);
		}

		if ( $condition == '' ) {
			$condition = ' rev_user_text=' . $this->dbr->addQuotes( $this->username );
			$index = 'usertext_timestamp';
		} else {
			$condition = ' rev_user '.$condition ;
			$index = 'user_timestamp';
		}
		return array( $index, $condition );
	}

	function getNamespaceCond() {
		if ( $this->namespace !== false )
			return ' AND page_namespace = ' . (int)$this->namespace;
		return '';
	}

	/**
	 * @return Timestamp of first entry in previous page.
	*/
	function getPreviousOffsetForPaging() {
		list( $index, $usercond ) = $this->getUserCond();
		$nscond = $this->getNamespaceCond();

		$use_index = $this->dbr->useIndexClause( $index );
		list( $page, $revision ) = $this->dbr->tableNamesN( 'page', 'revision' );

		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND rev_timestamp > '" . $this->offset . "' AND " .
			$usercond . $nscond;
		$sql .=	" ORDER BY rev_timestamp ASC";
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		$res = $this->dbr->query( $sql );

		$numRows = $this->dbr->numRows( $res );
		if ( $numRows ) {
			$this->dbr->dataSeek( $res, $numRows - 1 );
			$row = $this->dbr->fetchObject( $res );
			$offset = $row->rev_timestamp;
		} else {
			$offset = false;
		}
		$this->dbr->freeResult( $res );
		return $offset;
	}

	/**
	 * @return Timestamp of first entry in next page.
	*/
	function getFirstOffsetForPaging() {
		list( $index, $usercond ) = $this->getUserCond();
		$use_index = $this->dbr->useIndexClause( $index );
		list( $page, $revision ) = $this->dbr->tableNamesN( 'page', 'revision' );
		$nscond = $this->getNamespaceCond();
		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND " .
			$usercond . $nscond;
		$sql .=	" ORDER BY rev_timestamp ASC";
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		$res = $this->dbr->query( $sql );

		$numRows = $this->dbr->numRows( $res );
		if ( $numRows ) {
			$this->dbr->dataSeek( $res, $numRows - 1 );
			$row = $this->dbr->fetchObject( $res );
			$offset = $row->rev_timestamp;
		} else {
			$offset = false;
		}
		$this->dbr->freeResult( $res );
		return $offset;
	}

	/* private */ function makeSql() {
		$offsetQuery = '';

		list( $page, $revision ) = $this->dbr->tableNamesN( 'page', 'revision' );
		list( $index, $userCond ) = $this->getUserCond();

		if ( $this->offset )
			$offsetQuery = "AND rev_timestamp < '{$this->offset}'";

		$nscond = $this->getNamespaceCond();
		$use_index = $this->dbr->useIndexClause( $index );
		$sql = 'SELECT ' .
			'page_namespace,page_title,page_is_new,page_latest,'.
			'rev_id,rev_page,rev_text_id,rev_timestamp,rev_comment,rev_minor_edit,rev_user,rev_user_text,'.
			'rev_deleted ' .
			"FROM $page,$revision $use_index " .
			"WHERE page_id=rev_page AND $userCond $nscond $offsetQuery " .
		 	'ORDER BY rev_timestamp DESC';
		$sql = $this->dbr->limitResult( $sql, $this->limit, 0 );
		return $sql;
	}

	/**
	 * This do the search for the user given when creating the object.
	 * It should probably be the only public function in this class.
	 * @return Array of contributions.
	*/
	function find() {
		$contribs = array();
		$res = $this->dbr->query( $this->makeSql(), __METHOD__ );
		while ( $c = $this->dbr->fetchObject( $res ) )
			$contribs[] = $c;
		$this->dbr->freeResult( $res );
		return $contribs;
	}
};

/**
 * Special page "user contributions".
 * Shows a list of the contributions of a user.
 *
 * @return	none
 * @param	$par	String: (optional) user name of the user for which to show the contributions
 */
function wfSpecialContributions( $par = null ) {
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

	$title = SpecialPage::getTitleFor( 'Contributions' );
	$options['target'] = $target;

	$nt =& Title::makeTitle( NS_USER, $nt->getDBkey() );
	$finder = new ContribsFinder( ( $target == 'newbies' ) ? 'newbies' : $nt->getText() );
	$finder->setLimit( $options['limit'] );
	$finder->setOffset( $options['offset'] );

	if ( ( $ns = $wgRequest->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
		$options['namespace'] = intval( $ns );
		$finder->setNamespace( $options['namespace'] );
	} else {
		$options['namespace'] = '';
	}

	if ( $wgUser->isAllowed( 'rollback' ) && $wgRequest->getBool( 'bot' ) ) {
		$options['bot'] = '1';
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

	if ( $wgRequest->getText( 'go' ) == 'first' && $target != 'newbies') {
		$offset = $finder->getFirstOffsetForPaging();
		if ( $offset !== false ) {
			$options['offset'] = $offset;
			$prevurl = $title->getLocalURL( wfArrayToCGI( $options ) );
			$wgOut->redirect( $prevurl );
			return;
		}
	}

	if ( $target == 'newbies' ) {
		$wgOut->setSubtitle( wfMsgHtml( 'sp-contributions-newbies-sub') );
	} else {
		$wgOut->setSubtitle( wfMsgHtml( 'contribsub', contributionsSub( $nt ) ) );
	}

	$id = User::idFromName( $nt->getText() );
	wfRunHooks( 'SpecialContributionsBeforeMainOutput', $id );

	$wgOut->addHTML( contributionsForm( $options) );

	$contribs = $finder->find();

	if ( count( $contribs ) == 0) {
		$wgOut->addWikiText( wfMsg( 'nocontribs' ) );
		return;
	}

	list( $early, $late ) = $finder->getEditLimits();
	$lastts = count( $contribs ) ? $contribs[count( $contribs ) - 1]->rev_timestamp : 0;
	$atstart = ( !count( $contribs ) || $late == $contribs[0]->rev_timestamp );
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

	if ( $target == 'newbies' ) {
		$firstlast ="($newestlink)";
	} else {
		$firstlast = "($newestlink | $oldestlink)";
	}

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
		$wgOut->addHTML( ucListEdit( $sk, $contrib ) );

	$wgOut->addHTML( "</ul>\n" );
	$wgOut->addHTML( "<p>{$prevnextbits}</p>\n" );
	
	# If there were contributions, and it was a valid user or IP, show
	# the appropriate "footer" message - WHOIS tools, etc.
	if( count( $contribs ) > 0 && $target != 'newbies' && $nt instanceof Title ) {
		$message = IP::isIPAddress( $target )      
			? 'sp-contributions-footer-anon'
			: 'sp-contributions-footer';
		$text = wfMsg( $message, $target );
		if( !wfEmptyMsg( $message, $text ) && $text != '-' ) {
			$wgOut->addHtml( '<div class="mw-contributions-footer">' );
			$wgOut->addWikiText( wfMsg( $message, $target ) );
			$wgOut->addHtml( '</div>' );
		}
	}
	
}

/**
 * Generates the subheading with links
 * @param $nt @see Title object for the target
 */
function contributionsSub( $nt ) {
	global $wgSysopUserBans, $wgLang, $wgUser;

	$sk = $wgUser->getSkin();
	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
	}
	$talk = $nt->getTalkPage();
	if( $talk ) {
		# Talk page link
		$tools[] = $sk->makeLinkObj( $talk, $wgLang->getNsText( NS_TALK ) );
		if( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && User::isIP( $nt->getText() ) ) ) {
			# Block link
			if( $wgUser->isAllowed( 'block' ) )
				$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Blockip', $nt->getDBkey() ), wfMsgHtml( 'blocklink' ) );
			# Block log link
			$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ), wfMsgHtml( 'sp-contributions-blocklog' ), 'type=block&page=' . $nt->getPrefixedUrl() );
		}
		# Other logs link
		$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ), wfMsgHtml( 'log' ), 'user=' . $nt->getPartialUrl() );
		$ul .= ' (' . implode( ' | ', $tools ) . ')';
	}
	return $ul;
}

/**
 * Generates the namespace selector form with hidden attributes.
 * @param $options Array: the options to be included.
 */
function contributionsForm( $options ) {
	global $wgScript, $wgTitle;

	$options['title'] = $wgTitle->getPrefixedText();

	$f = "<form method='get' action=\"$wgScript\">\n";
	foreach ( $options as $name => $value ) {
		if( $name === 'namespace') continue;
		$f .= "\t" . wfElement( 'input', array(
			'name' => $name,
			'type' => 'hidden',
			'value' => $value ) ) . "\n";
	}

	$f .= '<p>' . wfMsgHtml( 'namespace' ) . ' ' .
	HTMLnamespaceselector( $options['namespace'], '' ) .
	wfElement( 'input', array(
			'type' => 'submit',
			'value' => wfMsg( 'allpagessubmit' ) )
	) .
	"</p></form>\n";

	return $f;
}

/**
 * Generates each row in the contributions list.
 *
 * Contributions which are marked "top" are currently on top of the history.
 * For these contributions, a [rollback] link is shown for users with sysop
 * privileges. The rollback link restores the most recent version that was not
 * written by the target user.
 *
 * @todo This would probably look a lot nicer in a table.
 */
function ucListEdit( $sk, $row ) {
	$fname = 'ucListEdit';
	wfProfileIn( $fname );

	global $wgLang, $wgUser, $wgRequest;
	static $messages;
	if( !isset( $messages ) ) {
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg ) {
			$messages[$msg] = wfMsgExt( $msg, array( 'escape') );
		}
	}

	$rev = new Revision( $row );

	$page = Title::makeTitle( $row->page_namespace, $row->page_title );
	$link = $sk->makeKnownLinkObj( $page );
	$difftext = $topmarktext = '';
	if( $row->rev_id == $row->page_latest ) {
		$topmarktext .= '<strong>' . $messages['uctop'] . '</strong>';
		if( !$row->page_is_new ) {
			$difftext .= '(' . $sk->makeKnownLinkObj( $page, $messages['diff'], 'diff=0' ) . ')';
		} else {
			$difftext .= $messages['newarticle'];
		}

		if( $wgUser->isAllowed( 'rollback' ) ) {
			$topmarktext .= ' '.$sk->generateRollback( $rev );
		}

	}
	if( $rev->userCan( Revision::DELETED_TEXT ) ) {
		$difftext = '(' . $sk->makeKnownLinkObj( $page, $messages['diff'], 'diff=prev&oldid='.$row->rev_id ) . ')';
	} else {
		$difftext = '(' . $messages['diff'] . ')';
	}
	$histlink='('.$sk->makeKnownLinkObj( $page, $messages['hist'], 'action=history' ) . ')';

	$comment = $sk->revComment( $rev );
	$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $row->rev_timestamp ), true );

	if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
		$d = '<span class="history-deleted">' . $d . '</span>';
	}

	if( $row->rev_minor_edit ) {
		$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
	} else {
		$mflag = '';
	}

	$ret = "{$d} {$histlink} {$difftext} {$mflag} {$link} {$comment} {$topmarktext}";
	if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
		$ret .= ' ' . wfMsgHtml( 'deletedrev' );
	}
	$ret = "<li>$ret</li>\n";
	wfProfileOut( $fname );
	return $ret;
}

?>
