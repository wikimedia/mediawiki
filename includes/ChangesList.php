<?php

class ChangesList {
	# Called by history lists and recent changes
	#

	function ChangesList( &$skin ) {
		$this->skin =& $skin;
	}
	
	# Returns text for the start of the tabular part of RC
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
	 * Enhanced RC ungrouped line
	 */
	function recentChangesBlockLine ( $rcObj ) {
		global $wgStylePath, $wgContLang ;

		# Get rc_xxxx variables
		extract( $rcObj->mAttribs ) ;
		$curIdEq = 'curid='.$rc_cur_id;

		# Spacer image
		$r = '' ;

		$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" border="0" />' ;
		$r .= '<tt>' ;

		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&nbsp;&nbsp;';
		} else {
			# M, N and !
			$M = wfMsg( 'minoreditletter' );
			$N = wfMsg( 'newpageletter' );

			if ( $rc_type == RC_NEW ) {
				$r .= '<span class="newpage">' . htmlspecialchars( $N ) . '</span>';
			} else {
				$r .= '&nbsp;' ;
			}
			if ( $rc_minor ) {
				$r .= '<span class="minor">' . htmlspecialchars( $M ) . '</span>';
			} else {
				$r .= '&nbsp;' ;
			}
			if ( $rcObj->unpatrolled ) {
				$r .= '<span class="unpatrolled">!</span>';
			} else {
				$r .= '&nbsp;';
			}
		}

		# Timestamp
		$r .= ' '.$rcObj->timestamp.' ' ;
		$r .= '</tt>' ;

		# Article link
		$link = $rcObj->link ;
		if ( $rcObj->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		# Diff
		$r .= ' (' ;
		$r .= $rcObj->difflink ;
		$r .= '; ' ;

		# Hist
		$r .= $this->skin->makeKnownLinkObj( $rcObj->getTitle(), wfMsg( 'hist' ), $curIdEq.'&action=history' );

		# User/talk
		$r .= ') . . '.$rcObj->userlink ;
		$r .= $rcObj->usertalklink ;

		# Comment
		 if ( $rc_comment != '' && $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$rc_comment=$this->skin->formatComment($rc_comment, $rcObj->getTitle());
			$r .= $wgContLang->emphasize( ' ('.$rc_comment.')' );
		}

		$r .= "<br />\n" ;
		return $r ;
	}

	/**
	 * Enhanced RC group
	 */
	function recentChangesBlockGroup ( $block ) {
		global $wgStylePath, $wgContLang ;

		$r = '' ;
		$M = wfMsg( 'minoreditletter' );
		$N = wfMsg( 'newpageletter' );

		# Collate list of users
		$isnew = false ;
		$unpatrolled = false;
		$userlinks = array () ;
		foreach ( $block AS $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
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
			$text = $userlink ;
			if ( $count > 1 ) $text .= " ({$count}&times;)" ;
			array_push ( $users , $text ) ;
		}
		$users = ' <font size="-1">['.implode('; ',$users).']</font>' ;

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
		# M/N
		$r .= '<tt>' ;
		if ( $isnew ) {
			$r .= '<span class="newpage">' . htmlspecialchars( $N ) . '</span>';
		} else {
			$r .= '&nbsp;';
		}
		$r .= '&nbsp;'; # Minor
		if ( $unpatrolled ) {
			$r .= '<span class="unpatrolled">!</span>';
		} else {
			$r .= "&nbsp;";
		}

		# Timestamp
		$r .= ' '.$block[0]->timestamp.' ' ;
		$r .= '</tt>' ;

		# Article link
		$link = $block[0]->link ;
		if ( $block[0]->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		$curIdEq = 'curid=' . $block[0]->mAttribs['rc_cur_id'];
		if ( $block[0]->mAttribs['rc_type'] != RC_LOG ) {
			# Changes
			$r .= ' ('.count($block).' ' ;
			if ( $isnew ) $r .= wfMsg('changes');
			else $r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle() , wfMsg('changes') ,
				$curIdEq.'&diff=0&oldid='.$oldid ) ;
			$r .= '; ' ;

			# History
			$r .= $this->skin->makeKnownLinkObj( $block[0]->getTitle(), wfMsg( 'history' ), $curIdEq.'&action=history' );
			$r .= ')' ;
		}

		$r .= $users ;
		$r .= "<br />\n" ;

		# Sub-entries
		$r .= '<div id="'.$rci.'" style="display:none">' ;
		foreach ( $block AS $rcObj ) {
			# Get rc_xxxx variables
			extract( $rcObj->mAttribs );

			$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" />';
			$r .= '<tt>&nbsp; &nbsp; &nbsp; &nbsp;' ;
			if ( $rc_new ) {
				$r .= '<span class="newpage">' . htmlspecialchars( $N ) . '</span>';
			} else {
				$r .= '&nbsp;' ;
			}

			if ( $rc_minor ) {
				$r .= '<span class="minoredit">' . htmlspecialchars( $M ) . '</span>';
			} else {
				$r .= '&nbsp;' ;
			}

			if ( $rcObj->unpatrolled ) {
				$r .= '<span class="unpatrolled">!</span>';
			} else {
				$r .= "&nbsp;";
			}

			$r .= '&nbsp;</tt>' ;

			$o = '' ;
			if ( $rc_last_oldid != 0 ) {
				$o = 'oldid='.$rc_last_oldid ;
			}
			if ( $rc_type == RC_LOG ) {
				$link = $rcObj->timestamp ;
			} else {
				$link = $this->skin->makeKnownLinkObj( $rcObj->getTitle(), $rcObj->timestamp , "{$curIdEq}&$o" ) ;
			}
			$link = '<tt>'.$link.'</tt>' ;

			$r .= $link ;
			$r .= ' (' ;
			$r .= $rcObj->curlink ;
			$r .= '; ' ;
			$r .= $rcObj->lastlink ;
			$r .= ') . . '.$rcObj->userlink ;
			$r .= $rcObj->usertalklink ;
			if ( $rc_comment != '' ) {
				$rc_comment=$this->skin->formatComment($rc_comment, $rcObj->getTitle());
				$r .= $wgContLang->emphasize( ' ('.$rc_comment.')' ) ;
			}
			$r .= "<br />\n" ;
		}
		$r .= "</div>\n" ;

		$this->rcCacheIndex++ ;
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

	/**
	 * Called in a loop over all displayed RC entries
	 * Either returns the line, or caches it for later use
	 */
	function recentChangesLine( &$rc, $watched = false ) {
		global $wgUser ;
		$usenew = $wgUser->getOption( 'usenewrc' );
		if ( $usenew )
			$line = $this->recentChangesLineNew ( $rc, $watched ) ;
		else
			$line = $this->recentChangesLineOld ( $rc, $watched ) ;
		return $line ;
	}

	function recentChangesLineOld( &$rc, $watched = false ) {
		$fname = 'Skin::recentChangesLineOld';
		wfProfileIn( $fname );
		
		global $wgTitle, $wgLang, $wgContLang, $wgUser, $wgRCSeconds, $wgUseRCPatrol, $wgOnlySysopsCanPatrol;

		static $message;
		if( !isset( $message ) ) {
			foreach( explode(' ', 'diff hist minoreditletter newpageletter blocklink' ) as $msg ) {
				$message[$msg] = wfMsg( $msg );
			}
		}
		
		# Extract DB fields into local scope
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# Should patrol-related stuff be shown?
		$unpatrolled = $wgUseRCPatrol && $wgUser->getID() != 0 && 
		  ( !$wgOnlySysopsCanPatrol || $wgUser->isAllowed('patrol') ) && $rc_patrolled == 0;
		
		# Make date header if necessary
		$date = $wgLang->date( $rc_timestamp, true);
		$s = '';
		if ( $date != $this->lastdate ) {
			if ( '' != $this->lastdate ) { $s .= "</ul>\n"; }
			$s .= "<h4>{$date}</h4>\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}

		$s .= '<li>';

		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Diff
			$s .= '(' . $message['diff'] . ') (';
			# Hist
			$s .= $this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), $message['hist'], 'action=history' ) .
				') . . ';

			# "[[x]] moved to [[y]]"
			$msg = ( $rc_type == RC_MOVE ) ? '1movedto2' : '1movedto2_redir';
			$s .= wfMsg( $msg, $this->skin->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
				$this->skin->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
		} elseif( $rc_namespace == NS_SPECIAL && preg_match( '!^Log/(.*)$!', $rc_title, $matches ) ) {
			# Log updates, etc
			$logtype = $matches[1];
			$logname = LogPage::logName( $logtype );
			$s .= '(' . $this->skin->makeKnownLinkObj( $rc->getTitle(), $logname ) . ')';
		} else {
			wfProfileIn("$fname-page");
			# Diff link
			if ( $rc_type == RC_NEW || $rc_type == RC_LOG ) {
				$diffLink = $message['diff'];
			} else {
				if ( $unpatrolled )
					$rcidparam = "&rcid={$rc_id}";
				else
					$rcidparam = "";
				$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $message['diff'],
				  "{$curIdEq}&diff={$rc_this_oldid}&oldid={$rc_last_oldid}{$rcidparam}",
				  '', '', ' tabindex="'.$rc->counter.'"');
			}
			$s .= '('.$diffLink.') (';

			# History link
			$s .= $this->skin->makeKnownLinkObj( $rc->getTitle(), $message['hist'], $curIdEq.'&action=history' );
			$s .= ') . . ';

			# M, N and ! (minor, new and unpatrolled)
			if ( $rc_minor ) { $s .= ' <span class="minor">'.htmlspecialchars( $message["minoreditletter"] ).'</span>'; }
			if ( $rc_type == RC_NEW ) { $s .= '<span class="newpage">'.htmlspecialchars( $message["newpageletter"] ).'</span>'; }
			if ( $unpatrolled ) { $s .= ' <span class="unpatrolled">!</span>'; }

			# Article link
			# If it's a new article, there is no diff link, but if it hasn't been
			# patrolled yet, we need to give users a way to do so
			if ( $unpatrolled && $rc_type == RC_NEW )
				$articleLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
			else
				$articleLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), '' );

			if ( $watched ) {
				$articleLink = '<strong>'.$articleLink.'</strong>';
			}
			$s .= ' '.$articleLink;
			wfProfileOut("$fname-page");
		}

		wfProfileIn( "$fname-rest" );
		# Timestamp
		$s .= '; ' . $wgLang->time( $rc_timestamp, true, $wgRCSeconds ) . ' . . ';

		# User link (or contributions for unregistered users)
		if ( 0 == $rc_user ) {
			$contribsPage =& Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$userLink = $this->skin->makeKnownLinkObj( $contribsPage,
				$rc_user_text, 'target=' . $rc_user_text );
		} else {
			$userPage =& Title::makeTitle( NS_USER, $rc_user_text );
			$userLink = $this->skin->makeLinkObj( $userPage, $rc_user_text );
		}
		$s .= $userLink;

		# User talk link
		$talkname = $wgContLang->getNsText(NS_TALK); # use the shorter name
		global $wgDisableAnonTalk;
		if( 0 == $rc_user && $wgDisableAnonTalk ) {
			$userTalkLink = '';
		} else {
			$userTalkPage =& Title::makeTitle( NS_USER_TALK, $rc_user_text );
			$userTalkLink= $this->skin->makeLinkObj( $userTalkPage, $talkname );
		}
		# Block link
		$blockLink='';
		if ( ( 0 == $rc_user ) && $wgUser->isAllowed('block') ) {
			$blockLinkPage = Title::makeTitle( NS_SPECIAL, 'Blockip' );
			$blockLink = $this->skin->makeKnownLinkObj( $blockLinkPage,
				$message['blocklink'], 'ip='.$rc_user_text );

		}
		if($blockLink) {
			if($userTalkLink) $userTalkLink .= ' | ';
			$userTalkLink .= $blockLink;
		}
		if($userTalkLink) $s.=' ('.$userTalkLink.')';

		# Add comment
		if ( '' != $rc_comment && '*' != $rc_comment && $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$rc_comment = $this->skin->formatComment($rc_comment,$rc->getTitle());
			$s .= $wgContLang->emphasize(' (' . $rc_comment . ')');
		}
		$s .= "</li>\n";

		wfProfileOut( "$fname-rest" );
		wfProfileOut( $fname );
		return $s;
	}

	function recentChangesLineNew( &$baseRC, $watched = false ) {
		global $wgTitle, $wgLang, $wgContLang, $wgUser, $wgRCSeconds;
		global $wgUseRCPatrol, $wgOnlySysopsCanPatrol;
		
		static $message;
		if( !isset( $message ) ) {
			foreach( explode(' ', 'cur diff hist minoreditletter newpageletter last blocklink' ) as $msg ) {
				$message[$msg] = wfMsg( $msg );
			}
		}
		
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
		if ( $wgUseRCPatrol && $wgUser->getID() != 0 && 
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

		$time = $wgContLang->time( $rc_timestamp, true, $wgRCSeconds );
		$rc->watched = $watched ;
		$rc->link = $clink ;
		$rc->timestamp = $time;

		# Make "cur" and "diff" links
		$titleObj = $rc->getTitle();
		if ( $rc->unpatrolled ) {
			$rcIdQuery = "&rcid={$rc_id}";
		} else {
			$rcIdQuery = '';
		}
		if ( ( $rc_type == RC_NEW && $rc_this_oldid == 0 ) || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$curLink = $message['cur'];
			$diffLink = $message['diff'];
		} else {
			$query = $curIdEq.'&diff=0&oldid='.$rc_this_oldid;
			$aprops = ' tabindex="'.$baseRC->counter.'"';
			$curLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $message['cur'], $query, '' ,'' , $aprops );
			$diffLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $message['diff'], $query . $rcIdQuery, '' ,'' , $aprops );
		}

		# Make "last" link
		if ( $rc_last_oldid == 0 || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$lastLink = $message['last'];
		} else {
			$lastLink = $this->skin->makeKnownLinkObj( $rc->getTitle(), $message['last'],
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
		if ( ( 0 == $rc_user ) && $wgUser->isAllowed('block') ) {
			$blockPage =& Title::makeTitle( NS_SPECIAL, 'Blockip' );
			$blockLink = $this->skin->makeKnownLinkObj( $blockPage,
				$message['blocklink'], 'ip='.$rc_user_text );
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

}


?>
