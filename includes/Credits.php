<?php
/**
 * Credits.php -- formats credits for articles
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @author <evan@wikitravel.org>
 */

class Credits {

	/**
	 * This is largely cadged from PageHistory::history
	 * @param $article Article object
	 */
	public static function showPage( Article $article ) {
		global $wgOut;
	
		wfProfileIn( __METHOD__ );
	
		$wgOut->setPageTitle( $article->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'creditspage' ) );
		$wgOut->setArticleFlag( false );
		$wgOut->setArticleRelated( true );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
	
		if( $article->mTitle->getArticleID() == 0 ) {
			$s = wfMsg( 'nocredits' );
		} else {
			$s = self::getCredits($article, -1 );
		}
	
		$wgOut->addHTML( $s );
	
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get a list of contributors of $article
	 * @param $article Article object
	 * @param $cnt Int: maximum list of contributors to show
	 * @param $showIfMax Bool: whether to contributors if there more than $cnt
	 * @return String: html
	 */
	public static function getCredits($article, $cnt, $showIfMax=true) {
		wfProfileIn( __METHOD__ );
		$s = '';

		if( isset( $cnt ) && $cnt != 0 ){
			$s = self::getAuthor( $article );
			if ($cnt > 1 || $cnt < 0) {
				$s .= ' ' . self::getContributors( $article, $cnt - 1, $showIfMax );
			}
		}

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * Get the last author with the last modification time
	 * @param $article Article object
	 */
	protected static function getAuthor( Article $article ){
		global $wgLang, $wgAllowRealName;

		$user = User::newFromId( $article->getUser() );

		$timestamp = $article->getTimestamp();
		if( $timestamp ){
			$d = $wgLang->date( $article->getTimestamp(), true );
			$t = $wgLang->time( $article->getTimestamp(), true );
		} else {
			$d = '';
			$t = '';
		}
		return wfMsg( 'lastmodifiedatby', $d, $t, self::userLink( $user ) );
	}

	/**
	 * Get a list of contributors of $article
	 * @param $article Article object
	 * @param $cnt Int: maximum list of contributors to show
	 * @param $showIfMax Bool: whether to contributors if there more than $cnt
	 * @return String: html
	 */
	protected static function getContributors( Article $article, $cnt, $showIfMax ) {
		global $wgLang, $wgAllowRealName;
	
		$contributors = $article->getContributors();
	
		$others_link = '';
	
		# Hmm... too many to fit!
		if( $cnt > 0 && $contributors->count() > $cnt ){
			$others_link = self::othersLink( $article );
			if( !$showIfMax )
				return wfMsg( 'othercontribs', $others_link );
		}
	
		$real_names = array();
		$user_names = array();
		$anon = 0;
	
		# Sift for real versus user names
		foreach( $contributors as $user ) {
			$cnt--;
			if( $user->isLoggedIn() ){
				$link = self::link( $user );
				if( $wgAllowRealName && $user->getRealName() )
					$real_names[] = $link;
				else
					$user_names[] = $link;
			} else {
				$anon++;
			}
			if( $cnt == 0 ) break;
		}
	
		# Two strings: real names, and user names
		$real = $wgLang->listToText( $real_names );
		$user = $wgLang->listToText( $user_names );
		if( $anon )
			$anon = wfMsgExt( 'anonymous', array( 'parseinline' ), $anon );
	
		# "ThisSite user(s) A, B and C"
		if( !empty( $user ) ){
			$user = wfMsgExt( 'siteusers', array( 'parsemag' ), $user, count( $user_names ) );
		}
	
		# This is the big list, all mooshed together. We sift for blank strings
		$fulllist = array();
		foreach( array( $real, $user, $anon, $others_link ) as $s ){
			if( !empty( $s ) ){
				array_push( $fulllist, $s );
			}
		}

		# Make the list into text...
		$creds = $wgLang->listToText( $fulllist );

		# "Based on work by ..."
		return empty( $creds ) ? '' : wfMsg( 'othercontribs', $creds );
	}

	/**
	 * Get a link to $user_name page
	 * @param $user User object
	 * @return String: html
	 */
	protected static function link( User $user ) {
		global $wgUser, $wgAllowRealName;
		if( $wgAllowRealName )
			$real = $user->getRealName();
		else
			$real = false;

		$skin = $wgUser->getSkin();
		$page = $user->getUserPage();
			
		return $skin->link( $page, htmlspecialchars( $real ? $real : $user->getName() ) );
	}

	/**
	 * Get a link to $user_name page
	 * @param $user_name String: user name
	 * @param $linkText String: optional display
	 * @return String: html
	 */
	protected static function userLink( User $user ) {
		global $wgUser, $wgAllowRealName;
		if( $user->isAnon() ){
			return wfMsgExt( 'anonymous', array( 'parseinline' ), 1 );
		} else {
			$link = self::link( $user );
			if( $wgAllowRealName && $user->getRealName() )
				return $link;
			else 
				return wfMsgExt( 'siteuser', array( 'parseinline', 'replaceafter' ), $link );
		}
	}

	/**
	 * Get a link to action=credits of $article page
	 * @param $article Article object
	 * @return String: html
	 */
	protected static function othersLink( Article $article ) {
		global $wgUser;
		$skin = $wgUser->getSkin();
		return $skin->link( $article->getTitle(), wfMsgHtml( 'others' ), array(), array( 'action' => 'credits' ), array( 'known' ) );
	}
}