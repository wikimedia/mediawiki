<?php

/* Credits.php -- formats credits for articles
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
 * 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* This is largely cadged from PageHistory::history */

function showCreditsPage($article)
{
    global $wgOut;

    $fname = "showCreditsPage";
    
    wfProfileIn( $fname );

    $wgOut->setPageTitle( $article->mTitle->getPrefixedText() );
    $wgOut->setSubtitle( wfMsg( "creditspage" ) );
    $wgOut->setArticleFlag( false );
    $wgOut->setArticleRelated( true );
    $wgOut->setRobotpolicy( "noindex,nofollow" );

    if( $article->mTitle->getArticleID() == 0 ) {
	$s = wfMsg( "nocredits" );
    } else {
	$s = getCredits($article, -1);
    }

    wfDebug("Credits: '$s'\n");
    
    $wgOut->addHTML( $s );
    
    wfProfileOut( $fname );
}

function getCredits($article, $cnt) {
    
    $s = '';
    
    if (isset($cnt) && $cnt != 0) {
	$s = getAuthorCredits($article);
	if ($cnt > 1 || $cnt < 0) {
	    $s .= ' ' . getContributorCredits($article, $cnt - 1);
	}
    }
    
    return $s;
}

function getAuthorCredits($article) {
    
    global $wgLang;
    
    $last_author = $article->getUser();
	    
    if ($last_author == 0) {
	$author_credit = wfMsg('anonymous');
    } else {
	$real_name = User::whoIsReal($last_author);
	if (!empty($real_name)) {
	    $author_credit = $real_name;
	} else {
	    $author_credit = wfMsg('siteuser', User::whoIs($last_author));
	}
    }
    
    $timestamp = $article->getTimestamp();
    if ($timestamp) {
	$d = $wgLang->timeanddate($article->getTimestamp(), true);
    } else {
	$d = '';
    }
    return wfMsg('lastmodifiedby', $d, $author_credit);
}

function getContributorCredits($article, $cnt) {
	    
    global $wgLang, $wgAllowRealName;
    
    $contributors = $article->getContributors($cnt);
    
    $real_names = array();
    $user_names = array();

    # Sift for real versus user names
    
    foreach ($contributors as $user_id => $user_parts) {
	if ($user_id != 0) {
	    if ($wgAllowRealName && !empty($user_parts[1])) {
		$real_names[$user_id] = $user_parts[1];
	    } else {
		$user_names[$user_id] = $user_parts[0];
	    }
	}
    }
    
    $real = $wgLang->listToText(array_values($real_names));
    $user = $wgLang->listToText(array_values($user_names));
    
    if (!empty($user)) {
	$user = wfMsg('siteusers', $user);
    }
    
    if ($contributors[0] && $contributors[0][0] > 0) {
	$anon = wfMsg('anonymous');
    } else {
	$anon = '';
    }
    
    $creds = $wgLang->listToText(array($real, $user, $anon));
    
    return wfMsg('othercontribs', $creds);
}

?>
