<?php
/**
 * Generate a list of changes using the good old system (no javascript).
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
class OldChangesList extends ChangesList {

	/**
	 * Format a line using the old system (aka without any javascript).
	 *
	 * @param $rc RecentChange, passed by reference
	 * @param bool $watched (default false)
	 * @param int $linenumber (default null)
	 *
	 * @return string|bool
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
		if ( $linenumber ) {
			if ( $linenumber & 1 ) {
				$classes[] = 'mw-line-odd';
			} else {
				$classes[] = 'mw-line-even';
			}
		}

		// Indicate watched status on the line to allow for more
		// comprehensive styling.
		$classes[] = $watched && $rc->mAttribs['rc_timestamp'] >= $watched
			? 'mw-changeslist-line-watched' : 'mw-changeslist-line-not-watched';

		// Moved pages (very very old, not supported anymore)
		if ( $rc->mAttribs['rc_type'] == RC_MOVE || $rc->mAttribs['rc_type'] == RC_MOVE_OVER_REDIRECT ) {
		// Log entries
		} elseif ( $rc->mAttribs['rc_log_type'] ) {
			$logtitle = SpecialPage::getTitleFor( 'Log', $rc->mAttribs['rc_log_type'] );
			$this->insertLog( $s, $logtitle, $rc->mAttribs['rc_log_type'] );
		// Log entries (old format) or log targets, and special pages
		} elseif ( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			list( $name, $subpage ) = SpecialPageFactory::resolveAlias( $rc->mAttribs['rc_title'] );
			if ( $name == 'Log' ) {
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
		if ( $rc->numberofWatchingusers > 0 ) {
			$s .= ' ' . $this->numberofWatchingusers( $rc->numberofWatchingusers );
		}

		if ( $this->watchlist ) {
			$classes[] = Sanitizer::escapeClass( 'watchlist-' . $rc->mAttribs['rc_namespace'] . '-' . $rc->mAttribs['rc_title'] );
		}

		if ( !wfRunHooks( 'OldChangesListRecentChangesLine', array( &$this, &$s, $rc, &$classes ) ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfProfileOut( __METHOD__ );
		return "$dateheader<li class=\"" . implode( ' ', $classes ) . "\">" . $s . "</li>\n";
	}
}
