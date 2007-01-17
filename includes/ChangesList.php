<?php
/**
 * @package MediaWiki
 * Contain class to show various lists of change:
 * - what's link here
 * - related changes
 * - recent changes
 */

/**
 * @todo document
 * @package MediaWiki
 */
class RCCacheEntry extends RecentChange
{
	var $secureName, $link;
	var $curlink , $difflink, $lastlink , $usertalklink , $versionlink ;
	var $userlink, $timestamp, $watched;

	function newFromParent( $rc )
	{
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		return $rc2;
	}
} ;

/**
 * @package MediaWiki
 */
class ChangesList {
	# Called by history lists and recent changes
	#

	/** @todo document */
	function ChangesList( &$skin ) {
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
		$sk =& $user->getSkin();
		$list = NULL;
		if( wfRunHooks( 'FetchChangesList', array( &$user, &$sk, &$list ) ) ) {
			return $user->getOption( 'usenewrc' ) ? new EnhancedChangesList( $sk ) : new OldChangesList( $sk );
		} else {
			return $list;
		}
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			foreach( explode(' ', 'cur diff hist minoreditletter newpageletter last '.
				'blocklink changes history boteditletter' ) as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escape') );
			}
		}
	}


	/**
	 * Returns the appropriate flags for new page, minor change and patrolling
	 */
	function recentChangesFlags( $new, $minor, $patrolled, $nothing = '&nbsp;', $bot = false ) {
		$f = $new ? '<span class="newpage">' . $this->message['newpageletter'] . '</span>'
				: $nothing;
		$f .= $minor ? '<span class="minor">' . $this->message['minoreditletter'] . '</span>'
				: $nothing;
		$f .= $bot ? '<span class="bot">' . $this->message['boteditletter'] . '</span>' : $nothing;
		$f .= $patrolled ? '<span class="unpatrolled">!</span>' : $nothing;
		return $f;
	}

	/**
	 * Returns text for the start of the tabular part of RC
	 */
	function beginRecentChangesList() {
		$this->rc_cache = array();
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		return '';
	}

	/**
 	 * Returns text for the end of RC
	 */
	function endRecentChangesList() {
		if( $this->rclistOpen ) {
			return "</ul>\n";
		} else {
			return '';
		}
	}


	function insertMove( &$s, $rc ) {
		# Diff
		$s .= '(' . $this->message['diff'] . ') (';
		# Hist
		$s .= $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), $this->message['hist'], 'action=history' ) .
			') . . ';

		# "[[x]] moved to [[y]]"
		$msg = ( $rc->mAttribs['rc_type'] == RC_MOVE ) ? '1movedto2' : '1movedto2_redir';
		$s .= wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			$this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
	}

	function insertDateHeader(&$s, $rc_timestamp) {
		global $wgLang;

		# Make date header if necessary
		$date = $wgLang->date( $rc_timestamp, true, true );
		$s = '';
		if( $date != $this->lastdate ) {
			if( '' != $this->lastdate ) {
				$s .= "</ul>\n";
			}
			$s .= '<h4>'.$date."</h4>\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}
	}

	function insertLog(&$s, $title, $logtype) {
		$logname = LogPage::logName( $logtype );
		$s .= '(' . $this->skin->makeKnownLinkObj($title, $logname ) . ')';
	}


	function insertDiffHist(&$s, &$rc, $unpatrolled) {
		# Diff link
		if( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
			$diffLink = $this->message['diff'];
		} else {
			$rcidparam = $unpatrolled
				? array( 'rcid' => $rc->mAttribs['rc_id'] )
				: array();
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

	function insertArticleLink(&$s, &$rc, $unpatrolled, $watched) {
		# Article link
		# If it's a new article, there is no diff link, but if it hasn't been
		# patrolled yet, we need to give users a way to do so
		$params = ( $unpatrolled && $rc->mAttribs['rc_type'] == RC_NEW )
			? 'rcid='.$rc->mAttribs['rc_id']
			: '';
		$articlelink = ' '. $this->skin->makeKnownLinkObj( $rc->getTitle(), '', $params );
		if($watched) $articlelink = '<strong>'.$articlelink.'</strong>';
		global $wgContLang;
		$articlelink .= $wgContLang->getDirMark();

		$s .= ' '.$articlelink;
	}

	function insertTimestamp(&$s, $rc) {
		global $wgLang;
		# Timestamp
		$s .= '; ' . $wgLang->time( $rc->mAttribs['rc_timestamp'], true, true ) . ' . . ';
	}

	/** Insert links to user page, user talk page and eventually a blocking link */
	function insertUserRelatedLinks(&$s, &$rc) {
		$s .= $this->skin->userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		$s .= $this->skin->userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
	}

	/** insert a formatted comment */
	function insertComment(&$s, &$rc) {
		# Add comment
		if( $rc->mAttribs['rc_type'] != RC_MOVE && $rc->mAttribs['rc_type'] != RC_MOVE_OVER_REDIRECT ) {
			$s .= $this->skin->commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
		}
	}

	/**
	 * Check whether to enable recent changes patrol features
	 * @return bool
	 */
	function usePatrol() {
		global $wgUseRCPatrol, $wgUser;
		return( $wgUseRCPatrol && $wgUser->isAllowed( 'patrol' ) );
	}

	/**
	 * Returns the string which indicates the number of watching users
	 */
	function numberofWatchingusers( $count ) {
		global $wgLang;
		static $cache = array();
		if ( $count > 0 ) {
			if ( !isset( $cache[$count] ) ) {
				$cache[$count] = wfMsgExt('number_of_watching_users_RCview',
					array('parsemag', 'escape'), $wgLang->formatNum($count));
			}
			return $cache[$count];
		} else {
			return '';
		}
	}
}


/**
 * Generate a list of changes using the good old system (no javascript)
 */
class OldChangesList extends ChangesList {
	/**
	 * Format a line using the old system (aka without any javascript).
	 */
	function recentChangesLine( &$rc, $watched = false ) {
		global $wgContLang, $wgRCShowChangedSize;

		$fname = 'ChangesList::recentChangesLineOld';
		wfProfileIn( $fname );

		# Extract DB fields into local scope
		// FIXME: Would be good to replace this extract() call with something that explicitly initializes local variables.
		extract( $rc->mAttribs );

		# Should patrol-related stuff be shown?
		$unpatrolled = $this->usePatrol() && $rc_patrolled == 0;

		$this->insertDateHeader($s,$rc_timestamp);

		$s .= '<li>';

		// moved pages
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$this->insertMove( $s, $rc );
		// log entries
		} elseif ( $rc_namespace == NS_SPECIAL ) {
			list( $specialName, $specialSubpage ) = SpecialPage::resolveAliasWithSubpage( $rc_title );
			if ( $specialName == 'Log' ) {
				$this->insertLog( $s, $rc->getTitle(), $specialSubpage );
			} else {
				wfDebug( "Unexpected special page in recentchanges\n" );
			}
		// all other stuff
		} else {
			wfProfileIn($fname.'-page');

			$this->insertDiffHist($s, $rc, $unpatrolled);

			# M, N, b and ! (minor, new, bot and unpatrolled)
			$s .= ' ' . $this->recentChangesFlags( $rc_type == RC_NEW, $rc_minor, $unpatrolled, '', $rc_bot );
			$this->insertArticleLink($s, $rc, $unpatrolled, $watched);

			wfProfileOut($fname.'-page');
		}

		wfProfileIn( $fname.'-rest' );

		$this->insertTimestamp($s,$rc);

		if( $wgRCShowChangedSize ) {
			$s .= ( $rc->getCharacterDifference() == '' ? '' : $rc->getCharacterDifference() . ' . . ' );
		}

		$this->insertUserRelatedLinks($s,$rc);
		$this->insertComment($s, $rc);

		$s .=  rtrim(' ' . $this->numberofWatchingusers($rc->numberofWatchingusers));

		$s .= "</li>\n";

		wfProfileOut( $fname.'-rest' );

		wfProfileOut( $fname );
		return $s;
	}
}


/**
 * Generate a list of changes using an Enhanced system (use javascript).
 */
class EnhancedChangesList extends ChangesList {
	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 */
	function recentChangesLine( &$baseRC, $watched = false ) {
		global $wgLang, $wgContLang;

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC );

		# Extract fields from DB into the function scope (rc_xxxx variables)
		// FIXME: Would be good to replace this extract() call with something that explicitly initializes local variables.
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# If it's a new day, add the headline and flush the cache
		$date = $wgLang->date( $rc_timestamp, true);
		$ret = '';
		if( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock();
			$this->rc_cache = array();
			$ret .= "<h4>{$date}</h4>\n";
			$this->lastdate = $date;
		}

		# Should patrol-related stuff be shown?
		if( $this->usePatrol() ) {
		  	$rc->unpatrolled = !$rc_patrolled;
		} else {
			$rc->unpatrolled = false;
		}

		# Make article link
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$msg = ( $rc_type == RC_MOVE ) ? "1movedto2" : "1movedto2_redir";
			$clink = wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			  $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
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
		} elseif( $rc->unpatrolled && $rc_type == RC_NEW ) {
			# Unpatrolled new page, give rc_id in query
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
		} else {
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '' );
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
		$curLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['cur'], $querycur, '' ,'', $aprops );
		if( $rc_type == RC_NEW || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			if( $rc_type != RC_NEW ) {
				$curLink = $this->message['cur'];
			}
			$diffLink = $this->message['diff'];
		} else {
			$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['diff'], $querydiff, '' ,'', $aprops );
		}

		# Make "last" link
		if( $rc_last_oldid == 0 || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['last'],
			  $curIdEq.'&diff='.$rc_this_oldid.'&oldid='.$rc_last_oldid . $rcIdQuery );
		}

		$rc->userlink = $this->skin->userLink( $rc_user, $rc_user_text );

		$rc->lastlink = $lastLink;
		$rc->curlink  = $curLink;
		$rc->difflink = $diffLink;

		$rc->usertalklink = $this->skin->userToolLinks( $rc_user, $rc_user_text );

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ($this->rcMoveIndex++)] = array($rc);
		} else {
			if( !isset ( $this->rc_cache[$secureName] ) ) {
				$this->rc_cache[$secureName] = array();
			}
			array_push( $this->rc_cache[$secureName], $rc );
		}
		return $ret;
	}

	/**
	 * Enhanced RC group
	 */
	function recentChangesBlockGroup( $block ) {
		global $wgContLang, $wgRCShowChangedSize;
		$r = '';

		# Collate list of users
		$isnew = false;
		$unpatrolled = false;
		$userlinks = array();
		foreach( $block as $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			if( $rcObj->mAttribs['rc_new'] ) {
				$isnew = true;
			}
			$u = $rcObj->userlink;
			if( !isset( $userlinks[$u] ) ) {
				$userlinks[$u] = 0;
			}
			if( $rcObj->unpatrolled ) {
				$unpatrolled = true;
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
				$text .= ' ('.$count.'&times;)';
			}
			array_push( $users, $text );
		}

		$users = ' <span class="changedby">['.implode('; ',$users).']</span>';

		# Arrow
		$rci = 'RCI'.$this->rcCacheIndex;
		$rcl = 'RCL'.$this->rcCacheIndex;
		$rcm = 'RCM'.$this->rcCacheIndex;
		$toggleLink = "javascript:toggleVisibility('$rci','$rcm','$rcl')";
		$tl  = '<span id="'.$rcm.'"><a href="'.$toggleLink.'">' . $this->sideArrow() . '</a></span>';
		$tl .= '<span id="'.$rcl.'" style="display:none"><a href="'.$toggleLink.'">' . $this->downArrow() . '</a></span>';
		$r .= $tl;

		# Main line
		$r .= '<tt>';
		$r .= $this->recentChangesFlags( $isnew, false, $unpatrolled, '&nbsp;', $bot );

		# Timestamp
		$r .= ' '.$block[0]->timestamp.' </tt>';

		# Article link
		$r .= $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		$r .= $wgContLang->getDirMark();

		$curIdEq = 'curid=' . $block[0]->mAttribs['rc_cur_id'];
		$currentRevision = $block[0]->mAttribs['rc_this_oldid'];
		if( $block[0]->mAttribs['rc_type'] != RC_LOG ) {
			# Changes
			$r .= ' ('.count($block).' ';

			if( $isnew ) {
				$r .= $this->message['changes'];
			} else {
				$r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle(),
					$this->message['changes'], $curIdEq."&diff=$currentRevision&oldid=$oldid" );
			}

			$r .= ') . . ';

			# Character difference
			$chardiff = $rcObj->getCharacterDifference( $block[ count( $block ) - 1 ]->mAttribs['rc_old_len'],
					$block[0]->mAttribs['rc_new_len'] );
			if( $chardiff == '' ) {
				$r .= ' (';
			} else {
				$r .= ' ' . $chardiff. ' . . (';
			}
			

			# History
			$r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle(),
				$this->message['history'], $curIdEq.'&action=history' );
			$r .= ')';
		}

		$r .= $users;

		$r .= $this->numberofWatchingusers($block[0]->numberofWatchingusers);
		$r .= "<br />\n";

		# Sub-entries
		$r .= '<div id="'.$rci.'" style="display:none">';
		foreach( $block as $rcObj ) {
			# Get rc_xxxx variables
			// FIXME: Would be good to replace this extract() call with something that explicitly initializes local variables.
			extract( $rcObj->mAttribs );

			$r .= $this->spacerArrow();
			$r .= '<tt>&nbsp; &nbsp; &nbsp; &nbsp;';
			$r .= $this->recentChangesFlags( $rc_new, $rc_minor, $rcObj->unpatrolled, '&nbsp;', $rc_bot );
			$r .= '&nbsp;</tt>';

			$o = '';
			if( $rc_this_oldid != 0 ) {
				$o = 'oldid='.$rc_this_oldid;
			}
			if( $rc_type == RC_LOG ) {
				$link = $rcObj->timestamp;
			} else {
				$link = $this->skin->makeKnownLinkObj( $rcObj->getTitle(), $rcObj->timestamp, $curIdEq.'&'.$o );
			}
			$link = '<tt>'.$link.'</tt>';

			$r .= $link;
			$r .= ' (';
			$r .= $rcObj->curlink;
			$r .= '; ';
			$r .= $rcObj->lastlink;
			$r .= ') . . ';

			# Character diff
			if( $wgRCShowChangedSize ) {
				$r .= ( $rcObj->getCharacterDifference() == '' ? '' : $rcObj->getCharacterDifference() . ' . . ' ) ;
			}

			$r .= $rcObj->userlink;
			$r .= $rcObj->usertalklink;
			$r .= $this->skin->commentBlock( $rc_comment, $rcObj->getTitle() );
			$r .= "<br />\n";
		}
		$r .= "</div>\n";

		$this->rcCacheIndex++;
		return $r;
	}

	function maybeWatchedLink( $link, $watched=false ) {
		if( $watched ) {
			// FIXME: css style might be more appropriate
			return '<strong>' . $link . '</strong>';
		} else {
			return $link;
		}
	}

	/**
	 * Generate HTML for an arrow or placeholder graphic
	 * @param string $dir one of '', 'd', 'l', 'r'
	 * @param string $alt text
	 * @return string HTML <img> tag
	 * @access private
	 */
	function arrow( $dir, $alt='' ) {
		global $wgStylePath;
		$encUrl = htmlspecialchars( $wgStylePath . '/common/images/Arr_' . $dir . '.png' );
		$encAlt = htmlspecialchars( $alt );
		return "<img src=\"$encUrl\" width=\"12\" height=\"12\" alt=\"$encAlt\" />";
	}

	/**
	 * Generate HTML for a right- or left-facing arrow,
	 * depending on language direction.
	 * @return string HTML <img> tag
	 * @access private
	 */
	function sideArrow() {
		global $wgContLang;
		$dir = $wgContLang->isRTL() ? 'l' : 'r';
		return $this->arrow( $dir, '+' );
	}

	/**
	 * Generate HTML for a down-facing arrow
	 * depending on language direction.
	 * @return string HTML <img> tag
	 * @access private
	 */
	function downArrow() {
		return $this->arrow( 'd', '-' );
	}

	/**
	 * Generate HTML for a spacer image
	 * @return string HTML <img> tag
	 * @access private
	 */
	function spacerArrow() {
		return $this->arrow( '', ' ' );
	}

	/**
	 * Enhanced RC ungrouped line.
	 * @return string a HTML formated line (generated using $r)
	 */
	function recentChangesBlockLine( $rcObj ) {
		global $wgContLang, $wgRCShowChangedSize;

		# Get rc_xxxx variables
		// FIXME: Would be good to replace this extract() call with something that explicitly initializes local variables.
		extract( $rcObj->mAttribs );
		$curIdEq = 'curid='.$rc_cur_id;

		$r = '';

		# Spacer image
		$r .= $this->spacerArrow();

		# Flag and Timestamp
		$r .= '<tt>';

		if( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&nbsp;&nbsp;&nbsp;';
		} else {
			$r .= $this->recentChangesFlags( $rc_type == RC_NEW, $rc_minor, $rcObj->unpatrolled, '&nbsp;', $rc_bot );
		}
		$r .= ' '.$rcObj->timestamp.' </tt>';

		# Article link
		$r .= $this->maybeWatchedLink( $rcObj->link, $rcObj->watched );

		# Diff
		$r .= ' ('. $rcObj->difflink .'; ';

		# Hist
		$r .= $this->skin->makeKnownLinkObj( $rcObj->getTitle(), wfMsg( 'hist' ), $curIdEq.'&action=history' ) . ') . . ';

		# Character diff
		if( $wgRCShowChangedSize ) {
			$r .= ( $rcObj->getCharacterDifference() == '' ? '' : '&nbsp;' . $rcObj->getCharacterDifference() . ' . . ' ) ;
		}

		# User/talk
		$r .= $rcObj->userlink . $rcObj->usertalklink;

		# Comment
		if( $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$r .= $this->skin->commentBlock( $rc_comment, $rcObj->getTitle() );
		}

		$r .= $this->numberofWatchingusers($rcObj->numberofWatchingusers);

		$r .= "<br />\n";
		return $r;
	}

	/**
	 * If enhanced RC is in use, this function takes the previously cached
	 * RC lines, arranges them, and outputs the HTML
	 */
	function recentChangesBlock() {
		if( count ( $this->rc_cache ) == 0 ) {
			return '';
		}
		$blockOut = '';
		foreach( $this->rc_cache as $block ) {
			if( count( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine( array_shift( $block ) );
			} else {
				$blockOut .= $this->recentChangesBlockGroup( $block );
			}
		}

		return '<div>'.$blockOut.'</div>';
	}

	/**
 	 * Returns text for the end of RC
	 * If enhanced RC is in use, returns pretty much all the text
	 */
	function endRecentChangesList() {
		return $this->recentChangesBlock() . parent::endRecentChangesList();
	}

}
?>
