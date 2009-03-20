<?php
/**
 * Implements Special:DeletedContributions to display archived revisions
 * @ingroup SpecialPage
 */

class DeletedContribsPager extends IndexPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;

	function __construct( $target, $namespace = false ) {
		parent::__construct();
		foreach( explode( ' ', 'deletionlog undeletebtn minoreditletter diff' ) as $msg ) {
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
		global $wgUser;
		list( $index, $userCond ) = $this->getUserCond();
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		// Paranoia: avoid brute force searches (bug 17792)
		if( !$wgUser->isAllowed( 'suppressrevision' ) ) {
			$conds[] = 'ar_deleted & ' . Revision::DELETED_USER . ' = 0';
		}
		return array(
			'tables' => array( 'archive' ),
			'fields' => array(
				'ar_rev_id', 'ar_namespace', 'ar_title', 'ar_timestamp', 'ar_comment', 'ar_minor_edit',
				'ar_user', 'ar_user_text', 'ar_deleted'
			),
			'conds' => $conds,
			'options' => array( 'USE INDEX' => $index )
		);
	}

	function getUserCond() {
		$condition = array();

		$condition['ar_user_text'] = $this->target;
		$index = 'usertext_timestamp';

		return array( $index, $condition );
	}

	function getIndexField() {
		return 'ar_timestamp';
	}

	function getStartBody() {
		return "<ul>\n";
	}

	function getEndBody() {
		return "</ul>\n";
	}

	function getNavigationBar() {
		global $wgLang;

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}
		$linkTexts = array(
			'prev' => wfMsgHtml( 'pager-newer-n', $this->mLimit ),
			'next' => wfMsgHtml( 'pager-older-n', $this->mLimit ),
			'first' => wfMsgHtml( 'histlast' ),
			'last' => wfMsgHtml( 'histfirst' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $wgLang->pipeList( $limitLinks );

		$this->mNavigationBar = "(" . $wgLang->pipeList( array( $pagingLinks['first'], $pagingLinks['last'] ) ) . ") " .
			wfMsgExt( 'viewprevnext', array( 'parsemag' ), $pagingLinks['prev'], $pagingLinks['next'], $limits );
		return $this->mNavigationBar;
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			return array( 'ar_namespace' => (int)$this->namespace );
		} else {
			return array();
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
	 * @todo This would probably look a lot nicer in a table.
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		global $wgLang, $wgUser;

		$sk = $this->getSkin();

		$rev = new Revision( array(
				'id'         => $row->ar_rev_id,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'deleted' => $row->ar_deleted,
				) );

		$page = Title::makeTitle( $row->ar_namespace, $row->ar_title );

		$undelete = SpecialPage::getTitleFor( 'Undelete' );

		$logs = SpecialPage::getTitleFor( 'Log' );
		$dellog = $sk->makeKnownLinkObj( $logs,
			$this->messages['deletionlog'],
			'type=delete&page=' . $page->getPrefixedUrl() );

		$reviewlink = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Undelete', $page->getPrefixedDBkey() ),
			$this->messages['undeletebtn'] );

		$link = $sk->makeKnownLinkObj( $undelete,
			htmlspecialchars( $page->getPrefixedText() ),
			'target=' . $page->getPrefixedUrl() .
			'&timestamp=' . $rev->getTimestamp() );

		$last = $sk->makeKnownLinkObj( $undelete,
			$this->messages['diff'],
			"target=" . $page->getPrefixedUrl() .
			"&timestamp=" . $rev->getTimestamp() .
			"&diff=prev" );

		$comment = $sk->revComment( $rev );
		$d = $wgLang->timeanddate( $rev->getTimestamp(), true );

		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		} else {
			$link = $sk->makeKnownLinkObj( $undelete, $d,
				'target=' . $page->getPrefixedUrl() .
				'&timestamp=' . $rev->getTimestamp() );
		}

		$pagelink = $sk->makeLinkObj( $page );

		if( $rev->isMinor() ) {
			$mflag = '<span class="minor">' . $this->messages['minoreditletter'] . '</span> ';
		} else {
			$mflag = '';
		}


		$ret = "{$link} ($last) ({$dellog}) ({$reviewlink}) . . {$mflag} {$pagelink} {$comment}";
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$ret .= ' ' . wfMsgHtml( 'deletedrev' );
		}

		$ret = "<li>$ret</li>\n";

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get the Database object in use
	 *
	 * @return Database
	 */
	public function getDatabase() {
		return $this->mDb;
	}
}

class DeletedContributionsPage extends SpecialPage {
	function __construct() {
		parent::__construct( 'DeletedContributions', 'deletedhistory',
		/*listed*/ true, /*function*/ false, /*file*/ false );
	}

	/**
	 * Special page "deleted user contributions".
	 * Shows a list of the deleted contributions of a user.
	 *
	 * @return	none
	 * @param	$par	String: (optional) user name of the user for which to show the contributions
	 */
	function execute( $par ) {
		global $wgUser;
		$this->setHeaders();

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		global $wgUser, $wgOut, $wgLang, $wgRequest;

		$wgOut->setPageTitle( wfMsgExt( 'deletedcontributions-title', array( 'parsemag' ) ) );

		$options = array();

		if ( isset( $par ) ) {
			$target = $par;
		} else {
			$target = $wgRequest->getVal( 'target' );
		}

		if ( !strlen( $target ) ) {
			$wgOut->addHTML( $this->getForm( '' ) );
			return;
		}

		$options['limit'] = $wgRequest->getInt( 'limit', 50 );
		$options['target'] = $target;

		$nt = Title::makeTitleSafe( NS_USER, $target );
		if ( !$nt ) {
			$wgOut->addHTML( $this->getForm( '' ) );
			return;
		}
		$id = User::idFromName( $nt->getText() );

		$target = $nt->getText();
		$wgOut->setSubtitle( $this->getSubTitle( $nt, $id ) );

		if ( ( $ns = $wgRequest->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
			$options['namespace'] = intval( $ns );
		} else {
			$options['namespace'] = '';
		}

		$wgOut->addHTML( $this->getForm( $options ) );

		$pager = new DeletedContribsPager( $target, $options['namespace'] );
		if ( !$pager->getNumRows() ) {
			$wgOut->addWikiText( wfMsg( 'nocontribs' ) );
			return;
		}

		# Show a message about slave lag, if applicable
		if( ( $lag = $pager->getDatabase()->getLag() ) > 0 )
			$wgOut->showLagWarning( $lag );

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


			$text = wfMsgNoTrans( $message, $target );
			if( !wfEmptyMsg( $message, $text ) && $text != '-' ) {
				$wgOut->addHTML( '<div class="mw-contributions-footer">' );
				$wgOut->addWikiText( $text );
				$wgOut->addHTML( '</div>' );
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param $nt @see Title object for the target
	 */
	function getSubTitle( $nt, $id ) {
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
			$tools[] = $sk->makeLinkObj( $talk, wfMsgHtml( 'talkpagelinktext' ) );
			if( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && User::isIP( $nt->getText() ) ) ) {
				# Block link
				if( $wgUser->isAllowed( 'block' ) )
					$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Blockip', $nt->getDBkey() ),
						wfMsgHtml( 'blocklink' ) );
				# Block log link
				$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ),
					wfMsgHtml( 'sp-contributions-blocklog' ), 'type=block&page=' . $nt->getPrefixedUrl() );
			}
			# Other logs link
			$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'log' ), 'user=' . $nt->getPartialUrl() );
			# Link to undeleted contributions
			$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Contributions', $nt->getDBkey() ),
				wfMsgHtml( 'contributions' ) );
				
			wfRunHooks( 'ContributionsToolLinks', array( $id, $nt, &$tools ) );

			$links = $wgLang->pipeList( $tools );
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
	function getForm( $options ) {
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

		$f .=  Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'sp-contributions-search' ) ) .
			Xml::tags( 'label', array( 'for' => 'target' ), wfMsgExt( 'sp-contributions-username', 'parseinline' ) ) . ' ' .
			Xml::input( 'target', 20, $options['target']) . ' '.
			Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
			Xml::namespaceSelector( $options['namespace'], '' ) . ' ' .
			Xml::submitButton( wfMsg( 'sp-contributions-submit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
		return $f;
	}
}
