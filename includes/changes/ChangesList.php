<?php
/**
 * Base class for all changes lists.
 *
 * The class is used for formatting recent changes, related changes and watchlist.
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
 */

use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use OOUI\IconWidget;
use Wikimedia\Rdbms\IResultWrapper;

class ChangesList extends ContextSource {
	use ProtectedHookAccessorTrait;

	public const CSS_CLASS_PREFIX = 'mw-changeslist-';

	protected $watchlist = false;
	protected $lastdate;
	protected $message;
	protected $rc_cache;
	protected $rcCacheIndex;
	protected $rclistOpen;
	protected $rcMoveIndex;

	/** @var callable */
	protected $changeLinePrefixer;

	/** @var MapCacheLRU */
	protected $watchMsgCache;

	/**
	 * @var LinkRenderer
	 */
	protected $linkRenderer;

	/**
	 * @var RowCommentFormatter
	 */
	protected $commentFormatter;

	/**
	 * @var string[] Comments indexed by rc_id
	 */
	protected $formattedComments;

	/**
	 * @var ChangesListFilterGroup[]
	 */
	protected $filterGroups;

	/**
	 * @param IContextSource $context
	 * @param ChangesListFilterGroup[] $filterGroups Array of ChangesListFilterGroup objects (currently optional)
	 */
	public function __construct( $context, array $filterGroups = [] ) {
		$this->setContext( $context );
		$this->preCacheMessages();
		$this->watchMsgCache = new MapCacheLRU( 50 );
		$this->filterGroups = $filterGroups;

		$services = MediaWikiServices::getInstance();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->commentFormatter = $services->getRowCommentFormatter();
	}

	/**
	 * Fetch an appropriate changes list class for the specified context
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param IContextSource $context
	 * @param array $groups Array of ChangesListFilterGroup objects (currently optional)
	 * @return ChangesList
	 */
	public static function newFromContext( IContextSource $context, array $groups = [] ) {
		$user = $context->getUser();
		$sk = $context->getSkin();
		$list = null;
		if ( Hooks::runner()->onFetchChangesList( $user, $sk, $list, $groups ) ) {
			$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
			$new = $context->getRequest()->getBool(
				'enhanced',
				$userOptionsLookup->getBoolOption( $user, 'usenewrc' )
			);

			return $new ?
				new EnhancedChangesList( $context, $groups ) :
				new OldChangesList( $context, $groups );
		} else {
			return $list;
		}
	}

	/**
	 * Format a line
	 *
	 * @since 1.27
	 *
	 * @param RecentChange &$rc Passed by reference
	 * @param bool $watched (default false)
	 * @param int|null $linenumber (default null)
	 *
	 * @return string|bool
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {
		throw new RuntimeException( 'recentChangesLine should be implemented' );
	}

	/**
	 * Get the container for highlights that are used in the new StructuredFilters
	 * system
	 *
	 * @return string HTML structure of the highlight container div
	 */
	protected function getHighlightsContainerDiv() {
		$highlightColorDivs = '';
		foreach ( [ 'none', 'c1', 'c2', 'c3', 'c4', 'c5' ] as $color ) {
			$highlightColorDivs .= Html::rawElement(
				'div',
				[
					'class' => 'mw-rcfilters-ui-highlights-color-' . $color,
					'data-color' => $color
				]
			);
		}

		return Html::rawElement(
			'div',
			[ 'class' => 'mw-rcfilters-ui-highlights' ],
			$highlightColorDivs
		);
	}

	/**
	 * Sets the list to use a "<li class='watchlist-(namespace)-(page)'>" tag
	 * @param bool $value
	 */
	public function setWatchlistDivs( $value = true ) {
		$this->watchlist = $value;
	}

	/**
	 * @return bool True when setWatchlistDivs has been called
	 * @since 1.23
	 */
	public function isWatchlist() {
		return (bool)$this->watchlist;
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		if ( !isset( $this->message ) ) {
			$this->message = [];
			foreach ( [
				'cur', 'diff', 'hist', 'enhancedrc-history', 'last', 'blocklink', 'history',
				'semicolon-separator', 'pipe-separator' ] as $msg
			) {
				$this->message[$msg] = $this->msg( $msg )->escaped();
			}
		}
	}

	/**
	 * Returns the appropriate flags for new page, minor change and patrolling
	 * @param array $flags Associative array of 'flag' => Bool
	 * @param string $nothing To use for empty space
	 * @return string
	 */
	public function recentChangesFlags( $flags, $nothing = "\u{00A0}" ) {
		$f = '';
		foreach (
			array_keys( $this->getConfig()->get( MainConfigNames::RecentChangesFlags ) ) as $flag
		) {
			$f .= isset( $flags[$flag] ) && $flags[$flag]
				? self::flag( $flag, $this->getContext() )
				: $nothing;
		}

		return $f;
	}

	/**
	 * Get an array of default HTML class attributes for the change.
	 *
	 * @param RecentChange|RCCacheEntry $rc
	 * @param string|bool $watched Optionally timestamp for adding watched class
	 *
	 * @return string[] List of CSS class names
	 */
	protected function getHTMLClasses( $rc, $watched ) {
		$classes = [ self::CSS_CLASS_PREFIX . 'line' ];
		$logType = $rc->mAttribs['rc_log_type'];

		if ( $logType ) {
			$classes[] = self::CSS_CLASS_PREFIX . 'log';
			$classes[] = Sanitizer::escapeClass( self::CSS_CLASS_PREFIX . 'log-' . $logType );
		} else {
			$classes[] = self::CSS_CLASS_PREFIX . 'edit';
			$classes[] = Sanitizer::escapeClass( self::CSS_CLASS_PREFIX . 'ns' .
				$rc->mAttribs['rc_namespace'] . '-' . $rc->mAttribs['rc_title'] );
		}

		// Indicate watched status on the line to allow for more
		// comprehensive styling.
		$classes[] = $watched && $rc->mAttribs['rc_timestamp'] >= $watched
			? self::CSS_CLASS_PREFIX . 'line-watched'
			: self::CSS_CLASS_PREFIX . 'line-not-watched';

		$classes = array_merge( $classes, $this->getHTMLClassesForFilters( $rc ) );

		return $classes;
	}

	/**
	 * Get an array of CSS classes attributed to filters for this row. Used for highlighting
	 * in the front-end.
	 *
	 * @param RecentChange $rc
	 * @return string[] Array of CSS classes
	 */
	protected function getHTMLClassesForFilters( $rc ) {
		$classes = [];

		$classes[] = Sanitizer::escapeClass( self::CSS_CLASS_PREFIX . 'ns-' .
			$rc->mAttribs['rc_namespace'] );

		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		$classes[] = Sanitizer::escapeClass(
			self::CSS_CLASS_PREFIX .
			'ns-' .
			( $nsInfo->isTalk( $rc->mAttribs['rc_namespace'] ) ? 'talk' : 'subject' )
		);

		foreach ( $this->filterGroups as $filterGroup ) {
			foreach ( $filterGroup->getFilters() as $filter ) {
				$filter->applyCssClassIfNeeded( $this, $rc, $classes );
			}
		}

		return $classes;
	}

	/**
	 * Make an "<abbr>" element for a given change flag. The flag indicating a new page, minor edit,
	 * bot edit, or unpatrolled edit. In English it typically contains "N", "m", "b", or "!".
	 *
	 * Styling for these flags is provided through mediawiki.interface.helpers.styles.
	 *
	 * @param string $flag One key of $wgRecentChangesFlags
	 * @param IContextSource|null $context
	 * @return string HTML
	 */
	public static function flag( $flag, IContextSource $context = null ) {
		static $map = [ 'minoredit' => 'minor', 'botedit' => 'bot' ];
		static $flagInfos = null;

		if ( $flagInfos === null ) {
			$recentChangesFlags = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::RecentChangesFlags );
			$flagInfos = [];
			foreach ( $recentChangesFlags as $key => $value ) {
				$flagInfos[$key]['letter'] = $value['letter'];
				$flagInfos[$key]['title'] = $value['title'];
				// Allow customized class name, fall back to flag name
				$flagInfos[$key]['class'] = $value['class'] ?? $key;
			}
		}

		$context = $context ?: RequestContext::getMain();

		// Inconsistent naming, kept for b/c
		if ( isset( $map[$flag] ) ) {
			$flag = $map[$flag];
		}

		$info = $flagInfos[$flag];
		return Html::element( 'abbr', [
			'class' => $info['class'],
			'title' => wfMessage( $info['title'] )->setContext( $context )->text(),
		], wfMessage( $info['letter'] )->setContext( $context )->text() );
	}

	/**
	 * Returns text for the start of the tabular part of RC
	 * @return string
	 */
	public function beginRecentChangesList() {
		$this->rc_cache = [];
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$this->getOutput()->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special.changeslist'
		] );

		return '<div class="mw-changeslist">';
	}

	/**
	 * @param IResultWrapper|stdClass[] $rows
	 */
	public function initChangesListRows( $rows ) {
		$this->getHookRunner()->onChangesListInitRows( $this, $rows );
		$this->formattedComments = $this->commentFormatter->createBatch()
			->comments(
				$this->commentFormatter->rows( $rows )
					->commentKey( 'rc_comment' )
					->namespaceField( 'rc_namespace' )
					->titleField( 'rc_title' )
					->indexField( 'rc_id' )
			)
			->useBlock()
			->execute();
	}

	/**
	 * Show formatted char difference
	 *
	 * Needs the css module 'mediawiki.special.changeslist' to style output
	 *
	 * @param int $old Number of bytes
	 * @param int $new Number of bytes
	 * @param IContextSource|null $context
	 * @return string
	 */
	public static function showCharacterDifference( $old, $new, IContextSource $context = null ) {
		if ( !$context ) {
			$context = RequestContext::getMain();
		}

		$new = (int)$new;
		$old = (int)$old;
		$szdiff = $new - $old;

		$lang = $context->getLanguage();
		$config = $context->getConfig();
		$code = $lang->getCode();
		static $fastCharDiff = [];
		if ( !isset( $fastCharDiff[$code] ) ) {
			$fastCharDiff[$code] = $config->get( MainConfigNames::MiserMode )
				|| $context->msg( 'rc-change-size' )->plain() === '$1';
		}

		$formattedSize = $lang->formatNum( $szdiff );

		if ( !$fastCharDiff[$code] ) {
			$formattedSize = $context->msg( 'rc-change-size', $formattedSize )->text();
		}

		if ( abs( $szdiff ) > abs( $config->get( MainConfigNames::RCChangedSizeThreshold ) ) ) {
			$tag = 'strong';
		} else {
			$tag = 'span';
		}

		if ( $szdiff === 0 ) {
			$formattedSizeClass = 'mw-plusminus-null';
		} elseif ( $szdiff > 0 ) {
			$formattedSize = '+' . $formattedSize;
			$formattedSizeClass = 'mw-plusminus-pos';
		} else {
			$formattedSizeClass = 'mw-plusminus-neg';
		}
		$formattedSizeClass .= ' mw-diff-bytes';

		$formattedTotalSize = $context->msg( 'rc-change-size-new' )->numParams( $new )->text();

		return Html::element( $tag,
			[ 'dir' => 'ltr', 'class' => $formattedSizeClass, 'title' => $formattedTotalSize ],
			$formattedSize ) . $lang->getDirMark();
	}

	/**
	 * Format the character difference of one or several changes.
	 *
	 * @param RecentChange $old
	 * @param RecentChange|null $new Last change to use, if not provided, $old will be used
	 * @return string HTML fragment
	 */
	public function formatCharacterDifference( RecentChange $old, RecentChange $new = null ) {
		$oldlen = $old->mAttribs['rc_old_len'];

		if ( $new ) {
			$newlen = $new->mAttribs['rc_new_len'];
		} else {
			$newlen = $old->mAttribs['rc_new_len'];
		}

		if ( $oldlen === null || $newlen === null ) {
			return '';
		}

		return self::showCharacterDifference( $oldlen, $newlen, $this->getContext() );
	}

	/**
	 * Returns text for the end of RC
	 * @return string
	 */
	public function endRecentChangesList() {
		$out = $this->rclistOpen ? "</ul>\n" : '';
		$out .= '</div>';

		return $out;
	}

	/**
	 * Render the date and time of a revision in the current user language
	 * based on whether the user is able to view this information or not.
	 * @param RevisionRecord $rev
	 * @param Authority $performer
	 * @param Language $lang
	 * @param Title|null $title (optional) where Title does not match
	 *   the Title associated with the RevisionRecord
	 * @internal For usage by Pager classes only (e.g. HistoryPager and ContribsPager).
	 * @return string HTML
	 */
	public static function revDateLink(
		RevisionRecord $rev,
		Authority $performer,
		Language $lang,
		$title = null
	) {
		$ts = $rev->getTimestamp();
		$time = $lang->userTime( $ts, $performer->getUser() );
		$date = $lang->userTimeAndDate( $ts, $performer->getUser() );
		if ( $rev->userCan( RevisionRecord::DELETED_TEXT, $performer ) ) {
			$link = MediaWikiServices::getInstance()->getLinkRenderer()->makeKnownLink(
				$title ?? $rev->getPageAsLinkTarget(),
				$date,
				[ 'class' => 'mw-changeslist-date' ],
				[ 'oldid' => $rev->getId() ]
			);
		} else {
			$link = htmlspecialchars( $date );
		}
		if ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$deletedClass = Linker::getRevisionDeletedClass( $rev );
			$link = "<span class=\"$deletedClass mw-changeslist-date\">$link</span>";
		}
		return Html::element( 'span', [
			'class' => 'mw-changeslist-time'
		], $time ) . $link;
	}

	/**
	 * @param string &$s HTML to update
	 * @param mixed $rc_timestamp
	 */
	public function insertDateHeader( &$s, $rc_timestamp ) {
		# Make date header if necessary
		$date = $this->getLanguage()->userDate( $rc_timestamp, $this->getUser() );
		if ( $date != $this->lastdate ) {
			if ( $this->lastdate != '' ) {
				$s .= "</ul>\n";
			}
			$s .= Xml::element( 'h4', null, $date ) . "\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}
	}

	/**
	 * @param string &$s HTML to update
	 * @param Title $title
	 * @param string $logtype
	 * @param bool $useParentheses (optional) Wrap log entry in parentheses where needed
	 */
	public function insertLog( &$s, $title, $logtype, $useParentheses = true ) {
		$page = new LogPage( $logtype );
		$logname = $page->getName()->setContext( $this->getContext() )->text();
		$link = $this->linkRenderer->makeKnownLink( $title, $logname, [
			'class' => $useParentheses ? '' : 'mw-changeslist-links'
		] );
		if ( $useParentheses ) {
			$s .= $this->msg( 'parentheses' )->rawParams(
				$link
			)->escaped();
		} else {
			$s .= $link;
		}
	}

	/**
	 * @param string &$s HTML to update
	 * @param RecentChange &$rc
	 * @param bool|null $unpatrolled Unused variable, since 1.27.
	 */
	public function insertDiffHist( &$s, &$rc, $unpatrolled = null ) {
		# Diff link
		if (
			$rc->mAttribs['rc_type'] == RC_NEW ||
			$rc->mAttribs['rc_type'] == RC_LOG ||
			$rc->mAttribs['rc_type'] == RC_CATEGORIZE
		) {
			$diffLink = $this->message['diff'];
		} elseif ( !self::userCan( $rc, RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			$diffLink = $this->message['diff'];
		} else {
			$query = [
				'curid' => $rc->mAttribs['rc_cur_id'],
				'diff' => $rc->mAttribs['rc_this_oldid'],
				'oldid' => $rc->mAttribs['rc_last_oldid']
			];

			$diffLink = $this->linkRenderer->makeKnownLink(
				$rc->getTitle(),
				new HtmlArmor( $this->message['diff'] ),
				[ 'class' => 'mw-changeslist-diff' ],
				$query
			);
		}
		if ( $rc->mAttribs['rc_type'] == RC_CATEGORIZE ) {
			$histLink = $this->message['hist'];
		} else {
			$histLink = $this->linkRenderer->makeKnownLink(
				$rc->getTitle(),
				new HtmlArmor( $this->message['hist'] ),
				[ 'class' => 'mw-changeslist-history' ],
				[
					'curid' => $rc->mAttribs['rc_cur_id'],
					'action' => 'history'
				]
			);
		}

		$s .= Html::rawElement( 'div', [ 'class' => 'mw-changeslist-links' ],
				Html::rawElement( 'span', [], $diffLink ) .
				Html::rawElement( 'span', [], $histLink )
			) .
			' <span class="mw-changeslist-separator"></span> ';
	}

	/**
	 * Get the HTML link to the changed page, possibly with a prefix from hook handlers, and a
	 * suffix for temporarily watched items.
	 *
	 * @param RecentChange &$rc
	 * @param bool $unpatrolled
	 * @param bool $watched
	 * @return string HTML
	 * @since 1.26
	 */
	public function getArticleLink( &$rc, $unpatrolled, $watched ) {
		$params = [];
		if ( $rc->getTitle()->isRedirect() ) {
			$params = [ 'redirect' => 'no' ];
		}

		$articlelink = $this->linkRenderer->makeLink(
			$rc->getTitle(),
			null,
			[ 'class' => 'mw-changeslist-title' ],
			$params
		);
		if ( static::isDeleted( $rc, RevisionRecord::DELETED_TEXT ) ) {
			$class = 'history-deleted';
			if ( static::isDeleted( $rc, RevisionRecord::DELETED_RESTRICTED ) ) {
				$class .= ' mw-history-suppressed';
			}
			$articlelink = '<span class="' . $class . '">' . $articlelink . '</span>';
		}
		# To allow for boldening pages watched by this user
		$articlelink = "<span class=\"mw-title\">{$articlelink}</span>";
		# RTL/LTR marker
		$articlelink .= $this->getLanguage()->getDirMark();

		# TODO: Deprecate the $s argument, it seems happily unused.
		$s = '';
		$this->getHookRunner()->onChangesListInsertArticleLink( $this, $articlelink,
			$s, $rc, $unpatrolled, $watched );

		// Watchlist expiry icon.
		$watchlistExpiry = '';
		if ( isset( $rc->watchlistExpiry ) && $rc->watchlistExpiry ) {
			$watchlistExpiry = $this->getWatchlistExpiry( $rc );
		}

		return "{$s} {$articlelink}{$watchlistExpiry}";
	}

	/**
	 * Get HTML to display the clock icon for watched items that have a watchlist expiry time.
	 * @since 1.35
	 * @param RecentChange $recentChange
	 * @return string The HTML to display an indication of the expiry time.
	 */
	public function getWatchlistExpiry( RecentChange $recentChange ): string {
		$item = WatchedItem::newFromRecentChange( $recentChange, $this->getUser() );
		// Guard against expired items, even though they shouldn't come here.
		if ( $item->isExpired() ) {
			return '';
		}
		$daysLeftText = $item->getExpiryInDaysText( $this->getContext() );
		// Matching widget is also created in ChangesListSpecialPage, for the legend.
		$widget = new IconWidget( [
			'icon' => 'clock',
			'title' => $daysLeftText,
			'classes' => [ 'mw-changesList-watchlistExpiry' ],
		] );
		$widget->setAttributes( [
			// Add labels for assistive technologies.
			'role' => 'img',
			'aria-label' => $this->msg( 'watchlist-expires-in-aria-label' )->text(),
			// Days-left is used in resources/src/mediawiki.special.changeslist.watchlistexpiry/watchlistexpiry.js
			'data-days-left' => $item->getExpiryInDays(),
		] );
		// Add spaces around the widget (the page title is to one side,
		// and a semicolon or opening-parenthesis to the other).
		return " $widget ";
	}

	/**
	 * Get the timestamp from $rc formatted with current user's settings
	 * and a separator
	 *
	 * @param RecentChange $rc
	 * @deprecated use revDateLink instead.
	 * @return string HTML fragment
	 */
	public function getTimestamp( $rc ) {
		// This uses the semi-colon separator unless there's a watchlist expiry date for the entry,
		// because in that case the timestamp is preceded by a clock icon.
		// A space is important after `.mw-changeslist-separator--semicolon` to make sure
		// that whatever comes before it is distinguishable.
		// (Otherwise your have the text of titles pushing up against the timestamp)
		// A specific element is used for this purpose rather than styling `.mw-changeslist-date`
		// as the `.mw-changeslist-date` class is used in a variety
		// of other places with a different position and the information proceeding getTimestamp can vary.
		// The `.mw-changeslist-time` class allows us to distinguish from `.mw-changeslist-date` elements that
		// contain the full date (month, year) and adds consistency with Special:Contributions
		// and other pages.
		$separatorClass = $rc->watchlistExpiry ? 'mw-changeslist-separator' : 'mw-changeslist-separator--semicolon';
		return Html::element( 'span', [ 'class' => $separatorClass ] ) . ' ' .
			'<span class="mw-changeslist-date mw-changeslist-time">' .
			htmlspecialchars( $this->getLanguage()->userTime(
				$rc->mAttribs['rc_timestamp'],
				$this->getUser()
			) ) . '</span> <span class="mw-changeslist-separator"></span> ';
	}

	/**
	 * Insert time timestamp string from $rc into $s
	 *
	 * @param string &$s HTML to update
	 * @param RecentChange $rc
	 */
	public function insertTimestamp( &$s, $rc ) {
		$s .= $this->getTimestamp( $rc );
	}

	/**
	 * Insert links to user page, user talk page and eventually a blocking link
	 *
	 * @param string &$s HTML to update
	 * @param RecentChange &$rc
	 */
	public function insertUserRelatedLinks( &$s, &$rc ) {
		if ( static::isDeleted( $rc, RevisionRecord::DELETED_USER ) ) {
			$deletedClass = 'history-deleted';
			if ( static::isDeleted( $rc, RevisionRecord::DELETED_RESTRICTED ) ) {
				$deletedClass .= ' mw-history-suppressed';
			}
			$s .= ' <span class="' . $deletedClass . '">' .
				$this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			$s .= $this->getLanguage()->getDirMark() . Linker::userLink( $rc->mAttribs['rc_user'],
				$rc->mAttribs['rc_user_text'] );
			$s .= Linker::userToolLinks(
				$rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'],
				false, 0, null,
				// The text content of tools is not wrapped with parentheses or "piped".
				// This will be handled in CSS (T205581).
				false
			);
		}
	}

	/**
	 * Insert a formatted action
	 *
	 * @param RecentChange $rc
	 * @return string
	 */
	public function insertLogEntry( $rc ) {
		$formatter = LogFormatter::newFromRow( $rc->mAttribs );
		$formatter->setContext( $this->getContext() );
		$formatter->setShowUserToolLinks( true );
		$mark = $this->getLanguage()->getDirMark();

		return Html::openElement( 'span', [ 'class' => 'mw-changeslist-log-entry' ] )
			. $formatter->getActionText()
			. " $mark"
			. $formatter->getComment()
			. $this->msg( 'word-separator' )->escaped()
			. $formatter->getActionLinks()
			. Html::closeElement( 'span' );
	}

	/**
	 * Insert a formatted comment
	 * @param RecentChange $rc
	 * @return string
	 */
	public function insertComment( $rc ) {
		if ( static::isDeleted( $rc, RevisionRecord::DELETED_COMMENT ) ) {
			$deletedClass = 'history-deleted';
			if ( static::isDeleted( $rc, RevisionRecord::DELETED_RESTRICTED ) ) {
				$deletedClass .= ' mw-history-suppressed';
			}
			return ' <span class="' . $deletedClass . ' comment">' .
				$this->msg( 'rev-deleted-comment' )->escaped() . '</span>';
		} elseif ( isset( $rc->mAttribs['rc_id'] )
			&& isset( $this->formattedComments[$rc->mAttribs['rc_id']] )
		) {
			return $this->formattedComments[$rc->mAttribs['rc_id']];
		} else {
			return $this->commentFormatter->formatBlock(
				$rc->mAttribs['rc_comment'],
				$rc->getTitle(),
				// Whether section links should refer to local page (using default false)
				false,
				// wikid to generate links for (using default null) */
				null,
				// whether parentheses should be rendered as part of the message
				false
			);
		}
	}

	/**
	 * Returns the string which indicates the number of watching users
	 * @param int $count Number of user watching a page
	 * @return string
	 */
	protected function numberofWatchingusers( $count ) {
		if ( $count <= 0 ) {
			return '';
		}

		return $this->watchMsgCache->getWithSetCallback(
			"watching-users-msg:$count",
			function () use ( $count ) {
				return $this->msg( 'number-of-watching-users-for-recent-changes' )
					->numParams( $count )->escaped();
			}
		);
	}

	/**
	 * Determine if said field of a revision is hidden
	 * @param RCCacheEntry|RecentChange $rc
	 * @param int $field One of DELETED_* bitfield constants
	 * @return bool
	 */
	public static function isDeleted( $rc, $field ) {
		return ( $rc->mAttribs['rc_deleted'] & $field ) == $field;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 * @param RCCacheEntry|RecentChange $rc
	 * @param int $field
	 * @param Authority|null $performer to check permissions against. If null, the global RequestContext's
	 * User is assumed instead.
	 * @return bool
	 */
	public static function userCan( $rc, $field, Authority $performer = null ) {
		if ( $performer === null ) {
			$performer = RequestContext::getMain()->getAuthority();
		}

		if ( $rc->mAttribs['rc_type'] == RC_LOG ) {
			return LogEventsList::userCanBitfield( $rc->mAttribs['rc_deleted'], $field, $performer );
		}

		return RevisionRecord::userCanBitfield( $rc->mAttribs['rc_deleted'], $field, $performer );
	}

	/**
	 * @param string $link
	 * @param bool $watched
	 * @return string
	 */
	protected function maybeWatchedLink( $link, $watched = false ) {
		if ( $watched ) {
			return '<strong class="mw-watched">' . $link . '</strong>';
		} else {
			return '<span class="mw-rc-unwatched">' . $link . '</span>';
		}
	}

	/**
	 * Insert a rollback link
	 *
	 * @param string &$s
	 * @param RecentChange &$rc
	 */
	public function insertRollback( &$s, &$rc ) {
		if ( $rc->mAttribs['rc_type'] == RC_EDIT
			&& $rc->mAttribs['rc_this_oldid']
			&& $rc->mAttribs['rc_cur_id']
			&& $rc->getAttribute( 'page_latest' ) == $rc->mAttribs['rc_this_oldid']
		) {
			$title = $rc->getTitle();
			/** Check for rollback permissions, disallow special pages, and only
			 * show a link on the top-most revision
			 */
			if ( $this->getAuthority()->probablyCan( 'rollback', $title ) ) {
				$revRecord = new MutableRevisionRecord( $title );
				$revRecord->setId( (int)$rc->mAttribs['rc_this_oldid'] );
				$revRecord->setVisibility( (int)$rc->mAttribs['rc_deleted'] );
				$user = new UserIdentityValue(
					(int)$rc->mAttribs['rc_user'],
					$rc->mAttribs['rc_user_text']
				);
				$revRecord->setUser( $user );

				$s .= ' ';
				$s .= Linker::generateRollback(
					$revRecord,
					$this->getContext(),
					[ 'noBrackets' ]
				);
			}
		}
	}

	/**
	 * @param RecentChange $rc
	 * @return string
	 * @since 1.26
	 */
	public function getRollback( RecentChange $rc ) {
		$s = '';
		$this->insertRollback( $s, $rc );
		return $s;
	}

	/**
	 * @param string &$s
	 * @param RecentChange &$rc
	 * @param string[] &$classes
	 */
	public function insertTags( &$s, &$rc, &$classes ) {
		if ( empty( $rc->mAttribs['ts_tags'] ) ) {
			return;
		}

		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
			$rc->mAttribs['ts_tags'],
			'changeslist',
			$this->getContext()
		);
		$classes = array_merge( $classes, $newClasses );
		$s .= ' ' . $tagSummary;
	}

	/**
	 * @param RecentChange $rc
	 * @param string[] &$classes
	 * @return string
	 * @since 1.26
	 */
	public function getTags( RecentChange $rc, array &$classes ) {
		$s = '';
		$this->insertTags( $s, $rc, $classes );
		return $s;
	}

	public function insertExtra( &$s, &$rc, &$classes ) {
		// Empty, used for subclasses to add anything special.
	}

	protected function showAsUnpatrolled( RecentChange $rc ) {
		return self::isUnpatrolled( $rc, $this->getUser() );
	}

	/**
	 * @param stdClass|RecentChange $rc Database row from recentchanges or a RecentChange object
	 * @param User $user
	 * @return bool
	 */
	public static function isUnpatrolled( $rc, User $user ) {
		if ( $rc instanceof RecentChange ) {
			$isPatrolled = $rc->mAttribs['rc_patrolled'];
			$rcType = $rc->mAttribs['rc_type'];
			$rcLogType = $rc->mAttribs['rc_log_type'];
		} else {
			$isPatrolled = $rc->rc_patrolled;
			$rcType = $rc->rc_type;
			$rcLogType = $rc->rc_log_type;
		}

		if ( !$isPatrolled ) {
			if ( $user->useRCPatrol() ) {
				return true;
			}
			if ( $user->useNPPatrol() && $rcType == RC_NEW ) {
				return true;
			}
			if ( $user->useFilePatrol() && $rcLogType == 'upload' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determines whether a revision is linked to this change; this may not be the case
	 * when the categorization wasn't done by an edit but a conditional parser function
	 *
	 * @since 1.27
	 *
	 * @param RecentChange|RCCacheEntry $rcObj
	 * @return bool
	 */
	protected function isCategorizationWithoutRevision( $rcObj ) {
		return intval( $rcObj->getAttribute( 'rc_type' ) ) === RC_CATEGORIZE
			&& intval( $rcObj->getAttribute( 'rc_this_oldid' ) ) === 0;
	}

	/**
	 * Get recommended data attributes for a change line.
	 * @param RecentChange $rc
	 * @return string[] attribute name => value
	 */
	protected function getDataAttributes( RecentChange $rc ) {
		$attrs = [];

		$type = $rc->getAttribute( 'rc_source' );
		switch ( $type ) {
			case RecentChange::SRC_EDIT:
			case RecentChange::SRC_NEW:
				$attrs['data-mw-revid'] = $rc->mAttribs['rc_this_oldid'];
				break;
			case RecentChange::SRC_LOG:
				$attrs['data-mw-logid'] = $rc->mAttribs['rc_logid'];
				$attrs['data-mw-logaction'] =
					$rc->mAttribs['rc_log_type'] . '/' . $rc->mAttribs['rc_log_action'];
				break;
			case RecentChange::SRC_CATEGORIZE:
				$attrs['data-mw-revid'] = $rc->mAttribs['rc_this_oldid'];
				break;
		}

		$attrs[ 'data-mw-ts' ] = $rc->getAttribute( 'rc_timestamp' );

		return $attrs;
	}

	/**
	 * Sets the callable that generates a change line prefix added to the beginning of each line.
	 *
	 * @param callable $prefixer Callable to run that generates the change line prefix.
	 *     Takes three parameters: a RecentChange object, a ChangesList object,
	 *     and whether the current entry is a grouped entry.
	 */
	public function setChangeLinePrefixer( callable $prefixer ) {
		$this->changeLinePrefixer = $prefixer;
	}
}
