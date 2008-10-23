<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Run text & title search and display the output
 * @file
 * @ingroup SpecialPage
 */

/**
 * Entry point
 *
 * @param $par String: (default '')
 */
function wfSpecialSearch( $par = '' ) {
	global $wgRequest, $wgUser;

	// Strip underscores from title parameter; most of the time we'll want
	// text form here. But don't strip underscores from actual text params!
	$titleParam = str_replace( '_', ' ', $par );
	
	$search = str_replace( "\n", " ", $wgRequest->getText( 'search', $titleParam ) );
	$searchPage = new SpecialSearch( $wgRequest, $wgUser );
	if( $wgRequest->getVal( 'fulltext' ) 
		|| !is_null( $wgRequest->getVal( 'offset' )) 
		|| !is_null( $wgRequest->getVal( 'searchx' ))) {
		$searchPage->showResults( $search, 'search' );
	} else {
		$searchPage->goResult( $search );
	}
}

/**
 * implements Special:Search - Run text & title search and display the output
 * @ingroup SpecialPage
 */
class SpecialSearch {

	/**
	 * Set up basic search parameters from the request and user settings.
	 * Typically you'll pass $wgRequest and $wgUser.
	 *
	 * @param WebRequest $request
	 * @param User $user
	 * @public
	 */
	function SpecialSearch( &$request, &$user ) {
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, 'searchlimit' );

		$this->namespaces = $this->powerSearch( $request );
		if( empty( $this->namespaces ) ) {
			$this->namespaces = SearchEngine::userNamespaces( $user );
		}

		$this->searchRedirects = $request->getcheck( 'redirs' ) ? true : false;
		$this->searchAdvanced = $request->getVal('advanced');
	}

	/**
	 * If an exact title match can be found, jump straight ahead to it.
	 * @param string $term
	 * @public
	 */
	function goResult( $term ) {
		global $wgOut;
		global $wgGoToEdit;

		$this->setupPage( $term );

		# Try to go to page as entered.
		$t = Title::newFromText( $term );

		# If the string cannot be used to create a title
		if( is_null( $t ) ){
			return $this->showResults( $term );
		}

		# If there's an exact or very near match, jump right there.
		$t = SearchEngine::getNearMatch( $term );
		if( !is_null( $t ) ) {
			$wgOut->redirect( $t->getFullURL() );
			return;
		}

		# No match, generate an edit URL
		$t = Title::newFromText( $term );
		if( ! is_null( $t ) ) {
			wfRunHooks( 'SpecialSearchNogomatch', array( &$t ) );
			# If the feature is enabled, go straight to the edit page
			if ( $wgGoToEdit ) {
				$wgOut->redirect( $t->getFullURL( 'action=edit' ) );
				return;
			}
		}

		return $this->showResults( $term );
	}

	/**
	 * @param string $term
	 * @public
	 */
	function showResults( $term ) {
		$fname = 'SpecialSearch::showResults';
		wfProfileIn( $fname );
		global $wgOut, $wgUser;
		$sk = $wgUser->getSkin();

		$this->setupPage( $term );
		$this->searchEngine = SearchEngine::create();
		
		$t = Title::newFromText( $term );
		
		// add a table since it's difficult to stack divs horizontally nicely
		// left - search box, right - search menu

		$wgOut->addHtml( 
			Xml::openElement('table', array( 'border'=>'0' ) ).
			Xml::openElement('tr').
			Xml::openElement('td')		
		);
	
		
		if( $this->searchAdvanced ){
			$wgOut->addHTML( $this->powerSearchBox( $term ) );
			$showMenu = false;
		} else { 			
			$wgOut->addHTML( $this->shortDialog( $term ) );
			$showMenu = true;
		}
		
		$wgOut->addHtml( Xml::closeElement('div').
			Xml::closeElement('td'));
			
		
		$wgOut->addHtml( Xml::openElement('td', array( 'id' => 'mw-search-menu' )) );
		
		if( $showMenu ){
			if( $t!=null && $t->quickUserCan( 'create' ) && $t->quickUserCan( 'edit' ) ) {
				if( $t->exists() ){
					$wgOut->addWikiMsg( 'searchmenu-exists', wfEscapeWikiText( $term ) );
				} else {
					$wgOut->addWikiMsg( 'searchmenu-new', wfEscapeWikiText( $term ) );	
				}
			}
		}
		$wgOut->addWikiMsg( 'searchmenu', wfEscapeWikiText( $term ) );
		
		
		$wgOut->addHtml(
			Xml::closeElement('td').
			Xml::closeElement('tr').
			Xml::closeElement('table')
		);
		

		if( '' === trim( $term ) ) {
			// Empty query -- straight view of search form
			wfProfileOut( $fname );
			return;
		}

		global $wgDisableTextSearch;
		if ( $wgDisableTextSearch ) {
			global $wgSearchForwardUrl;
			if( $wgSearchForwardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $wgSearchForwardUrl );
				$wgOut->redirect( $url );
				return;
			}
			global $wgInputEncoding;
			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'search-external' ) ) .
				Xml::element( 'p', array( 'class' => 'mw-searchdisabled' ), wfMsg( 'searchdisabled' ) ) .
				wfMsg( 'googlesearch',
					htmlspecialchars( $term ),
					htmlspecialchars( $wgInputEncoding ),
					htmlspecialchars( wfMsg( 'searchbutton' ) )
				) .
				Xml::closeElement( 'fieldset' )
			);
			wfProfileOut( $fname );
			return;
		}

		$search = $this->searchEngine;
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->showRedirects = $this->searchRedirects;
		$rewritten = $search->replacePrefixes($term);

		$titleMatches = $search->searchTitle( $rewritten );

		// Sometimes the search engine knows there are too many hits
		if ($titleMatches instanceof SearchResultTooMany) {
			$wgOut->addWikiText( '==' . wfMsg( 'toomanymatches' ) . "==\n" );
			wfProfileOut( $fname );
			return;
		}
		
		$textMatches = $search->searchText( $rewritten );
		
		
		// did you mean... suggestions
		if($textMatches && $textMatches->hasSuggestion()){
			$st = SpecialPage::getTitleFor( 'Search' );			
			$stParams = wfArrayToCGI( array( 
					'search' 	=> $textMatches->getSuggestionQuery(), 
					'fulltext' 	=> wfMsg('search')),
					$this->powerSearchOptions());
					
			$suggestLink = '<a href="'.$st->escapeLocalURL($stParams).'">'.
					$textMatches->getSuggestionSnippet().'</a>';
			 		
			$wgOut->addHTML('<div class="searchdidyoumean">'.wfMsg('search-suggest',$suggestLink).'</div>');
		}		
	
		// show number of results
		$num = ( $titleMatches ? $titleMatches->numRows() : 0 )
			+ ( $textMatches ? $textMatches->numRows() : 0);
		$totalNum = 0;
		if($titleMatches && !is_null($titleMatches->getTotalHits()))
			$totalNum += $titleMatches->getTotalHits();
		if($textMatches && !is_null($textMatches->getTotalHits()))
			$totalNum += $textMatches->getTotalHits();
		if ( $num > 0 ) {
			if ( $totalNum > 0 ){
				$top = wfMsgExt('showingresultstotal', array( 'parseinline' ), 
					$this->offset+1, $this->offset+$num, $totalNum, $num );
			} elseif ( $num >= $this->limit ) {
				$top = wfShowingResults( $this->offset, $this->limit );
			} else {
				$top = wfShowingResultsNum( $this->offset, $this->limit, $num );
			}
			$wgOut->addHTML( "<p class='mw-search-numberresults'>{$top}</p>\n" );
		}

		// prev/next links
		if( $num || $this->offset ) {
			$prevnext = wfViewPrevNext( $this->offset, $this->limit,
				SpecialPage::getTitleFor( 'Search' ),
				wfArrayToCGI(
					$this->powerSearchOptions(),
					array( 'search' => $term ) ),
					($num < $this->limit) );
			$wgOut->addHTML( "<p class='mw-search-pager-top'>{$prevnext}</p>\n" );
			wfRunHooks( 'SpecialSearchResults', array( $term, &$titleMatches, &$textMatches ) );
		} else {
			wfRunHooks( 'SpecialSearchNoResults', array( $term ) );
		}

		if( $titleMatches ) {
			if( $titleMatches->numRows() ) {
				$wgOut->wrapWikiMsg( "==$1==\n", 'titlematches' );
				$wgOut->addHTML( $this->showMatches( $titleMatches ) );
			}
			$titleMatches->free();
		}

		if( $textMatches ) {
			// output appropriate heading
			if( $textMatches->numRows() ) {
				if($titleMatches)
					$wgOut->wrapWikiMsg( "==$1==\n", 'textmatches' );
				else // if no title matches the heading is redundant
					$wgOut->addHTML("<hr/>");								
			} elseif( $num == 0 ) {
				# Don't show the 'no text matches' if we received title matches
				$wgOut->wrapWikiMsg( "==$1==\n", 'notextmatches' );
			}
			// show interwiki results if any
			if( $textMatches->hasInterwikiResults() )
				$wgOut->addHtml( $this->showInterwiki( $textMatches->getInterwikiResults(), $term ));
			// show results
			if( $textMatches->numRows() )
				$wgOut->addHTML( $this->showMatches( $textMatches ) );

			$textMatches->free();
		}

		if ( $num == 0 ) {
			$wgOut->addWikiMsg( 'search-nonefound' );
		}
		if( $num || $this->offset ) {
			$wgOut->addHTML( "<p class='mw-search-pager-bottom'>{$prevnext}</p>\n" );
		}
		wfProfileOut( $fname );
	}

	#------------------------------------------------------------------
	# Private methods below this line
	
	/**
	 *
	 */
	function setupPage( $term ) {
		global $wgOut;
		if( !empty( $term ) ){
			$wgOut->setPageTitle( wfMsg( 'searchresults') );
			$wgOut->setHTMLTitle( wfMsg( 'pagetitle', wfMsg( 'searchresults-title', $term) ) );
		}			
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
	}

	/**
	 * Extract "power search" namespace settings from the request object,
	 * returning a list of index numbers to search.
	 *
	 * @param WebRequest $request
	 * @return array
	 * @private
	 */
	function powerSearch( &$request ) {
		$arr = array();
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			if( $request->getCheck( 'ns' . $ns ) ) {
				$arr[] = $ns;
			}
		}
		return $arr;
	}

	/**
	 * Reconstruct the 'power search' options for links
	 * @return array
	 * @private
	 */
	function powerSearchOptions() {
		$opt = array();
		foreach( $this->namespaces as $n ) {
			$opt['ns' . $n] = 1;
		}
		$opt['redirs'] = $this->searchRedirects ? 1 : 0;
		if( $this->searchAdvanced )
			$opt['advanced'] = $this->searchAdvanced;
		return $opt;
	}

	/**
	 * Show whole set of results 
	 * 
	 * @param SearchResultSet $matches
	 */
	function showMatches( &$matches ) {
		$fname = 'SpecialSearch::showMatches';
		wfProfileIn( $fname );

		global $wgContLang;
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );

		$out = "";
		
		$infoLine = $matches->getInfo();
		if( !is_null($infoLine) )
			$out .= "\n<!-- {$infoLine} -->\n";
			
		
		$off = $this->offset + 1;
		$out .= "<ul class='mw-search-results'>\n";

		while( $result = $matches->next() ) {
			$out .= $this->showHit( $result, $terms );
		}
		$out .= "</ul>\n";

		// convert the whole thing to desired language variant
		global $wgContLang;
		$out = $wgContLang->convert( $out );
		wfProfileOut( $fname );
		return $out;
	}

	/**
	 * Format a single hit result
	 * @param SearchResult $result
	 * @param array $terms terms to highlight
	 */
	function showHit( $result, $terms ) {
		$fname = 'SpecialSearch::showHit';
		wfProfileIn( $fname );
		global $wgUser, $wgContLang, $wgLang;
		
		if( $result->isBrokenTitle() ) {
			wfProfileOut( $fname );
			return "<!-- Broken link in search result -->\n";
		}
		
		$t = $result->getTitle();
		$sk = $wgUser->getSkin();

		$link = $sk->makeKnownLinkObj( $t, $result->getTitleSnippet($terms));

		//If page content is not readable, just return the title.
		//This is not quite safe, but better than showing excerpts from non-readable pages
		//Note that hiding the entry entirely would screw up paging.
		if (!$t->userCanRead()) {
			wfProfileOut( $fname );
			return "<li>{$link}</li>\n";
		}

		// If the page doesn't *exist*... our search index is out of date.
		// The least confusing at this point is to drop the result.
		// You may get less results, but... oh well. :P
		if( $result->isMissingRevision() ) {
			wfProfileOut( $fname );
			return "<!-- missing page " .
				htmlspecialchars( $t->getPrefixedText() ) . "-->\n";
		}

		// format redirects / relevant sections
		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet($terms);
		$sectionTitle = $result->getSectionTitle();
		$sectionText = $result->getSectionSnippet($terms);
		$redirect = '';
		if( !is_null($redirectTitle) )
			$redirect = "<span class='searchalttitle'>"
				.wfMsg('search-redirect',$sk->makeKnownLinkObj( $redirectTitle, $redirectText))
				."</span>";
		$section = '';
		if( !is_null($sectionTitle) )
			$section = "<span class='searchalttitle'>" 
				.wfMsg('search-section', $sk->makeKnownLinkObj( $sectionTitle, $sectionText))
				."</span>";

		// format text extract
		$extract = "<div class='searchresult'>".$result->getTextSnippet($terms)."</div>";
		
		// format score
		if( is_null( $result->getScore() ) ) {
			// Search engine doesn't report scoring info
			$score = '';
		} else {
			$percent = sprintf( '%2.1f', $result->getScore() * 100 );
			$score = wfMsg( 'search-result-score', $wgLang->formatNum( $percent ) )
				. ' - ';
		}

		// format description
		$byteSize = $result->getByteSize();
		$wordCount = $result->getWordCount();
		$timestamp = $result->getTimestamp();
		$size = wfMsgExt( 'search-result-size', array( 'parsemag', 'escape' ),
			$sk->formatSize( $byteSize ),
			$wordCount );
		$date = $wgLang->timeanddate( $timestamp );

		// link to related articles if supported
		$related = '';
		if( $result->hasRelated() ){
			$st = SpecialPage::getTitleFor( 'Search' );
			$stParams = wfArrayToCGI( $this->powerSearchOptions(),
				array('search'    => wfMsgForContent('searchrelated').':'.$t->getPrefixedText(),
				      'fulltext'  => wfMsg('search') ));
			
			$related = ' -- <a href="'.$st->escapeLocalURL($stParams).'">'. 
				wfMsg('search-relatedarticle').'</a>';
		}
				
		// Include a thumbnail for media files...
		if( $t->getNamespace() == NS_IMAGE ) {
			$img = wfFindFile( $t );
			if( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$desc = $img->getShortDesc();
					wfProfileOut( $fname );
					// Ugly table. :D
					// Float doesn't seem to interact well with the bullets.
					// Table messes up vertical alignment of the bullet, but I'm
					// not sure what more I can do about that. :(
					return "<li>" .
						'<table class="searchResultImage">' .
						'<tr>' .
						'<td width="120" align="center">' .
						$thumb->toHtml( array( 'desc-link' => true ) ) .
						'</td>' .
						'<td valign="top">' .
						$link .
						$extract .
						"<div class='mw-search-result-data'>{$score}{$desc} - {$date}{$related}</div>" .
						'</td>' .
						'</tr>' .
						'</table>' .
						"</li>\n";
				}
			}
		}

		wfProfileOut( $fname );
		return "<li>{$link} {$redirect} {$section} {$extract}\n" .
			"<div class='mw-search-result-data'>{$score}{$size} - {$date}{$related}</div>" .
			"</li>\n";

	}

	/**
	 * Show results from other wikis
	 * 
	 * @param SearchResultSet $matches
	 */
	function showInterwiki( &$matches, $query ) {
		$fname = 'SpecialSearch::showInterwiki';
		wfProfileIn( $fname );

		global $wgContLang;
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );

		$out = "<div id='mw-search-interwiki'><div id='mw-search-interwiki-caption'>".wfMsg('search-interwiki-caption')."</div>\n";		
		$off = $this->offset + 1;
		$out .= "<ul start='{$off}' class='mw-search-iwresults'>\n";

		// work out custom project captions
		$customCaptions = array();
		$customLines = explode("\n",wfMsg('search-interwiki-custom')); // format per line <iwprefix>:<caption>
		foreach($customLines as $line){
			$parts = explode(":",$line,2);
			if(count($parts) == 2) // validate line
				$customCaptions[$parts[0]] = $parts[1]; 
		}
		
		
		$prev = null;
		while( $result = $matches->next() ) {
			$out .= $this->showInterwikiHit( $result, $prev, $terms, $query, $customCaptions );
			$prev = $result->getInterwikiPrefix();
		}
		// FIXME: should support paging in a non-confusing way (not sure how though, maybe via ajax)..
		$out .= "</ul></div>\n";

		// convert the whole thing to desired language variant
		global $wgContLang;
		$out = $wgContLang->convert( $out );
		wfProfileOut( $fname );
		return $out;
	}
	
	/**
	 * Show single interwiki link
	 *
	 * @param SearchResult $result
	 * @param string $lastInterwiki
	 * @param array $terms
	 * @param string $query 
	 * @param array $customCaptions iw prefix -> caption
	 */
	function showInterwikiHit( $result, $lastInterwiki, $terms, $query, $customCaptions){
		$fname = 'SpecialSearch::showInterwikiHit';
		wfProfileIn( $fname );
		global $wgUser, $wgContLang, $wgLang;
		
		if( $result->isBrokenTitle() ) {
			wfProfileOut( $fname );
			return "<!-- Broken link in search result -->\n";
		}
		
		$t = $result->getTitle();
		$sk = $wgUser->getSkin();
		
		$link = $sk->makeKnownLinkObj( $t, $result->getTitleSnippet($terms));
				
		// format redirect if any
		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet($terms);
		$redirect = '';
		if( !is_null($redirectTitle) )
			$redirect = "<span class='searchalttitle'>"
				.wfMsg('search-redirect',$sk->makeKnownLinkObj( $redirectTitle, $redirectText))
				."</span>";

		$out = "";
		// display project name 
		if(is_null($lastInterwiki) || $lastInterwiki != $t->getInterwiki()){
			if( key_exists($t->getInterwiki(),$customCaptions) )
				// captions from 'search-interwiki-custom'
				$caption = $customCaptions[$t->getInterwiki()];
			else{
				// default is to show the hostname of the other wiki which might suck 
				// if there are many wikis on one hostname
				$parsed = parse_url($t->getFullURL());
				$caption = wfMsg('search-interwiki-default', $parsed['host']); 
			}		
			// "more results" link (special page stuff could be localized, but we might not know target lang)
			$searchTitle = Title::newFromText($t->getInterwiki().":Special:Search");   			
			$searchLink = $sk->makeKnownLinkObj( $searchTitle, wfMsg('search-interwiki-more'),
				wfArrayToCGI(array('search' => $query, 'fulltext' => 'Search'))); 
			$out .= "</ul><div class='mw-search-interwiki-project'><span class='mw-search-interwiki-more'>{$searchLink}</span>{$caption}</div>\n<ul>";
		}

		$out .= "<li>{$link} {$redirect}</li>\n"; 
		wfProfileOut( $fname );
		return $out;
	}
	

	/**
	 * Generates the power search box at bottom of [[Special:Search]]
	 * @param $term string: search term
	 * @return $out string: HTML form
	 */
	function powerSearchBox( $term ) {
		global $wgScript;

		$namespaces = '';
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			$name = str_replace( '_', ' ', $name );
			if( '' == $name ) {
				$name = wfMsg( 'blanknamespace' );
			}
			$namespaces .= Xml::openElement( 'span', array( 'style' => 'white-space: nowrap' ) ) .
					Xml::checkLabel( $name, "ns{$ns}", "mw-search-ns{$ns}", in_array( $ns, $this->namespaces ) ) .
					Xml::closeElement( 'span' ) . "\n";
		}

		if( $this->searchEngine->acceptListRedirects() ){
			$redirect = Xml::check( 'redirs', $this->searchRedirects, array( 'value' => '1', 'id' => 'redirs' ) );
			$redirectLabel =  Xml::label( wfMsg( 'powersearch-redir' ), 'redirs' );
		} else{
			$redirect = '';
			$redirectLabel = '';
		}
		$searchField = Xml::input( 'search', 50, $term, array( 'type' => 'text', 'id' => 'powerSearchText' ) );
		$searchButton = Xml::submitButton( wfMsg( 'powersearch' ), array( 'name' => 'fulltext' ) ) . "\n";
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		
		$out = Xml::openElement( 'form', array(	'id' => 'powersearch', 'method' => 'get', 'action' => $wgScript ) ) .
				Xml::hidden( 'title', $searchTitle->getPrefixedText() ) .
				"<p>" .
				wfMsgExt( 'powersearch-ns', array( 'parseinline' ) ) .
				"<br />" .
				$namespaces .
				"</p>" .
				"<p>" .
				$redirect . " " . $redirectLabel .
				"</p>" .
				wfMsgExt( 'powersearch-field', array( 'parseinline' ) ) .
				"&nbsp;" .
				$searchField .
				"&nbsp;" .
				$searchButton.
			"</form>";

		return Xml::openElement( 'fieldset', array( 'id' => 'mw-searchoptions' ) ) . $this->formHeader($term) . 
					$out . Xml::closeElement( 'fieldset' );
	}

	function powerSearchFocus() {
		global $wgJsMimeType;
		return "<script type=\"$wgJsMimeType\">" .
			"hookEvent(\"load\", function(){" .
				"document.getElementById('powerSearchText').focus();" .
			"});" .
			"</script>";
	}
	
	/** Make a search link with some target namespaces */
	function makeSearchLink($term, $namespaces, $label, $tooltip, $params=array()){
		$opt = $params;
		foreach( $namespaces as $n ) {
			$opt['ns' . $n] = 1;
		}
		$opt['redirs'] = $this->searchRedirects ? 1 : 0;
		
		$st = SpecialPage::getTitleFor( 'Search' );		
		$stParams = wfArrayToCGI( array( 
				'search' 	=> $term, 
				'fulltext' 	=> wfMsg('search')),
				$opt);
				
		return Xml::element('a', 
			array('href'=> $st->getLocalURL($stParams), 'title' => $tooltip), 
			$label);
		
	}
	
	/** Check if query starts with image: prefix */
	function startsWithImage($term){
		global $wgContLang;
		
		$p = explode(':',$term);
		if( count($p)> 1 ){
			return $wgContLang->getNsIndex($p[0]) == NS_IMAGE;
		}
		return false;
	}
	
	/** Check if query begins with all: magic prefix */
	function startsWithAll($term){
		global $wgContLang;
		
		$p = explode(':',$term);
		return count($p) > 1 && $p[0]==wfMsg('searchall');
	}

	function formHeader($term) {
		global $wgContLang;
		
		$sep = '&nbsp;&nbsp;&nbsp;';
		$out = Xml::openElement('div', array('style'=>'padding-bottom:0.5em;'));
		
		$bareterm = $term;
		if($this->startsWithAll($term) || $this->startsWithImage($term))
			$bareterm = substr( $term, strpos($term,':')+1 ); // delete all/image prefix
			
		// figure out the active search profile header
		if( $this->searchAdvanced )
			$active = 'advanced';
		else if( $this->namespaces == NS_IMAGE || $this->startsWithImage($term) )
			$active = 'images';
		elseif( $this->startsWithAll($term) )
			$active = 'all';
		elseif( $this->namespaces == SearchEngine::defaultNamespaces() )
			$active = 'default';
		elseif( $this->namespaces == SearchEngine::projectNamespaces() )
			$active = 'project';
		else
			$active = 'advanced';
		
		
		// search profiles headers
		$m = wfMsg('searchprofile-articles');
		$tt = wfMsg('searchprofile-articles-tooltip', 
			implode(', ', SearchEngine::namespacesAsText( SearchEngine::defaultNamespaces() )));
		if( $active == 'default' ){
			$out .= Xml::element('strong', array('title'=>$tt), $m);	
		} else
			$out .= $this->makeSearchLink( $bareterm, SearchEngine::defaultNamespaces(), $m, $tt );
			
		$out .= $sep;
			
		$m = wfMsg('searchprofile-project');
		$tt = wfMsg('searchprofile-project-tooltip', 
			implode(', ', SearchEngine::namespacesAsText( SearchEngine::projectNamespaces() )));
		if( $active == 'project' ){
			$out .= Xml::element('strong', array('title'=>$tt), $m);	
		} else
			$out .= $this->makeSearchLink( $bareterm, SearchEngine::projectNamespaces(), $m, $tt );
			
		$out .= $sep;
			
		$m = wfMsg('searchprofile-images');
		$tt = wfMsg('searchprofile-images-tooltip');
		if( $active == 'images' ){
			$out .= Xml::element('strong', array('title'=>$tt), $m);	
		} else
			$out .= $this->makeSearchLink( $wgContLang->getFormattedNsText(NS_IMAGE).':'.$bareterm, array() , $m, $tt );
			
		$out .= $sep;
			
		$m = wfMsg('searchprofile-everything');
		$tt = wfMsg('searchprofile-everything-tooltip');
		if( $active == 'all' ){
			$out .= Xml::element('strong', array('title'=>$tt), $m);	
		} else
			$out .= $this->makeSearchLink( wfMsg('searchall').':'.$bareterm, array() , $m, $tt );
			
		$out .= $sep;
			
		$m = wfMsg('searchprofile-advanced');
		$tt = wfMsg('searchprofile-advanced-tooltip');
		if( $active == 'advanced' ){
			$out .= Xml::element('strong', array('title'=>$tt), $m);	
		} else
			$out .= $this->makeSearchLink( $bareterm, array() , $m, $tt, array( 'advanced' => '1') );
			
		$out .= Xml::closeElement('div') ;
		
		return $out;
	}
	
	function shortDialog($term) {
		global $wgScript;
		
		$out = Xml::openElement( 'form', array(
			'id' => 'search',
			'method' => 'get',
			'action' => $wgScript
		));
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		$out .= Xml::hidden( 'title', $searchTitle->getPrefixedText() );
		$out .= Xml::input( 'search', 50, $term, array( 'type' => 'text', 'id' => 'searchText' ) ) . ' ';
		
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			if( in_array( $ns, $this->namespaces ) ) {
				$out .= Xml::hidden( "ns{$ns}", '1' );
			}
		}
		$out .= Xml::submitButton( wfMsg( 'searchbutton' ), array( 'name' => 'fulltext' ) );
		$out .= Xml::closeElement( 'form' );
		return Xml::openElement( 'fieldset', array( 'id' => 'mw-searchoptions' ) ) . $this->formHeader($term) . 
					$out . Xml::closeElement( 'fieldset' );
	}
}
