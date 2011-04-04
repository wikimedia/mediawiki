<?php
/**
 * Classes to show various lists of changes:
 * - watchlist
 * - related changes
 * - recent changes
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
	 * @static
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
class ChangesList {

	/**
	 * @var Skin
	 */
	public $skin;
	protected $watchlist = false;
	
	/**
	* Changeslist contructor
	* @param $skin Skin
	*/
	public function __construct( $skin ) {
		$this->skin = $skin;
		$this->preCacheMessages();
	}

	/**
	 * Fetch an appropriate changes list class for the specified user
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param $user User to fetch the list class for
	 * @return ChangesList derivative
	 */
	public static function newFromUser( $user ) {
		global $wgRequest;

		$sk = $user->getSkin();
		$list = null;
		if( wfRunHooks( 'FetchChangesList', array( $user, &$sk, &$list ) ) ) {
			$new = $wgRequest->getBool( 'enhanced', $user->getOption( 'usenewrc' ) );
			return $new ? new EnhancedChangesList( $sk ) : new OldChangesList( $sk );
		} else {
			return $list;
		}
	}
	
	/**
	 * Sets the list to use a <li class="watchlist-(namespace)-(page)"> tag
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
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
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
	 * Provide the <abbr> element appropriate to a given abbreviated flag,
	 * namely the flag indicating a new page, a minor edit, a bot edit, or an
	 * unpatrolled edit.  By default in English it will contain "N", "m", "b",
	 * "!" respectively, plus it will have an appropriate title and class.
	 *
	 * @param $key String: 'newpage', 'unpatrolled', 'minor', or 'bot'
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
				$value[0] = wfMsgExt( $value[0], 'escapenoentities' );
				$value[1] = wfMsgExt( $value[1], 'escapenoentities' );
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
		return '';
	}
	
	/**
	 * Show formatted char difference
	 * @param $old Integer: bytes
	 * @param $new Integer: bytes
	 * @returns String
	 */
	public static function showCharacterDifference( $old, $new ) {
		global $wgRCChangedSizeThreshold, $wgLang, $wgMiserMode;
		$szdiff = $new - $old;

		$code = $wgLang->getCode();
		static $fastCharDiff = array();
		if ( !isset($fastCharDiff[$code]) ) {
			$fastCharDiff[$code] = $wgMiserMode || wfMsgNoTrans( 'rc-change-size' ) === '$1';
		}
			
		$formatedSize = $wgLang->formatNum($szdiff);

		if ( !$fastCharDiff[$code] ) {
			$formatedSize = wfMsgExt( 'rc-change-size', array( 'parsemag', 'escape' ), $formatedSize );
		}
			
		if( abs( $szdiff ) > abs( $wgRCChangedSizeThreshold ) ) {
			$tag = 'strong';
		} else {
		    $tag = 'span';
		}
		if( $szdiff === 0 ) {
			return "<$tag class='mw-plusminus-null'>($formatedSize)</$tag>";
		} elseif( $szdiff > 0 ) {
			return "<$tag class='mw-plusminus-pos'>(+$formatedSize)</$tag>";
	    } else {
			return "<$tag class='mw-plusminus-neg'>($formatedSize)</$tag>";
		}
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

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @return void
	 */
	public function insertMove( &$s, $rc ) {
		# Diff
		$s .= '(' . $this->message['diff'] . ') (';
		# Hist
		$s .= $this->skin->link(
			$rc->getMovedToTitle(),
			$this->message['hist'],
			array(),
			array( 'action' => 'history' ),
			array( 'known', 'noclasses' )
		) . ') . . ';
		# "[[x]] moved to [[y]]"
		$msg = ( $rc->mAttribs['rc_type'] == RC_MOVE ) ? '1movedto2' : '1movedto2_redir';
		$s .= wfMsg(
			$msg,
			$this->skin->link(
				$rc->getTitle(),
				null,
				array(),
				array( 'redirect' => 'no' ),
				array( 'known', 'noclasses' )
			),
			$this->skin->link(
				$rc->getMovedToTitle(),
				null,
				array(),
				array(),
				array( 'known', 'noclasses' )
			)
		);
	}

	public function insertDateHeader( &$s, $rc_timestamp ) {
		global $wgLang;
		# Make date header if necessary
		$date = $wgLang->date( $rc_timestamp, true, true );
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
		$logname = LogPage::logName( $logtype );
		$s .= '(' . $this->skin->link(
			$title,
			$logname,
			array(),
			array(),
			array( 'known', 'noclasses' )
		) . ')';
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 * @return void
	 */
	public function insertDiffHist( &$s, &$rc, $unpatrolled ) {
		# Diff link
		if( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
			$diffLink = $this->message['diff'];
		} else if( !self::userCan($rc,Revision::DELETED_TEXT) ) {
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

			$diffLink = $this->skin->link(
				$rc->getTitle(),
				$this->message['diff'],
				array( 'tabindex' => $rc->counter ),
				$query,
				array( 'known', 'noclasses' )
			);
		}
		$s .= '(' . $diffLink . $this->message['pipe-separator'];
		# History link
		$s .= $this->skin->link(
			$rc->getTitle(),
			$this->message['hist'],
			array(),
			array(
				'curid' => $rc->mAttribs['rc_cur_id'],
				'action' => 'history'
			),
			array( 'known', 'noclasses' )
		);
		$s .= ') . . ';
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @param $unpatrolled
	 * @param $watched
	 * @return void
	 */
	public function insertArticleLink( &$s, &$rc, $unpatrolled, $watched ) {
		global $wgContLang;
		# If it's a new article, there is no diff link, but if it hasn't been
		# patrolled yet, we need to give users a way to do so
		$params = array();

		if ( $unpatrolled && $rc->mAttribs['rc_type'] == RC_NEW ) {
			$params['rcid'] = $rc->mAttribs['rc_id'];
		}

		if( $this->isDeleted($rc,Revision::DELETED_TEXT) ) {
			$articlelink = $this->skin->link(
				$rc->getTitle(),
				null,
				array(),
				$params,
				array( 'known', 'noclasses' )
			);
			$articlelink = '<span class="history-deleted">' . $articlelink . '</span>';
		} else {
			$articlelink = ' '. $this->skin->link(
				$rc->getTitle(),
				null,
				array(),
				$params,
				array( 'known', 'noclasses' )
			);
		}
		# Bolden pages watched by this user
		if( $watched ) {
			$articlelink = "<strong class=\"mw-watched\">{$articlelink}</strong>";
		}
		# RTL/LTR marker
		$articlelink .= $wgContLang->getDirMark();

		wfRunHooks( 'ChangesListInsertArticleLink',
			array(&$this, &$articlelink, &$s, &$rc, $unpatrolled, $watched) );

		$s .= " $articlelink";
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @return void
	 */
	public function insertTimestamp( &$s, $rc ) {
		global $wgLang;
		$s .= $this->message['semicolon-separator'] . 
			$wgLang->time( $rc->mAttribs['rc_timestamp'], true, true ) . ' . . ';
	}

	/** Insert links to user page, user talk page and eventually a blocking link
	 *
	 * @param $rc RecentChange
	 */
	public function insertUserRelatedLinks( &$s, &$rc ) {
		if( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$s .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$s .= $this->skin->userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
			$s .= $this->skin->userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		}
	}

	/** insert a formatted action
	 *
	 * @param $rc RecentChange
	 */
	public function insertAction( &$s, &$rc ) {
		if( $rc->mAttribs['rc_type'] == RC_LOG ) {
			if( $this->isDeleted( $rc, LogPage::DELETED_ACTION ) ) {
				$s .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-event' ) . '</span>';
			} else {
				$s .= ' '.LogPage::actionText( $rc->mAttribs['rc_log_type'], $rc->mAttribs['rc_log_action'],
					$rc->getTitle(), $this->skin, LogPage::extractParams( $rc->mAttribs['rc_params'] ), true, true );
			}
		}
	}

	/** insert a formatted comment
	 *
	 * @param $rc RecentChange
	 */
	public function insertComment( &$s, &$rc ) {
		if( $rc->mAttribs['rc_type'] != RC_MOVE && $rc->mAttribs['rc_type'] != RC_MOVE_OVER_REDIRECT ) {
			if( $this->isDeleted( $rc, Revision::DELETED_COMMENT ) ) {
				$s .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-comment' ) . '</span>';
			} else {
				$s .= $this->skin->commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
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
	 */
	protected function numberofWatchingusers( $count ) {
		global $wgLang;
		static $cache = array();
		if( $count > 0 ) {
			if( !isset( $cache[$count] ) ) {
				$cache[$count] = wfMsgExt( 'number_of_watching_users_RCview',
					array('parsemag', 'escape' ), $wgLang->formatNum( $count ) );
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
	 * @return Boolean
	 */
	public static function userCan( $rc, $field ) {
		if( $rc->mAttribs['rc_type'] == RC_LOG ) {
			return LogEventsList::userCanBitfield( $rc->mAttribs['rc_deleted'], $field );
		} else {
			return Revision::userCanBitfield( $rc->mAttribs['rc_deleted'], $field );
		}
	}

	protected function maybeWatchedLink( $link, $watched = false ) {
		if( $watched ) {
			return '<strong class="mw-watched">' . $link . '</strong>';
		} else {
			return '<span class="mw-rc-unwatched">' . $link . '</span>';
		}
	}
	
	/** Inserts a rollback link
	 *
	 * @param $s
	 * @param $rc RecentChange
	 */
	public function insertRollback( &$s, &$rc ) {
		global $wgUser;
		if( !$rc->mAttribs['rc_new'] && $rc->mAttribs['rc_this_oldid'] && $rc->mAttribs['rc_cur_id'] ) {
			$page = $rc->getTitle();
			/** Check for rollback and edit permissions, disallow special pages, and only
			  * show a link on the top-most revision */
			if ($wgUser->isAllowed('rollback') && $rc->mAttribs['page_latest'] == $rc->mAttribs['rc_this_oldid'] )
			{
				$rev = new Revision( array(
					'id'        => $rc->mAttribs['rc_this_oldid'],
					'user'      => $rc->mAttribs['rc_user'],
					'user_text' => $rc->mAttribs['rc_user_text'],
					'deleted'   => $rc->mAttribs['rc_deleted']
				) );
				$rev->setTitle( $page );
				$s .= ' '.$this->skin->generateRollback( $rev );
			}
		}
	}

	/**
	 * @param $s
	 * @param $rc RecentChange
	 * @param $classes
	 * @return
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
}


/**
 * Generate a list of changes using the good old system (no javascript)
 */
class OldChangesList extends ChangesList {
	/**
	 * Format a line using the old system (aka without any javascript).
	 *
	 * @param $rc RecentChange
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {
		global $wgLang, $wgRCShowChangedSize, $wgUser;
		wfProfileIn( __METHOD__ );
		# Should patrol-related stuff be shown?
		$unpatrolled = $wgUser->useRCPatrol() && !$rc->mAttribs['rc_patrolled'];

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

		// Moved pages
		if( $rc->mAttribs['rc_type'] == RC_MOVE || $rc->mAttribs['rc_type'] == RC_MOVE_OVER_REDIRECT ) {
			$this->insertMove( $s, $rc );
		// Log entries
		} elseif( $rc->mAttribs['rc_log_type'] ) {
			$logtitle = Title::newFromText( 'Log/'.$rc->mAttribs['rc_log_type'], NS_SPECIAL );
			$this->insertLog( $s, $logtitle, $rc->mAttribs['rc_log_type'] );
		// Log entries (old format) or log targets, and special pages
		} elseif( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			list( $name, $subpage ) = SpecialPage::resolveAliasWithSubpage( $rc->mAttribs['rc_title'] );
			if( $name == 'Log' ) {
				$this->insertLog( $s, $rc->getTitle(), $subpage );
			}
		// Regular entries
		} else {
			$this->insertDiffHist( $s, $rc, $unpatrolled );
			# M, N, b and ! (minor, new, bot and unpatrolled)
			$s .= $this->recentChangesFlags( 
				array(
					'newpage' => $rc->mAttribs['rc_new'],
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
		if( $wgRCShowChangedSize ) {
			$cd = $rc->getCharacterDifference();
			if( $cd != '' ) {
				$s .= "$cd  . . ";
			}
		}
		# User tool links
		$this->insertUserRelatedLinks( $s, $rc );
		# Log action text (if any)
		$this->insertAction( $s, $rc );
		# Edit or log comment
		$this->insertComment( $s, $rc );
		# Tags
		$this->insertTags( $s, $rc, $classes );
		# Rollback
		$this->insertRollback( $s, $rc );
		# For subclasses
		$this->insertExtra( $s, $rc, $classes );
		
		# How many users watch this page
		if( $rc->numberofWatchingusers > 0 ) {
			$s .= ' ' . wfMsgExt( 'number_of_watching_users_RCview', 
				array( 'parsemag', 'escape' ), $wgLang->formatNum( $rc->numberofWatchingusers ) );
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
	/**
	 * Add the JavaScript file for enhanced changeslist
	 * @return String
	 */
	public function beginRecentChangesList() {
		global $wgOut;
		$this->rc_cache = array();
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$wgOut->addModuleStyles( 'mediawiki.special.changeslist' );
		return '';
	}
	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 *
	 * @param $baseRC RecentChange
	 */
	public function recentChangesLine( &$baseRC, $watched = false ) {
		global $wgLang, $wgUser;
		
		wfProfileIn( __METHOD__ );

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC );

		$curIdEq = array( 'curid' => $rc->mAttribs['rc_cur_id'] );

		# If it's a new day, add the headline and flush the cache
		$date = $wgLang->date( $rc->mAttribs['rc_timestamp'], true );
		$ret = '';
		if( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock();
			$this->rc_cache = array();
			$ret .= Xml::element( 'h4', null, $date ) . "\n";
			$this->lastdate = $date;
		}

		# Should patrol-related stuff be shown?
		if( $wgUser->useRCPatrol() ) {
		  	$rc->unpatrolled = !$rc->mAttribs['rc_patrolled'];
		} else {
			$rc->unpatrolled = false;
		}

		$showdifflinks = true;
		# Make article link
		$type = $rc->mAttribs['rc_type'];
		$logType = $rc->mAttribs['rc_log_type'];
		// Page moves
		if( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			$msg = ( $type == RC_MOVE ) ? "1movedto2" : "1movedto2_redir";
			$clink = wfMsg( $msg, $this->skin->linkKnown( $rc->getTitle(), null,
				array(), array( 'redirect' => 'no' ) ),
				$this->skin->linkKnown( $rc->getMovedToTitle() ) );
		// New unpatrolled pages
		} else if( $rc->unpatrolled && $type == RC_NEW ) {
			$clink = $this->skin->linkKnown( $rc->getTitle(), null, array(),
				array( 'rcid' => $rc->mAttribs['rc_id'] ) );
		// Log entries
		} else if( $type == RC_LOG ) {
			if( $logType ) {
				$logtitle = SpecialPage::getTitleFor( 'Log', $logType );
				$clink = '(' . $this->skin->linkKnown( $logtitle, 
					LogPage::logName( $logType ) ) . ')';
			} else {
				$clink = $this->skin->link( $rc->getTitle() );
			}
			$watched = false;
		// Log entries (old format) and special pages
		} elseif( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			list( $specialName, $logtype ) = SpecialPage::resolveAliasWithSubpage( $rc->mAttribs['rc_title'] );
			if ( $specialName == 'Log' ) {
				# Log updates, etc
				$logname = LogPage::logName( $logtype );
				$clink = '(' . $this->skin->linkKnown( $rc->getTitle(), $logname ) . ')';
			} else {
				wfDebug( "Unexpected special page in recentchanges\n" );
				$clink = '';
			}
		// Edits
		} else {
			$clink = $this->skin->linkKnown( $rc->getTitle() );
		}

		# Don't show unusable diff links
		if ( !ChangesList::userCan($rc,Revision::DELETED_TEXT) ) {
			$showdifflinks = false;
		}

		$time = $wgLang->time( $rc->mAttribs['rc_timestamp'], true, true );
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
		} else if( in_array( $type, array( RC_NEW, RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
			if ( $type != RC_NEW ) {
				$curLink = $this->message['cur'];
			} else {
				$curUrl = htmlspecialchars( $rc->getTitle()->getLinkUrl( $querycur ) );
				$curLink = "<a href=\"$curUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['cur']}</a>";
			}
			$diffLink = $this->message['diff'];
		} else {
			$diffUrl = htmlspecialchars( $rc->getTitle()->getLinkUrl( $querydiff ) );
			$curUrl = htmlspecialchars( $rc->getTitle()->getLinkUrl( $querycur ) );
			$diffLink = "<a href=\"$diffUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['diff']}</a>";
			$curLink = "<a href=\"$curUrl\" tabindex=\"{$baseRC->counter}\">{$this->message['cur']}</a>";
		}

		# Make "last" link
		if( !$showdifflinks || !$lastOldid ) {
			$lastLink = $this->message['last'];
		} else if( in_array( $type, array( RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = $this->skin->linkKnown( $rc->getTitle(), $this->message['last'],
				array(), $curIdEq + array('diff' => $thisOldid, 'oldid' => $lastOldid) + $rcIdQuery );
		}

		# Make user links
		if( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$rc->userlink = ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$rc->userlink = $this->skin->userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
			$rc->usertalklink = $this->skin->userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
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
	 */
	protected function recentChangesBlockGroup( $block ) {
		global $wgLang, $wgContLang, $wgRCShowChangedSize;

		wfProfileIn( __METHOD__ );

		# Add the namespace and title of the block as part of the class
		if ( $block[0]->mAttribs['rc_log_type'] ) {
			# Log entry
			$classes = 'mw-collapsible mw-collapsed mw-enhanced-rc ' . Sanitizer::escapeClass( 'mw-changeslist-log-' . $block[0]->mAttribs['rc_log_type'] . '-' . $block[0]->mAttribs['rc_title'] );
		} else {
			$classes = 'mw-collapsible mw-collapsed mw-enhanced-rc ' . Sanitizer::escapeClass( 'mw-changeslist-ns' . $block[0]->mAttribs['rc_namespace'] . '-' . $block[0]->mAttribs['rc_title'] );
		}
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
			if( $rcObj->mAttribs['rc_new'] ) {
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
			$text .= $wgContLang->getDirMark();
			if( $count > 1 ) {
				$text .= ' (' . $wgLang->formatNum( $count ) . '×)';
			}
			array_push( $users, $text );
		}

		$users = ' <span class="changedby">[' . 
			implode( $this->message['semicolon-separator'], $users ) . ']</span>';

		# Title for <a> tags
		$expandTitle = htmlspecialchars( wfMsg( 'rc-enhanced-expand' ) );
		$closeTitle = htmlspecialchars( wfMsg( 'rc-enhanced-hide' ) );

		$tl = "<span class='mw-collapsible-toggle'>"
			. "<span class='mw-rc-openarrow'>"
			. "<a href='#' title='$expandTitle'>{$this->sideArrow()}</a>"
			. "</span><span class='mw-rc-closearrow'>"
			. "<a href='#' title='$closeTitle'>{$this->downArrow()}</a>"
			. "</span></span>";
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
			$r .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-event' ) . '</span>';
		} else if( $allLogs ) {
			$r .= $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$this->insertArticleLink( $r, $block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		$r .= $wgContLang->getDirMark();

		$queryParams['curid'] = $curId;
		# Changes message
		$n = count($block);
		static $nchanges = array();
		if ( !isset( $nchanges[$n] ) ) {
			$nchanges[$n] = wfMsgExt( 'nchanges', array( 'parsemag', 'escape' ), $wgLang->formatNum( $n ) );
		}
		# Total change link
		$r .= ' ';
		if( !$allLogs ) {
			$r .= '(';
			if( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT ) ) {
				$r .= $nchanges[$n];
			} else if( $isnew ) {
				$r .= $nchanges[$n];
			} else {
				$params = $queryParams;
				$params['diff'] = $currentRevision;
				$params['oldid'] = $oldid;
				
				$r .= $this->skin->link(
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
		} else if( $namehidden || !$block[0]->getTitle()->exists() ) {
			$r .= $this->message['pipe-separator'] . $this->message['hist'] . ')';
		} else {
			$params = $queryParams;
			$params['action'] = 'history';

			$r .= $this->message['pipe-separator'] .
				$this->skin->link(
					$block[0]->getTitle(),
					$this->message['hist'],
					array(),
					$params,
					array( 'known', 'noclasses' )
				) . ')';
		}
		$r .= ' . . ';

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
			$chardiff = $rcObj->getCharacterDifference( $block[$first]->mAttribs['rc_old_len'],
				$block[$last]->mAttribs['rc_new_len'] );

			if( $chardiff == '' ) {
				$r .= ' ';
			} else {
				$r .= ' ' . $chardiff. ' . . ';
			}
		}

		$r .= $users;
		$r .= $this->numberofWatchingusers($block[0]->numberofWatchingusers);

		# Sub-entries
		foreach( $block as $rcObj ) {
			# Classes to apply -- TODO implement
			$classes = array();
			$type = $rcObj->mAttribs['rc_type'];

			#$r .= '<tr><td valign="top">'.$this->spacerArrow();
			$r .= '<tr><td></td><td class="mw-enhanced-rc">';
			$r .= $this->recentChangesFlags( array(
				'newpage' => $rcObj->mAttribs['rc_new'],
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
			} else if( !ChangesList::userCan($rcObj,Revision::DELETED_TEXT) ) {
				$link = '<span class="history-deleted">'.$rcObj->timestamp.'</span> ';
			} else {
				if ( $rcObj->unpatrolled && $type == RC_NEW) {
					$params['rcid'] = $rcObj->mAttribs['rc_id'];
				}

				$link = $this->skin->link(
						$rcObj->getTitle(),
						$rcObj->timestamp,
						array(),
						$params,
						array( 'known', 'noclasses' )
					);
				if( $this->isDeleted($rcObj,Revision::DELETED_TEXT) )
					$link = '<span class="history-deleted">'.$link.'</span> ';
			}
			$r .= $link . '</span>';

			if ( !$type == RC_LOG || $type == RC_NEW ) {
				$r .= ' (';
				$r .= $rcObj->curlink;
				$r .= $this->message['pipe-separator'];
				$r .= $rcObj->lastlink;
				$r .= ')';
			}
			$r .= ' . . ';

			# Character diff
			if( $wgRCShowChangedSize && $rcObj->getCharacterDifference() ) {
				$r .= $rcObj->getCharacterDifference() . ' . . ' ;
			}

			# User links
			$r .= $rcObj->userlink;
			$r .= $rcObj->usertalklink;
			// log action
			$this->insertAction( $r, $rcObj );
			// log comment
			$this->insertComment( $r, $rcObj );
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
	 * @return String: HTML <img> tag
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
	 * @return String: HTML <img> tag
	 */
	protected function sideArrow() {
		global $wgContLang;
		$dir = $wgContLang->isRTL() ? 'l' : 'r';
		return $this->arrow( $dir, '+', wfMsg( 'rc-enhanced-expand' ) );
	}

	/**
	 * Generate HTML for a down-facing arrow
	 * depending on language direction.
	 * @return String: HTML <img> tag
	 */
	protected function downArrow() {
		return $this->arrow( 'd', '-', wfMsg( 'rc-enhanced-hide' ) );
	}

	/**
	 * Generate HTML for a spacer image
	 * @return String: HTML <img> tag
	 */
	protected function spacerArrow() {
		return $this->arrow( '', codepointToUtf8( 0xa0 ) ); // non-breaking space
	}

	/**
	 * Enhanced RC ungrouped line.
	 *
	 * @param $rcObj RecentChange
	 * @return String: a HTML formated line (generated using $r)
	 */
	protected function recentChangesBlockLine( $rcObj ) {
		global $wgRCShowChangedSize;

		wfProfileIn( __METHOD__ );
		$query['curid'] = $rcObj->mAttribs['rc_cur_id'];

		$type = $rcObj->mAttribs['rc_type'];
		$logType = $rcObj->mAttribs['rc_log_type'];
		if( $logType ) {
			# Log entry
			$classes = 'mw-enhanced-rc ' . Sanitizer::escapeClass( 'mw-changeslist-log-' . $logType . '-' . $rcObj->mAttribs['rc_title'] );
		} else {
			$classes = 'mw-enhanced-rc ' . Sanitizer::escapeClass( 'mw-changeslist-ns' . $rcObj->mAttribs['rc_namespace'] . '-' . $rcObj->mAttribs['rc_title'] );
		}
		$r = Html::openElement( 'table', array( 'class' => $classes ) ) .
			Html::openElement( 'tr' );

		$r .= '<td class="mw-enhanced-rc">' . $this->spacerArrow();
		# Flag and Timestamp
		if( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&#160;&#160;&#160;&#160;'; // 4 flags -> 4 spaces
		} else {
			$r .= $this->recentChangesFlags( array(
				'newpage' => $type == RC_NEW,
				'mino' => $rcObj->mAttribs['rc_minor'],
				'unpatrolled' => $rcObj->unpatrolled, 
				'bot' => $rcObj->mAttribs['rc_bot'],
			) );
		}
		$r .= '&#160;'.$rcObj->timestamp.'&#160;</td><td>';
		# Article or log link
		if( $logType ) {
			$logtitle = Title::newFromText( "Log/$logType", NS_SPECIAL );
			$logname = LogPage::logName( $logType );
			$r .= '(' . $this->skin->link(
				$logtitle,
				$logname,
				array(),
				array(),
				array( 'known', 'noclasses' )
			) . ')';
		} else {
			$this->insertArticleLink( $r, $rcObj, $rcObj->unpatrolled, $rcObj->watched );
		}
		# Diff and hist links
		if ( $type != RC_LOG ) {
			$r .= ' ('. $rcObj->difflink . $this->message['pipe-separator'];
			$query['action'] = 'history';
			$r .= $this->skin->link(
				$rcObj->getTitle(),
				$this->message['hist'],
				array(),
				$query,
				array( 'known', 'noclasses' )
			) . ')';
		}
		$r .= ' . . ';
		# Character diff
		if( $wgRCShowChangedSize && ($cd = $rcObj->getCharacterDifference()) ) {
			$r .= "$cd . . ";
		}
		# User/talk
		$r .= ' '.$rcObj->userlink . $rcObj->usertalklink;
		# Log action (if any)
		if( $logType ) {
			if( $this->isDeleted($rcObj,LogPage::DELETED_ACTION) ) {
				$r .= ' <span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
			} else {
				$r .= ' ' . LogPage::actionText( $logType, $rcObj->mAttribs['rc_log_action'], $rcObj->getTitle(),
					$this->skin, LogPage::extractParams( $rcObj->mAttribs['rc_params'] ), true, true );
			}
		}
		$this->insertComment( $r, $rcObj );
		$this->insertRollback( $r, $rcObj );
		# Tags
		$classes = explode( ' ', $classes );
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
	 */
	public function endRecentChangesList() {
		return $this->recentChangesBlock() . parent::endRecentChangesList();
	}

}
