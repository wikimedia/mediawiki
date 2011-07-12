<?php
/**
 * Formats credits for articles
 *
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 * @author <evan@wikitravel.org>
 */

class CreditsAction extends FormlessAction {

	public function getName() {
		return 'credits';
	}

	public function getRestriction() {
		return null;
	}

	protected function getDescription() {
		return wfMsg( 'creditspage' );
	}

	/**
	 * This is largely cadged from PageHistory::history
	 *
	 * @return String HTML
	 */
	public function onView() {
		wfProfileIn( __METHOD__ );

		if ( $this->page->getID() == 0 ) {
			$s = wfMsg( 'nocredits' );
		} else {
			$s = $this->getCredits( -1 );
		}

		wfProfileOut( __METHOD__ );

		return Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $s );
	}

	/**
	 * Get a list of contributors
	 *
	 * @param $cnt Int: maximum list of contributors to show
	 * @param $showIfMax Bool: whether to contributors if there more than $cnt
	 * @return String: html
	 */
	public function getCredits( $cnt, $showIfMax = true ) {
		wfProfileIn( __METHOD__ );
		$s = '';

		if ( isset( $cnt ) && $cnt != 0 ) {
			$s = self::getAuthor( $this->page );
			if ( $cnt > 1 || $cnt < 0 ) {
				$s .= ' ' . $this->getContributors( $cnt - 1, $showIfMax );
			}
		}

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * Get the last author with the last modification time
	 * @param $article Article object
	 * @return String HTML
	 */
	protected static function getAuthor( Page $article ) {
		global $wgLang;

		$user = User::newFromId( $article->getUser() );

		$timestamp = $article->getTimestamp();
		if ( $timestamp ) {
			$d = $wgLang->date( $article->getTimestamp(), true );
			$t = $wgLang->time( $article->getTimestamp(), true );
		} else {
			$d = '';
			$t = '';
		}
		return wfMessage( 'lastmodifiedatby', $d, $t )->rawParams( self::userLink( $user ) )->params( $user->getName() )->escaped();
	}

	/**
	 * Get a list of contributors of $article
	 * @param $cnt Int: maximum list of contributors to show
	 * @param $showIfMax Bool: whether to contributors if there more than $cnt
	 * @return String: html
	 */
	protected function getContributors( $cnt, $showIfMax ) {
		global $wgLang, $wgHiddenPrefs;

		$contributors = $this->page->getContributors();

		$others_link = false;

		# Hmm... too many to fit!
		if ( $cnt > 0 && $contributors->count() > $cnt ) {
			$others_link = $this->othersLink();
			if ( !$showIfMax )
				return wfMessage( 'othercontribs' )->rawParams( $others_link )->params( $contributors->count() )->escaped();
		}

		$real_names = array();
		$user_names = array();
		$anon_ips = array();

		# Sift for real versus user names
		foreach ( $contributors as $user ) {
			$cnt--; 
			if ( $user->isLoggedIn() ) {
				$link = self::link( $user );
				if ( !in_array( 'realname', $wgHiddenPrefs ) && $user->getRealName() ) {
					$real_names[] = $link;
				} else {
					$user_names[] = $link;
				}
			} else {
				$anon_ips[] = self::link( $user );
			}

			if ( $cnt == 0 ) {
				break;
			}
		}

		if ( count( $real_names ) ) {
			$real = $wgLang->listToText( $real_names );
		} else {
			$real = false;
		}

		# "ThisSite user(s) A, B and C"
		if ( count( $user_names ) ) {
			$user = wfMessage( 'siteusers' )->rawParams( $wgLang->listToText( $user_names ) )->params(
				count( $user_names ) )->escaped();
		} else {
			$user = false;
		}

		if ( count( $anon_ips ) ) {
			$anon = wfMessage( 'anonusers' )->rawParams( $wgLang->listToText( $anon_ips ) )->params(
				count( $anon_ips ) )->escaped();
		} else {
			$anon = false;
		}

		# This is the big list, all mooshed together. We sift for blank strings
		$fulllist = array();
		foreach ( array( $real, $user, $anon, $others_link ) as $s ) {
			if ( $s !== false ) {
				array_push( $fulllist, $s );
			}
		}

		$count = count( $fulllist );
		# "Based on work by ..."
		return $count
			? wfMessage( 'othercontribs' )->rawParams(
				$wgLang->listToText( $fulllist ) )->params( $count )->escaped()
			: '';
	}

	/**
	 * Get a link to $user's user page
	 * @param $user User object
	 * @return String: html
	 */
	protected static function link( User $user ) {
		global $wgHiddenPrefs;
		if ( !in_array( 'realname', $wgHiddenPrefs ) && !$user->isAnon() ) {
			$real = $user->getRealName();
		} else {
			$real = false;
		}

		$page = $user->isAnon()
			? SpecialPage::getTitleFor( 'Contributions', $user->getName() )
			: $user->getUserPage();

		return Linker::link( $page, htmlspecialchars( $real ? $real : $user->getName() ) );
	}

	/**
	 * Get a link to $user's user page
	 * @param $user User object
	 * @return String: html
	 */
	protected static function userLink( User $user ) {
		$link = self::link( $user );
		if ( $user->isAnon() ) {
			return wfMsgExt( 'anonuser', array( 'parseinline', 'replaceafter' ), $link );
		} else {
			global $wgHiddenPrefs;
			if ( !in_array( 'realname', $wgHiddenPrefs ) && $user->getRealName() ) {
				return $link;
			} else {
				return wfMessage( 'siteuser' )->rawParams( $link )->params( $user->getName() )->escaped();
			}
		}
	}

	/**
	 * Get a link to action=credits of $article page
	 * @param $article Article object
	 * @return String: html
	 */
	protected function othersLink() {
		return Linker::link(
			$this->getTitle(),
			wfMsgHtml( 'others' ),
			array(),
			array( 'action' => 'credits' ),
			array( 'known' )
		);
	}
}
