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

    $wgOut->addHTML( $s );
    
    wfProfileOut( $fname );
}

function getCredits($article, $cnt, $showIfMax=true) {
    
    $s = '';
    
    if (isset($cnt) && $cnt != 0) {
	$s = getAuthorCredits($article);
	if ($cnt > 1 || $cnt < 0) {
	    $s .= ' ' . getContributorCredits($article, $cnt - 1, $showIfMax);
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
	$user_name = User::whoIs($last_author);
	
	if (!empty($real_name)) {
	    $author_credit = creditLink($user_name, $real_name);
	} else {
	    $author_credit = wfMsg('siteuser', creditLink($user_name));
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

function getContributorCredits($article, $cnt, $showIfMax) {
	    
    global $wgLang, $wgAllowRealName;
    
    $contributors = $article->getContributors();

    $others_link = '';
    
    # Hmm... too many to fit!

    if ($cnt > 0 && count($contributors) > $cnt) {
	$others_link = creditOthersLink($article);
	if (!$showIfMax) {
	    return wfMsg('othercontribs', $others_link);
	} else {
	    $contributors = array_slice($contributors, 0, $cnt);
	}
    }
	
    $real_names = array();
    $user_names = array();

    $anon = '';
    
    # Sift for real versus user names
    
    foreach ($contributors as $user_parts) {
	if ($user_parts[0] != 0) {
	    if ($wgAllowRealName && !empty($user_parts[2])) {
		$real_names[$user_id] = creditLink($user_parts[1], $user_parts[2]);
	    } else {
		$user_names[$user_id] = creditLink($user_parts[1]);
	    }
	} else {
	    $anon = wfMsg('anonymous');
	}
    }
	
    # Two strings: real names, and user names
    
    $real = $wgLang->listToText(array_values($real_names));
    $user = $wgLang->listToText(array_values($user_names));

    # "ThisSite user(s) A, B and C"
    
    if (!empty($user)) {
        $user = wfMsg('siteusers', $user);
    }

    # This is the big list, all mooshed together. We sift for blank strings
    
    $fulllist = array();
      
    foreach (array($real, $user, $anon, $others_link) as $s) {
	if (!empty($s)) {
	    array_push($fulllist, $s);
	}
    }

    # Make the list into text...
    
    $creds = $wgLang->listToText($fulllist);

    # "Based on work by ..."
    
    return wfMsg('othercontribs', $creds);
}

function creditLink($user_name, $link_text = '') {
    global $wgUser, $wgLang;
    $skin = $wgUser->getSkin();
    return $skin->makeKnownLink($wgLang->getNsText(NS_USER) . ":" . $user_name,
				(empty($link_text)) ? $user_name : $link_text);
}

function creditOthersLink($article) {
    global $wgUser, $wgLang;
    $skin = $wgUser->getSkin();
    return $skin->makeKnownLink($article->mTitle->getPrefixedText(), wfMsg('others'), "action=credits");
}

?>
