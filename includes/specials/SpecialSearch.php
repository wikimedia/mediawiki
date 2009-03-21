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
	global $wgRequest, $wgUser, $wgUseOldSearchUI;
	// Strip underscores from title parameter; most of the time we'll want
	// text form here. But don't strip underscores from actual text params!
	$titleParam = str_replace( '_', ' ', $par );
	// Fetch the search term
	$search = str_replace( "\n", " ", $wgRequest->getText( 'search', $titleParam ) );
	$class = $wgUseOldSearchUI ? 'SpecialSearchOld' : 'SpecialSearch';
	$searchPage = new $class( $wgRequest, $wgUser );
	if( $wgRequest->getVal( 'fulltext' ) 
		|| !is_null( $wgRequest->getVal( 'offset' )) 
		|| !is_null( $wgRequest->getVal( 'searchx' )) )
	{
		$searchPage->showResults( $search );
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
	function __construct( &$request, &$user ) {
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, 'searchlimit' );
		$this->mPrefix = $request->getVal('prefix', '');
		# Extract requested namespaces
		$this->namespaces = $this->powerSearch( $request );
		if( empty( $this->namespaces ) ) {
			$this->namespaces = SearchEngine::userNamespaces( $user );
		}
		$this->searchRedirects = $request->getcheck( 'redirs' ) ? true : false;
		$this->searchAdvanced = $request->getVal( 'advanced' );
		$this->active = 'advanced';
		$this->sk = $user->getSkin();
		$this->didYouMeanHtml = ''; # html of did you mean... link
		$this->fulltext = $request->getVal('fulltext'); 
	}

	/**
	 * If an exact title match can be found, jump straight ahead to it.
	 * @param string $term
	 */
	public function goResult( $term ) {
		global $wgOut;
		$this->setupPage( $term );
		# Try to go to page as entered.
		$t = Title::newFromText( $term );
		# If the string cannot be used to create a title
		if( is_null( $t ) ) {
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
		if( !is_null( $t ) ) {
			global $wgGoToEdit;
			wfRunHooks( 'SpecialSearchNogomatch', array( &$t ) );
			# If the feature is enabled, go straight to the edit page
			if( $wgGoToEdit ) {
				$wgOut->redirect( $t->getFullURL( 'action=edit' ) );
				return;
			}
		}
		return $this->showResults( $term );
	}

	/**
	 * @param string $term
	 */
	public function showResults( $term ) {
		global $wgOut, $wgUser, $wgDisableTextSearch, $wgContLang;
		wfProfileIn( __METHOD__ );
		
		$sk = $wgUser->getSkin();
		
		$this->searchEngine = SearchEngine::create();
		$search =& $this->searchEngine;
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->showRedirects = $this->searchRedirects;
		$search->prefix = $this->mPrefix;
		$term = $search->transformSearchTerm($term);
		
		$this->setupPage( $term );
		
		if( $wgDisableTextSearch ) {
			global $wgSearchForwardUrl;
			if( $wgSearchForwardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $wgSearchForwardUrl );
				$wgOut->redirect( $url );
				wfProfileOut( __METHOD__ );
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
			wfProfileOut( __METHOD__ );
			return;
		}
		
		$t = Title::newFromText( $term );
		
		// fetch search results		
		$rewritten = $search->replacePrefixes($term);

		$titleMatches = $search->searchTitle( $rewritten );
		if( !($titleMatches instanceof SearchResultTooMany))
			$textMatches = $search->searchText( $rewritten );

		// did you mean... suggestions
		if( $textMatches && $textMatches->hasSuggestion() ) {
			$st = SpecialPage::getTitleFor( 'Search' );
			# mirror Go/Search behaviour of original request ..
			$didYouMeanParams = array( 'search' => $textMatches->getSuggestionQuery() );
			if($this->fulltext != NULL)
				$didYouMeanParams['fulltext'] = $this->fulltext;				
			$stParams = wfArrayToCGI( 
				$didYouMeanParams,
				$this->powerSearchOptions()
			);
			$suggestLink = $sk->makeKnownLinkObj( $st,
				$textMatches->getSuggestionSnippet(),
				$stParams );

			$this->didYouMeanHtml = '<div class="searchdidyoumean">'.wfMsg('search-suggest',$suggestLink).'</div>';
		}
		
		// start rendering the page		
		$wgOut->addHtml( 
			Xml::openElement( 'table', array( 'border'=>0, 'cellpadding'=>0, 'cellspacing'=>0 ) ) .
			Xml::openElement( 'tr' ) .
			Xml::openElement( 'td' ) . "\n"	.
			( $this->searchAdvanced ? $this->powerSearchBox( $term ) : $this->shortDialog( $term ) ) .
			Xml::closeElement('td') .
			Xml::closeElement('tr') .
			Xml::closeElement('table')
		);
		
		// Sometimes the search engine knows there are too many hits
		if( $titleMatches instanceof SearchResultTooMany ) {
			$wgOut->addWikiText( '==' . wfMsg( 'toomanymatches' ) . "==\n" );
			wfProfileOut( __METHOD__ );
			return;
		}
		
		$filePrefix = $wgContLang->getFormattedNsText(NS_FILE).':';
		if( '' === trim( $term ) || $filePrefix === trim( $term ) ) {
			$wgOut->addHTML( $this->searchAdvanced ? $this->powerSearchFocus() : $this->searchFocus() );
			// Empty query -- straight view of search form
			wfProfileOut( __METHOD__ );
			return;
		}

		// show direct page/create link
		if( !is_null($t) ) {
			if( !$t->exists() ) {
				$wgOut->addWikiMsg( 'searchmenu-new', wfEscapeWikiText( $t->getPrefixedText() ) );
			} else {
				$wgOut->addWikiMsg( 'searchmenu-exists', wfEscapeWikiText( $t->getPrefixedText() ) );
			}
		}

		// Get number of results
		$titleMatchesSQL = $titleMatches ? $titleMatches->numRows() : 0;
		$textMatchesSQL = $textMatches ? $textMatches->numRows() : 0;
		// Total initial query matches (possible false positives)
		$numSQL = $titleMatchesSQL + $textMatchesSQL;
		// Get total actual results (after second filtering, if any)
		$numTitleMatches = $titleMatches && !is_null( $titleMatches->getTotalHits() ) ?
			$titleMatches->getTotalHits() : $titleMatchesSQL;
		$numTextMatches = $textMatches && !is_null( $textMatches->getTotalHits() ) ?
			$textMatches->getTotalHits() : $textMatchesSQL;
		$totalRes = $numTitleMatches + $numTextMatches;

		// show number of results and current offset
		if( $numSQL > 0 ) {
			if( $numSQL > 0 ) {
				$top = wfMsgExt('showingresultstotal', array( 'parseinline' ), 
					$this->offset+1, $this->offset+$numSQL, $totalRes, $numSQL );
			} elseif( $numSQL >= $this->limit ) {
				$top = wfShowingResults( $this->offset, $this->limit );
			} else {
				$top = wfShowingResultsNum( $this->offset, $this->limit, $numSQL );
			}
			$wgOut->addHTML( "<p class='mw-search-numberresults'>{$top}</p>\n" );
		}

		// prev/next links
		if( $numSQL || $this->offset ) {
			$prevnext = wfViewPrevNext( $this->offset, $this->limit,
				SpecialPage::getTitleFor( 'Search' ),
				wfArrayToCGI( $this->powerSearchOptions(), array( 'search' => $term ) ),
				max( $titleMatchesSQL, $textMatchesSQL ) < $this->limit
			);
			$wgOut->addHTML( "<p class='mw-search-pager-top'>{$prevnext}</p>\n" );
			wfRunHooks( 'SpecialSearchResults', array( $term, &$titleMatches, &$textMatches ) );
		} else {
			wfRunHooks( 'SpecialSearchNoResults', array( $term ) );
		}

		$wgOut->addHtml( "<div class='searchresults'>" );
		if( $titleMatches ) {
			if( $numTitleMatches > 0 ) {
				$wgOut->wrapWikiMsg( "==$1==\n", 'titlematches' );
				$wgOut->addHTML( $this->showMatches( $titleMatches ) );
			}
			$titleMatches->free();
		}
		if( $textMatches ) {
			// output appropriate heading
			if( $numTextMatches > 0 && $numTitleMatches > 0 ) {
				// if no title matches the heading is redundant
				$wgOut->wrapWikiMsg( "==$1==\n", 'textmatches' );					
			} elseif( $totalRes == 0 ) {
				# Don't show the 'no text matches' if we received title matches
				$wgOut->wrapWikiMsg( "==$1==\n", 'notextmatches' );
			}
			// show interwiki results if any
			if( $textMatches->hasInterwikiResults() ) {
				$wgOut->addHTML( $this->showInterwiki( $textMatches->getInterwikiResults(), $term ) );
			}
			// show results
			if( $numTextMatches > 0 ) {
				$wgOut->addHTML( $this->showMatches( $textMatches ) );
			}

			$textMatches->free();
		}
		if( $totalRes === 0 ) {
			$wgOut->addWikiMsg( 'search-nonefound' );
		}
		$wgOut->addHtml( "</div>" );
		if( $totalRes === 0 ) {
			$wgOut->addHTML( $this->searchAdvanced ? $this->powerSearchFocus() : $this->searchFocus() );
		}

		if( $numSQL || $this->offset ) {
			$wgOut->addHTML( "<p class='mw-search-pager-bottom'>{$prevnext}</p>\n" );
		}
		wfProfileOut( __METHOD__ );
	}
	
	/**
	 *
	 */
	protected function setupPage( $term ) {
		global $wgOut;
		// Figure out the active search profile header
		$nsAllSet = array_keys( SearchEngine::searchableNamespaces() );
		if( $this->searchAdvanced )
			$this->active = 'advanced';
		else if( $this->namespaces === NS_FILE || $this->startsWithImage( $term ) )
			$this->active = 'images';
		elseif( $this->namespaces === $nsAllSet )
			$this->active = 'all';
		elseif( $this->namespaces === SearchEngine::defaultNamespaces() )
			$this->active = 'default';
		elseif( $this->namespaces === SearchEngine::projectNamespaces() )
			$this->active = 'project';
		else
			$this->active = 'advanced';
		# Should advanced UI be used?
		$this->searchAdvanced = ($this->active === 'advanced');
		if( !empty( $term ) ) {
			$wgOut->setPageTitle( wfMsg( 'searchresults') );
			$wgOut->setHTMLTitle( wfMsg( 'pagetitle', wfMsg( 'searchresults-title', $term ) ) );
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
	 */
	protected function powerSearch( &$request ) {
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
	 */
	protected function powerSearchOptions() {
		$opt = array();
		foreach( $this->namespaces as $n ) {
			$opt['ns' . $n] = 1;
		}
		$opt['redirs'] = $this->searchRedirects ? 1 : 0;
		if( $this->searchAdvanced ) {
			$opt['advanced'] = $this->searchAdvanced;
		}
		return $opt;
	}

	/**
	 * Show whole set of results 
	 * 
	 * @param SearchResultSet $matches
	 */
	protected function showMatches( &$matches ) {
		global $wgContLang;
		wfProfileIn( __METHOD__ );

		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );

		$out = "";
		$infoLine = $matches->getInfo();
		if( !is_null($infoLine) ) {
			$out .= "\n<!-- {$infoLine} -->\n";
		}
		$off = $this->offset + 1;
		$out .= "<ul class='mw-search-results'>\n";
		while( $result = $matches->next() ) {
			$out .= $this->showHit( $result, $terms );
		}
		$out .= "</ul>\n";

		// convert the whole thing to desired language variant
		$out = $wgContLang->convert( $out );
		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * Format a single hit result
	 * @param SearchResult $result
	 * @param array $terms terms to highlight
	 */
	protected function showHit( $result, $terms ) {
		global $wgContLang, $wgLang, $wgUser;
		wfProfileIn( __METHOD__ );

		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- Broken link in search result -->\n";
		}

		$sk = $wgUser->getSkin();
		$t = $result->getTitle();

		$link = $this->sk->makeKnownLinkObj( $t, $result->getTitleSnippet($terms));

		//If page content is not readable, just return the title.
		//This is not quite safe, but better than showing excerpts from non-readable pages
		//Note that hiding the entry entirely would screw up paging.
		if( !$t->userCanRead() ) {
			wfProfileOut( __METHOD__ );
			return "<li>{$link}</li>\n";
		}

		// If the page doesn't *exist*... our search index is out of date.
		// The least confusing at this point is to drop the result.
		// You may get less results, but... oh well. :P
		if( $result->isMissingRevision() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- missing page " . htmlspecialchars( $t->getPrefixedText() ) . "-->\n";
		}

		// format redirects / relevant sections
		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet($terms);
		$sectionTitle = $result->getSectionTitle();
		$sectionText = $result->getSectionSnippet($terms);
		$redirect = '';
		if( !is_null($redirectTitle) )
			$redirect = "<span class='searchalttitle'>"
				.wfMsg('search-redirect',$this->sk->makeKnownLinkObj( $redirectTitle, $redirectText))
				."</span>";
		$section = '';
		if( !is_null($sectionTitle) )
			$section = "<span class='searchalttitle'>" 
				.wfMsg('search-section', $this->sk->makeKnownLinkObj( $sectionTitle, $sectionText))
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
			$this->sk->formatSize( $byteSize ), $wordCount );
		$date = $wgLang->timeanddate( $timestamp );

		// link to related articles if supported
		$related = '';
		if( $result->hasRelated() ) {
			$st = SpecialPage::getTitleFor( 'Search' );
			$stParams = wfArrayToCGI( $this->powerSearchOptions(),
				array('search'    => wfMsgForContent('searchrelated').':'.$t->getPrefixedText(),
				      'fulltext'  => wfMsg('search') ));
			
			$related = ' -- ' . $sk->makeKnownLinkObj( $st,
				wfMsg('search-relatedarticle'), $stParams );
		}

		// Include a thumbnail for media files...
		if( $t->getNamespace() == NS_FILE ) {
			$img = wfFindFile( $t );
			if( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$desc = $img->getShortDesc();
					wfProfileOut( __METHOD__ );
					// Float doesn't seem to interact well with the bullets.
					// Table messes up vertical alignment of the bullets.
					// Bullets are therefore disabled (didn't look great anyway).
					return "<li>" .
						'<table class="searchResultImage">' .
						'<tr>' .
						'<td width="120" align="center" valign="top">' .
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

		wfProfileOut( __METHOD__ );
		return "<li>{$link} {$redirect} {$section} {$extract}\n" .
			"<div class='mw-search-result-data'>{$score}{$size} - {$date}{$related}</div>" .
			"</li>\n";

	}

	/**
	 * Show results from other wikis
	 * 
	 * @param SearchResultSet $matches
	 */
	protected function showInterwiki( &$matches, $query ) {
		global $wgContLang;
		wfProfileIn( __METHOD__ );
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );

		$out = "<div id='mw-search-interwiki'><div id='mw-search-interwiki-caption'>".
			wfMsg('search-interwiki-caption')."</div>\n";		
		$off = $this->offset + 1;
		$out .= "<ul class='mw-search-iwresults'>\n";

		// work out custom project captions
		$customCaptions = array();
		$customLines = explode("\n",wfMsg('search-interwiki-custom')); // format per line <iwprefix>:<caption>
		foreach($customLines as $line) {
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
		$out = $wgContLang->convert( $out );
		wfProfileOut( __METHOD__ );
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
	protected function showInterwikiHit( $result, $lastInterwiki, $terms, $query, $customCaptions) {
		wfProfileIn( __METHOD__ );
		global $wgContLang, $wgLang;
		
		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- Broken link in search result -->\n";
		}
		
		$t = $result->getTitle();

		$link = $this->sk->makeKnownLinkObj( $t, $result->getTitleSnippet($terms));

		// format redirect if any
		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet($terms);
		$redirect = '';
		if( !is_null($redirectTitle) )
			$redirect = "<span class='searchalttitle'>"
				.wfMsg('search-redirect',$this->sk->makeKnownLinkObj( $redirectTitle, $redirectText))
				."</span>";

		$out = "";
		// display project name 
		if(is_null($lastInterwiki) || $lastInterwiki != $t->getInterwiki()) {
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
			$searchLink = $this->sk->makeKnownLinkObj( $searchTitle, wfMsg('search-interwiki-more'),
				wfArrayToCGI(array('search' => $query, 'fulltext' => 'Search'))); 
			$out .= "</ul><div class='mw-search-interwiki-project'><span class='mw-search-interwiki-more'>
				{$searchLink}</span>{$caption}</div>\n<ul>";
		}

		$out .= "<li>{$link} {$redirect}</li>\n"; 
		wfProfileOut( __METHOD__ );
		return $out;
	}
	

	/**
	 * Generates the power search box at bottom of [[Special:Search]]
	 * @param $term string: search term
	 * @return $out string: HTML form
	 */
	protected function powerSearchBox( $term ) {
		global $wgScript;

		$namespaces = SearchEngine::searchableNamespaces();

		$tables = $this->namespaceTables( $namespaces );

		$redirect = Xml::check( 'redirs', $this->searchRedirects, array( 'value' => '1', 'id' => 'redirs' ) );
		$redirectLabel = Xml::label( wfMsg( 'powersearch-redir' ), 'redirs' );
		$searchField = Xml::inputLabel( wfMsg('powersearch-field'), 'search', 'powerSearchText', 50, $term,
			array( 'type' => 'text') );
		$searchButton = Xml::submitButton( wfMsg( 'powersearch' ), array( 'name' => 'fulltext' )) . "\n";
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		
		$redirectText = '';
		// show redirects check only if backend supports it 
		if( $this->searchEngine->acceptListRedirects() ) {
			$redirectText = "<p>". $redirect . " " . $redirectLabel ."</p>";
		}
		
		$out = Xml::openElement( 'form', array(	'id' => 'powersearch', 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::hidden( 'title', $searchTitle->getPrefixedText() ) . "\n" .
			"<p>" .
			wfMsgExt( 'powersearch-ns', array( 'parseinline' ) ) .
			"</p>\n" .
			'<input type="hidden" name="advanced" value="'.$this->searchAdvanced."\"/>\n".
			$tables .
			"<hr style=\"clear: both;\" />\n".			
			$redirectText ."\n".
			"<div style=\"padding-top:2px;padding-bottom:2px;\">".
			$searchField .
			"&nbsp;" .
			Xml::hidden( 'fulltext', 'Advanced search' ) . "\n" .
			$searchButton .
			"</div>".
			"</form>";
		$t = Title::newFromText( $term );
		/* if( $t != null && count($this->namespaces) === 1 ) {
			$out .= wfMsgExt( 'searchmenu-prefix', array('parseinline'), $term );
		} */
		return Xml::openElement( 'fieldset', array('id' => 'mw-searchoptions','style' => 'margin:0em;') ) .
			Xml::element( 'legend', null, wfMsg('powersearch-legend') ) .
			$this->formHeader($term) . $out . $this->didYouMeanHtml . 
			Xml::closeElement( 'fieldset' );
	}
	
	protected function searchFocus() {
		global $wgJsMimeType;
		return "<script type=\"$wgJsMimeType\">" .
			"hookEvent(\"load\", function() {" .
				"document.getElementById('searchText').focus();" .
			"});" .
			"</script>";
	}

	protected function powerSearchFocus() {
		global $wgJsMimeType;
		return "<script type=\"$wgJsMimeType\">" .
			"hookEvent(\"load\", function() {" .
				"document.getElementById('powerSearchText').focus();" .
			"});" .
			"</script>";
	}

	protected function formHeader( $term ) {
		global $wgContLang, $wgCanonicalNamespaceNames, $wgLang;

		$sep = '&nbsp;&nbsp;&nbsp;';
		$out = Xml::openElement('div', array( 'style' => 'padding-bottom:0.5em;' ) );

		$bareterm = $term;
		if( $this->startsWithImage( $term ) )
			$bareterm = substr( $term, strpos( $term, ':' ) + 1 ); // delete all/image prefix
			
		$nsAllSet = array_keys( SearchEngine::searchableNamespaces() );

		// search profiles headers
		$m = wfMsg( 'searchprofile-articles' );
		$tt = wfMsg( 'searchprofile-articles-tooltip', 
			$wgLang->commaList( SearchEngine::namespacesAsText( SearchEngine::defaultNamespaces() ) ) );
		if( $this->active == 'default' ) {
			$out .= Xml::element( 'strong', array( 'title'=>$tt ), $m );	
		} else {
			$out .= $this->makeSearchLink( $bareterm, SearchEngine::defaultNamespaces(), $m, $tt );
		}
		$out .= $sep;
		
		$m = wfMsg( 'searchprofile-images' );
		$tt = wfMsg( 'searchprofile-images-tooltip' );
		if( $this->active == 'images' ) {
			$out .= Xml::element( 'strong', array( 'title'=>$tt ), $m );	
		} else {
			$imageTextForm = $wgContLang->getFormattedNsText(NS_FILE).':'.$bareterm;
			$out .= $this->makeSearchLink( $imageTextForm, array( NS_FILE ) , $m, $tt );
		}
		$out .= $sep;

		$m = wfMsg( 'searchprofile-project' );
		$tt = wfMsg( 'searchprofile-project-tooltip', 
			$wgLang->commaList( SearchEngine::namespacesAsText( SearchEngine::projectNamespaces() ) ) );
		if( $this->active == 'project' ) {
			$out .= Xml::element( 'strong', array( 'title'=>$tt ), $m );	
		} else {
			$out .= $this->makeSearchLink( $bareterm, SearchEngine::projectNamespaces(), $m, $tt );
		}
		$out .= $sep;
			
		$m = wfMsg( 'searchprofile-everything' );
		$tt = wfMsg( 'searchprofile-everything-tooltip' );
		if( $this->active == 'all' ) {
			$out .= Xml::element( 'strong', array( 'title'=>$tt ), $m );	
		} else {
			$out .= $this->makeSearchLink( $bareterm, $nsAllSet, $m, $tt );
		}
		$out .= $sep;
		
		$m = wfMsg( 'searchprofile-advanced' );
		$tt = wfMsg( 'searchprofile-advanced-tooltip' );
		if( $this->active == 'advanced' ) {
			$out .= Xml::element( 'strong', array( 'title'=>$tt ), $m );	
		} else {
			$out .= $this->makeSearchLink( $bareterm, $this->namespaces, $m, $tt, array( 'advanced' => '1' ) );
		}
		$out .= Xml::closeElement('div') ;
		
		return $out;
	}
	
	protected function shortDialog( $term ) {
		global $wgScript;
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		$searchable = SearchEngine::searchableNamespaces();
		$out = Xml::openElement( 'form', array( 'id' => 'search', 'method' => 'get', 'action' => $wgScript ) );
		$out .= Xml::hidden( 'title', $searchTitle->getPrefixedText() ) . "\n";
		// show namespaces only for advanced search
		if( $this->active == 'advanced' ) {
			$active = array();
			foreach( $this->namespaces as $ns ) {
				$active[$ns] = $searchable[$ns];
			}
			$out .= wfMsgExt( 'powersearch-ns', array( 'parseinline' ) ) . "<br/>\n";
			$out .= $this->namespaceTables( $active, 1 )."<br/>\n";
		// Still keep namespace settings otherwise, but don't show them
		} else {
			foreach( $this->namespaces as $ns ) {
				$out .= Xml::hidden( "ns{$ns}", '1' );
			}
		}
		// Keep redirect setting
		$out .= Xml::hidden( "redirs", (int)$this->searchRedirects );
		// Term box
		$out .= Xml::input( 'search', 50, $term, array( 'type' => 'text', 'id' => 'searchText' ) ) . "\n";
		$out .= Xml::hidden( 'fulltext', 'Search' );
		$out .= Xml::submitButton( wfMsg( 'searchbutton' ), array( 'name' => 'fulltext' ) );
		$out .= ' (' . wfMsgExt('searchmenu-help',array('parseinline') ) . ')';
		$out .= Xml::closeElement( 'form' );
		// Add prefix link for single-namespace searches
		$t = Title::newFromText( $term );
		/*if( $t != null && count($this->namespaces) === 1 ) {
			$out .= wfMsgExt( 'searchmenu-prefix', array('parseinline'), $term );
		}*/
		return Xml::openElement( 'fieldset', array('id' => 'mw-searchoptions','style' => 'margin:0em;') ) .
			Xml::element( 'legend', null, wfMsg('searchmenu-legend') ) .
			$this->formHeader($term) . $out . $this->didYouMeanHtml .
			Xml::closeElement( 'fieldset' );
	}
	
	/** Make a search link with some target namespaces */
	protected function makeSearchLink( $term, $namespaces, $label, $tooltip, $params=array() ) {
		$opt = $params;
		foreach( $namespaces as $n ) {
			$opt['ns' . $n] = 1;
		}
		$opt['redirs'] = $this->searchRedirects ? 1 : 0;

		$st = SpecialPage::getTitleFor( 'Search' );		
		$stParams = wfArrayToCGI( array( 'search' => $term, 'fulltext' => wfMsg( 'search' ) ), $opt );

		return Xml::element( 'a', 
			array( 'href'=> $st->getLocalURL( $stParams ), 'title' => $tooltip ), 
			$label );	
	}
	
	/** Check if query starts with image: prefix */
	protected function startsWithImage( $term ) {
		global $wgContLang;
		
		$p = explode( ':', $term );
		if( count( $p ) > 1 ) {
			return $wgContLang->getNsIndex( $p[0] ) == NS_FILE;
		}
		return false;
	}
	
	protected function namespaceTables( $namespaces, $rowsPerTable = 3 ) {
		global $wgContLang;
		// Group namespaces into rows according to subject.
		// Try not to make too many assumptions about namespace numbering.
		$rows = array();
		$tables = "";
		foreach( $namespaces as $ns => $name ) {
			$subj = MWNamespace::getSubject( $ns );
			if( !array_key_exists( $subj, $rows ) ) {
				$rows[$subj] = "";
			}
			$name = str_replace( '_', ' ', $name );
			if( '' == $name ) {
				$name = wfMsg( 'blanknamespace' );
			}
			$rows[$subj] .= Xml::openElement( 'td', array( 'style' => 'white-space: nowrap' ) ) .
				Xml::checkLabel( $name, "ns{$ns}", "mw-search-ns{$ns}", in_array( $ns, $this->namespaces ) ) .
				Xml::closeElement( 'td' ) . "\n";
		}
		$rows = array_values( $rows );
		$numRows = count( $rows );
		// Lay out namespaces in multiple floating two-column tables so they'll
		// be arranged nicely while still accommodating different screen widths
		// Float to the right on RTL wikis
		$tableStyle = $wgContLang->isRTL() ?
			'float: right; margin: 0 0 0em 1em' : 'float: left; margin: 0 1em 0em 0';
		// Build the final HTML table...
		for( $i = 0; $i < $numRows; $i += $rowsPerTable ) {
			$tables .= Xml::openElement( 'table', array( 'style' => $tableStyle ) );
			for( $j = $i; $j < $i + $rowsPerTable && $j < $numRows; $j++ ) {
				$tables .= "<tr>\n" . $rows[$j] . "</tr>";
			}
			$tables .= Xml::closeElement( 'table' ) . "\n";
		}
		return $tables;
	}
}

/**
 * implements Special:Search - Run text & title search and display the output
 * @ingroup SpecialPage
 */
class SpecialSearchOld {

	/**
	 * Set up basic search parameters from the request and user settings.
	 * Typically you'll pass $wgRequest and $wgUser.
	 *
	 * @param WebRequest $request
	 * @param User $user
	 * @public
	 */
	function __construct( &$request, &$user ) {
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, 'searchlimit' );
		$this->mPrefix = $request->getVal('prefix', '');
		$this->namespaces = $this->powerSearch( $request );
		if( empty( $this->namespaces ) ) {
			$this->namespaces = SearchEngine::userNamespaces( $user );
		}

		$this->searchRedirects = $request->getcheck( 'redirs' ) ? true : false;
		$this->fulltext = $request->getVal('fulltext'); 
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

		$extra = $wgOut->parse( '=='.wfMsgNoTrans( 'notitlematches' )."==\n" );
		if( $t->quickUserCan( 'create' ) && $t->quickUserCan( 'edit' ) ) {
			$extra .= wfMsgExt( 'noexactmatch', 'parse', wfEscapeWikiText( $term ) );
		} else {
			$extra .= wfMsgExt( 'noexactmatch-nocreate', 'parse', wfEscapeWikiText( $term ) );
		}

		$this->showResults( $term, $extra );
	}

	/**
	 * @param string $term
	 * @param string $extra Extra HTML to add after "did you mean"
	 */
	public function showResults( $term, $extra = '' ) {
		wfProfileIn( __METHOD__ );
		global $wgOut, $wgUser;
		$sk = $wgUser->getSkin();

		$search = SearchEngine::create();
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->showRedirects = $this->searchRedirects;
		$search->prefix = $this->mPrefix;
		$term = $search->transformSearchTerm($term);

		$this->setupPage( $term );

		$rewritten = $search->replacePrefixes($term);
		$titleMatches = $search->searchTitle( $rewritten );
		$textMatches = $search->searchText( $rewritten );

		// did you mean... suggestions
		if($textMatches && $textMatches->hasSuggestion()){
			$st = SpecialPage::getTitleFor( 'Search' );
			
			# mirror Go/Search behaviour of original request		
			$didYouMeanParams = array( 'search' => $textMatches->getSuggestionQuery() );
			if($this->fulltext != NULL)
				$didYouMeanParams['fulltext'] = $this->fulltext;				
			$stParams = wfArrayToCGI( 
				$didYouMeanParams,
				$this->powerSearchOptions()
			);	

			$suggestLink = $sk->makeKnownLinkObj( $st,
				$textMatches->getSuggestionSnippet(),
				$stParams );

			$wgOut->addHTML('<div class="searchdidyoumean">'.wfMsg('search-suggest',$suggestLink).'</div>');
		}

		$wgOut->addHTML( $extra );

		$wgOut->wrapWikiMsg( "<div class='mw-searchresult'>\n$1</div>", 'searchresulttext' );

		if( '' === trim( $term ) ) {
			// Empty query -- straight view of search form
			$wgOut->setSubtitle( '' );
			$wgOut->addHTML( $this->powerSearchBox( $term ) );
			$wgOut->addHTML( $this->powerSearchFocus() );
			wfProfileOut( __METHOD__ );
			return;
		}

		global $wgDisableTextSearch;
		if ( $wgDisableTextSearch ) {
			global $wgSearchForwardUrl;
			if( $wgSearchForwardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $wgSearchForwardUrl );
				$wgOut->redirect( $url );
				wfProfileOut( __METHOD__ );
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
			wfProfileOut( __METHOD__ );
			return;
		}

		$wgOut->addHTML( $this->shortDialog( $term ) );		

		// Sometimes the search engine knows there are too many hits
		if ($titleMatches instanceof SearchResultTooMany) {
			$wgOut->addWikiText( '==' . wfMsg( 'toomanymatches' ) . "==\n" );
			$wgOut->addHTML( $this->powerSearchBox( $term ) );
			$wgOut->addHTML( $this->powerSearchFocus() );
			wfProfileOut( __METHOD__ );
			return;
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
				$wgOut->addHTML( $this->showInterwiki( $textMatches->getInterwikiResults(), $term ));
			// show results
			if( $textMatches->numRows() )
				$wgOut->addHTML( $this->showMatches( $textMatches ) );

			$textMatches->free();
		}

		if ( $num == 0 ) {
			$wgOut->addWikiMsg( 'nonefound' );
		}
		if( $num || $this->offset ) {
			$wgOut->addHTML( "<p class='mw-search-pager-bottom'>{$prevnext}</p>\n" );
		}
		$wgOut->addHTML( $this->powerSearchBox( $term ) );
		wfProfileOut( __METHOD__ );
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
		$subtitlemsg = ( Title::newFromText( $term ) ? 'searchsubtitle' : 'searchsubtitleinvalid' );
		$wgOut->setSubtitle( $wgOut->parse( wfMsg( $subtitlemsg, wfEscapeWikiText($term) ) ) );
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
		return $opt;
	}

	/**
	 * Show whole set of results 
	 * 
	 * @param SearchResultSet $matches
	 */
	function showMatches( &$matches ) {
		wfProfileIn( __METHOD__ );

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
		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * Format a single hit result
	 * @param SearchResult $result
	 * @param array $terms terms to highlight
	 */
	function showHit( $result, $terms ) {
		wfProfileIn( __METHOD__ );
		global $wgUser, $wgContLang, $wgLang;
		
		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- Broken link in search result -->\n";
		}
		
		$t = $result->getTitle();
		$sk = $wgUser->getSkin();

		$link = $sk->makeKnownLinkObj( $t, $result->getTitleSnippet($terms));

		//If page content is not readable, just return the title.
		//This is not quite safe, but better than showing excerpts from non-readable pages
		//Note that hiding the entry entirely would screw up paging.
		if (!$t->userCanRead()) {
			wfProfileOut( __METHOD__ );
			return "<li>{$link}</li>\n";
		}

		// If the page doesn't *exist*... our search index is out of date.
		// The least confusing at this point is to drop the result.
		// You may get less results, but... oh well. :P
		if( $result->isMissingRevision() ) {
			wfProfileOut( __METHOD__ );
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
			
			$related = ' -- ' . $sk->makeKnownLinkObj( $st,
				wfMsg('search-relatedarticle'), $stParams );
		}
				
		// Include a thumbnail for media files...
		if( $t->getNamespace() == NS_FILE ) {
			$img = wfFindFile( $t );
			if( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$desc = $img->getShortDesc();
					wfProfileOut( __METHOD__ );
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

		wfProfileOut( __METHOD__ );
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
		wfProfileIn( __METHOD__ );

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
		wfProfileOut( __METHOD__ );
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
	function showInterwikiHit( $result, $lastInterwiki, $terms, $query, $customCaptions) {
		wfProfileIn( __METHOD__ );
		global $wgUser, $wgContLang, $wgLang;
		
		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
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
		wfProfileOut( __METHOD__ );
		return $out;
	}
	

	/**
	 * Generates the power search box at bottom of [[Special:Search]]
	 * @param $term string: search term
	 * @return $out string: HTML form
	 */
	function powerSearchBox( $term ) {
		global $wgScript, $wgContLang;

		$namespaces = SearchEngine::searchableNamespaces();

		// group namespaces into rows according to subject; try not to make too
		// many assumptions about namespace numbering
		$rows = array();
		foreach( $namespaces as $ns => $name ) {
			$subj = MWNamespace::getSubject( $ns );
			if( !array_key_exists( $subj, $rows ) ) {
				$rows[$subj] = "";
			}
			$name = str_replace( '_', ' ', $name );
			if( '' == $name ) {
				$name = wfMsg( 'blanknamespace' );
			}
			$rows[$subj] .= Xml::openElement( 'td', array( 'style' => 'white-space: nowrap' ) ) .
					Xml::checkLabel( $name, "ns{$ns}", "mw-search-ns{$ns}", in_array( $ns, $this->namespaces ) ) .
					Xml::closeElement( 'td' ) . "\n";
		}
		$rows = array_values( $rows );
		$numRows = count( $rows );

		// lay out namespaces in multiple floating two-column tables so they'll
		// be arranged nicely while still accommodating different screen widths
		$rowsPerTable = 3;  // seems to look nice

		// float to the right on RTL wikis
		$tableStyle = ( $wgContLang->isRTL() ?
				'float: right; margin: 0 0 1em 1em' :
				'float: left; margin: 0 1em 1em 0' );

		$tables = "";
		for( $i = 0; $i < $numRows; $i += $rowsPerTable ) {
			$tables .= Xml::openElement( 'table', array( 'style' => $tableStyle ) );
			for( $j = $i; $j < $i + $rowsPerTable && $j < $numRows; $j++ ) {
				$tables .= "<tr>\n" . $rows[$j] . "</tr>";
			}
			$tables .= Xml::closeElement( 'table' ) . "\n";
		}

		$redirect = Xml::check( 'redirs', $this->searchRedirects, array( 'value' => '1', 'id' => 'redirs' ) );
		$redirectLabel = Xml::label( wfMsg( 'powersearch-redir' ), 'redirs' );
		$searchField = Xml::input( 'search', 50, $term, array( 'type' => 'text', 'id' => 'powerSearchText' ) );
		$searchButton = Xml::submitButton( wfMsg( 'powersearch' ), array( 'name' => 'fulltext' ) ) . "\n";
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		$searchHiddens = Xml::hidden( 'title', $searchTitle->getPrefixedText() ) . "\n";
		$searchHiddens .= Xml::hidden( 'fulltext', 'Advanced search' ) . "\n";
		
		$out = Xml::openElement( 'form', array(	'id' => 'powersearch', 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::fieldset( wfMsg( 'powersearch-legend' ),				
				"<p>" .
				wfMsgExt( 'powersearch-ns', array( 'parseinline' ) ) .
				"</p>\n" .
				$tables .
				"<hr style=\"clear: both\" />\n" .
				"<p>" .
				$redirect . " " . $redirectLabel .
				"</p>\n" .
				wfMsgExt( 'powersearch-field', array( 'parseinline' ) ) .
				"&nbsp;" .
				$searchField .
				"&nbsp;" .
				$searchHiddens . 
				$searchButton ) .
			"</form>";

		return $out;
	}

	function powerSearchFocus() {
		global $wgJsMimeType;
		return "<script type=\"$wgJsMimeType\">" .
			"hookEvent(\"load\", function(){" .
				"document.getElementById('powerSearchText').focus();" .
			"});" .
			"</script>";
	}

	function shortDialog($term) {
		global $wgScript;

		$out  = Xml::openElement( 'form', array(
			'id' => 'search',
			'method' => 'get',
			'action' => $wgScript
		));
		$searchTitle = SpecialPage::getTitleFor( 'Search' );
		$out .= Xml::input( 'search', 50, $term, array( 'type' => 'text', 'id' => 'searchText' ) ) . ' ';
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			if( in_array( $ns, $this->namespaces ) ) {
				$out .= Xml::hidden( "ns{$ns}", '1' );
			}
		}
		$out .= Xml::hidden( 'title', $searchTitle->getPrefixedText() );
		$out .= Xml::hidden( 'fulltext', 'Search' );
		$out .= Xml::submitButton( wfMsg( 'searchbutton' ), array( 'name' => 'fulltext' ) );
		$out .= Xml::closeElement( 'form' );

		return $out;
	}
}
