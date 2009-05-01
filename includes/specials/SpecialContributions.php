<?php
/**
 * Special:Contributions, show user contributions in a paged list
 * @file
 * @ingroup SpecialPage
 */
 
class SpecialContributions extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Contributions' );
	}

	public function execute( $par ) {
		global $wgUser, $wgOut, $wgLang, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();

		$this->opts = array();

		if( $par == 'newbies' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		} elseif( isset( $par ) ) {
			$target = $par;
		} else {
			$target = $wgRequest->getVal( 'target' );
		}

		// check for radiobox
		if( $wgRequest->getVal( 'contribs' ) == 'newbie' ) {
			$target = 'newbies';
			$this->opts['contribs'] = 'newbie';
		}

		if( !strlen( $target ) ) {
			$wgOut->addHTML( $this->getForm() );
			return;
		}

		$this->opts['limit'] = $wgRequest->getInt( 'limit', 50 );
		$this->opts['target'] = $target;

		$nt = Title::makeTitleSafe( NS_USER, $target );
		if( !$nt ) {
			$wgOut->addHTML( $this->getForm() );
			return;
		}
		$id = User::idFromName( $nt->getText() );

		if( $target != 'newbies' ) {
			$target = $nt->getText();
			$wgOut->setSubtitle( $this->contributionsSub( $nt, $id ) );
			$wgOut->setHTMLTitle( wfMsg( 'pagetitle', wfMsgExt( 'contributions-title', array( 'parsemag' ),$target ) ) );
		} else {
			$wgOut->setSubtitle( wfMsgHtml( 'sp-contributions-newbies-sub') );
			$wgOut->setHTMLTitle( wfMsg( 'pagetitle', wfMsg( 'sp-contributions-newbies-title' ) ) );
		}

		if( ( $ns = $wgRequest->getVal( 'namespace', null ) ) !== null && $ns !== '' ) {
			$this->opts['namespace'] = intval( $ns );
		} else {
			$this->opts['namespace'] = '';
		}

		$this->opts['tagfilter'] = (string) $wgRequest->getVal( 'tagfilter' );
	
		// Allows reverts to have the bot flag in recent changes. It is just here to
		// be passed in the form at the top of the page 
		if( $wgUser->isAllowed( 'markbotedits' ) && $wgRequest->getBool( 'bot' ) ) {
			$this->opts['bot'] = '1';
		}

		$skip = $wgRequest->getText( 'offset' ) || $wgRequest->getText( 'dir' ) == 'prev';
		# Offset overrides year/month selection
		if( $skip ) {
			$this->opts['year'] = '';
			$this->opts['month'] = '';
		} else {
			$this->opts['year'] = $wgRequest->getIntOrNull( 'year' );
			$this->opts['month'] = $wgRequest->getIntOrNull( 'month' );
		}
		
		// Add RSS/atom links
		$this->setSyndicated();
		$feedType = $wgRequest->getVal( 'feed' );
		if( $feedType ) {
			return $this->feed( $feedType );
		}

		wfRunHooks( 'SpecialContributionsBeforeMainOutput', $id );

		$wgOut->addHTML( $this->getForm() );

		$pager = new ContribsPager( $target, $this->opts['namespace'], $this->opts['year'], $this->opts['month'] );
		if( !$pager->getNumRows() ) {
			$wgOut->addWikiMsg( 'nocontribs', $target );
			return;
		}

		# Show a message about slave lag, if applicable
		if( ( $lag = $pager->getDatabase()->getLag() ) > 0 )
			$wgOut->showLagWarning( $lag );

		$wgOut->addHTML(
			'<p>' . $pager->getNavigationBar() . '</p>' .
			$pager->getBody() .
			'<p>' . $pager->getNavigationBar() . '</p>'
		);

		# If there were contributions, and it was a valid user or IP, show
		# the appropriate "footer" message - WHOIS tools, etc.
		if( $target != 'newbies' ) {
			$message = IP::isIPAddress( $target ) ?
				'sp-contributions-footer-anon' : 'sp-contributions-footer';

			$text = wfMsgNoTrans( $message, $target );
			if( !wfEmptyMsg( $message, $text ) && $text != '-' ) {
				$wgOut->addHTML( '<div class="mw-contributions-footer">' );
				$wgOut->addWikiText( $text );
				$wgOut->addHTML( '</div>' );
			}
		}
	}
	
	protected function setSyndicated() {
		global $wgOut;
		$queryParams = array(
			'namespace' => $this->opts['namespace'],
			'target'  => $this->opts['target']
		);
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( wfArrayToCGI( $queryParams ) );
	}

	/**
	* Generates the subheading with links
	* @param Title $nt Title object for the target
	* @param integer $id User ID for the target
	* @return String: appropriately-escaped HTML to be output literally
	*/
	protected function contributionsSub( $nt, $id ) {
		global $wgSysopUserBans, $wgLang, $wgUser;

		$sk = $wgUser->getSkin();

		if( 0 == $id ) {
			$user = $nt->getText();
		} else {
			$user = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		}
		$talk = $nt->getTalkPage();
		if( $talk ) {
			# Talk page link
			$tools[] = $sk->makeLinkObj( $talk, wfMsgHtml( 'talkpagelinktext' ) );
			if( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && IP::isIPAddress( $nt->getText() ) ) ) {
				# Block link
				if( $wgUser->isAllowed( 'block' ) )
					$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Blockip', 
						$nt->getDBkey() ), wfMsgHtml( 'blocklink' ) );
				# Block log link
				$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ), 
					wfMsgHtml( 'sp-contributions-blocklog' ), 'type=block&page=' . $nt->getPrefixedUrl() );
			}
			# Other logs link
			$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ), wfMsg( 'sp-contributions-logs' ), 
				'user=' . $nt->getPartialUrl() );

			# Add link to deleted user contributions for priviledged users
			if( $wgUser->isAllowed( 'deletedhistory' ) ) {
				$tools[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'DeletedContributions', 
					$nt->getDBkey() ), wfMsgHtml( 'deletedcontributions' ) );
			}

			# Add a link to change user rights for privileged users
			$userrightsPage = new UserrightsPage();
			if( 0 !== $id && $userrightsPage->userCanChangeRights( User::newFromId( $id ) ) ) {
				$tools[] = $sk->makeKnownLinkObj(
					SpecialPage::getTitleFor( 'Userrights', $nt->getDBkey() ),
					wfMsgHtml( 'userrights' )
				);
			}

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
	 * @param $this->opts Array: the options to be included.
	 */
	protected function getForm() {
		global $wgScript, $wgTitle;
	
		$this->opts['title'] = $wgTitle->getPrefixedText();
		if( !isset( $this->opts['target'] ) ) {
			$this->opts['target'] = '';
		} else {
			$this->opts['target'] = str_replace( '_' , ' ' , $this->opts['target'] );
		}
	
		if( !isset( $this->opts['namespace'] ) ) {
			$this->opts['namespace'] = '';
		}
	
		if( !isset( $this->opts['contribs'] ) ) {
			$this->opts['contribs'] = 'user';
		}
	
		if( !isset( $this->opts['year'] ) ) {
			$this->opts['year'] = '';
		}
	
		if( !isset( $this->opts['month'] ) ) {
			$this->opts['month'] = '';
		}
	
		if( $this->opts['contribs'] == 'newbie' ) {
			$this->opts['target'] = '';
		}

		if( !isset( $this->opts['tagfilter'] ) ) {
			$this->opts['tagfilter'] = '';
		}
	
		$f = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		# Add hidden params for tracking
		foreach ( $this->opts as $name => $value ) {
			if( in_array( $name, array( 'namespace', 'target', 'contribs', 'year', 'month' ) ) ) {
				continue;
			}
			$f .= "\t" . Xml::hidden( $name, $value ) . "\n";
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $this->opts['tagfilter'] );
	
		$f .= '<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'sp-contributions-search' ) ) .
			Xml::radioLabel( wfMsgExt( 'sp-contributions-newbies', array( 'parseinline' ) ), 
				'contribs', 'newbie' , 'newbie', $this->opts['contribs'] == 'newbie' ? true : false ) . '<br />' .
			Xml::radioLabel( wfMsgExt( 'sp-contributions-username', array( 'parseinline' ) ), 
				'contribs' , 'user', 'user', $this->opts['contribs'] == 'user' ? true : false ) . ' ' .
			Xml::input( 'target', 20, $this->opts['target']) . ' '.
			'<span style="white-space: nowrap">' .
			Xml::label( wfMsg( 'namespace' ), 'namespace' ) . ' ' .
			Xml::namespaceSelector( $this->opts['namespace'], '' ) .
			'</span>' .
			( $tagFilter ? Xml::tags( 'p', null, implode( '&nbsp;', $tagFilter ) ) : '' ) .
			Xml::openElement( 'p' ) .
			'<span style="white-space: nowrap">' .
			Xml::dateMenu( $this->opts['year'], $this->opts['month'] ) .
			'</span>' . ' ' .
			Xml::submitButton( wfMsg( 'sp-contributions-submit' ) ) .
			Xml::closeElement( 'p' );
	
		$explain = wfMsgExt( 'sp-contributions-explain', 'parseinline' );
		if( !wfEmptyMsg( 'sp-contributions-explain', $explain ) )
			$f .= "<p>{$explain}</p>";
	
		$f .= '</fieldset>' .
			Xml::closeElement( 'form' );
		return $f;
	}
	
	/**
	 * Output a subscription feed listing recent edits to this page.
	 * @param string $type
	 */
	protected function feed( $type ) {
		global $wgRequest, $wgFeed, $wgFeedClasses, $wgFeedLimit;

		if( !$wgFeed ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return;
		}

		if( !isset( $wgFeedClasses[$type] ) ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-invalid' );
			return;
		}

		$feed = new $wgFeedClasses[$type](
			$this->feedTitle(),
			wfMsgExt( 'tagline', 'parsemag' ),
			$this->getTitle()->getFullUrl() . "/" . urlencode($this->opts['target'])
		);
			
		// Already valid title
		$nt = Title::makeTitleSafe( NS_USER, $this->opts['target'] );
		$target = $this->opts['target'] == 'newbies' ? 'newbies' : $nt->getText();
			
		$pager = new ContribsPager( $target, $this->opts['namespace'], 
			$this->opts['year'], $this->opts['month'], $this->opts['tagfilter'] );

		$pager->mLimit = min( $this->opts['limit'], $wgFeedLimit );

		$feed->outHeader();
		if( $pager->getNumRows() > 0 ) {
			while( $row = $pager->mResult->fetchObject() ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}

	protected function feedTitle() {
		global $wgContLanguageCode, $wgSitename;
		$page = SpecialPage::getPage( 'Contributions' );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgContLanguageCode]";
	}

	protected function feedItem( $row ) {
		$title = Title::MakeTitle( intval( $row->page_namespace ), $row->page_title );
		if( $title ) {
			$date = $row->rev_timestamp;
			$comments = $title->getTalkPage()->getFullURL();
			$revision = Revision::newFromTitle( $title, $row->rev_id );

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $revision ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $revision ),
				$comments
			);
		} else {
			return NULL;
		}
	}

	protected function feedItemAuthor( $revision ) {
		return $revision->getUserText();
	}

	protected function feedItemDesc( $revision ) {
		if( $revision ) {
			return '<p>' . htmlspecialchars( $revision->getUserText() ) . wfMsgForContent( 'colon-separator' ) .
				htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) . 
				"</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
		}
		return '';
	}
}

/**
 * Pager for Special:Contributions
 * @ingroup SpecialPage Pager
 */
class ContribsPager extends ReverseChronologicalPager {
	public $mDefaultDirection = true;
	var $messages, $target;
	var $namespace = '', $mDb;

	function __construct( $target, $namespace = false, $year = false, $month = false, $tagFilter = false ) {
		parent::__construct();
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist newpageletter minoreditletter' ) as $msg ) {
			$this->messages[$msg] = wfMsgExt( $msg, array( 'escape') );
		}
		$this->target = $target;
		$this->namespace = $namespace;
		$this->tagFilter = $tagFilter;

		$this->getDateCond( $year, $month );

		$this->mDb = wfGetDB( DB_SLAVE, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;
		return $query;
	}

	function getQueryInfo() {
		global $wgUser;
		list( $tables, $index, $userCond, $join_cond ) = $this->getUserCond();
		
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		// Paranoia: avoid brute force searches (bug 17342)
		if( !$wgUser->isAllowed( 'suppressrevision' ) ) {
			$conds[] = 'rev_deleted & ' . Revision::DELETED_USER . ' = 0';
		}
		$join_cond['page'] = array( 'INNER JOIN', 'page_id=rev_page' );
		
		$queryInfo = array(
			'tables' => $tables,
			'fields' => array(
				'page_namespace', 'page_title', 'page_is_new', 'page_latest', 'page_is_redirect',
				'page_len','rev_id', 'rev_page', 'rev_text_id', 'rev_timestamp', 'rev_comment', 
				'rev_minor_edit', 'rev_user', 'rev_user_text', 'rev_parent_id', 'rev_deleted'
			),
			'conds' => $conds,
			'options' => array( 'USE INDEX' => array('revision' => $index) ),
			'join_conds' => $join_cond
		);
		
		ChangeTags::modifyDisplayQuery( $queryInfo['tables'],
										$queryInfo['fields'],
										$queryInfo['conds'],
										$queryInfo['join_conds'],
										$queryInfo['options'],
										$this->tagFilter );
		
		wfRunHooks( 'ContribsPager::getQueryInfo', array( &$this, &$queryInfo ) );
		return $queryInfo;
	}

	function getUserCond() {
		$condition = array();
		$join_conds = array();
		if( $this->target == 'newbies' ) {
			$tables = array( 'user_groups', 'page', 'revision' );
			$max = $this->mDb->selectField( 'user', 'max(user_id)', false, __METHOD__ );
			$condition[] = 'rev_user >' . (int)($max - $max / 100);
			$condition[] = 'ug_group IS NULL';
			$index = 'user_timestamp';
			# FIXME: other groups may have 'bot' rights
			$join_conds['user_groups'] = array( 'LEFT JOIN', "ug_user = rev_user AND ug_group = 'bot'" );
		} else {
			$tables = array( 'page', 'revision' );
			$condition['rev_user_text'] = $this->target;
			$index = 'usertext_timestamp';
		}
		return array( $tables, $index, $condition, $join_conds );
	}

	function getNamespaceCond() {
		if( $this->namespace !== '' ) {
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

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with roll-
	 * back privileges. The rollback link restores the most recent version that
	 * was not written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 */
	function formatRow( $row ) {
		global $wgLang, $wgUser, $wgContLang;
		wfProfileIn( __METHOD__ );

		$sk = $this->getSkin();
		$rev = new Revision( $row );
		$classes = array();

		$page = Title::newFromRow( $row );
		$page->resetArticleId( $row->rev_page ); // use process cache
		$link = $sk->makeLinkObj( $page, $page->getPrefixedText(), $page->isRedirect() ? 'redirect=no' : '' );
		# Mark current revisions
		$difftext = $topmarktext = '';
		if( $row->rev_id == $row->page_latest ) {
			$topmarktext .= '<strong>' . $this->messages['uctop'] . '</strong>';
			if( !$row->page_is_new ) {
				$difftext .= '(' . $sk->makeKnownLinkObj( $page, $this->messages['diff'], 'diff=0' ) . ')';
				# Add rollback link
				if( $page->quickUserCan( 'rollback') && $page->quickUserCan( 'edit' ) ) {
					$topmarktext .= ' '.$sk->generateRollback( $rev );
				}
			} else {
				$difftext .= $this->messages['newarticle'];
			}
		}
		# Is there a visible previous revision?
		if( $rev->userCan(Revision::DELETED_TEXT) ) {
			$difftext = '(' . $sk->makeKnownLinkObj( $page, $this->messages['diff'],
				'diff=prev&oldid='.$row->rev_id ) . ')';
		} else {
			$difftext = '(' . $this->messages['diff'] . ')';
		}
		$histlink = '('.$sk->makeKnownLinkObj( $page, $this->messages['hist'], 'action=history' ) . ')';

		$comment = $wgContLang->getDirMark() . $sk->revComment( $rev, false, true );
		$date = $wgLang->timeanddate( wfTimestamp( TS_MW, $row->rev_timestamp ), true );
		$d = $sk->makeKnownLinkObj( $page, $date, 'oldid='.intval($row->rev_id) );

		if( $this->target == 'newbies' ) {
			$userlink = ' . . ' . $sk->userLink( $row->rev_user, $row->rev_user_text );
			$userlink .= ' (' . $sk->userTalkLink( $row->rev_user, $row->rev_user_text ) . ') ';
		} else {
			$userlink = '';
		}

		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$d = '<span class="history-deleted">' . $d . '</span>';
		}

		if( $rev->getParentId() === 0 ) {
			$nflag = '<span class="newpage">' . $this->messages['newpageletter'] . '</span>';
		} else {
			$nflag = '';
		}

		if( $rev->isMinor() ) {
			$mflag = '<span class="minor">' . $this->messages['minoreditletter'] . '</span> ';
		} else {
			$mflag = '';
		}

		$ret = "{$d} {$histlink} {$difftext} {$nflag}{$mflag} {$link}{$userlink} {$comment} {$topmarktext}";
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$ret .= ' ' . wfMsgHtml( 'deletedrev' );
		}

		# Tags, if any.
		list($tagSummary, $newClasses) = ChangeTags::formatSummaryRow( $row->ts_tags, 'contributions' );
		$classes = array_merge( $classes, $newClasses );
		$ret .= " $tagSummary";

		// Let extensions add data
		wfRunHooks( 'ContributionsLineEnding', array( &$this, &$ret, $row ) );

		$classes = implode( ' ', $classes );
		$ret = "<li class=\"$classes\">$ret</li>\n";
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
