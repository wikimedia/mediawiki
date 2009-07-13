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
			$conds[] = $this->mDb->bitAnd('ar_deleted', Revision::DELETED_USER) . ' = 0';
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
		$fmtLimit = $wgLang->formatNum( $this->mLimit );
		$linkTexts = array(
			'prev' => wfMsgExt( 'pager-newer-n', array( 'escape', 'parsemag' ), $fmtLimit ),
			'next' => wfMsgExt( 'pager-older-n', array( 'escape', 'parsemag' ), $fmtLimit ),
			'first' => wfMsgHtml( 'histlast' ),
			'last' => wfMsgHtml( 'histfirst' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $wgLang->pipeList( $limitLinks );

		$this->mNavigationBar = "(" . $wgLang->pipeList( array( $pagingLinks['first'], $pagingLinks['last'] ) ) . ") " .
			wfMsgExt( 'viewprevnext', array( 'parsemag', 'escape', 'replaceafter' ), $pagingLinks['prev'], $pagingLinks['next'], $limits );
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
		global $wgUser, $wgLang;
		wfProfileIn( __METHOD__ );

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
		$dellog = $sk->linkKnown(
			$logs,
			$this->messages['deletionlog'],
			array(),
			array(
				'type' => 'delete',
				'page' => $page->getPrefixedText()
			)
		);

		$reviewlink = $sk->linkKnown(
			SpecialPage::getTitleFor( 'Undelete', $page->getPrefixedDBkey() ),
			$this->messages['undeletebtn']
		);

		$link = $sk->linkKnown(
			$undelete,
			htmlspecialchars( $page->getPrefixedText() ),
			array(),
			array(
				'target' => $page->getPrefixedText(),
				'timestamp' => $rev->getTimestamp()
			)
		);

		$last = $sk->linkKnown(
			$undelete,
			$this->messages['diff'],
			array(),
			array(
				'target' => $page->getPrefixedText(),
				'timestamp' => $rev->getTimestamp(),
				'diff' => 'prev'
			)
		);

		$comment = $sk->revComment( $rev );
		$d = htmlspecialchars( $wgLang->timeanddate( $rev->getTimestamp(), true ) );

		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		} else {
			$link = $sk->linkKnown(
				$undelete,
				$d,
				array(),
				array(
					'target' => $page->getPrefixedText(),
					'timestamp' => $rev->getTimestamp()
				)
			);
		}

		$pagelink = $sk->link( $page );

		if( $rev->isMinor() ) {
			$mflag = '<span class="minor">' . $this->messages['minoreditletter'] . '</span> ';
		} else {
			$mflag = '';
		}

		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			// If revision was hidden from sysops
			if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$del = Xml::tags( 'span', array( 'class'=>'mw-revdelundel-link' ),
					'(' . $this->message['rev-delundel'] . ')' ) . ' ';
			// Otherwise, show the link...
			} else {
				$query = array(
					'type' => 'archive',
					'target' => $page->getPrefixedDbkey(),
					'ids' => $rev->getTimestamp() );
				$del = $this->mSkin->revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ) ) . ' ';
			}
		} else {
			$del = '';
		}

		$ret = "{$del}{$link} ({$last}) ({$dellog}) ({$reviewlink}) . . {$mflag} {$pagelink} {$comment}";

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
			$wgOut->addWikiMsg( 'nocontribs' );
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
				$wgOut->wrapWikiMsg( "<div class='mw-contributions-footer'>\n$1\n</div>", array( $message, $target ) );
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
			$user = $sk->link( $nt, htmlspecialchars( $nt->getText() ) );
		}
		$talk = $nt->getTalkPage();
		if( $talk ) {
			# Talk page link
			$tools[] = $sk->link( $talk, wfMsgHtml( 'talkpagelinktext' ) );
			if( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && User::isIP( $nt->getText() ) ) ) {
				# Block link
				if( $wgUser->isAllowed( 'block' ) )
					$tools[] = $sk->linkKnown(
						SpecialPage::getTitleFor( 'Blockip', $nt->getDBkey() ),
						wfMsgHtml( 'blocklink' )
					);
				# Block log link
				$tools[] = $sk->linkKnown(
					SpecialPage::getTitleFor( 'Log' ),
					wfMsgHtml( 'sp-contributions-blocklog' ),
					array(),
					array(
						'type' => 'block',
						'page' => $nt->getPrefixedText()
					)
				);
			}
			# Other logs link
			$tools[] = $sk->linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'sp-contributions-logs' ),
				array(),
				array( 'user' => $nt->getText() )
			);
			# Link to undeleted contributions
			$tools[] = $sk->linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $nt->getDBkey() ),
				wfMsgHtml( 'sp-deletedcontributions-contribs' )
			);

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
		global $wgScript, $wgRequest;

		$options['title'] = SpecialPage::getTitleFor( 'DeletedContributions' )->getPrefixedText();
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
