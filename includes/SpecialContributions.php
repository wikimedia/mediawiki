<?php
/**
 * Special:Contributions, show user contributions in a paged list
 * @addtogroup SpecialPage
 */

class ContribsPager extends IndexPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;

	function __construct( $target, $namespace = false ) {
		global $wgUser;

		parent::__construct();
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg ) {
			$this->messages[$msg] = wfMsgExt( $msg, array( 'escape') );
		}
		$this->target = $target;
		$this->namespace = $namespace;
		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;
		return $query;
	}

	function getQueryInfo() {
		list( $index, $userCond ) = $this->getUserCond();
		$conds = array_merge( array( 'page_id=rev_page' ), $userCond, $this->getNamespaceCond() );

		return array(
			'tables' => array( 'page', 'revision' ),
			'fields' => array( 
				'page_namespace', 'page_title', 'page_is_new', 'page_latest', 'rev_id', 'rev_page', 
				'rev_text_id', 'rev_timestamp', 'rev_comment', 'rev_minor_edit', 'rev_user', 
				'rev_user_text', 'rev_deleted'
			),
			'conds' => $conds,
			'options' => array( 'FORCE INDEX' => $index )
		);
	}

	function getUserCond() {
		$condition = array();

		if ( $this->target == 'newbies' ) {
			$max = $this->mDb->selectField( 'user', 'max(user_id)', false, __METHOD__ );
			$condition[] = 'rev_user >' . (int)($max - $max / 2/*100*/);
			$index = 'user_timestamp';
		} else {
			$condition['rev_user_text'] = $this->target;
			$index = 'usertext_timestamp';
		}
		return array( $index, $condition );
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			return array( 'page_namespace' => (int)$this->namespace );
		} else {
			return array();
		}
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function getStartBody() {
		return "<ul>\n";
	}

	function getEndBody() {
		return "</ul>\n";
	}

	function getNavigationBar() {
		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}
		$linkTexts = array(
			'prev' => wfMsgHtml( "sp-contributions-newer", $this->mLimit ),
			'next' => wfMsgHtml( 'sp-contributions-older', $this->mLimit ),
			'first' => wfMsgHtml('sp-contributions-newest'),
			'last' => wfMsgHtml( 'sp-contributions-oldest' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = implode( ' | ', $limitLinks );
		
		$this->mNavigationBar = "({$pagingLinks['first']} | {$pagingLinks['last']}) " . 
			wfMsgHtml("viewprevnext", $pagingLinks['prev'], $pagingLinks['next'], $limits);
		return $this->mNavigationBar;
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
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		global $wgLang, $wgUser;

		$sk = $this->getSkin();
		$rev = new Revision( $row );

		$page = Title::makeTitle( $row->page_namespace, $row->page_title );
		$link = $sk->makeKnownLinkObj( $page );
		$difftext = $topmarktext = '';
		if( $row->rev_id == $row->page_latest ) {
			$topmarktext .= '<strong>' . $this->messages['uctop'] . '</strong>';
			if( !$row->page_is_new ) {
				$difftext .= '(' . $sk->makeKnownLinkObj( $page, $this->messages['diff'], 'diff=0' ) . ')';
			} else {
				$difftext .= $this->messages['newarticle'];
			}

			if( $wgUser->isAllowed( 'rollback' ) ) {
				$topmarktext .= ' '.$sk->generateRollback( $rev );
			}

		}
		if( $rev->userCan( Revision::DELETED_TEXT ) ) {
			$difftext = '(' . $sk->makeKnownLinkObj( $page, $this->messages['diff'], 'diff=prev&oldid='.$row->rev_id ) . ')';
		} else {
			$difftext = '(' . $this->messages['diff'] . ')';
		}
		$histlink='('.$sk->makeKnownLinkObj( $page, $this->messages['hist'], 'action=history' ) . ')';

		$comment = $sk->revComment( $rev );
		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $row->rev_timestamp ), true );

		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		}

		if( $row->rev_minor_edit ) {
			$mflag = '<span class="minor">' . $this->messages['minoreditletter'] . '</span> ';
		} else {
			$mflag = '';
		}

		$ret = "{$d} {$histlink} {$difftext} {$mflag} {$link} {$comment} {$topmarktext}";
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$ret .= ' ' . wfMsgHtml( 'deletedrev' );
		}
		$ret = "<li>$ret</li>\n";
		wfProfileOut( __METHOD__ );
		return $ret;
	}
}

/**
 * Special page "user contributions".
 * Shows a list of the contributions of a user.
 *
 * @return	none
 * @param	$par	String: (optional) user name of the user for which to show the contributions
 */
function wfSpecialContributions( $par = null ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest;

	$options = array();
	
	if ( isset( $par ) && $par == 'newbies' ) {
		$target = 'newbies';
		$options['contribs'] = 'newbie';
	} elseif ( isset( $par ) ) {
		$target = $par;
	} else {
		$target = $wgRequest->getVal( 'target' );
	}

	// check for radiobox
	if ( $wgRequest->getVal( 'contribs' ) == 'newbie' ) {
		$target = 'newbies';
		$options['contribs'] = 'newbie';
	}

	if ( !strlen( $target ) ) {
		$wgOut->addHTML( contributionsForm( '' ) );
		return;
	}

	$options['limit'] = $wgRequest->getInt( 'limit', 50 );
	$options['target'] = $target;

	$nt = Title::makeTitleSafe( NS_USER, $target );
	if ( !$nt ) {
		$wgOut->addHTML( contributionsForm( '' ) );
		return;
	}
	$id = User::idFromName( $nt->getText() );

	if ( $target != 'newbies' ) {
		$target = $nt->getText();
		$wgOut->setSubtitle( contributionsSub( $nt, $id ) );
	} else {
		$wgOut->setSubtitle( wfMsgHtml( 'sp-contributions-newbies-sub') );
	}
	
	if ( ( $ns = $wgRequest->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
		$options['namespace'] = intval( $ns );
	} else {
		$options['namespace'] = '';
	}
	if ( $wgUser->isAllowed( 'rollback' ) && $wgRequest->getBool( 'bot' ) ) {
		$options['bot'] = '1';
	}

	wfRunHooks( 'SpecialContributionsBeforeMainOutput', $id );

	$wgOut->addHTML( contributionsForm( $options ) );

	$pager = new ContribsPager( $target, $options['namespace'] );
	if ( !$pager->getNumRows() ) {
		$wgOut->addWikiText( wfMsg( 'nocontribs' ) );
		return;
	}
	$wgOut->addHTML( 
		'<p>' . $pager->getNavigationBar() . '</p>' .
		$pager->getBody() .
		'<p>' . $pager->getNavigationBar() . '</p>' );
	
	# If there were contributions, and it was a valid user or IP, show
	# the appropriate "footer" message - WHOIS tools, etc.
	if( $target != 'newbies' ) {
		$message = IP::isIPAddress( $target )      
			? 'sp-contributions-footer-anon'
			: 'sp-contributions-footer';


		$text = wfMsg( $message, $target );
		if( !wfEmptyMsg( $message, $text ) && $text != '-' ) {
			$wgOut->addHtml( '<div class="mw-contributions-footer">' );
			$wgOut->addWikiText( $text );
			$wgOut->addHtml( '</div>' );
		}
	}
}

/**
 * Generates the subheading with links
 * @param Title $nt Title object for the target
 * @param integer $id User ID for the target
 * @return String: appropriately-escaped HTML to be output literally
 */
function contributionsSub( $nt, $id ) {
	global $wgSysopUserBans, $wgLang, $wgUser;

	$sk = $wgUser->getSkin();

	if ( 0 == $id ) {
		$user = $nt->getText();
	} else {
		$user = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
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
		$links = implode( ' | ', $tools );
	}

	// Old message 'contribsub' had one parameter, but that doesn't work for
	// languages that want to put the "for" bit right after $user but before
	// $links.  If 'contribsub' is around, use it for reverse compatibility,
	// otherwise use 'contribsub2'.
	if( wfEmptyMsg( 'contribsub', wfMsg( 'contribsub' ) ) ) {
		return wfMsgHtml( 'contribsub2', $user, $links );
	} else {
		return wfMsgHtml( 'contribsub', "$user ($links)" );
	}
}

/**
 * Generates the namespace selector form with hidden attributes.
 * @param $options Array: the options to be included.
 */
function contributionsForm( $options ) {
	global $wgScript, $wgTitle, $wgRequest;

	$options['title'] = $wgTitle->getPrefixedText();
	if ( !isset( $options['target'] ) ) {
		$options['target'] = '';
	} else {
		$options['target'] = str_replace( '_' , ' ' , $options['target'] );
	}

	if ( !isset( $options['namespace'] ) ) {
		$options['namespace'] = '';
	}

	if ( !isset( $options['contribs'] ) ) {
		$options['contribs'] = 'user';
	}

	if ( $options['contribs'] == 'newbie' ) {
		$options['target'] = '';
	}

	$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );

	foreach ( $options as $name => $value ) {
		if ( in_array( $name, array( 'namespace', 'target', 'contribs' ) ) ) {
			continue;
		}
		$f .= "\t" . Xml::hidden( $name, $value ) . "\n";
	}

	$f .= '<fieldset>' .
		Xml::element( 'legend', array(), wfMsg( 'sp-contributions-search' ) ) .
		Xml::radioLabel( wfMsgExt( 'sp-contributions-newbies', array( 'parseinline' ) ), 'contribs' , 'newbie' , 'newbie', $options['contribs'] == 'newbie' ? true : false ) . '<br />' .
		Xml::radioLabel( wfMsgExt( 'sp-contributions-username', array( 'parseinline' ) ), 'contribs' , 'user', 'user', $options['contribs'] == 'user' ? true : false ) . ' ' .
		Xml::input( 'target', 20, $options['target']) . ' '.
		Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
		Xml::namespaceSelector( $options['namespace'], '' ) .
		Xml::submitButton( wfMsg( 'sp-contributions-submit' ) ) .
		'</fieldset>' .
		Xml::closeElement( 'form' );
	return $f;
}


?>
