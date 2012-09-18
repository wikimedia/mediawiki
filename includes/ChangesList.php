<?php
/**
 * Classes to show lists of changes.
 *
 * These can be:
 * - watchlist
 * - related changes
 * - recent changes
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

/**
 * @todo document
 */
class RCCacheEntry extends RecentChange {
	var $secureName, $link;
	var $curlink , $difflink, $lastlink, $usertalklink, $versionlink;
	var $userlink, $timestamp, $watched;

	/**
	 * @param $rc RecentChange
	 * @return RCCacheEntry
	 */
	static function newFromParent( $rc ) {
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		return $rc2;
	}
}

/**
 * Base class for all changes lists
 */
class ChangesList extends ContextSource {

	/**
	 * @var Skin
	 */
	public $skin;

	protected $watchlist = false;

	protected $message;

	/**
	 * Changeslist contructor
	 *
	 * @param $obj Skin or IContextSource
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
	 * Fetch an appropriate changes list class for the main context
	 * This first argument used to be an User object.
	 *
	 * @deprecated in 1.18; use newFromContext() instead
	 * @param $unused string|User Unused
	 * @return ChangesList|EnhancedChangesList|OldChangesList derivative
	 */
	public static function newFromUser( $unused ) {
		wfDeprecated( __METHOD__, '1.18' );
		return self::newFromContext( RequestContext::getMain() );
	}

	/**
	 * Fetch an appropriate changes list class for the specified context
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param $context IContextSource to use
	 * @return ChangesList|EnhancedChangesList|OldChangesList derivative
	 */
	public static function newFromContext( IContextSource $context ) {
		$user = $context->getUser();
		$sk = $context->getSkin();
		$list = null;
		if( wfRunHooks( 'FetchChangesList', array( $user, &$sk, &$list ) ) ) {
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
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		if( !isset( $this->message ) ) {
			foreach ( explode( ' ', 'cur diff hist last blocklink history ' .
			'semicolon-separator pipe-separator' ) as $msg ) {
				$this->message[$msg] = $this->msg( $msg )->escaped();
			}
		}
	}

	/**
	 * Returns the appropriate flags for new page, minor change and patrolling
	 * @param $flags Array Associative array of 'flag' => Bool
	 * @param $nothing String to use for empty space
	 * @return String
	 */
	protected function recentChangesFlags( $flags, $nothing = '&#160;' ) {
		$f = '';
		foreach( array( 'newpage', 'minor', 'bot', 'unpatrolled' ) as $flag ){
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
	 * @param $flag String: 'newpage', 'unpatrolled', 'minor', or 'bot'
	 * @return String: Raw HTML
	 */
	public static function flag( $flag ) {
		static $messages = null;
		if ( is_null( $messages ) ) {
			$messages = array(
				'newpage' => array( 'newpageletter', 'recentchanges-label-newpage' ),
				'minoredit' => array( 'minoreditletter', 'recentchanges-label-minor' ),
				'botedit' => array( 'boteditletter', 'recentchanges-label-bot' ),
				'unpatrolled' => array( 'unpatrolledletter', 'recentchanges-label-unpatrolled' ),
			);
			foreach( $messages as &$value ) {
				$value[0] = wfMessage( $value[0] )->escaped();
				$value[1] = wfMessage( $value[1] )->escaped();
			}
		}

		# Inconsistent naming, bleh
		$map = array(
			'newpage' => 'newpage',
			'minor' => 'minoredit',
			'bot' => 'botedit',
			'unpatrolled' => 'unpatrolled',
			'minoredit' => 'minoredit',
			'botedit' => 'botedit',
		);
		$flag = $map[$flag];

		return "<abbr class='$flag' title='" . $messages[$flag][1] . "'>" . $messages[$flag][0] . '</abbr>';
	}

	/**
	 * Returns text for the start of the tabular part of RC
	 * @return String
	 */
	public function beginRecentChangesList() {
		$this->rc_cache = array();
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$this->getOutput()->addModuleStyles( 'mediawiki.special.changeslist' );
		return '';
	}

	/**
	 * Show formatted char difference
	 * @param $old Integer: bytes
	 * @param $new Integer: bytes
	 * @param $context IContextSource context to use
	 * @return String
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
		if ( !isset($fastCharDiff[$code]) ) {
			$fastCharDiff[$code] = $wgMiserMode || $context->msg( 'rc-change-size' )->plain() === '$1';
		}

		$formattedSize = $lang->formatNum( $szdiff );

		if ( !$fastCharDiff[$code] ) {
			$formattedSize = $context->msg( 'rc-change-size', $formattedSize )->text();
		}

		if( abs( $szdiff ) > abs( $wgRCChangedSizeThreshold ) ) {
			$tag = 'strong';
		} else {
			$tag = 'span';
		}

		if ( $szdiff === 0 ) {
			$formattedSizeClass = 'mw-plusminus-null';
		}
		if ( $szdiff > 0 ) {
			$formattedSize = '+' . $formattedSize;
			$formattedSizeClass = 'mw-plusminus-pos';
		}
		if ( $szdiff < 0 ) {
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

		if( $oldlen === null || $newlen === null ) {
			return '';
		}

		return self::showCharacterDifference( $oldlen, $newlen, $this->getContext() );
	}

	/**
	 * Returns text for the end of RC
	 * @return String
	 */
	public function endRecentChangesList() {
		if( $this->rclistOpen ) {
			return "</ul>\n";
		} else {
			return '';
		}
	}

	public function insertDateHeader( &$s, $rc_timestamp ) {
		# Make date header if necessary
		$date = $this->getLanguage()->userDate( $rc_timestamp, $this->getUser() );
		if( $date != $this->lastdate ) {
			if( $this->lastdate != '' ) {
				$s .= "</ul>\n";
			}
			$s .= Xml::element( 'h4', null, $date ) . "\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}
	}

	public function insertLog( &$s, $title, $logtype ) {
		$page = new LogPage( $logtype );
		$logname = $page->getName()->escaped();
		$s .= $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $title, $logname ) )->escaped();
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 */
	public function insertDiffHist( &$s, &$rc, $unpatrolled ) {
		# Diff link
		if( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
			$diffLink = $this->message['diff'];
		} elseif ( !self::userCan( $rc, Revision::DELETED_TEXT, $this->getUser() ) ) {
			$diffLink = $this->message['diff'];
		} else {
			$query = array(
				'curid' => $rc->mAttribs['rc_cur_id'],
				'diff'  => $rc->mAttribs['rc_this_oldid'],
				'oldid' => $rc->mAttribs['rc_last_oldid']
			);

			if( $unpatrolled ) {
				$query['rcid'] = $rc->mAttribs['rc_id'];
			};

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
		$s .= $this->msg( 'parentheses' )->rawParams( $diffhist )->escaped() . ' <span class="mw-changeslist-separator">. .</span> ';
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 * @param $watched
	 */
	public function insertArticleLink( &$s, &$rc, $unpatrolled, $watched ) {
		# If it's a new article, there is no diff link, but if it hasn't been
		# patrolled yet, we need to give users a way to do so
		$params = array();

		if ( $unpatrolled && $rc->mAttribs['rc_type'] == RC_NEW ) {
			$params['rcid'] = $rc->mAttribs['rc_id'];
		}

		$articlelink = Linker::linkKnown(
			$rc->getTitle(),
			null,
			array( 'class' => 'mw-changeslist-title' ),
			$params
		);
		if( $this->isDeleted($rc,Revision::DELETED_TEXT) ) {
			$articlelink = '<span class="history-deleted">' . $articlelink . '</span>';
		}
		# To allow for boldening pages watched by this user
		$articlelink = "<span class=\"mw-title\">{$articlelink}</span>";
		# RTL/LTR marker
		$articlelink .= $this->getLanguage()->getDirMark();

		wfRunHooks( 'ChangesListInsertArticleLink',
			array(&$this, &$articlelink, &$s, &$rc, $unpatrolled, $watched) );

		$s .= " $articlelink";
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 */
	public function insertTimestamp( &$s, $rc ) {
		$s .= $this->message['semicolon-separator'] . '<span class="mw-changeslist-date">' .
			$this->getLanguage()->userTime( $rc->mAttribs['rc_timestamp'], $this->getUser() ) . '</span> <span class="mw-changeslist-separator">. .</span> ';
	}

	/**
	 * Insert links to user page, user talk page and eventually a blocking link
	 *
	 * @param &$s String HTML to update
	 * @param &$rc RecentChange
	 */
	public function insertUserRelatedLinks( &$s, &$rc ) {
		if( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$s .= ' <span class="history-deleted">' . $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
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
		if( $rc->mAttribs['rc_type'] != RC_MOVE && $rc->mAttribs['rc_type'] != RC_MOVE_OVER_REDIRECT ) {
			if( $this->isDeleted( $rc, Revision::DELETED_COMMENT ) ) {
				return ' <span class="history-deleted">' . $this->msg( 'rev-deleted-comment' )->escaped() . '</span>';
			} else {
				return Linker::commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
			}
		}
	}

	/**
	 * Check whether to enable recent changes patrol features
	 * @return Boolean
	 */
	public static function usePatrol() {
		global $wgUser;
		return $wgUser->useRCPatrol();
	}

	/**
	 * Returns the string which indicates the number of watching users
	 * @return string
	 */
	protected function numberofWatchingusers( $count ) {
		static $cache = array();
		if( $count > 0 ) {
			if( !isset( $cache[$count] ) ) {
				$cache[$count] = $this->msg( 'number_of_watching_users_RCview' )->numParams( $count )->escaped();
			}
			return $cache[$count];
		} else {
			return '';
		}
	}

	/**
	 * Determine if said field of a revision is hidden
	 * @param $rc RCCacheEntry
	 * @param $field Integer: one of DELETED_* bitfield constants
	 * @return Boolean
	 */
	public static function isDeleted( $rc, $field ) {
		return ( $rc->mAttribs['rc_deleted'] & $field ) == $field;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 * @param $rc RCCacheEntry
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return Boolean
	 */
	public static function userCan( $rc, $field, User $user = null ) {
		if( $rc->mAttribs['rc_type'] == RC_LOG ) {
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
		if( $watched ) {
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
		if( $rc->mAttribs['rc_type'] != RC_NEW && $rc->mAttribs['rc_this_oldid'] && $rc->mAttribs['rc_cur_id'] ) {
			$page = $rc->getTitle();
			/** Check for rollback and edit permissions, disallow special pages, and only
			  * show a link on the top-most revision */
			if ( $this->getUser()->isAllowed('rollback') && $rc->mAttribs['page_latest'] == $rc->mAttribs['rc_this_oldid'] )
			{
				$rev = new Revision( array(
					'id'        => $rc->mAttribs['rc_this_oldid'],
					'user'      => $rc->mAttribs['rc_user'],
					'user_text' => $rc->mAttribs['rc_user_text'],
					'deleted'   => $rc->mAttribs['rc_deleted']
				) );
				$rev->setTitle( $page );
				$s .= ' '.Linker::generateRollback( $rev, $this->getContext() );
			}
		}
	}

	/**
	 * @param $s string
	 * @param $rc RecentChange
	 * @param $classes
	 */
	public function insertTags( &$s, &$rc, &$classes ) {
		if ( empty($rc->mAttribs['ts_tags']) )
			return;

		list($tagSummary, $newClasses) = ChangeTags::formatSummaryRow( $rc->mAttribs['ts_tags'], 'changeslist' );
		$classes = array_merge( $classes, $newClasses );
		$s .= ' ' . $tagSummary;
	}

	public function insertExtra( &$s, &$rc, &$classes ) {
		## Empty, used for subclassers to add anything special.
	}

	protected function showAsUnpatrolled( RecentChange $rc ) {
		$unpatrolled = false;
		if ( !$rc->mAttribs['rc_patrolled'] ) {
			if ( $this->getUser()->useRCPatrol() ) {
				$unpatrolled = true;
			} elseif ( $this->getUser()->useNPPatrol() && $rc->mAttribs['rc_type'] == RC_NEW ) {
				$unpatrolled = true;
			}
		}
		return $unpatrolled;
	}
}


/**
 * Generate a list of changes using the good old system (no javascript)
 */
class OldChangesList extends ChangesList {
	/**
	 * Format a line using the old system (aka without any javascript).
	 *
	 * @param $rc RecentChange, passed by reference
	 * @param $watched Bool (default false)
	 * @param $linenumber Int (default null)
	 * @return string
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {
		global $wgRCShowChangedSize;
		wfProfileIn( __METHOD__ );

		# Should patrol-related stuff be shown?
		$unpatrolled = $this->showAsUnpatrolled( $rc );

		$dateheader = ''; // $s now contains only <li>...</li>, for hooks' convenience.
		$this->insertDateHeader( $dateheader, $rc->mAttribs['rc_timestamp'] );

		$s = '';
		$classes = array();
		// use mw-line-even/mw-line-odd class only if linenumber is given (feature from bug 14468)
		if( $linenumber ) {
			if( $linenumber & 1 ) {
				$classes[] = 'mw-line-odd';
			}
			else {
				$classes[] = 'mw-line-even';
			}
		}

		// Indicate watched status on the line to allow for more
		// comprehensive styling.
		$classes[] = $watched ? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';

		// Moved pages (very very old, not supported anymore)
		if( $rc->mAttribs['rc_type'] == RC_MOVE || $rc->mAttribs['rc_type'] == RC_MOVE_OVER_REDIRECT ) {
		// Log entries
		} elseif( $rc->mAttribs['rc_log_type'] ) {
			$logtitle = SpecialPage::getTitleFor( 'Log', $rc->mAttribs['rc_log_type'] );
			$this->insertLog( $s, $logtitle, $rc->mAttribs['rc_log_type'] );
		// Log entries (old format) or log targets, and special pages
		} elseif( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			list( $name, $subpage ) = SpecialPageFactory::resolveAlias( $rc->mAttribs['rc_title'] );
			if( $name == 'Log' ) {
				$this->insertLog( $s, $rc->getTitle(), $subpage );
			}
		// Regular entries
		} else {
			$this->insertDiffHist( $s, $rc, $unpatrolled );
			# M, N, b and ! (minor, new, bot and unpatrolled)
			$s .= $this->recentChangesFlags(
				array(
					'newpage' => $rc->mAttribs['rc_type'] == RC_NEW,
					'minor' => $rc->mAttribs['rc_minor'],
					'unpatrolled' => $unpatrolled,
					'bot' => $rc->mAttribs['rc_bot']
				),
				''
			);
			$this->insertArticleLink( $s, $rc, $unpatrolled, $watched );
		}
		# Edit/log timestamp
		$this->insertTimestamp( $s, $rc );
		# Bytes added or removed
		if ( $wgRCShowChangedSize ) {
			$cd = $this->formatCharacterDifference( $rc );
			if ( $cd !== '' ) {
				$s .= $cd . '  <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		if ( $rc->mAttribs['rc_type'] == RC_LOG ) {
			$s .= $this->insertLogEntry( $rc );
		} else {
			# User tool links
			$this->insertUserRelatedLinks( $s, $rc );
			# LTR/RTL direction mark
			$s .= $this->getLanguage()->getDirMark();
			$s .= $this->insertComment( $rc );
		}

		# Tags
		$this->insertTags( $s, $rc, $classes );
		# Rollback
		$this->insertRollback( $s, $rc );
		# For subclasses
		$this->insertExtra( $s, $rc, $classes );

		# How many users watch this page
		if( $rc->numberofWatchingusers > 0 ) {
			$s .= ' ' . $this->numberofWatchingusers( $rc->numberofWatchingusers );
		}

		if( $this->watchlist ) {
			$classes[] = Sanitizer::escapeClass( 'watchlist-'.$rc->mAttribs['rc_namespace'].'-'.$rc->mAttribs['rc_title'] );
		}

		wfRunHooks( 'OldChangesListRecentChangesLine', array(&$this, &$s, $rc) );

		wfProfileOut( __METHOD__ );
		return "$dateheader<li class=\"".implode( ' ', $classes )."\">".$s."</li>\n";
	}
}


/**
 * Generate a list of changes using an Enhanced system (uses javascript).
 */
class EnhancedChangesList extends ChangesList {

	protected $rc_cache;

	/**
	 * Add the JavaScript file for enhanced changeslist
	 * @return String
	 */
	public function beginRecentChangesList() {
		$this->rc_cache = array();
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$this->getOutput()->addModuleStyles( 'mediawiki.special.changeslist' );
		return '';
	}
	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 *
	 * @param $baseRC RecentChange
	 * @param $watched bool
	 *
	 * @return string
	 */
	public function recentChangesLine( &$baseRC, $watched = false ) {
		wfProfileIn( __METHOD__ );

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC );

		$curIdEq = array( 'curid' => $rc->mAttribs['rc_cur_id'] );

		# If it's a new day, add the headline and flush the cache
		$date = $this->getLanguage()->userDate( $rc->mAttribs['rc_timestamp'], $this->getUser() );
		$ret = '';
		if( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock();
			$this->rc_cache = array();
			$ret .= Xml::element( 'h4', null, $date ) . "\n";
			$this->lastdate = $date;
		}

		# Should patrol-related stuff be shown?
		$rc->unpatrolled = $this->showAsUnpatrolled( $rc );

		$showdifflinks = true;
		# Make article link
		$type = $rc->mAttribs['rc_type'];
		$logType = $rc->mAttribs['rc_log_type'];
		// Page moves, very old style, not supported anymore
		if( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
		// New unpatrolled pages
		} elseif( $rc->unpatrolled && $type == RC_NEW ) {
			$clink = Linker::linkKnown( $rc->getTitle(), null, array(),
				array( 'rcid' => $rc->mAttribs['rc_id'] ) );
		// Log entries
		} elseif( $type == RC_LOG ) {
			if( $logType ) {
				$logtitle = SpecialPage::getTitleFor( 'Log', $logType );
				$logpage = new LogPage( $logType );
				$logname = $logpage->getName()->escaped();
				$clink = $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $logtitle, $logname ) )->escaped();
			} else {
				$clink = Linker::link( $rc->getTitle() );
			}
			$watched = false;
		// Log entries (old format) and special pages
		} elseif( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			wfDebug( "Unexpected special page in recentchanges\n" );
			$clink = '';
		// Edits
		} else {
			$clink = Linker::linkKnown( $rc->getTitle() );
		}

		# Don't show unusable diff links
		if ( !ChangesList::userCan( $rc, Revision::DELETED_TEXT, $this->getUser() ) ) {
			$showdifflinks = false;
		}

		$time = $this->getLanguage()->userTime( $rc->mAttribs['rc_timestamp'], $this->getUser() );
		$rc->watched = $watched;
		$rc->link = $clink;
		$rc->timestamp = $time;
		$rc->numberofWatchingusers = $baseRC->numberofWatchingusers;

		# Make "cur" and "diff" links.  Do not use link(), it is too slow if
		# called too many times (50% of CPU time on RecentChanges!).
		$thisOldid = $rc->mAttribs['rc_this_oldid'];
		$lastOldid = $rc->mAttribs['rc_last_oldid'];
		if( $rc->unpatrolled ) {
			$rcIdQuery = array( 'rcid' => $rc->mAttribs['rc_id'] );
		} else {
			$rcIdQuery = array();
		}
		$querycur = $curIdEq + array( 'diff' => '0', 'oldid' => $thisOldid );
		$querydiff = $curIdEq + array( 'diff' => $thisOldid, 'oldid' =>
			$lastOldid ) + $rcIdQuery;

		if( !$showdifflinks ) {
			$curLink = $this->message['cur'];
			$diffLink = $this->message['diff'];
		} elseif( in_array( $type, array( RC_NEW, RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
			if ( $type != RC_NEW ) {
				$curLink = $this->message['cur'];
			} else {
				$curUrl = htmlspecialchars( $rc->getTitle()->getLinkURL( $querycur ) );
				$curLink = "<a href=\"$curUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['cur']}</a>";
			}
			$diffLink = $this->message['diff'];
		} else {
			$diffUrl = htmlspecialchars( $rc->getTitle()->getLinkURL( $querydiff ) );
			$curUrl = htmlspecialchars( $rc->getTitle()->getLinkURL( $querycur ) );
			$diffLink = "<a href=\"$diffUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['diff']}</a>";
			$curLink = "<a href=\"$curUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['cur']}</a>";
		}

		# Make "last" link
		if( !$showdifflinks || !$lastOldid ) {
			$lastLink = $this->message['last'];
		} elseif( in_array( $type, array( RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = Linker::linkKnown( $rc->getTitle(), $this->message['last'],
				array(), $curIdEq + array('diff' => $thisOldid, 'oldid' => $lastOldid) + $rcIdQuery );
		}

		# Make user links
		if( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$rc->userlink = ' <span class="history-deleted">' . $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			$rc->userlink = Linker::userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
			$rc->usertalklink = Linker::userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		}

		$rc->lastlink = $lastLink;
		$rc->curlink  = $curLink;
		$rc->difflink = $diffLink;

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ($this->rcMoveIndex++)] = array($rc);
		} else {
			# Logs are grouped by type
			if( $type == RC_LOG ){
				$secureName = SpecialPage::getTitleFor( 'Log', $logType )->getPrefixedDBkey();
			}
			if( !isset( $this->rc_cache[$secureName] ) ) {
				$this->rc_cache[$secureName] = array();
			}

			array_push( $this->rc_cache[$secureName], $rc );
		}

		wfProfileOut( __METHOD__ );

		return $ret;
	}

	/**
	 * Enhanced RC group
	 * @return string
	 */
	protected function recentChangesBlockGroup( $block ) {
		global $wgRCShowChangedSize;

		wfProfileIn( __METHOD__ );

		# Add the namespace and title of the block as part of the class
		$classes = array( 'mw-collapsible', 'mw-collapsed', 'mw-enhanced-rc' );
		if ( $block[0]->mAttribs['rc_log_type'] ) {
			# Log entry
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-'
					. $block[0]->mAttribs['rc_log_type'] . '-' . $block[0]->mAttribs['rc_title'] );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns'
					. $block[0]->mAttribs['rc_namespace'] . '-' . $block[0]->mAttribs['rc_title'] );
		}
		$classes[] = $block[0]->watched ? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';
		$r = Html::openElement( 'table', array( 'class' => $classes ) ) .
			Html::openElement( 'tr' );

		# Collate list of users
		$userlinks = array();
		# Other properties
		$unpatrolled = false;
		$isnew = false;
		$curId = $currentRevision = 0;
		# Some catalyst variables...
		$namehidden = true;
		$allLogs = true;
		foreach( $block as $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			if( $rcObj->mAttribs['rc_type'] == RC_NEW ) {
				$isnew = true;
			}
			// If all log actions to this page were hidden, then don't
			// give the name of the affected page for this block!
			if( !$this->isDeleted( $rcObj, LogPage::DELETED_ACTION ) ) {
				$namehidden = false;
			}
			$u = $rcObj->userlink;
			if( !isset( $userlinks[$u] ) ) {
				$userlinks[$u] = 0;
			}
			if( $rcObj->unpatrolled ) {
				$unpatrolled = true;
			}
			if( $rcObj->mAttribs['rc_type'] != RC_LOG ) {
				$allLogs = false;
			}
			# Get the latest entry with a page_id and oldid
			# since logs may not have these.
			if( !$curId && $rcObj->mAttribs['rc_cur_id'] ) {
				$curId = $rcObj->mAttribs['rc_cur_id'];
			}
			if( !$currentRevision && $rcObj->mAttribs['rc_this_oldid'] ) {
				$currentRevision = $rcObj->mAttribs['rc_this_oldid'];
			}

			$bot = $rcObj->mAttribs['rc_bot'];
			$userlinks[$u]++;
		}

		# Sort the list and convert to text
		krsort( $userlinks );
		asort( $userlinks );
		$users = array();
		foreach( $userlinks as $userlink => $count) {
			$text = $userlink;
			$text .= $this->getLanguage()->getDirMark();
			if( $count > 1 ) {
				$text .= ' ' . $this->msg( 'parentheses' )->rawParams( $this->getLanguage()->formatNum( $count ) . '×' )->escaped();
			}
			array_push( $users, $text );
		}

		$users = ' <span class="changedby">'
			. $this->msg( 'brackets' )->rawParams(
				implode( $this->message['semicolon-separator'], $users )
			)->escaped() . '</span>';

		$tl = '<span class="mw-collapsible-toggle mw-enhancedchanges-arrow"></span>';
		$r .= "<td>$tl</td>";

		# Main line
		$r .= '<td class="mw-enhanced-rc">' . $this->recentChangesFlags( array(
			'newpage' => $isnew,
			'minor' => false,
			'unpatrolled' => $unpatrolled,
			'bot' => $bot ,
		) );

		# Timestamp
		$r .= '&#160;'.$block[0]->timestamp.'&#160;</td><td>';

		# Article link
		if( $namehidden ) {
			$r .= ' <span class="history-deleted">' . $this->msg( 'rev-deleted-event' )->escaped() . '</span>';
		} elseif( $allLogs ) {
			$r .= $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$this->insertArticleLink( $r, $block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		$r .= $this->getLanguage()->getDirMark();

		$queryParams['curid'] = $curId;
		# Changes message
		$n = count($block);
		static $nchanges = array();
		if ( !isset( $nchanges[$n] ) ) {
			$nchanges[$n] = $this->msg( 'nchanges' )->numParams( $n )->escaped();
		}
		# Total change link
		$r .= ' ';
		$logtext = '';
		if( !$allLogs ) {
			if( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ) {
				$logtext .= $nchanges[$n];
			} elseif( $isnew ) {
				$logtext .= $nchanges[$n];
			} else {
				$params = $queryParams;
				$params['diff'] = $currentRevision;
				$params['oldid'] = $oldid;

				$logtext .= Linker::link(
					$block[0]->getTitle(),
					$nchanges[$n],
					array(),
					$params,
					array( 'known', 'noclasses' )
				);
			}
		}

		# History
		if( $allLogs ) {
			// don't show history link for logs
		} elseif( $namehidden || !$block[0]->getTitle()->exists() ) {
			$logtext .= $this->message['pipe-separator'] . $this->message['hist'];
		} else {
			$params = $queryParams;
			$params['action'] = 'history';

			$logtext .= $this->message['pipe-separator'] .
				Linker::linkKnown(
					$block[0]->getTitle(),
					$this->message['hist'],
					array(),
					$params
				);
		}

		if( $logtext !== '' ) {
			$r .= $this->msg( 'parentheses' )->rawParams( $logtext )->escaped();
		}

		$r .= ' <span class="mw-changeslist-separator">. .</span> ';

		# Character difference (does not apply if only log items)
		if( $wgRCShowChangedSize && !$allLogs ) {
			$last = 0;
			$first = count($block) - 1;
			# Some events (like logs) have an "empty" size, so we need to skip those...
			while( $last < $first && $block[$last]->mAttribs['rc_new_len'] === null ) {
				$last++;
			}
			while( $first > $last && $block[$first]->mAttribs['rc_old_len'] === null ) {
				$first--;
			}
			# Get net change
			$chardiff = $this->formatCharacterDifference( $block[$first], $block[$last] );

			if( $chardiff == '' ) {
				$r .= ' ';
			} else {
				$r .= ' ' . $chardiff. ' <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		$r .= $users;
		$r .= $this->numberofWatchingusers($block[0]->numberofWatchingusers);

		# Sub-entries
		foreach( $block as $rcObj ) {
			# Classes to apply -- TODO implement
			$classes = array();
			$type = $rcObj->mAttribs['rc_type'];

			$r .= '<tr><td></td><td class="mw-enhanced-rc">';
			$r .= $this->recentChangesFlags( array(
				'newpage' => $type == RC_NEW,
				'minor' => $rcObj->mAttribs['rc_minor'],
				'unpatrolled' => $rcObj->unpatrolled,
				'bot' => $rcObj->mAttribs['rc_bot'],
			) );
			$r .= '&#160;</td><td class="mw-enhanced-rc-nested"><span class="mw-enhanced-rc-time">';

			$params = $queryParams;

			if( $rcObj->mAttribs['rc_this_oldid'] != 0 ) {
				$params['oldid'] = $rcObj->mAttribs['rc_this_oldid'];
			}

			# Log timestamp
			if( $type == RC_LOG ) {
				$link = $rcObj->timestamp;
			# Revision link
			} elseif( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ) {
				$link = '<span class="history-deleted">'.$rcObj->timestamp.'</span> ';
			} else {
				if ( $rcObj->unpatrolled && $type == RC_NEW) {
					$params['rcid'] = $rcObj->mAttribs['rc_id'];
				}

				$link = Linker::linkKnown(
						$rcObj->getTitle(),
						$rcObj->timestamp,
						array(),
						$params
					);
				if( $this->isDeleted($rcObj,Revision::DELETED_TEXT) )
					$link = '<span class="history-deleted">'.$link.'</span> ';
			}
			$r .= $link . '</span>';

			if ( !$type == RC_LOG || $type == RC_NEW ) {
				$r .= ' ' . $this->msg( 'parentheses' )->rawParams( $rcObj->curlink . $this->message['pipe-separator'] . $rcObj->lastlink )->escaped();
			}
			$r .= ' <span class="mw-changeslist-separator">. .</span> ';

			# Character diff
			if ( $wgRCShowChangedSize ) {
				$cd = $this->formatCharacterDifference( $rcObj );
				if ( $cd !== '' ) {
					$r .= $cd . ' <span class="mw-changeslist-separator">. .</span> ';
				}
			}

			if ( $rcObj->mAttribs['rc_type'] == RC_LOG ) {
				$r .= $this->insertLogEntry( $rcObj );
			} else {
				# User links
				$r .= $rcObj->userlink;
				$r .= $rcObj->usertalklink;
				$r .= $this->insertComment( $rcObj );
			}

			# Rollback
			$this->insertRollback( $r, $rcObj );
			# Tags
			$this->insertTags( $r, $rcObj, $classes );

			$r .= "</td></tr>\n";
		}
		$r .= "</table>\n";

		$this->rcCacheIndex++;

		wfProfileOut( __METHOD__ );

		return $r;
	}

	/**
	 * Generate HTML for an arrow or placeholder graphic
	 * @param $dir String: one of '', 'd', 'l', 'r'
	 * @param $alt String: text
	 * @param $title String: text
	 * @return String: HTML "<img>" tag
	 */
	protected function arrow( $dir, $alt='', $title='' ) {
		global $wgStylePath;
		$encUrl = htmlspecialchars( $wgStylePath . '/common/images/Arr_' . $dir . '.png' );
		$encAlt = htmlspecialchars( $alt );
		$encTitle = htmlspecialchars( $title );
		return "<img src=\"$encUrl\" width=\"12\" height=\"12\" alt=\"$encAlt\" title=\"$encTitle\" />";
	}

	/**
	 * Generate HTML for a right- or left-facing arrow,
	 * depending on language direction.
	 * @return String: HTML "<img>" tag
	 */
	protected function sideArrow() {
		$dir = $this->getLanguage()->isRTL() ? 'l' : 'r';
		return $this->arrow( $dir, '+', $this->msg( 'rc-enhanced-expand' )->text() );
	}

	/**
	 * Generate HTML for a down-facing arrow
	 * depending on language direction.
	 * @return String: HTML "<img>" tag
	 */
	protected function downArrow() {
		return $this->arrow( 'd', '-', $this->msg( 'rc-enhanced-hide' )->text() );
	}

	/**
	 * Generate HTML for a spacer image
	 * @return String: HTML "<img>" tag
	 */
	protected function spacerArrow() {
		return $this->arrow( '', codepointToUtf8( 0xa0 ) ); // non-breaking space
	}

	/**
	 * Enhanced RC ungrouped line.
	 *
	 * @param $rcObj RecentChange
	 * @return String: a HTML formatted line (generated using $r)
	 */
	protected function recentChangesBlockLine( $rcObj ) {
		global $wgRCShowChangedSize;

		wfProfileIn( __METHOD__ );
		$query['curid'] = $rcObj->mAttribs['rc_cur_id'];

		$type = $rcObj->mAttribs['rc_type'];
		$logType = $rcObj->mAttribs['rc_log_type'];
		$classes = array( 'mw-enhanced-rc' );
		if( $logType ) {
			# Log entry
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-'
					. $logType . '-' . $rcObj->mAttribs['rc_title'] );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns' .
					$rcObj->mAttribs['rc_namespace'] . '-' . $rcObj->mAttribs['rc_title'] );
		}
		$classes[] = $rcObj->watched ? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';
		$r = Html::openElement( 'table', array( 'class' => $classes ) ) .
			Html::openElement( 'tr' );

		$r .= '<td class="mw-enhanced-rc"><span class="mw-enhancedchanges-arrow mw-enhancedchanges-arrow-space"></span>';
		# Flag and Timestamp
		if( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&#160;&#160;&#160;&#160;'; // 4 flags -> 4 spaces
		} else {
			$r .= $this->recentChangesFlags( array(
				'newpage' => $type == RC_NEW,
				'minor' => $rcObj->mAttribs['rc_minor'],
				'unpatrolled' => $rcObj->unpatrolled,
				'bot' => $rcObj->mAttribs['rc_bot'],
			) );
		}
		$r .= '&#160;'.$rcObj->timestamp.'&#160;</td><td>';
		# Article or log link
		if( $logType ) {
			$logPage = new LogPage( $logType );
			$logTitle = SpecialPage::getTitleFor( 'Log', $logType );
			$logName = $logPage->getName()->escaped();
			$r .= $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $logTitle, $logName ) )->escaped();
		} else {
			$this->insertArticleLink( $r, $rcObj, $rcObj->unpatrolled, $rcObj->watched );
		}
		# Diff and hist links
		if ( $type != RC_LOG ) {
			$query['action'] = 'history';
			$r .= ' ' . $this->msg( 'parentheses' )->rawParams( $rcObj->difflink . $this->message['pipe-separator'] . Linker::linkKnown(
				$rcObj->getTitle(),
				$this->message['hist'],
				array(),
				$query
			) )->escaped();
		}
		$r .= ' <span class="mw-changeslist-separator">. .</span> ';
		# Character diff
		if ( $wgRCShowChangedSize ) {
			$cd = $this->formatCharacterDifference( $rcObj );
			if ( $cd !== '' ) {
				$r .= $cd . ' <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		if ( $type == RC_LOG ) {
			$r .= $this->insertLogEntry( $rcObj );
		} else {
			$r .= ' '.$rcObj->userlink . $rcObj->usertalklink;
			$r .= $this->insertComment( $rcObj );
			$this->insertRollback( $r, $rcObj );
		}

		# Tags
		$this->insertTags( $r, $rcObj, $classes );
		# Show how many people are watching this if enabled
		$r .= $this->numberofWatchingusers($rcObj->numberofWatchingusers);

		$r .= "</td></tr></table>\n";

		wfProfileOut( __METHOD__ );

		return $r;
	}

	/**
	 * If enhanced RC is in use, this function takes the previously cached
	 * RC lines, arranges them, and outputs the HTML
	 *
	 * @return string
	 */
	protected function recentChangesBlock() {
		if( count ( $this->rc_cache ) == 0 ) {
			return '';
		}

		wfProfileIn( __METHOD__ );

		$blockOut = '';
		foreach( $this->rc_cache as $block ) {
			if( count( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine( array_shift( $block ) );
			} else {
				$blockOut .= $this->recentChangesBlockGroup( $block );
			}
		}

		wfProfileOut( __METHOD__ );

		return '<div>'.$blockOut.'</div>';
	}

	/**
	 * Returns text for the end of RC
	 * If enhanced RC is in use, returns pretty much all the text
	 * @return string
	 */
	public function endRecentChangesList() {
		return $this->recentChangesBlock() . parent::endRecentChangesList();
	}

}
