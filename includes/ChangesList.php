<?php
/**
 * @package MediaWiki
 * Contain class to show various lists of change:
 * - what's link here
 * - related changes
 * - recent changes
 */

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
	
	function newFromUser( &$user ) {
		$sk =& $user->getSkin();
		if ( $user->getOption('usenewrc') ) {
			return new EnhancedChangesList( $sk );
		} else {
			return new OldChangesList( $sk );
		}
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			foreach( explode(' ', 'cur diff hist minoreditletter newpageletter last blocklink' ) as $msg ) {
				$this->message[$msg] = wfMsg( $msg );
			}
		}
	}


	/**
	 * Returns the appropiate flags for new page, minor change and patrolling
	 */
	function recentChangesFlags( $new, $minor, $patrolled, $nothing = '&nbsp;' ) {
		$f = $new ? '<span class="newpage">' . wfMsgHtml( 'newpageletter' ) . '</span>'
				: $nothing;
		$f .= $minor ? '<span class="minor">' . wfMsgHtml( 'minoreditletter' ) . '</span>'
				: $nothing;
		$f .= $patrolled ? '<span class="unpatrolled">!</span>' : $nothing;
		return $f;
	}

	/**
	 * Returns text for the start of the tabular part of RC
	 */
	function beginRecentChangesList() {
		$this->rc_cache = array() ;
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0 ;
		$this->lastdate = '';
		$this->rclistOpen = false;
		return '';
	}

	/**
 	 * Returns text for the end of RC
	 * If enhanced RC is in use, returns pretty much all the text
	 */
	function endRecentChangesList() {
		$s = $this->recentChangesBlock() ;
		if( $this->rclistOpen ) {
			$s .= "</ul>\n";
		}
		return $s;
	}

	/**
	 * Enhanced RC ungrouped line.
	 * @return string a HTML formated line (generated using $r)
	 */
	function recentChangesBlockLine ( $rcObj ) {
		global $wgStylePath, $wgContLang ;

		# Get rc_xxxx variables
		extract( $rcObj->mAttribs ) ;
		$curIdEq = 'curid='.$rc_cur_id;

		$r = '' ;

		# Spacer image
		$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" border="0" />' ;

		# Flag and Timestamp
		$r .= '<tt>' ;

		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&nbsp;&nbsp;&nbsp;';
		} else {
			$r .= $this->recentChangesFlags( $rc_type == RC_NEW, $rc_minor, $rcObj->unpatrolled );
		}
		$r .= ' '.$rcObj->timestamp.' </tt>' ;

		# Article link
		$link = $rcObj->link ;
		// FIXME: should be handled with a css class
		if ( $rcObj->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		# Diff
		$r .= ' ('. $rcObj->difflink .'; ' ;

		# Hist
		$r .= $this->skin->makeKnownLinkObj( $rcObj->getTitle(), wfMsg( 'hist' ), $curIdEq.'&action=history' );

		# User/talk
		$r .= ') . . '.$rcObj->userlink . $rcObj->usertalklink ;

		# Comment
		 if ( $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$r .= $this->skin->commentBlock( $rc_comment, $rcObj->getTitle() );
		}

		if ($rcObj->numberofWatchingusers > 0) {
			$r .= wfMsg('number_of_watching_users_RCview',  $wgContLang->formatNum($rcObj->numberofWatchingusers));
		}

		$r .= "<br />\n" ;
		return $r ;
	}

	/**
	 * If enhanced RC is in use, this function takes the previously cached
	 * RC lines, arranges them, and outputs the HTML
	 */
	function recentChangesBlock () {
		global $wgStylePath ;
		if ( count ( $this->rc_cache ) == 0 ) return '' ;
		$blockOut = '';
		foreach ( $this->rc_cache AS $secureName => $block ) {
			if ( count ( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine ( array_shift ( $block ) ) ;
			} else {
				$blockOut .= $this->recentChangesBlockGroup ( $block ) ;
			}
		}

		return '<div>'.$blockOut.'</div>' ;
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
		if ( $date != $this->lastdate ) {
			if ( '' != $this->lastdate ) { $s .= "</ul>\n"; }
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
		if ( $rc->mAttribs['rc_type'] == RC_NEW || $rc->mAttribs['rc_type'] == RC_LOG ) {
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

		$s .= ' '.$articlelink;
	}

	function insertTimestamp(&$s, &$rc) {
		global $wgLang;
        # Timestamp
        $s .= '; ' . $wgLang->time( $rc->mAttribs['rc_timestamp'], true, true ) . ' . . ';
	}

	/** Insert links to user page, user talk page and eventually a blocking link */
	function insertUserRelatedLinks(&$s, &$rc) {
		$this->insertUserLink($s,$rc);
		$openParenthesis = false;

		global $wgDisableAnonTalk;
		if(!( 0 == $rc->mAttribs['rc_user'] && $wgDisableAnonTalk)) {
			$openParenthesis = true;
			$s .= ' (';
			$this->insertUserTalkLink($s,$rc);
		}

		global $wgSysopUserBans, $wgUser;
		if ( ( $wgSysopUserBans || 0 == $rc->mAttribs['rc_user'] ) && $wgUser->isAllowed('block') ) {
			$s .= $openParenthesis ? ' | ' : '(';
			$this->insertUserBlockLink($s,$rc);
		}
		$s .= $openParenthesis ? ') ' : '';
	}

	/** insert a formatted link to the user page */
	function insertUserLink(&$s, &$rc) {
		# User link (or contributions for unregistered users)
		if ( 0 == $rc->mAttribs['rc_user'] ) {
			$contribsPage =& Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$userLink = $this->skin->makeKnownLinkObj( $contribsPage,
				$rc->mAttribs['rc_user_text'], 'target=' . $rc->mAttribs['rc_user_text'] );
		} else {
			$userPage =& Title::makeTitle( NS_USER, $rc->mAttribs['rc_user_text'] );
			$userLink = $this->skin->makeLinkObj( $userPage, htmlspecialchars( $rc->mAttribs['rc_user_text'] ) );
		}
		$s .= $userLink;
	}

	/** insert a formatted link to the user talk page */
	function insertUserTalkLink(&$s, &$rc) {
		# User talk link
		global $wgContLang;
		$talkname = $wgContLang->getNsText(NS_TALK); # use the shorter name
		$userTalkPage =& Title::makeTitle( NS_USER_TALK, $rc->mAttribs['rc_user_text'] );
		$userTalkLink= $this->skin->makeLinkObj( $userTalkPage, htmlspecialchars( $talkname ) );
		$s .= $userTalkLink;
	}

	/** insert a formatted link to block an user */
	function insertUserBlockLink(&$s, &$rc) {
		# Block link
		$blockLinkPage = Title::makeTitle( NS_SPECIAL, 'Blockip' );
		$blockLink = $this->skin->makeKnownLinkObj( $blockLinkPage,
		htmlspecialchars( $this->message['blocklink'] ), 'ip=' . urlencode( $rc->mAttribs['rc_user_text'] ) );
		$s .= $blockLink;
	}

	/** insert a formatted comment */
	function insertComment(&$s, &$rc) {
		# Add comment
		if ( $rc->mAttribs['rc_type'] != RC_MOVE && $rc->mAttribs['rc_type'] != RC_MOVE_OVER_REDIRECT ) {
			$s .= $this->skin->commentBlock( $rc->mAttribs['rc_comment'], $rc->getTitle() );
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
		global $wgLang, $wgContLang, $wgUser, $wgUseRCPatrol;
		global $wgOnlySysopsCanPatrol, $wgSysopUserBans;

		$fname = 'ChangesList::recentChangesLineOld';
		wfProfileIn( $fname );


		# Extract DB fields into local scope
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# Should patrol-related stuff be shown?
		$unpatrolled = $wgUseRCPatrol && $wgUser->isLoggedIn() &&
		  ( !$wgOnlySysopsCanPatrol || $wgUser->isAllowed('patrol') ) && $rc_patrolled == 0;

		$this->insertDateHeader($s,$rc_timestamp);

		$s .= '<li>';

		// moved pages
		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$this->insertMove( $s, $rc );
		// log entries
		} elseif( $rc_namespace == NS_SPECIAL && preg_match( '!^Log/(.*)$!', $rc_title, $matches ) ) {
			$this->insertLog($s, $rc->getTitle(), $matches[1]);
		// all other stuff
		} else {
			wfProfileIn($fname.'-page');

			$this->insertDiffHist($s, $rc, $unpatrolled);

			# M, N and ! (minor, new and unpatrolled)
			$s .= ' ' . $this->recentChangesFlags( $rc_type == RC_NEW, $rc_minor, $unpatrolled, '' );
			$this->insertArticleLink($s, $rc, $unpatrolled, $watched);

			wfProfileOut($fname.'-page');
		}

		wfProfileIn( $fname.'-rest' );

		$this->insertTimestamp($s,$rc);
		$this->insertUserRelatedLinks($s,$rc);
		$this->insertComment($s, $rc);

		if ($rc->numberofWatchingusers > 0) {
			$s .= ' ' . wfMsg('number_of_watching_users_RCview',  $wgContLang->formatNum($rc->numberofWatchingusers));
		}

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
		global $wgLang, $wgContLang, $wgUser, $wgUseRCPatrol;
		global $wgOnlySysopsCanPatrol, $wgSysopUserBans;

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC ) ;

		# Extract fields from DB into the function scope (rc_xxxx variables)
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# If it's a new day, add the headline and flush the cache
		$date = $wgLang->date( $rc_timestamp, true);
		$ret = '';
		if ( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock () ;
			$this->rc_cache = array() ;
			$ret .= "<h4>{$date}</h4>\n";
			$this->lastdate = $date;
		}

		# Should patrol-related stuff be shown?
		if ( $wgUseRCPatrol && $wgUser->isLoggedIn() &&
		  ( !$wgOnlySysopsCanPatrol || $wgUser->isAllowed('patrol') )) {
		  	$rc->unpatrolled = !$rc_patrolled;
		} else {
			$rc->unpatrolled = false;
		}

		# Make article link
		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$msg = ( $rc_type == RC_MOVE ) ? "1movedto2" : "1movedto2_redir";
			$clink = wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			  $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
		} elseif( $rc_namespace == NS_SPECIAL && preg_match( '!^Log/(.*)$!', $rc_title, $matches ) ) {
			# Log updates, etc
			$logtype = $matches[1];
			$logname = LogPage::logName( $logtype );
			$clink = '(' . $this->skin->makeKnownLinkObj( $rc->getTitle(), $logname ) . ')';
		} elseif ( $rc->unpatrolled && $rc_type == RC_NEW ) {
			# Unpatrolled new page, give rc_id in query
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
		} else {
			$clink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '' ) ;
		}

		$time = $wgContLang->time( $rc_timestamp, true, true );
		$rc->watched = $watched ;
		$rc->link = $clink ;
		$rc->timestamp = $time;
		$rc->numberofWatchingusers = $baseRC->numberofWatchingusers;

		# Make "cur" and "diff" links
		$titleObj = $rc->getTitle();
		if ( $rc->unpatrolled ) {
			$rcIdQuery = "&rcid={$rc_id}";
		} else {
			$rcIdQuery = '';
		}
		$querycur = $curIdEq."&diff=0&oldid=$rc_this_oldid";
		$querydiff = $curIdEq."&diff=$rc_this_oldid&oldid=$rc_last_oldid";
		$aprops = ' tabindex="'.$baseRC->counter.'"';
		$curLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['cur'], $querycur, '' ,'' , $aprops );
		if( $rc_type == RC_NEW || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			if( $rc_type != RC_NEW ) {
				$curLink = $this->message['cur'];
			}
			$diffLink = $this->message['diff'];
		} else {
			$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['diff'], $querydiff, '' ,'' , $aprops );
		}

		# Make "last" link
		if ( $rc_last_oldid == 0 || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $this->message['last'],
			  $curIdEq.'&diff='.$rc_this_oldid.'&oldid='.$rc_last_oldid . $rcIdQuery );
		}

		# Make user link (or user contributions for unregistered users)
		if ( $rc_user == 0 ) {
			$contribsPage =& Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$userLink = $this->skin->makeKnownLinkObj( $contribsPage,
				$rc_user_text, 'target=' . $rc_user_text );
		} else {
			$userPage =& Title::makeTitle( NS_USER, $rc_user_text );
			$userLink = $this->skin->makeLinkObj( $userPage, $rc_user_text );
		}

		$rc->userlink = $userLink;
		$rc->lastlink = $lastLink;
		$rc->curlink  = $curLink;
		$rc->difflink = $diffLink;

		# Make user talk link
		$talkname = $wgContLang->getNsText( NS_TALK ); # use the shorter name
		$userTalkPage =& Title::makeTitle( NS_USER_TALK, $rc_user_text );
		$userTalkLink = $this->skin->makeLinkObj( $userTalkPage, $talkname );

		global $wgDisableAnonTalk;
		if ( ( $wgSysopUserBans || 0 == $rc_user ) && $wgUser->isAllowed('block') ) {
			$blockPage =& Title::makeTitle( NS_SPECIAL, 'Blockip' );
			$blockLink = $this->skin->makeKnownLinkObj( $blockPage,
				$this->message['blocklink'], 'ip='.$rc_user_text );
			if( $wgDisableAnonTalk )
				$rc->usertalklink = ' ('.$blockLink.')';
			else
				$rc->usertalklink = ' ('.$userTalkLink.' | '.$blockLink.')';
		} else {
			if( $wgDisableAnonTalk && ($rc_user == 0) )
				$rc->usertalklink = '';
			else
				$rc->usertalklink = ' ('.$userTalkLink.')';
		}

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ($this->rcMoveIndex++)] = array($rc);
		} else {
			if ( !isset ( $this->rc_cache[$secureName] ) ) $this->rc_cache[$secureName] = array() ;
			array_push ( $this->rc_cache[$secureName] , $rc ) ;
		}
		return $ret;
	}

	/**
	 * Enhanced RC group
	 */
	function recentChangesBlockGroup ( $block ) {
		global $wgStylePath, $wgContLang ;

		$r = '';

		# Collate list of users
		$isnew = false ;
		$unpatrolled = false;
		$userlinks = array () ;
		foreach ( $block AS $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			$newid = $rcObj->mAttribs['rc_this_oldid'];
			if ( $rcObj->mAttribs['rc_new'] ) {
				$isnew = true ;
			}
			$u = $rcObj->userlink ;
			if ( !isset ( $userlinks[$u] ) ) {
				$userlinks[$u] = 0 ;
			}
			if ( $rcObj->unpatrolled ) {
				$unpatrolled = true;
			}
			$userlinks[$u]++ ;
		}

		# Sort the list and convert to text
		krsort ( $userlinks ) ;
		asort ( $userlinks ) ;
		$users = array () ;
		foreach ( $userlinks as $userlink => $count) {
			$text = $userlink;
			if ( $count > 1 ) $text .= ' ('.$count.'&times;)' ;
			array_push ( $users , $text ) ;
		}

		$users = ' <span class="changedby">['.implode('; ',$users).']</span>';

		# Arrow
		$rci = 'RCI'.$this->rcCacheIndex ;
		$rcl = 'RCL'.$this->rcCacheIndex ;
		$rcm = 'RCM'.$this->rcCacheIndex ;
		$toggleLink = "javascript:toggleVisibility('$rci','$rcm','$rcl')" ;
		$arrowdir = $wgContLang->isRTL() ? 'l' : 'r';
		$tl  = '<span id="'.$rcm.'"><a href="'.$toggleLink.'"><img src="'.$wgStylePath.'/common/images/Arr_'.$arrowdir.'.png" width="12" height="12" alt="+" /></a></span>' ;
		$tl .= '<span id="'.$rcl.'" style="display:none"><a href="'.$toggleLink.'"><img src="'.$wgStylePath.'/common/images/Arr_d.png" width="12" height="12" alt="-" /></a></span>' ;
		$r .= $tl ;

		# Main line
		$r .= '<tt>' ;
		$r .= $this->recentChangesFlags( $isnew, false, $unpatrolled );

		# Timestamp
		$r .= ' '.$block[0]->timestamp.' ' ;
		$r .= '</tt>' ;

		# Article link
		$link = $block[0]->link ;
		if ( $block[0]->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		$curIdEq = 'curid=' . $block[0]->mAttribs['rc_cur_id'];
		$currentRevision = $block[0]->mAttribs['rc_this_oldid'];
		if ( $block[0]->mAttribs['rc_type'] != RC_LOG ) {
			# Changes
			$r .= ' ('.count($block).' ' ;
			if ( $isnew ) $r .= wfMsg('changes');
			else $r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle() , wfMsg('changes') ,
				$curIdEq."&diff=$currentRevision&oldid=$oldid" ) ;
			$r .= '; ' ;

			# History
			$r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle(), wfMsg( 'history' ), $curIdEq.'&action=history' );
			$r .= ')' ;
		}

		$r .= $users ;

		if ($block[0]->numberofWatchingusers > 0) {
			$r .= wfMsg('number_of_watching_users_RCview',  $wgContLang->formatNum($block[0]->numberofWatchingusers));
		}
		$r .= "<br />\n" ;

		# Sub-entries
		$r .= '<div id="'.$rci.'" style="display:none">' ;
		foreach ( $block AS $rcObj ) {
			# Get rc_xxxx variables
			extract( $rcObj->mAttribs );

			$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" />';
			$r .= '<tt>&nbsp; &nbsp; &nbsp; &nbsp;' ;
			$r .= $this->recentChangesFlags( $rc_new, $rc_minor, $rcObj->unpatrolled );
			$r .= '&nbsp;</tt>' ;

			$o = '' ;
			if ( $rc_this_oldid != 0 ) {
				$o = 'oldid='.$rc_this_oldid ;
			}
			if ( $rc_type == RC_LOG ) {
				$link = $rcObj->timestamp;
			} else {
				$link = $this->skin->makeKnownLinkObj( $rcObj->getTitle(), $rcObj->timestamp , $curIdEq.'&'.$o );
			}
			$link = '<tt>'.$link.'</tt>' ;

			$r .= $link ;
			$r .= ' (' ;
			$r .= $rcObj->curlink ;
			$r .= '; ' ;
			$r .= $rcObj->lastlink ;
			$r .= ') . . '.$rcObj->userlink ;
			$r .= $rcObj->usertalklink ;
			$r .= $this->skin->commentBlock( $rc_comment, $rcObj->getTitle() );
			$r .= "<br />\n" ;
		}
		$r .= "</div>\n" ;

		$this->rcCacheIndex++ ;
		return $r ;
	}
}
?>
