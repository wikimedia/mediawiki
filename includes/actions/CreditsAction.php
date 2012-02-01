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

	protected function getDescription() {
		return wfMsgHtml( 'creditspage' );
	}

	/**
	 * This is largely cadged from PageHistory::history
	 *
	 * @return String HTML
	 */
	public function onView() {
		wfProfileIn( __METHOD__ );

		if ( $this->page->getID() == 0 ) {
			$s = $this->msg( 'nocredits' )->parse();
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

		if ( $cnt != 0 ) {
			$s = $this->getAuthor( $this->page );
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
	protected function getAuthor( Page $article ) {
		$user = User::newFromName( $article->getUserText(), false );

		$timestamp = $article->getTimestamp();
		if ( $timestamp ) {
			$lang = $this->getLanguage();
			$d = $lang->date( $article->getTimestamp(), true );
			$t = $lang->time( $article->getTimestamp(), true );
		} else {
			$d = '';
			$t = '';
		}
		return $this->msg( 'lastmodifiedatby', $d, $t )->rawParams(
			$this->userLink( $user ) )->params( $user->getName() )->escaped();
	}

	/**
	 * Get a list of contributors of $article
	 * @param $cnt Int: maximum list of contributors to show
	 * @param $showIfMax Bool: whether to contributors if there more than $cnt
	 * @return String: html
	 */
	protected function getContributors( $cnt, $showIfMax ) {
		global $wgHiddenPrefs;

		$contributors = $this->page->getContributors();

		$others_link = false;

		# Hmm... too many to fit!
		if ( $cnt > 0 && $contributors->count() > $cnt ) {
			$others_link = $this->othersLink();
			if ( !$showIfMax )
				return $this->msg( 'othercontribs' )->rawParams(
					$others_link )->params( $contributors->count() )->escaped();
		}

		$real_names = array();
		$user_names = array();
		$anon_ips = array();

		# Sift for real versus user names
		foreach ( $contributors as $user ) {
			$cnt--; 
			if ( $user->isLoggedIn() ) {
				$link = $this->link( $user );
				if ( !in_array( 'realname', $wgHiddenPrefs ) && $user->getRealName() ) {
					$real_names[] = $link;
				} else {
					$user_names[] = $link;
				}
			} else {
				$anon_ips[] = $this->link( $user );
			}

			if ( $cnt == 0 ) {
				break;
			}
		}

		$lang = $this->getLanguage();

		if ( count( $real_names ) ) {
			$real = $lang->listToText( $real_names );
		} else {
			$real = false;
		}

		# "ThisSite user(s) A, B and C"
		if ( count( $user_names ) ) {
			$user = $this->msg( 'siteusers' )->rawParams( $lang->listToText( $user_names ) )->params(
				count( $user_names ) )->escaped();
		} else {
			$user = false;
		}

		if ( count( $anon_ips ) ) {
			$anon = $this->msg( 'anonusers' )->rawParams( $lang->listToText( $anon_ips ) )->params(
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
			? $this->msg( 'othercontribs' )->rawParams(
				$lang->listToText( $fulllist ) )->params( $count )->escaped()
			: '';
	}

	/**
	 * Get a link to $user's user page
	 * @param $user User object
	 * @return String: html
	 */
	protected function link( User $user ) {
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
	protected function userLink( User $user ) {
		$link = $this->link( $user );
		if ( $user->isAnon() ) {
			return $this->msg( 'anonuser' )->rawParams( $link )->parse();
		} else {
			global $wgHiddenPrefs;
			if ( !in_array( 'realname', $wgHiddenPrefs ) && $user->getRealName() ) {
				return $link;
			} else {
				return $this->msg( 'siteuser' )->rawParams( $link )->params( $user->getName() )->escaped();
			}
		}
	}

	/**
	 * Get a link to action=credits of $article page
	 * @return String: HTML link
	 */
	protected function othersLink() {
		return Linker::linkKnown(
			$this->getTitle(),
			$this->msg( 'others' )->escaped(),
			array(),
			array( 'action' => 'credits' )
		);
	}
}
