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
class HistoryPage {
	const DIR_PREV = 0;
	const DIR_NEXT = 1;

	var $article, $title, $skin;

	/**
	 * Construct a new HistoryPage.
	 *
	 * @param Article $article
	 * @returns nothing
	 */
	function __construct( $article ) {
		global $wgUser;
		$this->article = $article;
		$this->title = $article->getTitle();
		$this->skin = $wgUser->getSkin();
		$this->preCacheMessages();
	}

	function getArticle() {
		return $this->article;
	}

	function getTitle() {
		return $this->title;
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			$msgs = array( 'cur', 'last', 'rev-delundel' );
			foreach( $msgs as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities') );
			}
		}
	}

	/**
	 * Print the history page for an article.
	 *
	 * @returns nothing
	 */
	function history() {
		global $wgOut, $wgRequest, $wgScript;

		/*
		 * Allow client caching.
		 */
		if( $wgOut->checkLastModified( $this->article->getTouched() ) )
			return; // Client cache fresh and headers sent, nothing more to do.

		wfProfileIn( __METHOD__ );

		/*
		 * Setup page variables.
		 */
		$wgOut->setPageTitle( wfMsg( 'history-title', $this->title->getPrefixedText() ) );
		$wgOut->setPageTitleActionText( wfMsg( 'history_short' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( 'action=history' );
		$wgOut->addScriptFile( 'history.js' );

		$logPage = SpecialPage::getTitleFor( 'Log' );
		$logLink = $this->skin->link(
			$logPage,
			wfMsgHtml( 'viewpagelogs' ),
			array(),
			array( 'page' => $this->title->getPrefixedText() ),
			array( 'known', 'noclasses' )
		);
		$wgOut->setSubtitle( $logLink );

		$feedType = $wgRequest->getVal( 'feed' );
		if( $feedType ) {
			wfProfileOut( __METHOD__ );
			return $this->feed( $feedType );
		}

		/*
		 * Fail if article doesn't exist.
		 */
		if( !$this->title->exists() ) {
			$wgOut->addWikiMsg( 'nohistory' );
			# show deletion/move log if there is an entry
			LogEventsList::showLogExtract(
				$wgOut,
				array( 'delete', 'move' ),
				$this->title->getPrefixedText(),
				'',
				array(  'lim' => 10,
					'conds' => array( "log_action != 'revision'" ),
					'showIfEmpty' => false,
					'msgKey' => array( 'moveddeleted-notice' )
				)
			);
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
			Xml::fieldset(
				wfMsg( 'history-fieldset-title' ),
				false,
				array( 'id' => 'mw-history-search' )
			) .
			Xml::hidden( 'title', $this->title->getPrefixedDBKey() ) . "\n" .
			Xml::hidden( 'action', 'history' ) . "\n" .
			xml::dateMenu( $year, $month ) . '&nbsp;' .
			( $tagSelector ? ( implode( '&nbsp;', $tagSelector ) . '&nbsp;' ) : '' ) .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			'</fieldset></form>'
		);

		wfRunHooks( 'PageHistoryBeforeList', array( &$this->article ) );

		/**
		 * Do the list
		 */
		$pager = new HistoryPager( $this, $year, $month, $tagFilter );
		$wgOut->addHTML(
			$pager->getNavigationBar() .
			$pager->getBody() .
			$pager->getNavigationBar()
		);

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Fetch an array of revisions, specified by a given limit, offset and
	 * direction. This is now only used by the feeds. It was previously
	 * used by the main UI but that's now handled by the pager.
	 */
	function fetchRevisions($limit, $offset, $direction) {
		$dbr = wfGetDB( DB_SLAVE );

		if( $direction == HistoryPage::DIR_PREV )
			list($dirs, $oper) = array("ASC", ">=");
		else /* $direction == HistoryPage::DIR_NEXT */
			list($dirs, $oper) = array("DESC", "<=");

		if( $offset )
			$offsets = array("rev_timestamp $oper '$offset'");
		else
			$offsets = array();

		$page_id = $this->title->getArticleID();

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
			$this->title->getPrefixedText() . ' - ' .
			wfMsgForContent( 'history-feed-title' ),
			wfMsgForContent( 'history-feed-description' ),
			$this->title->getFullUrl( 'action=history' )
		);

		// Get a limit on number of feed entries. Provide a sane default
		// of 10 if none is defined (but limit to $wgFeedLimit max)
		$limit = $wgRequest->getInt( 'limit', 10 );
		if( $limit > $wgFeedLimit || $limit < 1 ) {
			$limit = 10;
		}
		$items = $this->fetchRevisions($limit, 0, HistoryPage::DIR_NEXT);

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
			$this->title->getFullUrl(),
			wfTimestamp( TS_MW ),
			'',
			$this->title->getTalkPage()->getFullUrl()
		);
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
		$rev->setTitle( $this->title );
		$text = FeedUtils::formatDiffRow(
			$this->title,
			$this->title->getPreviousRevisionID( $rev->getId() ),
			$rev->getId(),
			$rev->getTimestamp(),
			$rev->getComment()
		);
		if( $rev->getComment() == '' ) {
			global $wgContLang;
			$title = wfMsgForContent( 'history-feed-item-nocomment',
			$rev->getUserText(),
			$wgContLang->timeanddate( $rev->getTimestamp() ) );
		} else {
			$title = $rev->getUserText() .
			wfMsgForContent( 'colon-separator' ) .
			FeedItem::stripComment( $rev->getComment() );
		}
		return new FeedItem(
			$title,
			$text,
			$this->title->getFullUrl( 'diff=' . $rev->getId() . '&oldid=prev' ),
			$rev->getTimestamp(),
			$rev->getUserText(),
			$this->title->getTalkPage()->getFullUrl()
		);
	}
}

/**
 * @ingroup Pager
 */
class HistoryPager extends ReverseChronologicalPager {
	public $lastRow = false, $counter, $historyPage, $title, $buttons;
	protected $oldIdChecked;

	function __construct( $historyPage, $year='', $month='', $tagFilter = '' ) {
		parent::__construct();
		$this->historyPage = $historyPage;
		$this->title = $this->historyPage->title;
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month );
	}

	// For hook compatibility...
	function getArticle() {
		return $this->historyPage->getArticle();
	}

	function getQueryInfo() {
		$queryInfo = array(
			'tables'  => array('revision'),
			'fields'  => array_merge( Revision::selectFields(), array('ts_tags') ),
			'conds'   => array('rev_page' => $this->historyPage->title->getArticleID() ),
			'options' => array( 'USE INDEX' => array('revision' => 'page_timestamp') ),
			'join_conds' => array( 'tag_summary' => array( 'LEFT JOIN', 'ts_rev_id=rev_id' ) ),
		);
		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);
		wfRunHooks( 'PageHistoryPager::getQueryInfo', array( &$this, &$queryInfo ) );
		return $queryInfo;
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	function formatRow( $row ) {
		if( $this->lastRow ) {
			$latest = ($this->counter == 1 && $this->mIsFirst);
			$firstInList = $this->counter == 1;
			$s = $this->historyLine( $this->lastRow, $row, $this->counter++,
				$this->title->getNotificationTimestamp(), $latest, $firstInList );
		} else {
			$s = '';
		}
		$this->lastRow = $row;
		return $s;
	}

	/**
	 * Creates begin of history list with a submit button
	 *
	 * @return string HTML output
	 */
	function getStartBody() {
		global $wgScript, $wgEnableHtmlDiff, $wgUser, $wgOut;
		$this->lastRow = false;
		$this->counter = 1;
		$this->oldIdChecked = 0;

		$wgOut->wrapWikiMsg( "<div class='mw-history-legend'>\n$1</div>", 'histlegend' );
		$s = Xml::openElement( 'form', array( 'action' => $wgScript,
			'id' => 'mw-history-compare' ) ) . "\n";
		$s .= Xml::hidden( 'title', $this->title->getPrefixedDbKey() ) . "\n";

		$this->buttons = '<div>';
		if( $wgUser->isAllowed('deletedhistory') ) {
			$this->buttons .= Xml::element( 'button',
				array(
					'type' => 'submit',
					'name' => 'action',
					'value' => 'revisiondelete',
					'style' => 'float: right',
				),
				wfMsg( 'showhideselectedversions' )
			) . "\n";
		}
		if( $wgEnableHtmlDiff ) {
			$this->buttons .= Xml::element( 'button',
				array(
					'type'      => 'submit',
					'name'      => 'htmldiff',
					'value'     => '1',
					'class'     => 'historysubmit',
					'accesskey' => wfMsg( 'accesskey-visualcomparison' ),
					'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				),
				wfMsg( 'visualcomparison')
			) . "\n";
			$this->buttons .= $this->submitButton( wfMsg( 'wikicodecomparison'),
				array(
					'class'     => 'historysubmit',
					'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
					'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			) . "\n";
		} else {
			$this->buttons .= $this->submitButton( wfMsg( 'compareselectedversions'),
				array(
					'class'     => 'historysubmit',
					'accesskey' => wfMsg( 'accesskey-compareselectedversions' ),
					'title'     => wfMsg( 'tooltip-compareselectedversions' ),
				)
			) . "\n";
		}
		$this->buttons .= '</div>';
		$s .= $this->buttons . '<ul id="pagehistory">' . "\n";
		return $s;
	}

	function getEndBody() {
		if( $this->lastRow ) {
			$latest = $this->counter == 1 && $this->mIsFirst;
			$firstInList = $this->counter == 1;
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
			$s = $this->historyLine( $this->lastRow, $next, $this->counter++,
				$this->title->getNotificationTimestamp(), $latest, $firstInList );
		} else {
			$s = '';
		}
		$s .= "</ul>\n";
		$s .= $this->buttons;
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
		if( $this->getNumRows() > 1 ) {
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
	function historyLine( $row, $next, $counter = '', $notificationtimestamp = false,
		$latest = false, $firstInList = false )
	{
		global $wgUser, $wgLang;
		$rev = new Revision( $row );
		$rev->setTitle( $this->title );

		$curlink = $this->curLink( $rev, $latest );
		$lastlink = $this->lastLink( $rev, $next, $counter );
		$diffButtons = $this->diffButtons( $rev, $firstInList, $counter );
		$link = $this->revLink( $rev );
		$classes = array();

		$s = "($curlink) ($lastlink) $diffButtons";

		if( $wgUser->isAllowed( 'deletedhistory' ) ) {
			// Don't show useless link to people who cannot hide revisions
			if( !$rev->getVisibility() && !$wgUser->isAllowed( 'deleterevision' ) ) {
				$del = Xml::check( 'deleterevisions', false, array('class' => 'mw-revdelundel-hidden') );
			// If revision was hidden from sysops
			} else if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$del = Xml::check( 'deleterevisions', false, array('disabled' => 'disabled') );
			// Otherwise, show the link...
			} else {
				$id = $rev->getId();
				$del = Xml::check( 'showhiderevisions', false, array( 'name' => "ids[$id]" ) );
			}
			$s .= " $del ";
		}

		$s .= " $link";
		$s .= " <span class='history-user'>" . $this->getSkin()->revUserTools( $rev, true ) . "</span>";

		if( $rev->isMinor() ) {
			$s .= ' ' . ChangesList::flag( 'minor' );
		}

		if( !is_null( $size = $rev->getSize() ) && !$rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$s .= ' ' . $this->getSkin()->formatRevisionSize( $size );
		}

		$s .= $this->getSkin()->revComment( $rev, false, true );

		if( $notificationtimestamp && ($row->rev_timestamp >= $notificationtimestamp) ) {
			$s .= ' <span class="updatedmarker">' .  wfMsgHtml( 'updatedmarker' ) . '</span>';
		}

		$tools = array();

		# Rollback and undo links
		if( !is_null( $next ) && is_object( $next ) ) {
			if( $latest && $this->title->userCan( 'rollback' ) && $this->title->userCan( 'edit' ) ) {
				$tools[] = '<span class="mw-rollback-link">'.
					$this->getSkin()->buildRollbackLink( $rev ).'</span>';
			}

			if( $this->title->quickUserCan( 'edit' )
				&& !$rev->isDeleted( Revision::DELETED_TEXT )
				&& !$next->rev_deleted & Revision::DELETED_TEXT )
			{
				# Create undo tooltip for the first (=latest) line only
				$undoTooltip = $latest
					? array( 'title' => wfMsg( 'tooltip-undo' ) )
					: array();
				$undolink = $this->getSkin()->link(
					$this->title,
					wfMsgHtml( 'editundo' ),
					$undoTooltip,
					array(
						'action' => 'edit',
						'undoafter' => $next->rev_id,
						'undo' => $rev->getId()
					),
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

		$attribs = array();
		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		return Xml::tags( 'li', $attribs, $s ) . "\n";
	}

	/**
	 * Create a link to view this revision of the page
	 * @param Revision $rev
	 * @returns string
	 */
	function revLink( $rev ) {
		global $wgLang;
		$date = $wgLang->timeanddate( wfTimestamp(TS_MW, $rev->getTimestamp()), true );
		$date = htmlspecialchars( $date );
		if( !$rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = $this->getSkin()->link(
				$this->title,
				$date,
				array(),
				array( 'oldid' => $rev->getId() ),
				array( 'known', 'noclasses' )
			);
		} else {
			$link = "<span class=\"history-deleted\">$date</span>";
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
		$cur = $this->historyPage->message['cur'];
		if( $latest || !$rev->userCan( Revision::DELETED_TEXT ) ) {
			return $cur;
		} else {
			return $this->getSkin()->link(
				$this->title,
				$cur,
				array(),
				array(
					'diff' => $this->title->getLatestRevID(),
					'oldid' => $rev->getId()
				),
				array( 'known', 'noclasses' )
			);
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
		$last = $this->historyPage->message['last'];
		# $next may either be a Row, null, or "unkown"
		$nextRev = is_object($next) ? new Revision( $next ) : $next;
		if( is_null($next) ) {
			# Probably no next row
			return $last;
		} elseif( $next === 'unknown' ) {
			# Next row probably exists but is unknown, use an oldid=prev link
			return $this->getSkin()->link(
				$this->title,
				$last,
				array(),
				array(
					'diff' => $prevRev->getId(),
					'oldid' => 'prev'
				),
				array( 'known', 'noclasses' )
			);
		} elseif( !$prevRev->userCan(Revision::DELETED_TEXT) || !$nextRev->userCan(Revision::DELETED_TEXT) ) {
			return $last;
		} else {
			return $this->getSkin()->link(
				$this->title,
				$last,
				array(),
				array(
					'diff' => $prevRev->getId(),
					'oldid' => $next->rev_id
				),
				array( 'known', 'noclasses' )
			);
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
		if( $this->getNumRows() > 1 ) {
			$id = $rev->getId();
			$radio = array( 'type'  => 'radio', 'value' => $id );
			/** @todo: move title texts to javascript */
			if( $firstInList ) {
				$first = Xml::element( 'input',
					array_merge( $radio, array(
						'style' => 'visibility:hidden',
						'name'  => 'oldid',
						'id' => 'mw-oldid-null' ) )
				);
				$checkmark = array( 'checked' => 'checked' );
			} else {
				# Check visibility of old revisions
				if( !$rev->userCan( Revision::DELETED_TEXT ) ) {
					$radio['disabled'] = 'disabled';
					$checkmark = array(); // We will check the next possible one
				} else if( $counter == 2 || !$this->oldIdChecked ) {
					$checkmark = array( 'checked' => 'checked' );
					$this->oldIdChecked = $id;
				} else {
					$checkmark = array();
				}
				$first = Xml::element( 'input',
					array_merge( $radio, $checkmark, array(
						'name'  => 'oldid',
						'id' => "mw-oldid-$id" ) ) );
				$checkmark = array();
			}
			$second = Xml::element( 'input',
				array_merge( $radio, $checkmark, array(
					'name'  => 'diff',
					'id' => "mw-diff-$id" ) ) );
			return $first . $second;
		} else {
			return '';
		}
	}
}

/**
 * Backwards-compatibility aliases
 */
class PageHistory extends HistoryPage {}
class PageHistoryPager extends HistoryPager {}
