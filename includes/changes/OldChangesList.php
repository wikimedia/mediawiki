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
	 * @param RecentChange $rc Passed by reference
	 * @param bool $watched (default false)
	 * @param int $linenumber (default null)
	 *
	 * @return string|bool
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {

		$classes = $this->getHTMLClasses( $rc, $watched );
		// use mw-line-even/mw-line-odd class only if linenumber is given (feature from bug 14468)
		if ( $linenumber ) {
			if ( $linenumber & 1 ) {
				$classes[] = 'mw-line-odd';
			} else {
				$classes[] = 'mw-line-even';
			}
		}

		$html = $this->formatChangeLine( $rc, $classes, $watched );

		if ( $this->watchlist ) {
			$classes[] = Sanitizer::escapeClass( 'watchlist-' .
				$rc->mAttribs['rc_namespace'] . '-' . $rc->mAttribs['rc_title'] );
		}

		if ( !Hooks::run( 'OldChangesListRecentChangesLine', [ &$this, &$html, $rc, &$classes ] ) ) {
			return false;
		}

		$dateheader = ''; // $html now contains only <li>...</li>, for hooks' convenience.
		$this->insertDateHeader( $dateheader, $rc->mAttribs['rc_timestamp'] );

		return "$dateheader<li class=\"" . implode( ' ', $classes ) . "\">" . $html . "</li>\n";
	}

	/**
	 * @param RecentChange $rc
	 * @param string[] &$classes
	 * @param bool $watched
	 *
	 * @return string
	 */
	private function formatChangeLine( RecentChange $rc, array &$classes, $watched ) {
		$html = '';
		$unpatrolled = $this->showAsUnpatrolled( $rc );

		if ( $rc->mAttribs['rc_log_type'] ) {
			$logtitle = SpecialPage::getTitleFor( 'Log', $rc->mAttribs['rc_log_type'] );
			$this->insertLog( $html, $logtitle, $rc->mAttribs['rc_log_type'] );
			$flags = $this->recentChangesFlags( [ 'unpatrolled' =>$unpatrolled,
				'bot' => $rc->mAttribs['rc_bot'] ], '' );
			if ( $flags !== '' ) {
				$html .= ' ' . $flags;
			}
		// Log entries (old format) or log targets, and special pages
		} elseif ( $rc->mAttribs['rc_namespace'] == NS_SPECIAL ) {
			list( $name, $htmlubpage ) = SpecialPageFactory::resolveAlias( $rc->mAttribs['rc_title'] );
			if ( $name == 'Log' ) {
				$this->insertLog( $html, $rc->getTitle(), $htmlubpage );
			}
		// Regular entries
		} else {
			$this->insertDiffHist( $html, $rc );
			# M, N, b and ! (minor, new, bot and unpatrolled)
			$html .= $this->recentChangesFlags(
				[
					'newpage' => $rc->mAttribs['rc_type'] == RC_NEW,
					'minor' => $rc->mAttribs['rc_minor'],
					'unpatrolled' => $unpatrolled,
					'bot' => $rc->mAttribs['rc_bot']
				],
				''
			);
			$html .= $this->getArticleLink( $rc, $unpatrolled, $watched );
		}
		# Edit/log timestamp
		$this->insertTimestamp( $html, $rc );
		# Bytes added or removed
		if ( $this->getConfig()->get( 'RCShowChangedSize' ) ) {
			$cd = $this->formatCharacterDifference( $rc );
			if ( $cd !== '' ) {
				$html .= $cd . '  <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		if ( $rc->mAttribs['rc_type'] == RC_LOG ) {
			$html .= $this->insertLogEntry( $rc );
		} elseif ( $this->isCategorizationWithoutRevision( $rc ) ) {
			$html .= $this->insertComment( $rc );
		} else {
			# User tool links
			$this->insertUserRelatedLinks( $html, $rc );
			# LTR/RTL direction mark
			$html .= $this->getLanguage()->getDirMark();
			$html .= $this->insertComment( $rc );
		}

		# Tags
		$this->insertTags( $html, $rc, $classes );
		# Rollback
		$this->insertRollback( $html, $rc );
		# For subclasses
		$this->insertExtra( $html, $rc, $classes );

		# How many users watch this page
		if ( $rc->numberofWatchingusers > 0 ) {
			$html .= ' ' . $this->numberofWatchingusers( $rc->numberofWatchingusers );
		}

		return $html;
	}
}
