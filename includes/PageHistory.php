<?php
/**
 * Page history
 *
 * Split off from Article.php and Skin.php, 2003-12-22
 * @file
 */

/**
 * This class handles printing the history page for an article.  In order to
 * be efficient, it uses timestamps rather than offsets for paging, to avoid
 * costly LIMIT,offset queries.
 *
 * Construct it by passing in an Article, and call $h->history() to print the
 * history.
 *
 */
class PageHistory {
	const DIR_PREV = 0;
	const DIR_NEXT = 1;

	var $mArticle, $mTitle, $mSkin;
	var $lastdate;
	var $linesonpage;
	var $mLatestId = null;
	
	private $mOldIdChecked = 0;

	/**
	 * Construct a new PageHistory.
	 *
	 * @param Article $article
	 * @returns nothing
	 */
	function __construct( $article ) {
		global $wgUser;
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$this->mSkin = $wgUser->getSkin();
		$this->preCacheMessages();
	}

	function getArticle() {
		return $this->mArticle;
	}

	function getTitle() {
		return $this->mTitle;
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			foreach( explode(' ', 'cur last rev-delundel' ) as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escape') );
			}
		}
	}

	/**
	 * Print the history page for an article.
	 *
	 * @returns nothing
	 */
	function history() {
		global $wgOut, $wgRequest, $wgTitle, $wgScript;

		/*
		 * Allow client caching.
		 */
		if( $wgOut->checkLastModified( $this->mArticle->getTouched() ) )
			return; // Client cache fresh and headers sent, nothing more to do.

		wfProfileIn( __METHOD__ );

		/*
		 * Setup page variables.
		 */
		$wgOut->setPageTitle( wfMsg( 'history-title', $this->mTitle->getPrefixedText() ) );
		$wgOut->setPageTitleActionText( wfMsg( 'history_short' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( 'action=history' );
		$wgOut->addScriptFile( 'history.js' );

		$logPage = SpecialPage::getTitleFor( 'Log' );
		$logLink = $this->mSkin->makeKnownLinkObj( $logPage, wfMsgHtml( 'viewpagelogs' ),
			'page=' . $this->mTitle->getPrefixedUrl() );
		$wgOut->setSubtitle( $logLink );

		$feedType = $wgRequest->getVal( 'feed' );
		if( $feedType ) {
			wfProfileOut( __METHOD__ );
			return $this->feed( $feedType );
		}

		/*
		 * Fail if article doesn't exist.
		 */
		if( !$this->mTitle->exists() ) {
			$wgOut->addWikiMsg( 'nohistory' );
			wfProfileOut( __METHOD__ );
			return;
		}

		/**
		 * Add date selector to quickly get to a certain time
		 */
		$year = $wgRequest->getInt( 'year' );
		$month = $wgRequest->getInt( 'month' );
		$tagFilter = $wgRequest->getVal( 'tagfilter' );
		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		$action = htmlspecialchars( $wgScript );
		$wgOut->addHTML(
			"<form action=\"$action\" method=\"get\" id=\"mw-history-searchform\">" .
			Xml::fieldset( wfMsg( 'history-fieldset-title' ), false, array( 'id' => 'mw-history-search' ) ) .
			Xml::hidden( 'title', $this->mTitle->getPrefixedDBKey() ) . "\n" .
			Xml::hidden( 'action', 'history' ) . "\n" .
			xml::dateMenu( $year, $month ) . '&nbsp;' .
			( $tagSelector ? ( implode( '&nbsp;', $tagSelector ) . '&nbsp;' ) : '' ) .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			'</fieldset></form>'
		);

		wfRunHooks( 'PageHistoryBeforeList', array( &$this->mArticle ) );

		/**
		 * Do the list
		 */
		$pager = new PageHistoryPager( $this, $year, $month, $tagFilter );
		$this->linesonpage = $pager->getNumRows();
		$wgOut->addHTML(
			$pager->getNavigationBar() .
			$this->beginHistoryList() .
			$pager->getBody() .
			$this->endHistoryList() .
			$pager->getNavigationBar()
		);

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Creates begin of history list with a submit button
	 *
	 * @return string HTML output
	 */
	function beginHistoryList() {
		global $wgTitle, $wgScript, $wgEnableHtmlDiff;
		$this->lastdate = '';
		$s = wfMsgExt( 'histlegend', array( 'parse') );
		$s .= Xml::openElement( 'form', array( 'action' => $wgScript, 'id' => 'mw-history-compare' ) );
		$s .= Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() );
		if( $wgEnableHtmlDiff ) {
			$s .= $this->submitButton( wfMsg( 'visualcomparison'),
				array(
						'name' => 'htmldiff',
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-visualcomparison' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
			$s .= $this->submitButton( wfMsg( 'wikicodecomparison'),
				array(
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
		} else {
			$s .= $this->submitButton( wfMsg( 'compareselectedversions'),
				array(
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
		}
		$s .= '<ul id="pagehistory">' . "\n";
		return $s;
	}

	/**
	 * Creates end of history list with a submit button
	 *
	 * @return string HTML output
	 */
	function endHistoryList() {
		global $wgEnableHtmlDiff;
		$s = '</ul>';
		if( $wgEnableHtmlDiff ) {
			$s .= $this->submitButton( wfMsg( 'visualcomparison'),
				array(
						'name' => 'htmldiff',
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-visualcomparison' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
			$s .= $this->submitButton( wfMsg( 'wikicodecomparison'),
				array(
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
		} else {
			$s .= $this->submitButton( wfMsg( 'compareselectedversions'),
				array(
						'class'     => 'historysubmit',
						'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
						'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			);
		}
		$s .= '</form>';
		return $s;
	}

	/**
	 * Creates a submit button
	 *
	 * @param array $attributes attributes
	 * @return string HTML output for the submit button
	 */
	function submitButton($message, $attributes = array() ) {
		# Disable submit button if history has 1 revision only
		if( $this->linesonpage > 1 ) {
			return Xml::submitButton( $message , $attributes );
		} else {
			return '';
		}
	}

	/**
	 * Returns a row from the history printout.
	 *
	 * @todo document some more, and maybe clean up the code (some params redundant?)
	 *
	 * @param Row $row The database row corresponding to the previous line.
	 * @param mixed $next The database row corresponding to the next line.
	 * @param int $counter Apparently a counter of what row number we're at, counted from the top row = 1.
	 * @param $notificationtimestamp
	 * @param bool $latest Whether this row corresponds to the page's latest revision.
	 * @param bool $firstInList Whether this row corresponds to the first displayed on this history page.
	 * @return string HTML output for the row
	 */
	function historyLine( $row, $next, $counter = '', $notificationtimestamp = false, $latest = false, $firstInList = false ) {
		global $wgUser, $wgLang;
		$rev = new Revision( $row );
		$rev->setTitle( $this->mTitle );

		$curlink = $this->curLink( $rev, $latest );
		$lastlink = $this->lastLink( $rev, $next, $counter );
		$arbitrary = $this->diffButtons( $rev, $firstInList, $counter );
		$link = $this->revLink( $rev );
		$classes = array();

		$s = "($curlink) ($lastlink) $arbitrary";

		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			if( $latest ) {
				// We don't currently handle well changing the top revision's settings
				$del = Xml::tags( 'span', array( 'class'=>'mw-revdelundel-link' ), '('.$this->message['rev-delundel'].')' );
			} else if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				// If revision was hidden from sysops
				$del = Xml::tags( 'span', array( 'class'=>'mw-revdelundel-link' ), '('.$this->message['rev-delundel'].')' );
			} else {
				$query = array( 'target' => $this->mTitle->getPrefixedDbkey(),
					'oldid' => $rev->getId()
				);
				$del = $this->mSkin->revDeleteLink( $query, $rev->isDeleted( Revision::DELETED_RESTRICTED ) );
			}
			$s .= " $del ";
		}

		$s .= " $link";
		$s .= " <span class='history-user'>" . $this->mSkin->revUserTools( $rev, true ) . "</span>";

		if( $rev->isMinor() ) {
			$s .= ' ' . Xml::element( 'span', array( 'class' => 'minor' ), wfMsg( 'minoreditletter') );
		}

		if( !is_null( $size = $rev->getSize() ) && !$rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$s .= ' ' . $this->mSkin->formatRevisionSize( $size );
		}

		$s .= $this->mSkin->revComment( $rev, false, true );

		if( $notificationtimestamp && ($row->rev_timestamp >= $notificationtimestamp) ) {
			$s .= ' <span class="updatedmarker">' .  wfMsgHtml( 'updatedmarker' ) . '</span>';
		}
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$s .= ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
		}

		$tools = array();

		if( !is_null( $next ) && is_object( $next ) ) {
			if( $latest && $this->mTitle->userCan( 'rollback' ) && $this->mTitle->userCan( 'edit' ) ) {
				$tools[] = '<span class="mw-rollback-link">'.$this->mSkin->buildRollbackLink( $rev ).'</span>';
			}

			if( $this->mTitle->quickUserCan( 'edit' ) && !$rev->isDeleted( Revision::DELETED_TEXT ) &&
				!$next->rev_deleted & Revision::DELETED_TEXT )
			{
				# Create undo tooltip for the first (=latest) line only
				$undoTooltip = $latest
					? array( 'title' => wfMsg( 'tooltip-undo' ) )
					: array();
				$undolink = $this->mSkin->link(
					$this->mTitle,
					wfMsgHtml( 'editundo' ),
					$undoTooltip,
					array( 'action' => 'edit', 'undoafter' => $next->rev_id, 'undo' => $rev->getId() ),
					array( 'known', 'noclasses' )
				);
				$tools[] = "<span class=\"mw-history-undo\">{$undolink}</span>";
			}
		}

		if( $tools ) {
			$s .= ' (' . $wgLang->pipeList( $tools ) . ')';
		}

		# Tags
		list($tagSummary, $newClasses) = ChangeTags::formatSummaryRow( $row->ts_tags, 'history' );
		$classes = array_merge( $classes, $newClasses );
		$s .= " $tagSummary";

		wfRunHooks( 'PageHistoryLineEnding', array( $this, &$row , &$s ) );

		$classes = implode( ' ', $classes );

		return "<li class=\"$classes\">$s</li>\n";
	}

	/**
	 * Create a link to view this revision of the page
	 * @param Revision $rev
	 * @returns string
	 */
	function revLink( $rev ) {
		global $wgLang;
		$date = $wgLang->timeanddate( wfTimestamp(TS_MW, $rev->getTimestamp()), true );
		if( !$rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = $this->mSkin->makeKnownLinkObj( $this->mTitle, $date, "oldid=" . $rev->getId() );
		} else {
			$link = '<span class="history-deleted">' . $date . '</span>';
		}
		return $link;
	}

	/**
	 * Create a diff-to-current link for this revision for this page
	 * @param Revision $rev
	 * @param Bool $latest, this is the latest revision of the page?
	 * @returns string
	 */
	function curLink( $rev, $latest ) {
		$cur = $this->message['cur'];
		if( $latest || $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			return $cur;
		} else {
			return $this->mSkin->makeKnownLinkObj( $this->mTitle, $cur,
				'diff=' . $this->mTitle->getLatestRevID() . "&oldid=" . $rev->getId() );
		}
	}

	/**
	 * Create a diff-to-previous link for this revision for this page.
	 * @param Revision $prevRev, the previous revision
	 * @param mixed $next, the newer revision
	 * @param int $counter, what row on the history list this is
	 * @returns string
	 */
	function lastLink( $prevRev, $next, $counter ) {
		$last = $this->message['last'];
		# $next may either be a Row, null, or "unkown"
		$nextRev = is_object($next) ? new Revision( $next ) : $next;
		if( is_null($next) ) {
			# Probably no next row
			return $last;
		} elseif( $next === 'unknown' ) {
			# Next row probably exists but is unknown, use an oldid=prev link
			return $this->mSkin->makeKnownLinkObj( $this->mTitle, $last,
				"diff=" . $prevRev->getId() . "&oldid=prev" );
		} elseif( $prevRev->isDeleted(Revision::DELETED_TEXT) || $nextRev->isDeleted(Revision::DELETED_TEXT) ) {
			return $last;
		} else {
			return $this->mSkin->makeKnownLinkObj( $this->mTitle, $last,
				"diff=" . $prevRev->getId() . "&oldid={$next->rev_id}" );
		}
	}

	/**
	 * Create radio buttons for page history
	 *
	 * @param object $rev Revision
	 * @param bool $firstInList Is this version the first one?
	 * @param int $counter A counter of what row number we're at, counted from the top row = 1.
	 * @return string HTML output for the radio buttons
	 */
	function diffButtons( $rev, $firstInList, $counter ) {
		if( $this->linesonpage > 1 ) {
			$radio = array( 'type'  => 'radio', 'value' => $rev->getId() );
			/** @todo: move title texts to javascript */
			if( $firstInList ) {
				$first = Xml::element( 'input', 
					array_merge( $radio, array( 'style' => 'visibility:hidden', 'name'  => 'oldid' ) )
				);
				$checkmark = array( 'checked' => 'checked' );
			} else {
				# Check visibility of old revisions
				if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
					$radio['disabled'] = 'disabled';
					$checkmark = array(); // We will check the next possible one
				} else if( $counter == 2 || !$this->mOldIdChecked ) {
					$checkmark = array( 'checked' => 'checked' );
					$this->mOldIdChecked = $rev->getId();
				} else {
					$checkmark = array();
				}
				$first = Xml::element( 'input', array_merge( $radio, $checkmark, array( 'name'  => 'oldid' ) ) );
				$checkmark = array();
			}
			$second = Xml::element( 'input', array_merge( $radio, $checkmark, array( 'name'  => 'diff' ) ) );
			return $first . $second;
		} else {
			return '';
		}
	}

	/**
	 * Fetch an array of revisions, specified by a given limit, offset and
	 * direction. This is now only used by the feeds. It was previously
	 * used by the main UI but that's now handled by the pager.
	 */
	function fetchRevisions($limit, $offset, $direction) {
		$dbr = wfGetDB( DB_SLAVE );

		if( $direction == PageHistory::DIR_PREV )
			list($dirs, $oper) = array("ASC", ">=");
		else /* $direction == PageHistory::DIR_NEXT */
			list($dirs, $oper) = array("DESC", "<=");

		if( $offset )
			$offsets = array("rev_timestamp $oper '$offset'");
		else
			$offsets = array();

		$page_id = $this->mTitle->getArticleID();

		return $dbr->select( 'revision',
			Revision::selectFields(),
			array_merge(array("rev_page=$page_id"), $offsets),
			__METHOD__,
			array( 'ORDER BY' => "rev_timestamp $dirs", 
				'USE INDEX' => 'page_timestamp', 'LIMIT' => $limit)
		);
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 * @param string $type
	 */
	function feed( $type ) {
		global $wgFeedClasses, $wgRequest, $wgFeedLimit;
		if( !FeedUtils::checkFeedOutput($type) ) {
			return;
		}

		$feed = new $wgFeedClasses[$type](
		$this->mTitle->getPrefixedText() . ' - ' .
		wfMsgForContent( 'history-feed-title' ),
		wfMsgForContent( 'history-feed-description' ),
		$this->mTitle->getFullUrl( 'action=history' ) );

		// Get a limit on number of feed entries. Provide a sane default
		// of 10 if none is defined (but limit to $wgFeedLimit max)
		$limit = $wgRequest->getInt( 'limit', 10 );
		if( $limit > $wgFeedLimit || $limit < 1 ) {
			$limit = 10;
		}
		$items = $this->fetchRevisions($limit, 0, PageHistory::DIR_NEXT);

		$feed->outHeader();
		if( $items ) {
			foreach( $items as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		} else {
			$feed->outItem( $this->feedEmpty() );
		}
		$feed->outFooter();
	}

	function feedEmpty() {
		global $wgOut;
		return new FeedItem(
			wfMsgForContent( 'nohistory' ),
			$wgOut->parse( wfMsgForContent( 'history-feed-empty' ) ),
			$this->mTitle->getFullUrl(),
			wfTimestamp( TS_MW ),
				'',
			$this->mTitle->getTalkPage()->getFullUrl() );
	}

	/**
	 * Generate a FeedItem object from a given revision table row
	 * Borrows Recent Changes' feed generation functions for formatting;
	 * includes a diff to the previous revision (if any).
	 *
	 * @param $row
	 * @return FeedItem
	 */
	function feedItem( $row ) {
		$rev = new Revision( $row );
		$rev->setTitle( $this->mTitle );
		$text = FeedUtils::formatDiffRow( $this->mTitle,
		$this->mTitle->getPreviousRevisionID( $rev->getId() ),
		$rev->getId(),
		$rev->getTimestamp(),
		$rev->getComment() );

		if( $rev->getComment() == '' ) {
			global $wgContLang;
			$title = wfMsgForContent( 'history-feed-item-nocomment',
			$rev->getUserText(),
			$wgContLang->timeanddate( $rev->getTimestamp() ) );
		} else {
			$title = $rev->getUserText() . wfMsgForContent( 'colon-separator' ) . FeedItem::stripComment( $rev->getComment() );
		}

		return new FeedItem(
			$title,
			$text,
			$this->mTitle->getFullUrl( 'diff=' . $rev->getId() . '&oldid=prev' ),
			$rev->getTimestamp(),
			$rev->getUserText(),
			$this->mTitle->getTalkPage()->getFullUrl() );
	}
}


/**
 * @ingroup Pager
 */
class PageHistoryPager extends ReverseChronologicalPager {
	public $mLastRow = false, $mPageHistory, $mTitle;

	function __construct( $pageHistory, $year='', $month='', $tagFilter = '' ) {
		parent::__construct();
		$this->mPageHistory = $pageHistory;
		$this->mTitle =& $this->mPageHistory->mTitle;
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month );
	}

	function getQueryInfo() {
		$queryInfo = array(
			'tables'  => array('revision'),
			'fields'  => array_merge( Revision::selectFields(), array('ts_tags') ),
			'conds'   => array('rev_page' => $this->mPageHistory->mTitle->getArticleID() ),
			'options' => array( 'USE INDEX' => array('revision' => 'page_timestamp') ),
			'join_conds' => array( 'tag_summary' => array( 'LEFT JOIN', 'ts_rev_id=rev_id' ) ),
		);
		ChangeTags::modifyDisplayQuery( $queryInfo['tables'],
										$queryInfo['fields'],
										$queryInfo['conds'],
										$queryInfo['join_conds'],
										$queryInfo['options'],
										$this->tagFilter );
		wfRunHooks( 'PageHistoryPager::getQueryInfo', array( &$this, &$queryInfo ) );
		return $queryInfo;
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function formatRow( $row ) {
		if( $this->mLastRow ) {
			$latest = $this->mCounter == 1 && $this->mIsFirst;
			$firstInList = $this->mCounter == 1;
			$s = $this->mPageHistory->historyLine( $this->mLastRow, $row, $this->mCounter++,
				$this->mTitle->getNotificationTimestamp(), $latest, $firstInList );
		} else {
			$s = '';
		}
		$this->mLastRow = $row;
		return $s;
	}

	function getStartBody() {
		$this->mLastRow = false;
		$this->mCounter = 1;
		return '';
	}

	function getEndBody() {
		if( $this->mLastRow ) {
			$latest = $this->mCounter == 1 && $this->mIsFirst;
			$firstInList = $this->mCounter == 1;
			if( $this->mIsBackwards ) {
				# Next row is unknown, but for UI reasons, probably exists if an offset has been specified
				if( $this->mOffset == '' ) {
					$next = null;
				} else {
					$next = 'unknown';
				}
			} else {
				# The next row is the past-the-end row
				$next = $this->mPastTheEndRow;
			}
			$s = $this->mPageHistory->historyLine( $this->mLastRow, $next, $this->mCounter++,
				$this->mTitle->getNotificationTimestamp(), $latest, $firstInList );
		} else {
			$s = '';
		}
		return $s;
	}
}
