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

class ChangesList extends ContextSource {
	/**
	 * @var Skin
	 */
	public $skin;

	protected $watchlist = false;
	protected $lastdate;
	protected $message;
	protected $rc_cache;
	protected $rcCacheIndex;
	protected $rclistOpen;
	protected $rcMoveIndex;

	/** @var BagOStuff */
	protected $watchMsgCache;

	/**
	 * Changeslist constructor
	 *
	 * @param Skin|IContextSource $obj
	 */
	public function __construct( $obj ) {
		if ( $obj instanceof IContextSource ) {
			$this->setContext( $obj );
			$this->skin = $obj->getSkin();
		} else {
			$this->setContext( $obj->getContext() );
			$this->skin = $obj;
		}
		$this->preCacheMessages();
		$this->watchMsgCache = new HashBagOStuff( [ 'maxKeys' => 50 ] );
	}

	/**
	 * Fetch an appropriate changes list class for the specified context
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param IContextSource $context
	 * @return ChangesList
	 */
	public static function newFromContext( IContextSource $context ) {
		$user = $context->getUser();
		$sk = $context->getSkin();
		$list = null;
		if ( Hooks::run( 'FetchChangesList', [ $user, &$sk, &$list ] ) ) {
			$new = $context->getRequest()->getBool( 'enhanced', $user->getOption( 'usenewrc' ) );

			return $new ? new EnhancedChangesList( $context ) : new OldChangesList( $context );
		} else {
			return $list;
		}
	}

	/**
	 * Format a line
	 *
	 * @since 1.27
	 *
	 * @param RecentChange $rc Passed by reference
	 * @param bool $watched (default false)
	 * @param int $linenumber (default null)
	 *
	 * @return string|bool
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {
		throw new RuntimeException( 'recentChangesLine should be implemented' );
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
	public function recentChangesFlags( $flags, $nothing = '&#160;' ) {
		$f = '';
		foreach ( array_keys( $this->getConfig()->get( 'RecentChangesFlags' ) ) as $flag ) {
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
	 * @return array of classes
	 */
	protected function getHTMLClasses( $rc, $watched ) {
		$classes = [];
		$logType = $rc->mAttribs['rc_log_type'];

		if ( $logType ) {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-' . $logType );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns' .
				$rc->mAttribs['rc_namespace'] . '-' . $rc->mAttribs['rc_title'] );
		}

		// Indicate watched status on the line to allow for more
		// comprehensive styling.
		$classes[] = $watched && $rc->mAttribs['rc_timestamp'] >= $watched
			? 'mw-changeslist-line-watched'
			: 'mw-changeslist-line-not-watched';

		return $classes;
	}

	/**
	 * Make an "<abbr>" element for a given change flag. The flag indicating a new page, minor edit,
	 * bot edit, or unpatrolled edit. In English it typically contains "N", "m", "b", or "!".
	 *
	 * @param string $flag One key of $wgRecentChangesFlags
	 * @param IContextSource $context
	 * @return string HTML
	 */
	public static function flag( $flag, IContextSource $context = null ) {
		static $map = [ 'minoredit' => 'minor', 'botedit' => 'bot' ];
		static $flagInfos = null;

		if ( is_null( $flagInfos ) ) {
			global $wgRecentChangesFlags;
			$flagInfos = [];
			foreach ( $wgRecentChangesFlags as $key => $value ) {
				$flagInfos[$key]['letter'] = $value['letter'];
				$flagInfos[$key]['title'] = $value['title'];
				// Allow customized class name, fall back to flag name
				$flagInfos[$key]['class'] = isset( $value['class'] ) ? $value['class'] : $key;
			}
		}

		$context = $context ?: RequestContext::getMain();

		// Inconsistent naming, kepted for b/c
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
		$this->getOutput()->addModuleStyles( 'mediawiki.special.changeslist' );

		return '<div class="mw-changeslist">';
	}

	/**
	 * @param ResultWrapper|array $rows
	 */
	public function initChangesListRows( $rows ) {
		Hooks::run( 'ChangesListInitRows', [ $this, $rows ] );
	}

	/**
	 * Show formatted char difference
	 * @param int $old Number of bytes
	 * @param int $new Number of bytes
	 * @param IContextSource $context
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
			$fastCharDiff[$code] = $config->get( 'MiserMode' )
				|| $context->msg( 'rc-change-size' )->plain() === '$1';
		}

		$formattedSize = $lang->formatNum( $szdiff );

		if ( !$fastCharDiff[$code] ) {
			$formattedSize = $context->msg( 'rc-change-size', $formattedSize )->text();
		}

		if ( abs( $szdiff ) > abs( $config->get( 'RCChangedSizeThreshold' ) ) ) {
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

		$formattedTotalSize = $context->msg( 'rc-change-size-new' )->numParams( $new )->text();

		return Html::element( $tag,
			[ 'dir' => 'ltr', 'class' => $formattedSizeClass, 'title' => $formattedTotalSize ],
			$context->msg( 'parentheses', $formattedSize )->plain() ) . $lang->getDirMark();
	}

	/**
	 * Format the character difference of one or several changes.
	 *
	 * @param RecentChange $old
	 * @param RecentChange $new Last change to use, if not provided, $old will be used
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
	 * @param string $s HTML to update
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
	 * @param string $s HTML to update
	 * @param Title $title
	 * @param string $logtype
	 */
	public function insertLog( &$s, $title, $logtype ) {
		$page = new LogPage( $logtype );
		$logname = $page->getName()->setContext( $this->getContext() )->escaped();
		$s .= $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $title, $logname ) )->escaped();
	}

	/**
	 * @param string $s HTML to update
	 * @param RecentChange $rc
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
		} elseif ( !self::userCan( $rc, Revision::DELETED_TEXT, $this->getUser() ) ) {
			$diffLink = $this->message['diff'];
		} else {
			$query = [
				'curid' => $rc->mAttribs['rc_cur_id'],
				'diff' => $rc->mAttribs['rc_this_oldid'],
				'oldid' => $rc->mAttribs['rc_last_oldid']
			];

			$diffLink = Linker::linkKnown(
				$rc->getTitle(),
				$this->message['diff'],
				[ 'tabindex' => $rc->counter ],
				$query
			);
		}
		if ( $rc->mAttribs['rc_type'] == RC_CATEGORIZE ) {
			$diffhist = $diffLink . $this->message['pipe-separator'] . $this->message['hist'];
		} else {
			$diffhist = $diffLink . $this->message['pipe-separator'];
			# History link
			$diffhist .= Linker::linkKnown(
				$rc->getTitle(),
				$this->message['hist'],
				[],
				[
					'curid' => $rc->mAttribs['rc_cur_id'],
					'action' => 'history'
				]
			);
		}

		// @todo FIXME: Hard coded ". .". Is there a message for this? Should there be?
		$s .= $this->msg( 'parentheses' )->rawParams( $diffhist )->escaped() .
			' <span class="mw-changeslist-separator">. .</span> ';
	}

	/**
	 * @param string $s Article link will be appended to this string, in place.
	 * @param RecentChange $rc
	 * @param bool $unpatrolled
	 * @param bool $watched
	 * @deprecated since 1.27, use getArticleLink instead.
	 */
	public function insertArticleLink( &$s, RecentChange $rc, $unpatrolled, $watched ) {
		$s .= $this->getArticleLink( $rc, $unpatrolled, $watched );
	}

	/**
	 * @param RecentChange $rc
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

		$articlelink = Linker::link(
			$rc->getTitle(),
			null,
			[ 'class' => 'mw-changeslist-title' ],
			$params
		);
		if ( $this->isDeleted( $rc, Revision::DELETED_TEXT ) ) {
			$articlelink = '<span class="history-deleted">' . $articlelink . '</span>';
		}
		# To allow for boldening pages watched by this user
		$articlelink = "<span class=\"mw-title\">{$articlelink}</span>";
		# RTL/LTR marker
		$articlelink .= $this->getLanguage()->getDirMark();

		# TODO: Deprecate the $s argument, it seems happily unused.
		$s = '';
		Hooks::run( 'ChangesListInsertArticleLink',
			[ &$this, &$articlelink, &$s, &$rc, $unpatrolled, $watched ] );

		return "{$s} {$articlelink}";
	}

	/**
	 * Get the timestamp from $rc formatted with current user's settings
	 * and a separator
	 *
	 * @param RecentChange $rc
	 * @return string HTML fragment
	 */
	public function getTimestamp( $rc ) {
		// @todo FIXME: Hard coded ". .". Is there a message for this? Should there be?
		return $this->message['semicolon-separator'] . '<span class="mw-changeslist-date">' .
			$this->getLanguage()->userTime(
				$rc->mAttribs['rc_timestamp'],
				$this->getUser()
			) . '</span> <span class="mw-changeslist-separator">. .</span> ';
	}

	/**
	 * Insert time timestamp string from $rc into $s
	 *
	 * @param string $s HTML to update
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
		if ( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$s .= ' <span class="history-deleted">' .
				$this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			$s .= $this->getLanguage()->getDirMark() . Linker::userLink( $rc->mAttribs['rc_user'],
				$rc->mAttribs['rc_user_text'] );
			$s .= Linker::userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
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

		return $formatter->getActionText() . " $mark" . $formatter->getComment();
	}

	/**
	 * Insert a formatted comment
	 * @param RecentChange $rc
	 * @return string
	 */
	public function insertComment( $rc ) {
		if ( $this->isDeleted( $rc, Revision::DELETED_COMMENT ) ) {
			return ' <span class="history-deleted">' .
				$this->msg( 'rev-deleted-comment' )->escaped() . '</span>';
		} else {
			return Linker::commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
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
		$cache = $this->watchMsgCache;
		return $cache->getWithSetCallback( $count, $cache::TTL_INDEFINITE,
			function () use ( $count ) {
				return $this->msg( 'number_of_watching_users_RCview' )
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
	 * @param User $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public static function userCan( $rc, $field, User $user = null ) {
		if ( $rc->mAttribs['rc_type'] == RC_LOG ) {
			return LogEventsList::userCanBitfield( $rc->mAttribs['rc_deleted'], $field, $user );
		} else {
			return Revision::userCanBitfield( $rc->mAttribs['rc_deleted'], $field, $user );
		}
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

	/** Inserts a rollback link
	 *
	 * @param string $s
	 * @param RecentChange $rc
	 */
	public function insertRollback( &$s, &$rc ) {
		if ( $rc->mAttribs['rc_type'] == RC_EDIT
			&& $rc->mAttribs['rc_this_oldid']
			&& $rc->mAttribs['rc_cur_id']
		) {
			$page = $rc->getTitle();
			/** Check for rollback and edit permissions, disallow special pages, and only
			 * show a link on the top-most revision */
			if ( $this->getUser()->isAllowed( 'rollback' )
				&& $rc->mAttribs['page_latest'] == $rc->mAttribs['rc_this_oldid']
			) {
				$rev = new Revision( [
					'title' => $page,
					'id' => $rc->mAttribs['rc_this_oldid'],
					'user' => $rc->mAttribs['rc_user'],
					'user_text' => $rc->mAttribs['rc_user_text'],
					'deleted' => $rc->mAttribs['rc_deleted']
				] );
				$s .= ' ' . Linker::generateRollback( $rev, $this->getContext() );
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
	 * @param string $s
	 * @param RecentChange $rc
	 * @param array $classes
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
	 * @param array $classes
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
	 * @param object|RecentChange $rc Database row from recentchanges or a RecentChange object
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

}
