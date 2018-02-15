<?php
/**
 * Page history
 *
 * Split off from Article.php and Skin.php, 2003-12-22
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Actions
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * This class handles printing the history page for an article. In order to
 * be efficient, it uses timestamps rather than offsets for paging, to avoid
 * costly LIMIT,offset queries.
 *
 * Construct it by passing in an Article, and call $h->history() to print the
 * history.
 *
 * @ingroup Actions
 */
class HistoryAction extends FormlessAction {
	const DIR_PREV = 0;
	const DIR_NEXT = 1;

	/** @var array Array of message keys and strings */
	public $message;

	public function getName() {
		return 'history';
	}

	public function requiresWrite() {
		return false;
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getPageTitle() {
		return $this->msg( 'history-title', $this->getTitle()->getPrefixedText() )->text();
	}

	protected function getDescription() {
		// Creation of a subtitle link pointing to [[Special:Log]]
		return MediaWikiServices::getInstance()->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->msg( 'viewpagelogs' )->text(),
			[],
			[ 'page' => $this->getTitle()->getPrefixedText() ]
		);
	}

	/**
	 * @return WikiPage|Article|ImagePage|CategoryPage|Page The Article object we are working on.
	 */
	public function getArticle() {
		return $this->page;
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		// Precache various messages
		if ( !isset( $this->message ) ) {
			$msgs = [ 'cur', 'last', 'pipe-separator' ];
			foreach ( $msgs as $msg ) {
				$this->message[$msg] = $this->msg( $msg )->escaped();
			}
		}
	}

	/**
	 * Print the history page for an article.
	 */
	function onView() {
		$out = $this->getOutput();
		$request = $this->getRequest();

		/**
		 * Allow client caching.
		 */
		if ( $out->checkLastModified( $this->page->getTouched() ) ) {
			return; // Client cache fresh and headers sent, nothing more to do.
		}

		$this->preCacheMessages();
		$config = $this->context->getConfig();

		# Fill in the file cache if not set already
		if ( HTMLFileCache::useFileCache( $this->getContext() ) ) {
			$cache = new HTMLFileCache( $this->getTitle(), 'history' );
			if ( !$cache->isCacheGood( /* Assume up to date */ ) ) {
				ob_start( [ &$cache, 'saveToFileCache' ] );
			}
		}

		// Setup page variables.
		$out->setFeedAppendQuery( 'action=history' );
		$out->addModules( 'mediawiki.action.history' );
		$out->addModuleStyles( [
			'mediawiki.action.history.styles',
			'mediawiki.special.changeslist',
		] );
		if ( $config->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out = $this->getOutput();
			$out->addModuleStyles( [
				'mediawiki.ui.input',
				'mediawiki.ui.checkbox',
			] );
		}

		// Handle atom/RSS feeds.
		$feedType = $request->getVal( 'feed' );
		if ( $feedType ) {
			$this->feed( $feedType );

			return;
		}

		$this->addHelpLink( '//meta.wikimedia.org/wiki/Special:MyLanguage/Help:Page_history', true );

		// Fail nicely if article doesn't exist.
		if ( !$this->page->exists() ) {
			global $wgSend404Code;
			if ( $wgSend404Code ) {
				$out->setStatusCode( 404 );
			}
			$out->addWikiMsg( 'nohistory' );

			$dbr = wfGetDB( DB_REPLICA );

			# show deletion/move log if there is an entry
			LogEventsList::showLogExtract(
				$out,
				[ 'delete', 'move', 'protect' ],
				$this->getTitle(),
				'',
				[ 'lim' => 10,
					'conds' => [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ],
					'showIfEmpty' => false,
					'msgKey' => [ 'moveddeleted-notice' ]
				]
			);

			return;
		}

		/**
		 * Add date selector to quickly get to a certain time
		 */
		$year = $request->getInt( 'year' );
		$month = $request->getInt( 'month' );
		$tagFilter = $request->getVal( 'tagfilter' );
		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter, false, $this->getContext() );

		/**
		 * Option to show only revisions that have been (partially) hidden via RevisionDelete
		 */
		if ( $request->getBool( 'deleted' ) ) {
			$conds = [ 'rev_deleted != 0' ];
		} else {
			$conds = [];
		}
		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$checkDeleted = Xml::checkLabel( $this->msg( 'history-show-deleted' )->text(),
				'deleted', 'mw-show-deleted-only', $request->getBool( 'deleted' ) ) . "\n";
		} else {
			$checkDeleted = '';
		}

		// Add the general form
		$action = htmlspecialchars( wfScript() );
		$content = Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n";
		$content .= Html::hidden( 'action', 'history' ) . "\n";
		$content .= Xml::dateMenu(
			( $year == null ? MWTimestamp::getLocalInstance()->format( 'Y' ) : $year ),
			$month
		) . '&#160;';
		$content .= $tagSelector ? ( implode( '&#160;', $tagSelector ) . '&#160;' ) : '';
		$content .= $checkDeleted . Html::submitButton(
			$this->msg( 'historyaction-submit' )->text(),
			[],
			[ 'mw-ui-progressive' ]
		);
		$out->addHTML(
			"<form action=\"$action\" method=\"get\" id=\"mw-history-searchform\">" .
			Xml::fieldset(
				$this->msg( 'history-fieldset-title' )->text(),
				$content,
				[ 'id' => 'mw-history-search' ]
			) .
			'</form>'
		);

		Hooks::run( 'PageHistoryBeforeList', [ &$this->page, $this->getContext() ] );

		// Create and output the list.
		$pager = new HistoryPager( $this, $year, $month, $tagFilter, $conds );
		$out->addHTML(
			$pager->getNavigationBar() .
			$pager->getBody() .
			$pager->getNavigationBar()
		);
		$out->preventClickjacking( $pager->getPreventClickjacking() );
	}

	/**
	 * Fetch an array of revisions, specified by a given limit, offset and
	 * direction. This is now only used by the feeds. It was previously
	 * used by the main UI but that's now handled by the pager.
	 *
	 * @param int $limit The limit number of revisions to get
	 * @param int $offset
	 * @param int $direction Either self::DIR_PREV or self::DIR_NEXT
	 * @return ResultWrapper
	 */
	function fetchRevisions( $limit, $offset, $direction ) {
		// Fail if article doesn't exist.
		if ( !$this->getTitle()->exists() ) {
			return new FakeResultWrapper( [] );
		}

		$dbr = wfGetDB( DB_REPLICA );

		if ( $direction === self::DIR_PREV ) {
			list( $dirs, $oper ) = [ "ASC", ">=" ];
		} else { /* $direction === self::DIR_NEXT */
			list( $dirs, $oper ) = [ "DESC", "<=" ];
		}

		if ( $offset ) {
			$offsets = [ "rev_timestamp $oper " . $dbr->addQuotes( $dbr->timestamp( $offset ) ) ];
		} else {
			$offsets = [];
		}

		$page_id = $this->page->getId();

		$revQuery = Revision::getQueryInfo();
		return $dbr->select(
			$revQuery['tables'],
			$revQuery['fields'],
			array_merge( [ 'rev_page' => $page_id ], $offsets ),
			__METHOD__,
			[
				'ORDER BY' => "rev_timestamp $dirs",
				'USE INDEX' => [ 'revision' => 'page_timestamp' ],
				'LIMIT' => $limit
			],
			$revQuery['joins']
		);
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param string $type Feed type
	 */
	function feed( $type ) {
		if ( !FeedUtils::checkFeedOutput( $type ) ) {
			return;
		}
		$request = $this->getRequest();

		$feedClasses = $this->context->getConfig()->get( 'FeedClasses' );
		/** @var RSSFeed|AtomFeed $feed */
		$feed = new $feedClasses[$type](
			$this->getTitle()->getPrefixedText() . ' - ' .
			$this->msg( 'history-feed-title' )->inContentLanguage()->text(),
			$this->msg( 'history-feed-description' )->inContentLanguage()->text(),
			$this->getTitle()->getFullURL( 'action=history' )
		);

		// Get a limit on number of feed entries. Provide a sane default
		// of 10 if none is defined (but limit to $wgFeedLimit max)
		$limit = $request->getInt( 'limit', 10 );
		$limit = min(
			max( $limit, 1 ),
			$this->context->getConfig()->get( 'FeedLimit' )
		);

		$items = $this->fetchRevisions( $limit, 0, self::DIR_NEXT );

		// Generate feed elements enclosed between header and footer.
		$feed->outHeader();
		if ( $items->numRows() ) {
			foreach ( $items as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		} else {
			$feed->outItem( $this->feedEmpty() );
		}
		$feed->outFooter();
	}

	function feedEmpty() {
		return new FeedItem(
			$this->msg( 'nohistory' )->inContentLanguage()->text(),
			$this->msg( 'history-feed-empty' )->inContentLanguage()->parseAsBlock(),
			$this->getTitle()->getFullURL(),
			wfTimestamp( TS_MW ),
			'',
			$this->getTitle()->getTalkPage()->getFullURL()
		);
	}

	/**
	 * Generate a FeedItem object from a given revision table row
	 * Borrows Recent Changes' feed generation functions for formatting;
	 * includes a diff to the previous revision (if any).
	 *
	 * @param stdClass|array $row Database row
	 * @return FeedItem
	 */
	function feedItem( $row ) {
		$rev = new Revision( $row, 0, $this->getTitle() );

		$text = FeedUtils::formatDiffRow(
			$this->getTitle(),
			$this->getTitle()->getPreviousRevisionID( $rev->getId() ),
			$rev->getId(),
			$rev->getTimestamp(),
			$rev->getComment()
		);
		if ( $rev->getComment() == '' ) {
			global $wgContLang;
			$title = $this->msg( 'history-feed-item-nocomment',
				$rev->getUserText(),
				$wgContLang->timeanddate( $rev->getTimestamp() ),
				$wgContLang->date( $rev->getTimestamp() ),
				$wgContLang->time( $rev->getTimestamp() ) )->inContentLanguage()->text();
		} else {
			$title = $rev->getUserText() .
				$this->msg( 'colon-separator' )->inContentLanguage()->text() .
				FeedItem::stripComment( $rev->getComment() );
		}

		return new FeedItem(
			$title,
			$text,
			$this->getTitle()->getFullURL( 'diff=' . $rev->getId() . '&oldid=prev' ),
			$rev->getTimestamp(),
			$rev->getUserText(),
			$this->getTitle()->getTalkPage()->getFullURL()
		);
	}
}

/**
 * @ingroup Pager
 * @ingroup Actions
 */
class HistoryPager extends ReverseChronologicalPager {
	/**
	 * @var bool|stdClass
	 */
	public $lastRow = false;

	public $counter, $historyPage, $buttons, $conds;

	protected $oldIdChecked;

	protected $preventClickjacking = false;
	/**
	 * @var array
	 */
	protected $parentLens;

	/** @var bool Whether to show the tag editing UI */
	protected $showTagEditUI;

	/** @var string */
	private $tagFilter;

	/**
	 * @param HistoryAction $historyPage
	 * @param string $year
	 * @param string $month
	 * @param string $tagFilter
	 * @param array $conds
	 */
	function __construct( $historyPage, $year = '', $month = '', $tagFilter = '', $conds = [] ) {
		parent::__construct( $historyPage->getContext() );
		$this->historyPage = $historyPage;
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month );
		$this->conds = $conds;
		$this->showTagEditUI = ChangeTags::showTagEditingUI( $this->getUser() );
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
		$revQuery = Revision::getQueryInfo( [ 'user' ] );
		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => $revQuery['fields'],
			'conds' => array_merge(
				[ 'rev_page' => $this->getWikiPage()->getId() ],
				$this->conds ),
			'options' => [ 'USE INDEX' => [ 'revision' => 'page_timestamp' ] ],
			'join_conds' => $revQuery['joins'],
		];
		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		// Avoid PHP 7.1 warning of passing $this by reference
		$historyPager = $this;
		Hooks::run( 'PageHistoryPager::getQueryInfo', [ &$historyPager, &$queryInfo ] );

		return $queryInfo;
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	/**
	 * @param stdClass $row
	 * @return string
	 */
	function formatRow( $row ) {
		if ( $this->lastRow ) {
			$latest = ( $this->counter == 1 && $this->mIsFirst );
			$firstInList = $this->counter == 1;
			$this->counter++;

			$notifTimestamp = $this->getConfig()->get( 'ShowUpdatedMarker' )
				? $this->getTitle()->getNotificationTimestamp( $this->getUser() )
				: false;

			$s = $this->historyLine(
				$this->lastRow, $row, $notifTimestamp, $latest, $firstInList );
		} else {
			$s = '';
		}
		$this->lastRow = $row;

		return $s;
	}

	function doBatchLookups() {
		if ( !Hooks::run( 'PageHistoryPager::doBatchLookups', [ $this, $this->mResult ] ) ) {
			return;
		}

		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		$revIds = [];
		foreach ( $this->mResult as $row ) {
			if ( $row->rev_parent_id ) {
				$revIds[] = $row->rev_parent_id;
			}
			if ( !is_null( $row->user_name ) ) {
				$batch->add( NS_USER, $row->user_name );
				$batch->add( NS_USER_TALK, $row->user_name );
			} else { # for anons or usernames of imported revisions
				$batch->add( NS_USER, $row->rev_user_text );
				$batch->add( NS_USER_TALK, $row->rev_user_text );
			}
		}
		$this->parentLens = Revision::getParentLengths( $this->mDb, $revIds );
		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * Creates begin of history list with a submit button
	 *
	 * @return string HTML output
	 */
	function getStartBody() {
		$this->lastRow = false;
		$this->counter = 1;
		$this->oldIdChecked = 0;

		$this->getOutput()->wrapWikiMsg( "<div class='mw-history-legend'>\n$1\n</div>", 'histlegend' );
		$s = Html::openElement( 'form', [ 'action' => wfScript(),
			'id' => 'mw-history-compare' ] ) . "\n";
		$s .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n";
		$s .= Html::hidden( 'action', 'historysubmit' ) . "\n";
		$s .= Html::hidden( 'type', 'revision' ) . "\n";

		// Button container stored in $this->buttons for re-use in getEndBody()
		$this->buttons = '<div>';
		$className = 'historysubmit mw-history-compareselectedversions-button';
		$attrs = [ 'class' => $className ]
			+ Linker::tooltipAndAccesskeyAttribs( 'compareselectedversions' );
		$this->buttons .= $this->submitButton( $this->msg( 'compareselectedversions' )->text(),
			$attrs
		) . "\n";

		$user = $this->getUser();
		$actionButtons = '';
		if ( $user->isAllowed( 'deleterevision' ) ) {
			$actionButtons .= $this->getRevisionButton( 'revisiondelete', 'showhideselectedversions' );
		}
		if ( $this->showTagEditUI ) {
			$actionButtons .= $this->getRevisionButton( 'editchangetags', 'history-edit-tags' );
		}
		if ( $actionButtons ) {
			$this->buttons .= Xml::tags( 'div', [ 'class' =>
				'mw-history-revisionactions' ], $actionButtons );
		}

		if ( $user->isAllowed( 'deleterevision' ) || $this->showTagEditUI ) {
			$this->buttons .= ( new ListToggle( $this->getOutput() ) )->getHTML();
		}

		$this->buttons .= '</div>';

		$s .= $this->buttons;
		$s .= '<ul id="pagehistory">' . "\n";

		return $s;
	}

	private function getRevisionButton( $name, $msg ) {
		$this->preventClickjacking();
		# Note bug #20966, <button> is non-standard in IE<8
		$element = Html::element(
			'button',
			[
				'type' => 'submit',
				'name' => $name,
				'value' => '1',
				'class' => "historysubmit mw-history-$name-button",
			],
			$this->msg( $msg )->text()
		) . "\n";
		return $element;
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

			$notifTimestamp = $this->getConfig()->get( 'ShowUpdatedMarker' )
				? $this->getTitle()->getNotificationTimestamp( $this->getUser() )
				: false;

			$s = $this->historyLine(
				$this->lastRow, $next, $notifTimestamp, $latest, $firstInList );
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
	 * @param string $message Text of the submit button, will be escaped
	 * @param array $attributes
	 * @return string HTML output for the submit button
	 */
	function submitButton( $message, $attributes = [] ) {
		# Disable submit button if history has 1 revision only
		if ( $this->getNumRows() > 1 ) {
			return Html::submitButton( $message, $attributes );
		} else {
			return '';
		}
	}

	/**
	 * Returns a row from the history printout.
	 *
	 * @todo document some more, and maybe clean up the code (some params redundant?)
	 *
	 * @param stdClass $row The database row corresponding to the previous line.
	 * @param mixed $next The database row corresponding to the next line
	 *   (chronologically previous)
	 * @param bool|string $notificationtimestamp
	 * @param bool $latest Whether this row corresponds to the page's latest revision.
	 * @param bool $firstInList Whether this row corresponds to the first
	 *   displayed on this history page.
	 * @return string HTML output for the row
	 */
	function historyLine( $row, $next, $notificationtimestamp = false,
		$latest = false, $firstInList = false ) {
		$rev = new Revision( $row, 0, $this->getTitle() );

		if ( is_object( $next ) ) {
			$prevRev = new Revision( $next, 0, $this->getTitle() );
		} else {
			$prevRev = null;
		}

		$curlink = $this->curLink( $rev, $latest );
		$lastlink = $this->lastLink( $rev, $next );
		$curLastlinks = $curlink . $this->historyPage->message['pipe-separator'] . $lastlink;
		$histLinks = Html::rawElement(
			'span',
			[ 'class' => 'mw-history-histlinks' ],
			$this->msg( 'parentheses' )->rawParams( $curLastlinks )->escaped()
		);

		$diffButtons = $this->diffButtons( $rev, $firstInList );
		$s = $histLinks . $diffButtons;

		$link = $this->revLink( $rev );
		$classes = [];

		$del = '';
		$user = $this->getUser();
		$canRevDelete = $user->isAllowed( 'deleterevision' );
		// Show checkboxes for each revision, to allow for revision deletion and
		// change tags
		if ( $canRevDelete || $this->showTagEditUI ) {
			$this->preventClickjacking();
			// If revision was hidden from sysops and we don't need the checkbox
			// for anything else, disable it
			if ( !$this->showTagEditUI && !$rev->userCan( Revision::DELETED_RESTRICTED, $user ) ) {
				$del = Xml::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
			// Otherwise, enable the checkbox...
			} else {
				$del = Xml::check( 'showhiderevisions', false,
					[ 'name' => 'ids[' . $rev->getId() . ']' ] );
			}
		// User can only view deleted revisions...
		} elseif ( $rev->getVisibility() && $user->isAllowed( 'deletedhistory' ) ) {
			// If revision was hidden from sysops, disable the link
			if ( !$rev->userCan( Revision::DELETED_RESTRICTED, $user ) ) {
				$del = Linker::revDeleteLinkDisabled( false );
			// Otherwise, show the link...
			} else {
				$query = [ 'type' => 'revision',
					'target' => $this->getTitle()->getPrefixedDBkey(), 'ids' => $rev->getId() ];
				$del .= Linker::revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), false );
			}
		}
		if ( $del ) {
			$s .= " $del ";
		}

		$lang = $this->getLanguage();
		$dirmark = $lang->getDirMark();

		$s .= " $link";
		$s .= $dirmark;
		$s .= " <span class='history-user'>" .
			Linker::revUserTools( $rev, true ) . "</span>";
		$s .= $dirmark;

		if ( $rev->isMinor() ) {
			$s .= ' ' . ChangesList::flag( 'minor', $this->getContext() );
		}

		# Sometimes rev_len isn't populated
		if ( $rev->getSize() !== null ) {
			# Size is always public data
			$prevSize = isset( $this->parentLens[$row->rev_parent_id] )
				? $this->parentLens[$row->rev_parent_id]
				: 0;
			$sDiff = ChangesList::showCharacterDifference( $prevSize, $rev->getSize() );
			$fSize = Linker::formatRevisionSize( $rev->getSize() );
			$s .= ' <span class="mw-changeslist-separator">. .</span> ' . "$fSize $sDiff";
		}

		# Text following the character difference is added just before running hooks
		$s2 = Linker::revComment( $rev, false, true );

		if ( $notificationtimestamp && ( $row->rev_timestamp >= $notificationtimestamp ) ) {
			$s2 .= ' <span class="updatedmarker">' . $this->msg( 'updatedmarker' )->escaped() . '</span>';
			$classes[] = 'mw-history-line-updated';
		}

		$tools = [];

		# Rollback and undo links
		if ( $prevRev && $this->getTitle()->quickUserCan( 'edit', $user ) ) {
			if ( $latest && $this->getTitle()->quickUserCan( 'rollback', $user ) ) {
				// Get a rollback link without the brackets
				$rollbackLink = Linker::generateRollback(
					$rev,
					$this->getContext(),
					[ 'verify', 'noBrackets' ]
				);
				if ( $rollbackLink ) {
					$this->preventClickjacking();
					$tools[] = $rollbackLink;
				}
			}

			if ( !$rev->isDeleted( Revision::DELETED_TEXT )
				&& !$prevRev->isDeleted( Revision::DELETED_TEXT )
			) {
				# Create undo tooltip for the first (=latest) line only
				$undoTooltip = $latest
					? [ 'title' => $this->msg( 'tooltip-undo' )->text() ]
					: [];
				$undolink = MediaWikiServices::getInstance()->getLinkRenderer()->makeKnownLink(
					$this->getTitle(),
					$this->msg( 'editundo' )->text(),
					$undoTooltip,
					[
						'action' => 'edit',
						'undoafter' => $prevRev->getId(),
						'undo' => $rev->getId()
					]
				);
				$tools[] = "<span class=\"mw-history-undo\">{$undolink}</span>";
			}
		}
		// Allow extension to add their own links here
		Hooks::run( 'HistoryRevisionTools', [ $rev, &$tools, $prevRev, $user ] );

		if ( $tools ) {
			$s2 .= ' ' . $this->msg( 'parentheses' )->rawParams( $lang->pipeList( $tools ) )->escaped();
		}

		# Tags
		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'history',
			$this->getContext()
		);
		$classes = array_merge( $classes, $newClasses );
		if ( $tagSummary !== '' ) {
			$s2 .= " $tagSummary";
		}

		# Include separator between character difference and following text
		if ( $s2 !== '' ) {
			$s .= ' <span class="mw-changeslist-separator">. .</span> ' . $s2;
		}

		$attribs = [ 'data-mw-revid' => $rev->getId() ];

		Hooks::run( 'PageHistoryLineEnding', [ $this, &$row, &$s, &$classes, &$attribs ] );
		$attribs = wfArrayFilterByKey( $attribs, [ Sanitizer::class, 'isReservedDataAttribute' ] );

		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		return Xml::tags( 'li', $attribs, $s ) . "\n";
	}

	/**
	 * Create a link to view this revision of the page
	 *
	 * @param Revision $rev
	 * @return string
	 */
	function revLink( $rev ) {
		$date = $this->getLanguage()->userTimeAndDate( $rev->getTimestamp(), $this->getUser() );
		if ( $rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
			$link = MediaWikiServices::getInstance()->getLinkRenderer()->makeKnownLink(
				$this->getTitle(),
				$date,
				[ 'class' => 'mw-changeslist-date' ],
				[ 'oldid' => $rev->getId() ]
			);
		} else {
			$link = htmlspecialchars( $date );
		}
		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = "<span class=\"history-deleted\">$link</span>";
		}

		return $link;
	}

	/**
	 * Create a diff-to-current link for this revision for this page
	 *
	 * @param Revision $rev
	 * @param bool $latest This is the latest revision of the page?
	 * @return string
	 */
	function curLink( $rev, $latest ) {
		$cur = $this->historyPage->message['cur'];
		if ( $latest || !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
			return $cur;
		} else {
			return MediaWikiServices::getInstance()->getLinkRenderer()->makeKnownLink(
				$this->getTitle(),
				$cur,
				[],
				[
					'diff' => $this->getWikiPage()->getLatest(),
					'oldid' => $rev->getId()
				]
			);
		}
	}

	/**
	 * Create a diff-to-previous link for this revision for this page.
	 *
	 * @param Revision $prevRev The revision being displayed
	 * @param stdClass|string|null $next The next revision in list (that is
	 *        the previous one in chronological order).
	 *        May either be a row, "unknown" or null.
	 * @return string
	 */
	function lastLink( $prevRev, $next ) {
		$last = $this->historyPage->message['last'];

		if ( $next === null ) {
			# Probably no next row
			return $last;
		}

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( $next === 'unknown' ) {
			# Next row probably exists but is unknown, use an oldid=prev link
			return $linkRenderer->makeKnownLink(
				$this->getTitle(),
				$last,
				[],
				[
					'diff' => $prevRev->getId(),
					'oldid' => 'prev'
				]
			);
		}

		$nextRev = new Revision( $next );

		if ( !$prevRev->userCan( Revision::DELETED_TEXT, $this->getUser() )
			|| !$nextRev->userCan( Revision::DELETED_TEXT, $this->getUser() )
		) {
			return $last;
		}

		return $linkRenderer->makeKnownLink(
			$this->getTitle(),
			$last,
			[],
			[
				'diff' => $prevRev->getId(),
				'oldid' => $next->rev_id
			]
		);
	}

	/**
	 * Create radio buttons for page history
	 *
	 * @param Revision $rev
	 * @param bool $firstInList Is this version the first one?
	 *
	 * @return string HTML output for the radio buttons
	 */
	function diffButtons( $rev, $firstInList ) {
		if ( $this->getNumRows() > 1 ) {
			$id = $rev->getId();
			$radio = [ 'type' => 'radio', 'value' => $id ];
			/** @todo Move title texts to javascript */
			if ( $firstInList ) {
				$first = Xml::element( 'input',
					array_merge( $radio, [
						'style' => 'visibility:hidden',
						'name' => 'oldid',
						'id' => 'mw-oldid-null' ] )
				);
				$checkmark = [ 'checked' => 'checked' ];
			} else {
				# Check visibility of old revisions
				if ( !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
					$radio['disabled'] = 'disabled';
					$checkmark = []; // We will check the next possible one
				} elseif ( !$this->oldIdChecked ) {
					$checkmark = [ 'checked' => 'checked' ];
					$this->oldIdChecked = $id;
				} else {
					$checkmark = [];
				}
				$first = Xml::element( 'input',
					array_merge( $radio, $checkmark, [
						'name' => 'oldid',
						'id' => "mw-oldid-$id" ] ) );
				$checkmark = [];
			}
			$second = Xml::element( 'input',
				array_merge( $radio, $checkmark, [
					'name' => 'diff',
					'id' => "mw-diff-$id" ] ) );

			return $first . $second;
		} else {
			return '';
		}
	}

	/**
	 * This is called if a write operation is possible from the generated HTML
	 * @param bool $enable
	 */
	function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * Get the "prevent clickjacking" flag
	 * @return bool
	 */
	function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}
