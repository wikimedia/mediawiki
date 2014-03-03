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
	}

	/**
	 * Fetch an appropriate changes list class for the specified context
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param IContextSource $context
	 * @return ChangesList derivative
	 */
	public static function newFromContext( IContextSource $context ) {
		$user = $context->getUser();
		$sk = $context->getSkin();
		$list = null;
		if ( wfRunHooks( 'FetchChangesList', array( $user, &$sk, &$list ) ) ) {
			$new = $context->getRequest()->getBool( 'enhanced', $user->getOption( 'usenewrc' ) );

			return $new ? new EnhancedChangesList( $context ) : new OldChangesList( $context );
		} else {
			return $list;
		}
	}

	/**
	 * Sets the list to use a "<li class='watchlist-(namespace)-(page)'>" tag
	 * @param $value Boolean
	 */
	public function setWatchlistDivs( $value = true ) {
		$this->watchlist = $value;
	}

	/**
	 * @return bool true when setWatchlistDivs has been called
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
			foreach ( array(
				'cur', 'diff', 'hist', 'enhancedrc-history', 'last', 'blocklink', 'history',
				'semicolon-separator', 'pipe-separator' ) as $msg
			) {
				$this->message[$msg] = $this->msg( $msg )->escaped();
			}
		}
	}

	/**
	 * Returns the appropriate flags for new page, minor change and patrolling
	 * @param array $flags Associative array of 'flag' => Bool
	 * @param string $nothing to use for empty space
	 * @return string
	 */
	public function recentChangesFlags( $flags, $nothing = '&#160;' ) {
		global $wgRecentChangesFlags;
		$f = '';
		foreach ( array_keys( $wgRecentChangesFlags ) as $flag ) {
			$f .= isset( $flags[$flag] ) && $flags[$flag]
				? self::flag( $flag )
				: $nothing;
		}

		return $f;
	}

	/**
	 * Provide the "<abbr>" element appropriate to a given abbreviated flag,
	 * namely the flag indicating a new page, a minor edit, a bot edit, or an
	 * unpatrolled edit.  By default in English it will contain "N", "m", "b",
	 * "!" respectively, plus it will have an appropriate title and class.
	 *
	 * @param string $flag One key of $wgRecentChangesFlags
	 * @return string Raw HTML
	 */
	public static function flag( $flag ) {
		static $flagInfos = null;
		if ( is_null( $flagInfos ) ) {
			global $wgRecentChangesFlags;
			$flagInfos = array();
			foreach ( $wgRecentChangesFlags as $key => $value ) {
				$flagInfos[$key]['letter'] = wfMessage( $value['letter'] )->escaped();
				$flagInfos[$key]['title'] = wfMessage( $value['title'] )->escaped();
				// Allow customized class name, fall back to flag name
				$flagInfos[$key]['class'] = Sanitizer::escapeClass(
					isset( $value['class'] ) ? $value['class'] : $key );
			}
		}

		// Inconsistent naming, bleh, kepted for b/c
		$map = array(
			'minoredit' => 'minor',
			'botedit' => 'bot',
		);
		if ( isset( $map[$flag] ) ) {
			$flag = $map[$flag];
		}

		return "<abbr class='" . $flagInfos[$flag]['class'] . "' title='" .
			$flagInfos[$flag]['title'] . "'>" . $flagInfos[$flag]['letter'] .
			'</abbr>';
	}

	/**
	 * Returns text for the start of the tabular part of RC
	 * @return string
	 */
	public function beginRecentChangesList() {
		$this->rc_cache = array();
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
		wfRunHooks( 'ChangesListInitRows', array( $this, $rows ) );
	}

	/**
	 * Show formatted char difference
	 * @param int $old Number of bytes
	 * @param int $new Number of bytes
	 * @param IContextSource $context
	 * @return string
	 */
	public static function showCharacterDifference( $old, $new, IContextSource $context = null ) {
		global $wgRCChangedSizeThreshold, $wgMiserMode;

		if ( !$context ) {
			$context = RequestContext::getMain();
		}

		$new = (int)$new;
		$old = (int)$old;
		$szdiff = $new - $old;

		$lang = $context->getLanguage();
		$code = $lang->getCode();
		static $fastCharDiff = array();
		if ( !isset( $fastCharDiff[$code] ) ) {
			$fastCharDiff[$code] = $wgMiserMode || $context->msg( 'rc-change-size' )->plain() === '$1';
		}

		$formattedSize = $lang->formatNum( $szdiff );

		if ( !$fastCharDiff[$code] ) {
			$formattedSize = $context->msg( 'rc-change-size', $formattedSize )->text();
		}

		if ( abs( $szdiff ) > abs( $wgRCChangedSizeThreshold ) ) {
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
			array( 'dir' => 'ltr', 'class' => $formattedSizeClass, 'title' => $formattedTotalSize ),
			$context->msg( 'parentheses', $formattedSize )->plain() ) . $lang->getDirMark();
	}

	/**
	 * Format the character difference of one or several changes.
	 *
	 * @param $old RecentChange
	 * @param $new RecentChange last change to use, if not provided, $old will be used
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
	 * @return String
	 */
	public function endRecentChangesList() {
		$out = $this->rclistOpen ? "</ul>\n" : '';
		$out .= '</div>';

		return $out;
	}

	/**
	 * @param string $s HTML to update
	 * @param $rc_timestamp mixed
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
	 * @param $title Title
	 * @param $logtype string
	 */
	public function insertLog( &$s, $title, $logtype ) {
		$page = new LogPage( $logtype );
		$logname = $page->getName()->escaped();
		$s .= $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $title, $logname ) )->escaped();
	}

	/**
	 * @param string $s HTML to update
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 */
	public function insertDiffHist( &$s, &$rc, $unpatrolled ) {
		# Diff link
		if ( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
			$diffLink = $this->message['diff'];
		} elseif ( !self::userCan( $rc, Revision::DELETED_TEXT, $this->getUser() ) ) {
			$diffLink = $this->message['diff'];
		} else {
			$query = array(
				'curid' => $rc->mAttribs['rc_cur_id'],
				'diff' => $rc->mAttribs['rc_this_oldid'],
				'oldid' => $rc->mAttribs['rc_last_oldid']
			);

			$diffLink = Linker::linkKnown(
				$rc->getTitle(),
				$this->message['diff'],
				array( 'tabindex' => $rc->counter ),
				$query
			);
		}
		$diffhist = $diffLink . $this->message['pipe-separator'];
		# History link
		$diffhist .= Linker::linkKnown(
			$rc->getTitle(),
			$this->message['hist'],
			array(),
			array(
				'curid' => $rc->mAttribs['rc_cur_id'],
				'action' => 'history'
			)
		);
		// @todo FIXME: Hard coded ". .". Is there a message for this? Should there be?
		$s .= $this->msg( 'parentheses' )->rawParams( $diffhist )->escaped() .
			' <span class="mw-changeslist-separator">. .</span> ';
	}

	/**
	 * @param string $s HTML to update
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 * @param $watched
	 */
	public function insertArticleLink( &$s, &$rc, $unpatrolled, $watched ) {
		$params = array();

		$articlelink = Linker::linkKnown(
			$rc->getTitle(),
			null,
			array( 'class' => 'mw-changeslist-title' ),
			$params
		);
		if ( $this->isDeleted( $rc, Revision::DELETED_TEXT ) ) {
			$articlelink = '<span class="history-deleted">' . $articlelink . '</span>';
		}
		# To allow for boldening pages watched by this user
		$articlelink = "<span class=\"mw-title\">{$articlelink}</span>";
		# RTL/LTR marker
		$articlelink .= $this->getLanguage()->getDirMark();

		wfRunHooks( 'ChangesListInsertArticleLink',
			array( &$this, &$articlelink, &$s, &$rc, $unpatrolled, $watched ) );

		$s .= " $articlelink";
	}

	/**
	 * Get the timestamp from $rc formatted with current user's settings
	 * and a separator
	 *
	 * @param $rc RecentChange
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
	 * @param $rc RecentChange
	 */
	public function insertTimestamp( &$s, $rc ) {
		$s .= $this->getTimestamp( $rc );
	}

	/**
	 * Insert links to user page, user talk page and eventually a blocking link
	 *
	 * @param &$s String HTML to update
	 * @param &$rc RecentChange
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
	 * @param $rc RecentChange
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
	 * @param $rc RecentChange
	 * @return string
	 */
	public function insertComment( $rc ) {
		if ( $rc->mAttribs['rc_type'] != RC_MOVE && $rc->mAttribs['rc_type'] != RC_MOVE_OVER_REDIRECT ) {
			if ( $this->isDeleted( $rc, Revision::DELETED_COMMENT ) ) {
				return ' <span class="history-deleted">' .
					$this->msg( 'rev-deleted-comment' )->escaped() . '</span>';
			} else {
				return Linker::commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
			}
		}

		return '';
	}

	/**
	 * Check whether to enable recent changes patrol features
	 *
	 * @deprecated since 1.22
	 * @return Boolean
	 */
	public static function usePatrol() {
		global $wgUser;

		wfDeprecated( __METHOD__, '1.22' );

		return $wgUser->useRCPatrol();
	}

	/**
	 * Returns the string which indicates the number of watching users
	 * @param int $count Number of user watching a page
	 * @return string
	 */
	protected function numberofWatchingusers( $count ) {
		static $cache = array();
		if ( $count > 0 ) {
			if ( !isset( $cache[$count] ) ) {
				$cache[$count] = $this->msg( 'number_of_watching_users_RCview' )
					->numParams( $count )->escaped();
			}

			return $cache[$count];
		} else {
			return '';
		}
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
	 * @param $link string
	 * @param $watched bool
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
	 * @param $s string
	 * @param $rc RecentChange
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
				$rev = new Revision( array(
					'title' => $page,
					'id' => $rc->mAttribs['rc_this_oldid'],
					'user' => $rc->mAttribs['rc_user'],
					'user_text' => $rc->mAttribs['rc_user_text'],
					'deleted' => $rc->mAttribs['rc_deleted']
				) );
				$s .= ' ' . Linker::generateRollback( $rev, $this->getContext() );
			}
		}
	}

	/**
	 * @param $s string
	 * @param $rc RecentChange
	 * @param $classes
	 */
	public function insertTags( &$s, &$rc, &$classes ) {
		if ( empty( $rc->mAttribs['ts_tags'] ) ) {
			return;
		}

		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
			$rc->mAttribs['ts_tags'],
			'changeslist'
		);
		$classes = array_merge( $classes, $newClasses );
		$s .= ' ' . $tagSummary;
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
		} else {
			$isPatrolled = $rc->rc_patrolled;
			$rcType = $rc->rc_type;
		}

		if ( !$isPatrolled ) {
			if ( $user->useRCPatrol() ) {
				return true;
			}
			if ( $user->useNPPatrol() && $rcType == RC_NEW ) {
				return true;
			}
		}

		return false;
	}
}
