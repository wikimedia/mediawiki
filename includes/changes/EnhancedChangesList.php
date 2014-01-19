<?php
/**
 * Generates a list of changes using an Enhanced system (uses javascript).
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
		$this->getOutput()->addModuleStyles( array(
			'mediawiki.special.changeslist',
			'mediawiki.special.changeslist.enhanced',
		) );
		$this->getOutput()->addModules( array(
			'jquery.makeCollapsible',
			'mediawiki.icon',
		) );
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
		if ( $date != $this->lastdate ) {
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
		if ( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
		// New unpatrolled pages
		} elseif ( $rc->unpatrolled && $type == RC_NEW ) {
			$clink = Linker::linkKnown( $rc->getTitle() );
		// Log entries
		} elseif ( $type == RC_LOG ) {
			if ( $logType ) {
				$logtitle = SpecialPage::getTitleFor( 'Log', $logType );
				$logpage = new LogPage( $logType );
				$logname = $logpage->getName()->escaped();
				$clink = $this->msg( 'parentheses' )->rawParams( Linker::linkKnown( $logtitle, $logname ) )->escaped();
			} else {
				$clink = Linker::link( $rc->getTitle() );
			}
			$watched = false;
		// Log entries (old format) and special pages
		} elseif ( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
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

		$querycur = $curIdEq + array( 'diff' => '0', 'oldid' => $thisOldid );
		$querydiff = $curIdEq + array( 'diff' => $thisOldid, 'oldid' => $lastOldid );

		if ( !$showdifflinks ) {
			$curLink = $this->message['cur'];
			$diffLink = $this->message['diff'];
		} elseif ( in_array( $type, array( RC_NEW, RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
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
		if ( !$showdifflinks || !$lastOldid ) {
			$lastLink = $this->message['last'];
		} elseif ( in_array( $type, array( RC_LOG, RC_MOVE, RC_MOVE_OVER_REDIRECT ) ) ) {
			$lastLink = $this->message['last'];
		} else {
			$lastLink = Linker::linkKnown( $rc->getTitle(), $this->message['last'],
				array(), $curIdEq + array( 'diff' => $thisOldid, 'oldid' => $lastOldid ) );
		}

		# Make user links
		if ( $this->isDeleted( $rc, Revision::DELETED_USER ) ) {
			$rc->userlink = ' <span class="history-deleted">' . $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			$rc->userlink = Linker::userLink( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
			$rc->usertalklink = Linker::userToolLinks( $rc->mAttribs['rc_user'], $rc->mAttribs['rc_user_text'] );
		}

		$rc->lastlink = $lastLink;
		$rc->curlink = $curLink;
		$rc->difflink = $diffLink;

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if ( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ( $this->rcMoveIndex++ )] = array( $rc );
		} else {
			# Logs are grouped by type
			if ( $type == RC_LOG ) {
				$secureName = SpecialPage::getTitleFor( 'Log', $logType )->getPrefixedDBkey();
			}
			if ( !isset( $this->rc_cache[$secureName] ) ) {
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
				. $block[0]->mAttribs['rc_log_type'] );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns'
					. $block[0]->mAttribs['rc_namespace'] . '-' . $block[0]->mAttribs['rc_title'] );
		}
		$classes[] = $block[0]->watched && $block[0]->mAttribs['rc_timestamp'] >= $block[0]->watched
			? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';
		$r = Html::openElement( 'table', array( 'class' => $classes ) ) .
			Html::openElement( 'tr' );

		# Collate list of users
		$userlinks = array();
		# Other properties
		$unpatrolled = false;
		$isnew = false;
		$allBots = true;
		$allMinors = true;
		$curId = $currentRevision = 0;
		# Some catalyst variables...
		$namehidden = true;
		$allLogs = true;
		foreach ( $block as $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			if ( $rcObj->mAttribs['rc_type'] == RC_NEW ) {
				$isnew = true;
			}
			// If all log actions to this page were hidden, then don't
			// give the name of the affected page for this block!
			if ( !$this->isDeleted( $rcObj, LogPage::DELETED_ACTION ) ) {
				$namehidden = false;
			}
			$u = $rcObj->userlink;
			if ( !isset( $userlinks[$u] ) ) {
				$userlinks[$u] = 0;
			}
			if ( $rcObj->unpatrolled ) {
				$unpatrolled = true;
			}
			if ( $rcObj->mAttribs['rc_type'] != RC_LOG ) {
				$allLogs = false;
			}
			# Get the latest entry with a page_id and oldid
			# since logs may not have these.
			if ( !$curId && $rcObj->mAttribs['rc_cur_id'] ) {
				$curId = $rcObj->mAttribs['rc_cur_id'];
			}
			if ( !$currentRevision && $rcObj->mAttribs['rc_this_oldid'] ) {
				$currentRevision = $rcObj->mAttribs['rc_this_oldid'];
			}

			if ( !$rcObj->mAttribs['rc_bot'] ) {
				$allBots = false;
			}
			if ( !$rcObj->mAttribs['rc_minor'] ) {
				$allMinors = false;
			}

			$userlinks[$u]++;
		}

		# Sort the list and convert to text
		krsort( $userlinks );
		asort( $userlinks );
		$users = array();
		foreach ( $userlinks as $userlink => $count ) {
			$text = $userlink;
			$text .= $this->getLanguage()->getDirMark();
			if ( $count > 1 ) {
				$text .= ' ' . $this->msg( 'parentheses' )->rawParams( $this->getLanguage()->formatNum( $count ) . '×' )->escaped();
			}
			array_push( $users, $text );
		}

		$users = ' <span class="changedby">'
			. $this->msg( 'brackets' )->rawParams(
				implode( $this->message['semicolon-separator'], $users )
			)->escaped() . '</span>';

		$tl = '<span class="mw-collapsible-toggle mw-collapsible-arrow mw-enhancedchanges-arrow mw-enhancedchanges-arrow-space"></span>';
		$r .= "<td>$tl</td>";

		# Main line
		$r .= '<td class="mw-enhanced-rc">' . $this->recentChangesFlags( array(
			'newpage' => $isnew, # show, when one have this flag
			'minor' => $allMinors, # show only, when all have this flag
			'unpatrolled' => $unpatrolled, # show, when one have this flag
			'bot' => $allBots, # show only, when all have this flag
		) );

		# Timestamp
		$r .= '&#160;' . $block[0]->timestamp . '&#160;</td><td>';

		# Article link
		if ( $namehidden ) {
			$r .= ' <span class="history-deleted">' . $this->msg( 'rev-deleted-event' )->escaped() . '</span>';
		} elseif ( $allLogs ) {
			$r .= $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$this->insertArticleLink( $r, $block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		$r .= $this->getLanguage()->getDirMark();

		$queryParams['curid'] = $curId;

		# Changes message
		static $nchanges = array();
		static $sinceLastVisitMsg = array();

		$n = count( $block );
		if ( !isset( $nchanges[$n] ) ) {
			$nchanges[$n] = $this->msg( 'nchanges' )->numParams( $n )->escaped();
		}

		$sinceLast = 0;
		$unvisitedOldid = null;
		foreach ( $block as $rcObj ) {
			// Same logic as below inside main foreach
			if ( $rcObj->watched && $rcObj->mAttribs['rc_timestamp'] >= $rcObj->watched ) {
				$sinceLast++;
				$unvisitedOldid = $rcObj->mAttribs['rc_last_oldid'];
			}
		}
		if ( !isset( $sinceLastVisitMsg[$sinceLast] ) ) {
			$sinceLastVisitMsg[$sinceLast] =
				$this->msg( 'enhancedrc-since-last-visit' )->numParams( $sinceLast )->escaped();
		}

		# Total change link
		$r .= ' ';
		$logtext = '';
		if ( !$allLogs ) {
			if ( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ) {
				$logtext .= $nchanges[$n];
			} elseif ( $isnew ) {
				$logtext .= $nchanges[$n];
			} else {
				$logtext .= Linker::link(
					$block[0]->getTitle(),
					$nchanges[$n],
					array(),
					$queryParams + array(
						'diff' => $currentRevision,
						'oldid' => $oldid,
					),
					array( 'known', 'noclasses' )
				);
				if ( $sinceLast > 0 && $sinceLast < $n ) {
					$logtext .= $this->message['pipe-separator'] . Linker::link(
						$block[0]->getTitle(),
						$sinceLastVisitMsg[$sinceLast],
						array(),
						$queryParams + array(
							'diff' => $currentRevision,
							'oldid' => $unvisitedOldid,
						),
						array( 'known', 'noclasses' )
					);
				}
			}
		}

		# History
		if ( $allLogs ) {
			// don't show history link for logs
		} elseif ( $namehidden || !$block[0]->getTitle()->exists() ) {
			$logtext .= $this->message['pipe-separator'] . $this->message['enhancedrc-history'];
		} else {
			$params = $queryParams;
			$params['action'] = 'history';

			$logtext .= $this->message['pipe-separator'] .
				Linker::linkKnown(
					$block[0]->getTitle(),
					$this->message['enhancedrc-history'],
					array(),
					$params
				);
		}

		if ( $logtext !== '' ) {
			$r .= $this->msg( 'parentheses' )->rawParams( $logtext )->escaped();
		}

		$r .= ' <span class="mw-changeslist-separator">. .</span> ';

		# Character difference (does not apply if only log items)
		if ( $wgRCShowChangedSize && !$allLogs ) {
			$last = 0;
			$first = count( $block ) - 1;
			# Some events (like logs) have an "empty" size, so we need to skip those...
			while ( $last < $first && $block[$last]->mAttribs['rc_new_len'] === null ) {
				$last++;
			}
			while ( $first > $last && $block[$first]->mAttribs['rc_old_len'] === null ) {
				$first--;
			}
			# Get net change
			$chardiff = $this->formatCharacterDifference( $block[$first], $block[$last] );

			if ( $chardiff == '' ) {
				$r .= ' ';
			} else {
				$r .= ' ' . $chardiff . ' <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		$r .= $users;
		$r .= $this->numberofWatchingusers( $block[0]->numberofWatchingusers );

		# Sub-entries
		foreach ( $block as $rcObj ) {
			# Classes to apply -- TODO implement
			$classes = array();
			$type = $rcObj->mAttribs['rc_type'];

			$trClass = $rcObj->watched && $rcObj->mAttribs['rc_timestamp'] >= $rcObj->watched
				? ' class="mw-enhanced-watched"' : '';

			$r .= '<tr' . $trClass . '><td></td><td class="mw-enhanced-rc">';
			$r .= $this->recentChangesFlags( array(
				'newpage' => $type == RC_NEW,
				'minor' => $rcObj->mAttribs['rc_minor'],
				'unpatrolled' => $rcObj->unpatrolled,
				'bot' => $rcObj->mAttribs['rc_bot'],
			) );
			$r .= '&#160;</td><td class="mw-enhanced-rc-nested"><span class="mw-enhanced-rc-time">';

			$params = $queryParams;

			if ( $rcObj->mAttribs['rc_this_oldid'] != 0 ) {
				$params['oldid'] = $rcObj->mAttribs['rc_this_oldid'];
			}

			# Log timestamp
			if ( $type == RC_LOG ) {
				$link = $rcObj->timestamp;
			# Revision link
			} elseif ( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ) {
				$link = '<span class="history-deleted">' . $rcObj->timestamp . '</span> ';
			} else {

				$link = Linker::linkKnown(
						$rcObj->getTitle(),
						$rcObj->timestamp,
						array(),
						$params
					);
				if ( $this->isDeleted( $rcObj, Revision::DELETED_TEXT ) ) {
					$link = '<span class="history-deleted">' . $link . '</span> ';
				}
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
	 * @param string $dir one of '', 'd', 'l', 'r'
	 * @param string $alt text
	 * @param string $title text
	 * @return String: HTML "<img>" tag
	 */
	protected function arrow( $dir, $alt = '', $title = '' ) {
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
		if ( $logType ) {
			# Log entry
+			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-' . $logType );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns' .
					$rcObj->mAttribs['rc_namespace'] . '-' . $rcObj->mAttribs['rc_title'] );
		}
		$classes[] = $rcObj->watched && $rcObj->mAttribs['rc_timestamp'] >= $rcObj->watched
			? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';
		$r = Html::openElement( 'table', array( 'class' => $classes ) ) .
			Html::openElement( 'tr' );

		$r .= '<td class="mw-enhanced-rc"><span class="mw-enhancedchanges-arrow-space"></span>';
		# Flag and Timestamp
		if ( $type == RC_MOVE || $type == RC_MOVE_OVER_REDIRECT ) {
			$r .= $this->recentChangesFlags( array() ); // no flags, but need the placeholders
		} else {
			$r .= $this->recentChangesFlags( array(
				'newpage' => $type == RC_NEW,
				'minor' => $rcObj->mAttribs['rc_minor'],
				'unpatrolled' => $rcObj->unpatrolled,
				'bot' => $rcObj->mAttribs['rc_bot'],
			) );
		}
		$r .= '&#160;' . $rcObj->timestamp . '&#160;</td><td>';
		# Article or log link
		if ( $logType ) {
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
			$r .= ' ' . $rcObj->userlink . $rcObj->usertalklink;
			$r .= $this->insertComment( $rcObj );
			$this->insertRollback( $r, $rcObj );
		}

		# Tags
		$this->insertTags( $r, $rcObj, $classes );
		# Show how many people are watching this if enabled
		$r .= $this->numberofWatchingusers( $rcObj->numberofWatchingusers );

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
		if ( count ( $this->rc_cache ) == 0 ) {
			return '';
		}

		wfProfileIn( __METHOD__ );

		$blockOut = '';
		foreach ( $this->rc_cache as $block ) {
			if ( count( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine( array_shift( $block ) );
			} else {
				$blockOut .= $this->recentChangesBlockGroup( $block );
			}
		}

		wfProfileOut( __METHOD__ );

		return '<div>' . $blockOut . '</div>';
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
