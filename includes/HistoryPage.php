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

	/** Contains the Article object. Passed on construction. */
	private $article;
	/** The $article title object. Found on construction. */
	private $title;
	/** Shortcut to the user Skin object. */
   	private $skin;

	/**
	 * Construct a new HistoryPage.
	 *
	 * @param $article Article
	 */
	function __construct( $article ) {
		global $wgUser;
		$this->article = $article;
		$this->title = $article->getTitle();
		$this->skin = $wgUser->getSkin();
		$this->preCacheMessages();
	}

	/** Get the Article object we are working on. */
	public function getArticle() {
		return $this->article;
	}

	/** Get the Title object. */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		// Precache various messages
		if ( !isset( $this->message ) ) {
			$msgs = array( 'cur', 'last', 'pipe-separator' );
			foreach ( $msgs as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}
	}

	/**
	 * Print the history page for an article.
	 * @return nothing
	 */
	function history() {
		global $wgOut, $wgRequest, $wgScript;

		/**
		 * Allow client caching.
		 */
		if ( $wgOut->checkLastModified( $this->article->getTouched() ) )
			return; // Client cache fresh and headers sent, nothing more to do.

		wfProfileIn( __METHOD__ );

		// Setup page variables.
		$wgOut->setPageTitle( wfMsg( 'history-title', $this->title->getPrefixedText() ) );
		$wgOut->setPageTitleActionText( wfMsg( 'history_short' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( 'action=history' );
		$wgOut->addModules( array( 'mediawiki.legacy.history', 'mediawiki.views.history' ) );

		// Creation of a subtitle link pointing to [[Special:Log]]
		$logPage = SpecialPage::getTitleFor( 'Log' );
		$logLink = $this->skin->link(
			$logPage,
			wfMsgHtml( 'viewpagelogs' ),
			array(),
			array( 'page' => $this->title->getPrefixedText() ),
			array( 'known', 'noclasses' )
		);
		$wgOut->setSubtitle( $logLink );

		// Handle atom/RSS feeds.
		$feedType = $wgRequest->getVal( 'feed' );
		if ( $feedType ) {
			wfProfileOut( __METHOD__ );
			return $this->feed( $feedType );
		}

		// Fail nicely if article doesn't exist.
		if ( !$this->title->exists() ) {
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
		$year        = $wgRequest->getInt( 'year' );
		$month       = $wgRequest->getInt( 'month' );
		$tagFilter   = $wgRequest->getVal( 'tagfilter' );
		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		/**
		 * Option to show only revisions that have been (partially) hidden via RevisionDelete
		 */
		if ( $wgRequest->getBool( 'deleted' ) ) {
			$conds = array( "rev_deleted != '0'" );
		} else {
			$conds = array();
		}
		$checkDeleted = Xml::checkLabel( wfMsg( 'history-show-deleted' ),
			'deleted', 'mw-show-deleted-only', $wgRequest->getBool( 'deleted' ) ) . "\n";

		// Add the general form
		$action = htmlspecialchars( $wgScript );
		$wgOut->addHTML(
			"<form action=\"$action\" method=\"get\" id=\"mw-history-searchform\">" .
			Xml::fieldset(
				wfMsg( 'history-fieldset-title' ),
				false,
				array( 'id' => 'mw-history-search' )
			) .
			Html::hidden( 'title', $this->title->getPrefixedDBKey() ) . "\n" .
			Html::hidden( 'action', 'history' ) . "\n" .
			Xml::dateMenu( $year, $month ) . '&#160;' .
			( $tagSelector ? ( implode( '&#160;', $tagSelector ) . '&#160;' ) : '' ) .
			$checkDeleted .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			'</fieldset></form>'
		);

		wfRunHooks( 'PageHistoryBeforeList', array( &$this->article ) );

		// Create and output the list.
		$pager = new HistoryPager( $this, $year, $month, $tagFilter, $conds );
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
	 *
	 * @param $limit Integer: the limit number of revisions to get
	 * @param $offset Integer
	 * @param $direction Integer: either HistoryPage::DIR_PREV or HistoryPage::DIR_NEXT
	 * @return ResultWrapper
	 */
	function fetchRevisions( $limit, $offset, $direction ) {
		$dbr = wfGetDB( DB_SLAVE );

		if ( $direction == HistoryPage::DIR_PREV ) {
			list( $dirs, $oper ) = array( "ASC", ">=" );
		} else { /* $direction == HistoryPage::DIR_NEXT */
			list( $dirs, $oper ) = array( "DESC", "<=" );
		}

		if ( $offset ) {
			$offsets = array( "rev_timestamp $oper '$offset'" );
		} else {
			$offsets = array();
		}

		$page_id = $this->title->getArticleID();

		return $dbr->select( 'revision',
			Revision::selectFields(),
			array_merge( array( "rev_page=$page_id" ), $offsets ),
			__METHOD__,
			array( 'ORDER BY' => "rev_timestamp $dirs",
				'USE INDEX' => 'page_timestamp', 'LIMIT' => $limit )
		);
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param $type String: feed type
	 */
	function feed( $type ) {
		global $wgFeedClasses, $wgRequest, $wgFeedLimit;
		if ( !FeedUtils::checkFeedOutput( $type ) ) {
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
		if ( $limit > $wgFeedLimit || $limit < 1 ) {
			$limit = 10;
		}
		$items = $this->fetchRevisions( $limit, 0, HistoryPage::DIR_NEXT );

		// Generate feed elements enclosed between header and footer.
		$feed->outHeader();
		if ( $items ) {
			foreach ( $items as $row ) {
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
	 * @param $row Object: database row
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
		if ( $rev->getComment() == '' ) {
			global $wgContLang;
			$title = wfMsgForContent( 'history-feed-item-nocomment',
				$rev->getUserText(),
				$wgContLang->timeanddate( $rev->getTimestamp() ),
				$wgContLang->date( $rev->getTimestamp() ),
				$wgContLang->time( $rev->getTimestamp() )
			);
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
	public $lastRow = false, $counter, $historyPage, $title, $buttons, $conds;
	protected $oldIdChecked;

	function __construct( $historyPage, $year = '', $month = '', $tagFilter = '', $conds = array() ) {
		parent::__construct();
		$this->historyPage = $historyPage;
		$this->title = $this->historyPage->getTitle();
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month );
		$this->conds = $conds;
	}

	// For hook compatibility...
	function getArticle() {
		return $this->historyPage->getArticle();
	}

	function getSqlComment() {
		if ( $this->conds ) {
			return 'history page filtered'; // potentially slow, see CR r58153
		} else {
			return 'history page unfiltered';
		}
	}

	function getQueryInfo() {
		$queryInfo = array(
			'tables'  => array( 'revision' ),
			'fields'  => Revision::selectFields(),
			'conds'   => array_merge(
				array( 'rev_page' => $this->historyPage->getTitle()->getArticleID() ),
				$this->conds ),
			'options' => array( 'USE INDEX' => array( 'revision' => 'page_timestamp' ) ),
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
		if ( $this->lastRow ) {
			$latest = ( $this->counter == 1 && $this->mIsFirst );
			$firstInList = $this->counter == 1;
			$this->counter++;
			$s = $this->historyLine( $this->lastRow, $row,
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
		global $wgScript, $wgUser, $wgOut, $wgContLang;
		$this->lastRow = false;
		$this->counter = 1;
		$this->oldIdChecked = 0;

		$wgOut->wrapWikiMsg( "<div class='mw-history-legend'>\n$1\n</div>", 'histlegend' );
		$s = Html::openElement( 'form', array( 'action' => $wgScript,
			'id' => 'mw-history-compare' ) ) . "\n";
		$s .= Html::hidden( 'title', $this->title->getPrefixedDbKey() ) . "\n";
		$s .= Html::hidden( 'action', 'historysubmit' ) . "\n";

		$s .= '<div>' . $this->submitButton( wfMsg( 'compareselectedversions' ),
			array( 'class' => 'historysubmit' ) ) . "\n";

		$this->buttons = '<div>';
		$this->buttons .= $this->submitButton( wfMsg( 'compareselectedversions' ),
			array( 'class' => 'historysubmit' )
				+ $wgUser->getSkin()->tooltipAndAccessKeyAttribs( 'compareselectedversions' )
		) . "\n";

		if ( $wgUser->isAllowed( 'deleterevision' ) ) {
			$float = $wgContLang->alignEnd();
			# Note bug #20966, <button> is non-standard in IE<8
			$element = Html::element( 'button',
				array(
					'type' => 'submit',
					'name' => 'revisiondelete',
					'value' => '1',
					'style' => "float: $float;",
					'class' => 'mw-history-revisiondelete-button',
				),
				wfMsg( 'showhideselectedversions' )
			) . "\n";
			$s .= $element;
			$this->buttons .= $element;
		}
		if ( $wgUser->isAllowed( 'revisionmove' ) ) {
			$float = $wgContLang->alignEnd();
			# Note bug #20966, <button> is non-standard in IE<8
			$element = Html::element( 'button',
				array(
					'type' => 'submit',
					'name' => 'revisionmove',
					'value' => '1',
					'style' => "float: $float;",
					'class' => 'mw-history-revisionmove-button',
				),
				wfMsg( 'revisionmoveselectedversions' )
			) . "\n";
			$s .= $element;
			$this->buttons .= $element;
		}
		$this->buttons .= '</div>';
		$s .= '</div><ul id="pagehistory">' . "\n";
		return $s;
	}

	function getEndBody() {
		if ( $this->lastRow ) {
			$latest = $this->counter == 1 && $this->mIsFirst;
			$firstInList = $this->counter == 1;
			if ( $this->mIsBackwards ) {
				# Next row is unknown, but for UI reasons, probably exists if an offset has been specified
				if ( $this->mOffset == '' ) {
					$next = null;
				} else {
					$next = 'unknown';
				}
			} else {
				# The next row is the past-the-end row
				$next = $this->mPastTheEndRow;
			}
			$this->counter++;
			$s = $this->historyLine( $this->lastRow, $next,
				$this->title->getNotificationTimestamp(), $latest, $firstInList );
		} else {
			$s = '';
		}
		$s .= "</ul>\n";
		# Add second buttons only if there is more than one rev
		if ( $this->getNumRows() > 2 ) {
			$s .= $this->buttons;
		}
		$s .= '</form>';
		return $s;
	}

	/**
	 * Creates a submit button
	 *
	 * @param $message String: text of the submit button, will be escaped
	 * @param $attributes Array: attributes
	 * @return String: HTML output for the submit button
	 */
	function submitButton( $message, $attributes = array() ) {
		# Disable submit button if history has 1 revision only
		if ( $this->getNumRows() > 1 ) {
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
	 * @param $row Object: the database row corresponding to the previous line.
	 * @param $next Mixed: the database row corresponding to the next line.
	 * @param $notificationtimestamp
	 * @param $latest Boolean: whether this row corresponds to the page's latest revision.
	 * @param $firstInList Boolean: whether this row corresponds to the first displayed on this history page.
	 * @return String: HTML output for the row
	 */
	function historyLine( $row, $next, $notificationtimestamp = false,
		$latest = false, $firstInList = false )
	{
		global $wgUser, $wgLang;
		$rev = new Revision( $row );
		$rev->setTitle( $this->title );

		$curlink = $this->curLink( $rev, $latest );
		$lastlink = $this->lastLink( $rev, $next );
		$diffButtons = $this->diffButtons( $rev, $firstInList );
		$histLinks = Html::rawElement(
				'span',
				array( 'class' => 'mw-history-histlinks' ),
				'(' . $curlink . $this->historyPage->message['pipe-separator'] . $lastlink . ') '
		);
		$s = $histLinks . $diffButtons;

		$link = $this->revLink( $rev );
		$classes = array();

		$del = '';
		// Show checkboxes for each revision
		if ( $wgUser->isAllowed( 'deleterevision' ) || $wgUser->isAllowed( 'revisionmove' ) ) {
			// If revision was hidden from sysops, disable the checkbox
			// However, if the user has revisionmove rights, we cannot disable the checkbox
			if ( !$rev->userCan( Revision::DELETED_RESTRICTED ) && !$wgUser->isAllowed( 'revisionmove' ) ) {
				$del = Xml::check( 'deleterevisions', false, array( 'disabled' => 'disabled' ) );
			// Otherwise, enable the checkbox...
			} else {
				$del = Xml::check( 'showhiderevisions', false,
					array( 'name' => 'ids[' . $rev->getId() . ']' ) );
			}
		// User can only view deleted revisions...
		} else if ( $rev->getVisibility() && $wgUser->isAllowed( 'deletedhistory' ) ) {
			// If revision was hidden from sysops, disable the link
			if ( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$cdel = $this->getSkin()->revDeleteLinkDisabled( false );
			// Otherwise, show the link...
			} else {
				$query = array( 'type' => 'revision',
					'target' => $this->title->getPrefixedDbkey(), 'ids' => $rev->getId() );
				$del .= $this->getSkin()->revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), false );
			}
		}
		if ( $del ) {
			$s .= " $del ";
		}

		$s .= " $link";
		$s .= " <span class='history-user'>" .
			$this->getSkin()->revUserTools( $rev, true ) . "</span>";

		if ( $rev->isMinor() ) {
			$s .= ' ' . ChangesList::flag( 'minor' );
		}

		if ( !is_null( $size = $rev->getSize() ) && !$rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$s .= ' ' . $this->getSkin()->formatRevisionSize( $size );
		}

		$s .= $this->getSkin()->revComment( $rev, false, true );

		if ( $notificationtimestamp && ( $row->rev_timestamp >= $notificationtimestamp ) ) {
			$s .= ' <span class="updatedmarker">' .  wfMsgHtml( 'updatedmarker' ) . '</span>';
		}

		$tools = array();

		# Rollback and undo links
		if ( !is_null( $next ) && is_object( $next ) ) {
			if ( $latest && $this->title->userCan( 'rollback' ) && $this->title->userCan( 'edit' ) ) {
				$tools[] = '<span class="mw-rollback-link">' .
					$this->getSkin()->buildRollbackLink( $rev ) . '</span>';
			}

			if ( $this->title->quickUserCan( 'edit' )
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

		if ( $tools ) {
			$s .= ' (' . $wgLang->pipeList( $tools ) . ')';
		}

		# Tags
		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow( $row->ts_tags, 'history' );
		$classes = array_merge( $classes, $newClasses );
		$s .= " $tagSummary";

		wfRunHooks( 'PageHistoryLineEnding', array( $this, &$row , &$s, &$classes ) );

		$attribs = array();
		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		return Xml::tags( 'li', $attribs, $s ) . "\n";
	}

	/**
	 * Create a link to view this revision of the page
	 *
	 * @param $rev Revision
	 * @return String
	 */
	function revLink( $rev ) {
		global $wgLang;
		$date = $wgLang->timeanddate( wfTimestamp( TS_MW, $rev->getTimestamp() ), true );
		$date = htmlspecialchars( $date );
		if ( $rev->userCan( Revision::DELETED_TEXT ) ) {
			$link = $this->getSkin()->link(
				$this->title,
				$date,
				array(),
				array( 'oldid' => $rev->getId() ),
				array( 'known', 'noclasses' )
			);
		} else {
			$link = $date;
		}
		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = "<span class=\"history-deleted\">$link</span>";
		}
		return $link;
	}

	/**
	 * Create a diff-to-current link for this revision for this page
	 *
	 * @param $rev Revision
	 * @param $latest Boolean: this is the latest revision of the page?
	 * @return String
	 */
	function curLink( $rev, $latest ) {
		$cur = $this->historyPage->message['cur'];
		if ( $latest || !$rev->userCan( Revision::DELETED_TEXT ) ) {
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
	 *
	 * @param $prevRev Revision: the previous revision
	 * @param $next Mixed: the newer revision
	 * @return String
	 */
	function lastLink( $prevRev, $next ) {
		$last = $this->historyPage->message['last'];
		# $next may either be a Row, null, or "unkown"
		$nextRev = is_object( $next ) ? new Revision( $next ) : $next;
		if ( is_null( $next ) ) {
			# Probably no next row
			return $last;
		} elseif ( $next === 'unknown' ) {
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
		} elseif ( !$prevRev->userCan( Revision::DELETED_TEXT )
			|| !$nextRev->userCan( Revision::DELETED_TEXT ) )
		{
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
	 * @param $rev Revision object
	 * @param $firstInList Boolean: is this version the first one?
	 *
	 * @return String: HTML output for the radio buttons
	 */
	function diffButtons( $rev, $firstInList ) {
		if ( $this->getNumRows() > 1 ) {
			$id = $rev->getId();
			$radio = array( 'type'  => 'radio', 'value' => $id );
			/** @todo: move title texts to javascript */
			if ( $firstInList ) {
				$first = Xml::element( 'input',
					array_merge( $radio, array(
						'style' => 'visibility:hidden',
						'name'  => 'oldid',
						'id' => 'mw-oldid-null' ) )
				);
				$checkmark = array( 'checked' => 'checked' );
			} else {
				# Check visibility of old revisions
				if ( !$rev->userCan( Revision::DELETED_TEXT ) ) {
					$radio['disabled'] = 'disabled';
					$checkmark = array(); // We will check the next possible one
				} else if ( !$this->oldIdChecked ) {
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
