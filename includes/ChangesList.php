<?php

/**
 * @todo document
 */
class RCCacheEntry extends RecentChange {
	var $secureName, $link;
	var $curlink , $difflink, $lastlink, $usertalklink, $versionlink;
	var $userlink, $timestamp, $watched;

	static function newFromParent( $rc ) {
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		return $rc2;
	}
}

/**
 * Class to show various lists of changes:
 * - what links here
 * - related changes
 * - recent changes
 */
class ChangesList {
	# Called by history lists and recent changes
	public $skin;

	/**
	* Changeslist contructor
	* @param Skin $skin
	*/
	public function __construct( &$skin ) {
		$this->skin =& $skin;
		$this->preCacheMessages();
	}

	/**
	 * Fetch an appropriate changes list class for the specified user
	 * Some users might want to use an enhanced list format, for instance
	 *
	 * @param $user User to fetch the list class for
	 * @return ChangesList derivative
	 */
	public static function newFromUser( &$user ) {
		$sk = $user->getSkin();
		$list = NULL;
		if( wfRunHooks( 'FetchChangesList', array( &$user, &$sk, &$list ) ) ) {
			return $user->getOption( 'usenewrc' ) ?
				new EnhancedChangesList( $sk ) : new OldChangesList( $sk );
		} else {
			return $list;
		}
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		if( !isset( $this->message ) ) {
			foreach( explode(' ', 'cur diff hist minoreditletter newpageletter last '.
				'blocklink history boteditletter semicolon-separator' ) as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}
	}


	/**
	 * Returns the appropriate flags for new page, minor change and patrolling
	 * @param bool $new
	 * @param bool $minor
	 * @param bool $patrolled
	 * @param string $nothing, string to use for empty space
	 * @param bool $bot
	 * @return string
	 */
	protected function recentChangesFlags( $new, $minor, $patrolled, $nothing = '&nbsp;', $bot = false ) {
		$f = $new ?
			'<span class="newpage">' . $this->message['newpageletter'] . '</span>' : $nothing;
		$f .= $minor ?
			'<span class="minor">' . $this->message['minoreditletter'] . '</span>' : $nothing;
		$f .= $bot ? '<span class="bot">' . $this->message['boteditletter'] . '</span>' : $nothing;
		$f .= $patrolled ? '<span class="unpatrolled">!</span>' : $nothing;
		return $f;
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
		return '';
	}
	
	/**
	 * Show formatted char difference
	 * @param int $old bytes
	 * @param int $new bytes
	 * @returns string
	 */
	public static function showCharacterDifference( $old, $new ) {
		global $wgRCChangedSizeThreshold, $wgLang;
		$szdiff = $new - $old;
		$formatedSize = wfMsgExt( 'rc-change-size', array( 'parsemag', 'escape' ), $wgLang->formatNum( $szdiff ) );
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
	 * @return string
	 */
	public function endRecentChangesList() {
		if( $this->rclistOpen ) {
			return "</ul>\n";
		} else {
			return '';
		}
	}

	protected function insertMove( &$s, $rc ) {
		# Diff
		$s .= '(' . $this->message['diff'] . ') (';
		# Hist
		$s .= $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), $this->message['hist'], 
			'action=history' ) . ') . . ';
		# "[[x]] moved to [[y]]"
		$msg = ( $rc->mAttribs['rc_type'] == RC_MOVE ) ? '1movedto2' : '1movedto2_redir';
		$s .= wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			$this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
	}

	protected function insertDateHeader( &$s, $rc_timestamp ) {
		global $wgLang;
		# Make date header if necessary
		$date = $wgLang->date( $rc_timestamp, true, true );
		if( $date != $this->lastdate ) {
			if( '' != $this->lastdate ) {
				$s .= "</ul>\n";
			}
			$s .= '<h4>'.$date."</h4>\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}
	}

	protected function insertLog( &$s, $title, $logtype ) {
		$logname = LogPage::logName( $logtype );
		$s .= '(' . $this->skin->makeKnownLinkObj($title, $logname ) . ')';
	}

	protected function insertDiffHist( &$s, &$rc, $unpatrolled ) {
		# Diff link
		if( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
			$diffLink = $this->message['diff'];
		} else if( !$this->userCan($rc,Revision::DELETED_TEXT) ) {
			$diffLink = $this->message['diff'];
		} else {
			$rcidparam = $unpatrolled ? array( 'rcid' => $rc->mAttribs['rc_id'] ) : array();
			$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['diff'],
				wfArrayToCGI( array(
					'curid' => $rc->mAttribs['rc_cur_id'],
					'diff'  => $rc->mAttribs['rc_this_oldid'],
					'oldid' => $rc->mAttribs['rc_last_oldid'] ),
					$rcidparam ),
				'', '', ' tabindex="'.$rc->counter.'"');
		}
		$s .= '('.$diffLink.') (';
		# History link
		$s .= $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['hist'],
			wfArrayToCGI( array(
				'curid' => $rc->mAttribs['rc_cur_id'],
				'action' => 'history' ) ) );
		$s .= ') . . ';
	}

	protected function insertArticleLink( &$s, &$rc, $unpatrolled, $watched ) {
		global $wgContLang;
		# If it's a new article, there is no diff link, but if it hasn't been
		# patrolled yet, we need to give users a way to do so
		$params = ( $unpatrolled && $rc->mAttribs['rc_type'] == RC_NEW ) ?
			'rcid='.$rc->mAttribs['rc_id'] : '';
		if( $this->isDeleted($rc,Revision::DELETED_TEXT) ) {
			$articlelink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '', $params );
			$articlelink = '<span class="history-deleted">'.$articlelink.'</span>';
		} else {
		    $articlelink = ' '. $this->skin->makeKnownLinkObj( $rc->getTitle(), '', $params );
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

	protected function insertTimestamp( &$s, $rc ) {
		global $wgLang;
		$s .= $this->message['semicolon-separator'] . 
			$wgLang->time( $rc->mAttribs['rc_timestamp'], true, true ) . ' . . ';
	}

	/** Insert links to user page, user talk page and eventually a blocking link */
	public function insertUserRelatedLinks( &$s, &$rc ) {
		if( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
		   $s .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
		  $s .= $this->skin->userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		  $s .= $this->skin->userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		}
	}

	/** insert a formatted action */
	protected function insertAction( &$s, &$rc ) {
		if( $rc->mAttribs['rc_type'] == RC_LOG ) {
			if( $this->isDeleted( $rc, LogPage::DELETED_ACTION ) ) {
				$s .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-event' ) . '</span>';
			} else {
				$s .= ' '.LogPage::actionText( $rc->mAttribs['rc_log_type'], $rc->mAttribs['rc_log_action'],
					$rc->getTitle(), $this->skin, LogPage::extractParams( $rc->mAttribs['rc_params'] ), true, true );
			}
		}
	}

	/** insert a formatted comment */
	protected function insertComment( &$s, &$rc ) {
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
	 * @return bool
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
	 * @param RCCacheEntry $rc
	 * @param int $field one of DELETED_* bitfield constants
	 * @return bool
	 */
	public static function isDeleted( $rc, $field ) {
		return ( $rc->mAttribs['rc_deleted'] & $field ) == $field;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this revision, if it's marked as deleted.
	 * @param RCCacheEntry $rc
	 * @param int $field
	 * @return bool
	 */
	public static function userCan( $rc, $field ) {
		if( ( $rc->mAttribs['rc_deleted'] & $field ) == $field ) {
			global $wgUser;
			$permission = ( $rc->mAttribs['rc_deleted'] & Revision::DELETED_RESTRICTED ) == Revision::DELETED_RESTRICTED
				? 'suppressrevision'
				: 'deleterevision';
			wfDebug( "Checking for $permission due to $field match on {$rc->mAttribs['rc_deleted']}\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}

	protected function maybeWatchedLink( $link, $watched=false ) {
		if( $watched ) {
			return '<strong class="mw-watched">' . $link . '</strong>';
		} else {
			return '<span class="mw-rc-unwatched">' . $link . '</span>';
		}
	}
	
	/** Inserts a rollback link */
	protected function insertRollback( &$s, &$rc ) {
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

	protected function insertTags( &$s, &$rc, &$classes ) {
		if ( empty($rc->mAttribs['ts_tags']) )
			return;
			
		list($tagSummary, $newClasses) = ChangeTags::formatSummaryRow( $rc->mAttribs['ts_tags'], 'changeslist' );
		$classes = array_merge( $classes, $newClasses );
		$s .= ' ' . $tagSummary;
	}

	protected function insertExtra( &$s, &$rc, &$classes ) {
		## Empty, used for subclassers to add anything special.
	}
}


/**
 * Generate a list of changes using the good old system (no javascript)
 */
class OldChangesList extends ChangesList {
	/**
	 * Format a line using the old system (aka without any javascript).
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = NULL ) {
		global $wgContLang, $wgLang, $wgRCShowChangedSize, $wgUser;
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
			$s .= $this->recentChangesFlags( $rc->mAttribs['rc_new'], $rc->mAttribs['rc_minor'],
				$unpatrolled, '', $rc->mAttribs['rc_bot'] );
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
		
		# Mark revision as deleted if so
		if( !$rc->mAttribs['rc_log_type'] && $this->isDeleted($rc,Revision::DELETED_TEXT) ) {
		   $s .= ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
		}
		# How many users watch this page
		if( $rc->numberofWatchingusers > 0 ) {
			$s .= ' ' . wfMsgExt( 'number_of_watching_users_RCview', 
				array( 'parsemag', 'escape' ), $wgLang->formatNum( $rc->numberofWatchingusers ) );
		}

		wfRunHooks( 'OldChangesListRecentChangesLine', array(&$this, &$s, $rc) );

		wfProfileOut( __METHOD__ );
		return "$dateheader<li class=\"".implode( ' ', $classes )."\">$s</li>\n";
	}
}


/**
 * Generate a list of changes using an Enhanced system (uses javascript).
 */
class EnhancedChangesList extends ChangesList {
	/**
	*  Add the JavaScript file for enhanced changeslist
	*  @ return string
	*/
	public function beginRecentChangesList() {
		global $wgStylePath, $wgJsMimeType, $wgStyleVersion;
		$this->rc_cache = array();
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$script = Xml::tags( 'script', array(
			'type' => $wgJsMimeType,
			'src' => $wgStylePath . "/common/enhancedchanges.js?$wgStyleVersion" ), '' );
		return $script;
	}
	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 */
	public function recentChangesLine( &$baseRC, $watched = false ) {
		global $wgLang, $wgContLang, $wgUser;
		
		wfProfileIn( __METHOD__ );

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC );

		# Extract fields from DB into the function scope (rc_xxxx variables)
		// FIXME: Would be good to replace this extract() call with something
		// that explicitly initializes variables.
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# If it's a new day, add the headline and flush the cache
		$date = $wgLang->date( $rc_timestamp, true );
		$ret = '';
		if( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock();
			$this->rc_cache = array();
			$ret .= "<h4>{$date}</h4>\n";
			$this->lastdate = $date;
		}

		# Should patrol-related stuff be shown?
		if( $wgUser->useRCPatrol() ) {
		  	$rc->unpatrolled = !$rc_patrolled;
		} else {
			$rc->unpatrolled = false;
		}

		$showdifflinks = true;
		# Make article link
		// Page moves
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$msg = ( $rc_type == RC_MOVE ) ? "1movedto2" : "1movedto2_redir";
			$clink = wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			  $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
		// New unpatrolled pages
		} else if( $rc->unpatrolled && $rc_type == RC_NEW ) {
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
		// Log entries
		} else if( $rc_type == RC_LOG ) {
			if( $rc_log_type ) {
				$logtitle = SpecialPage::getTitleFor( 'Log', $rc_log_type );
				$clink = '(' . $this->skin->makeKnownLinkObj( $logtitle, 
					LogPage::logName($rc_log_type) ) . ')';
			} else {
				$clink = $this->skin->makeLinkObj( $rc->getTitle(), '' );
			}
			$watched = false;
		// Log entries (old format) and special pages
		} elseif( $rc_namespace == NS_SPECIAL ) {
			list( $specialName, $logtype ) = SpecialPage::resolveAliasWithSubpage( $rc_title );
			if ( $specialName == 'Log' ) {
				# Log updates, etc
				$logname = LogPage::logName( $logtype );
				$clink = '(' . $this->skin->makeKnownLinkObj( $rc->getTitle(), $logname ) . ')';
			} else {
				wfDebug( "Unexpected special page in recentchanges\n" );
				$clink = '';
			}
		// Edits
		} else {
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '' );
		}

		# Don't show unusable diff links
		if ( !ChangesList::userCan($rc,Revision::DELETED_TEXT) ) {
			$showdifflinks = false;
		}

		$time = $wgContLang->time( $rc_timestamp, true, true );
		$rc->watched = $watched;
		$rc->link = $clink;
		$rc->timestamp = $time;
		$rc->numberofWatchingusers = $baseRC->numberofWatchingusers;

		# Make "cur" and "diff" links
		if( $rc->unpatrolled ) {
			$rcIdQuery = "&rcid={$rc_id}";
		} else {
			$rcIdQuery = '';
		}
		$querycur = $curIdEq."&diff=0&oldid=$rc_this_oldid";
		$querydiff = $curIdEq."&diff=$rc_this_oldid&oldid=$rc_last_oldid$rcIdQuery";
		$aprops = ' tabindex="'.$baseRC->counter.'"';
		$curLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), 
			$this->message['cur'], $querycur, '' ,'', $aprops );

		# Make "diff" an "cur" links
		if( !$showdifflinks ) {
		   $curLink = $this->message['cur'];
		   $diffLink = $this->message['diff'];
		} else if( in_array( $rc_type, array(RC_NEW,RC_LOG,RC_MOVE,RC_MOVE_OVER_REDIRECT) ) ) {
			$curLink = ($rc_type != RC_NEW) ? $this->message['cur'] : $curLink;
			$diffLink = $this->message['diff'];
		} else {
			$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['diff'], 
				$querydiff, '' ,'', $aprops );
		}

		# Make "last" link
		if( !$showdifflinks || !$rc_last_oldid ) {
		    $lastLink = $this->message['last'];
		} else if( $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['last'],
			$curIdEq.'&diff='.$rc_this_oldid.'&oldid='.$rc_last_oldid . $rcIdQuery );
		}

		# Make user links
		if( $this->isDeleted($rc,Revision::DELETED_USER) ) {
		   	$rc->userlink = ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$rc->userlink = $this->skin->userLink( $rc_user, $rc_user_text );
			$rc->usertalklink = $this->skin->userToolLinks( $rc_user, $rc_user_text );
		}

		$rc->lastlink = $lastLink;
		$rc->curlink  = $curLink;
		$rc->difflink = $diffLink;

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ($this->rcMoveIndex++)] = array($rc);
		} else {
			# Logs are grouped by type
			if( $rc_type == RC_LOG ){
				$secureName = SpecialPage::getTitleFor( 'Log', $rc_log_type )->getPrefixedDBkey();
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

		$r = '<table cellpadding="0" cellspacing="0" border="0" style="background: none"><tr>';

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

		# ID for JS visibility toggle
		$jsid = $this->rcCacheIndex;
		# onclick handler to toggle hidden/expanded
		$toggleLink = "onclick='toggleVisibility($jsid); return false'";
		# Title for <a> tags
		$expandTitle = htmlspecialchars( wfMsg( 'rc-enhanced-expand' ) );
		$closeTitle = htmlspecialchars( wfMsg( 'rc-enhanced-hide' ) );

		$tl = "<span id='mw-rc-openarrow-$jsid' class='mw-changeslist-expanded' style='visibility:hidden'><a href='#' $toggleLink title='$expandTitle'>" . $this->sideArrow() . "</a></span>";
		$tl .= "<span id='mw-rc-closearrow-$jsid' class='mw-changeslist-hidden' style='display:none'><a href='#' $toggleLink title='$closeTitle'>" . $this->downArrow() . "</a></span>";
		$r .= '<td valign="top" style="white-space: nowrap"><tt>'.$tl.'&nbsp;';

		# Main line
		$r .= $this->recentChangesFlags( $isnew, false, $unpatrolled, '&nbsp;', $bot );

		# Timestamp
		$r .= '&nbsp;'.$block[0]->timestamp.'&nbsp;</tt></td><td>';

		# Article link
		if( $namehidden ) {
			$r .= ' <span class="history-deleted">' . wfMsgHtml( 'rev-deleted-event' ) . '</span>';
		} else if( $allLogs ) {
			$r .= $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$this->insertArticleLink( $r, $block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		$r .= $wgContLang->getDirMark();

		$curIdEq = 'curid=' . $curId;
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
				$r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle(),
					$nchanges[$n], $curIdEq."&diff=$currentRevision&oldid=$oldid" );
			}
		}

		# History
		if( $allLogs ) {
			// don't show history link for logs
		} else if( $namehidden || !$block[0]->getTitle()->exists() ) {
			$r .= $this->message['semicolon-separator'] . $this->message['hist'] . ')';
		} else {
			$r .= $this->message['semicolon-separator'] . $this->skin->makeKnownLinkObj( $block[0]->getTitle(),
				$this->message['hist'], $curIdEq . '&action=history' ) . ')';
		}
		$r .= ' . . ';

		# Character difference (does not apply if only log items)
		if( $wgRCShowChangedSize && !$allLogs ) {
			$last = 0;
			$first = count($block) - 1;
			# Some events (like logs) have an "empty" size, so we need to skip those...
			while( $last < $first && $block[$last]->mAttribs['rc_new_len'] === NULL ) {
				$last++;
			}
			while( $first > $last && $block[$first]->mAttribs['rc_old_len'] === NULL ) {
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

		$r .= "</td></tr></table>\n";

		# Sub-entries
		$r .= '<div id="mw-rc-subentries-'.$jsid.'" class="mw-changeslist-hidden">';
		$r .= '<table cellpadding="0" cellspacing="0"  border="0" style="background: none">';
		foreach( $block as $rcObj ) {
			# Extract fields from DB into the function scope (rc_xxxx variables)
			// FIXME: Would be good to replace this extract() call with something
			// that explicitly initializes variables.
			# Classes to apply -- TODO implement
			$classes = array();
			extract( $rcObj->mAttribs );

			#$r .= '<tr><td valign="top">'.$this->spacerArrow();
			$r .= '<tr><td valign="top">';
			$r .= '<tt>'.$this->spacerIndent() . $this->spacerIndent();
			$r .= $this->recentChangesFlags( $rc_new, $rc_minor, $rcObj->unpatrolled, '&nbsp;', $rc_bot );
			$r .= '&nbsp;</tt></td><td valign="top">';

			$o = '';
			if( $rc_this_oldid != 0 ) {
				$o = 'oldid='.$rc_this_oldid;
			}
			# Log timestamp
			if( $rc_type == RC_LOG ) {
				$link = '<tt>'.$rcObj->timestamp.'</tt> ';
			# Revision link
			} else if( !ChangesList::userCan($rcObj,Revision::DELETED_TEXT) ) {
				$link = '<span class="history-deleted"><tt>'.$rcObj->timestamp.'</tt></span> ';
			} else {
				$rcIdEq = ($rcObj->unpatrolled && $rc_type == RC_NEW) ?
					'&rcid='.$rcObj->mAttribs['rc_id'] : '';
				$link = '<tt>'.$this->skin->makeKnownLinkObj( $rcObj->getTitle(),
					$rcObj->timestamp, $curIdEq.'&'.$o.$rcIdEq ).'</tt>';
				if( $this->isDeleted($rcObj,Revision::DELETED_TEXT) )
					$link = '<span class="history-deleted">'.$link.'</span> ';
			}
			$r .= $link;

			if ( !$rc_type == RC_LOG || $rc_type == RC_NEW ) {
				$r .= ' (';
				$r .= $rcObj->curlink;
				$r .= $this->message['semicolon-separator'];
				$r .= $rcObj->lastlink;
				$r .= ')';
			}
			$r .= ' . . ';

			# Character diff
			if( $wgRCShowChangedSize ) {
				$r .= ( $rcObj->getCharacterDifference() == '' ? '' : $rcObj->getCharacterDifference() . ' . . ' ) ;
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
			
			# Mark revision as deleted
			if( !$rc_log_type && $this->isDeleted($rcObj,Revision::DELETED_TEXT) ) {
				$r .= ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
			}

			$r .= "</td></tr>\n";
		}
		$r .= "</table></div>\n";

		$this->rcCacheIndex++;

		wfProfileOut( __METHOD__ );

		return $r;
	}

	/**
	 * Generate HTML for an arrow or placeholder graphic
	 * @param string $dir one of '', 'd', 'l', 'r'
	 * @param string $alt text
	 * @param string $title text
	 * @return string HTML <img> tag
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
	 * @return string HTML <img> tag
	 */
	protected function sideArrow() {
		global $wgContLang;
		$dir = $wgContLang->isRTL() ? 'l' : 'r';
		return $this->arrow( $dir, '+', wfMsg( 'rc-enhanced-expand' ) );
	}

	/**
	 * Generate HTML for a down-facing arrow
	 * depending on language direction.
	 * @return string HTML <img> tag
	 */
	protected function downArrow() {
		return $this->arrow( 'd', '-', wfMsg( 'rc-enhanced-hide' ) );
	}

	/**
	 * Generate HTML for a spacer image
	 * @return string HTML <img> tag
	 */
	protected function spacerArrow() {
		return $this->arrow( '', codepointToUtf8( 0xa0 ) ); // non-breaking space
	}

	/**
	 * Add a set of spaces
	 * @return string HTML <td> tag
	 */
	protected function spacerIndent() {
		return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	/**
	 * Enhanced RC ungrouped line.
	 * @return string a HTML formated line (generated using $r)
	 */
	protected function recentChangesBlockLine( $rcObj ) {
		global $wgContLang, $wgRCShowChangedSize;

		wfProfileIn( __METHOD__ );

		# Extract fields from DB into the function scope (rc_xxxx variables)
		// FIXME: Would be good to replace this extract() call with something
		// that explicitly initializes variables.
		$classes = array(); // TODO implement
		extract( $rcObj->mAttribs );
		$curIdEq = "curid={$rc_cur_id}";

		$r = '<table cellspacing="0" cellpadding="0" border="0" style="background: none"><tr>';
		$r .= '<td valign="top" style="white-space: nowrap"><tt>' . $this->spacerArrow() . '&nbsp;';
		# Flag and Timestamp
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&nbsp;&nbsp;&nbsp;&nbsp;'; // 4 flags -> 4 spaces
		} else {
			$r .= $this->recentChangesFlags( $rc_type == RC_NEW, $rc_minor, $rcObj->unpatrolled, '&nbsp;', $rc_bot );
		}
		$r .= '&nbsp;'.$rcObj->timestamp.'&nbsp;</tt></td><td>';
		# Article or log link
		if( $rc_log_type ) {
			$logtitle = Title::newFromText( "Log/$rc_log_type", NS_SPECIAL );
			$logname = LogPage::logName( $rc_log_type );
			$r .= '(' . $this->skin->makeKnownLinkObj($logtitle, $logname ) . ')';
		} else {
			$this->insertArticleLink( $r, $rcObj, $rcObj->unpatrolled, $rcObj->watched );
		}
		# Diff and hist links
		if ( $rc_type != RC_LOG ) {
			$r .= ' ('. $rcObj->difflink . $this->message['semicolon-separator'];
			$r .= $this->skin->makeKnownLinkObj( $rcObj->getTitle(), $this->message['hist'], 
				$curIdEq.'&action=history' ) . ')';
		}
		$r .= ' . . ';
		# Character diff
		if( $wgRCShowChangedSize && ($cd = $rcObj->getCharacterDifference()) ) {
			$r .= "$cd . . ";
		}
		# User/talk
		$r .= ' '.$rcObj->userlink . $rcObj->usertalklink;
		# Log action (if any)
		if( $rc_log_type ) {
			if( $this->isDeleted($rcObj,LogPage::DELETED_ACTION) ) {
				$r .= ' <span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
			} else {
				$r .= ' ' . LogPage::actionText( $rc_log_type, $rc_log_action, $rcObj->getTitle(), 
					$this->skin, LogPage::extractParams($rc_params), true, true );
			}
		}
		$this->insertComment( $r, $rcObj );
		$this->insertRollback( $r, $rcObj );
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
