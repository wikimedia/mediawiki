<?php
/**
 * Implements Special:Search
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
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
 * @ingroup SpecialPage
 */

/**
 * implements Special:Search - Run text & title search and display the output
 * @ingroup SpecialPage
 */
class SpecialSearch extends SpecialPage {
	/// Current search profile
	protected $profile;

	/// Search engine
	protected $searchEngine;

	/// For links
	protected $extraParams = array();

	/// No idea, apparently used by some other classes
	protected $mPrefix;

	const NAMESPACES_CURRENT = 'sense';

	public function __construct() {
		parent::__construct( 'Search' );
	}

	/**
	 * Entry point
	 *
	 * @param $par String or null
	 */
	public function execute( $par ) {
		global $wgRequest, $wgUser, $wgOut;

		$this->setHeaders();
		$this->outputHeader();
		$wgOut->allowClickjacking();
		$wgOut->addModuleStyles( 'mediawiki.special' );

		// Strip underscores from title parameter; most of the time we'll want
		// text form here. But don't strip underscores from actual text params!
		$titleParam = str_replace( '_', ' ', $par );

		// Fetch the search term
		$search = str_replace( "\n", " ", $wgRequest->getText( 'search', $titleParam ) );

		$this->load( $wgRequest, $wgUser );

		if ( $wgRequest->getVal( 'fulltext' )
			|| !is_null( $wgRequest->getVal( 'offset' ) )
			|| !is_null( $wgRequest->getVal( 'searchx' ) ) )
		{
			$this->showResults( $search );
		} else {
			$this->goResult( $search );
		}
	}

	/**
	 * Set up basic search parameters from the request and user settings.
	 * Typically you'll pass $wgRequest and $wgUser.
	 *
	 * @param $request WebRequest
	 * @param $user User
	 */
	public function load( &$request, &$user ) {
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, 'searchlimit' );
		$this->mPrefix = $request->getVal( 'prefix', '' );


		# Extract manually requested namespaces
		$nslist = $this->powerSearch( $request );
		$this->profile = $profile = $request->getVal( 'profile', null );
		$profiles = $this->getSearchProfiles();
		if ( $profile === null) {
			// BC with old request format
			$this->profile = 'advanced';
			if ( count( $nslist ) ) {
				foreach( $profiles as $key => $data ) {
					if ( $nslist === $data['namespaces'] && $key !== 'advanced') {
						$this->profile = $key;
					}
				}
				$this->namespaces = $nslist;
			} else {
				$this->namespaces = SearchEngine::userNamespaces( $user );
			}
		} elseif ( $profile === 'advanced' ) {
			$this->namespaces = $nslist;
		} else {
			if ( isset( $profiles[$profile]['namespaces'] ) ) {
				$this->namespaces = $profiles[$profile]['namespaces'];
			} else {
				// Unknown profile requested
				$this->profile = 'default';
				$this->namespaces = $profiles['default']['namespaces'];
			}
		}

		// Redirects defaults to true, but we don't know whether it was ticked of or just missing
		$default = $request->getBool( 'profile' ) ? 0 : 1;
		$this->searchRedirects = $request->getBool( 'redirs', $default ) ? 1 : 0;
		$this->sk = $this->getSkin();
		$this->didYouMeanHtml = ''; # html of did you mean... link
		$this->fulltext = $request->getVal('fulltext');
	}

	/**
	 * If an exact title match can be found, jump straight ahead to it.
	 *
	 * @param $term String
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

		if ( !wfRunHooks( 'SpecialSearchGo', array( &$t, &$term ) ) ) {
			# Hook requested termination
			return;
		}

		if( !is_null( $t ) ) {
			$wgOut->redirect( $t->getFullURL() );
			return;
		}
		# No match, generate an edit URL
		$t = Title::newFromText( $term );
		if( !is_null( $t ) ) {
			global $wgGoToEdit;
			wfRunHooks( 'SpecialSearchNogomatch', array( &$t ) );
			wfDebugLog( 'nogomatch', $t->getText(), false );

			# If the feature is enabled, go straight to the edit page
			if( $wgGoToEdit ) {
				$wgOut->redirect( $t->getFullURL( array( 'action' => 'edit' ) ) );
				return;
			}
		}
		return $this->showResults( $term );
	}

	/**
	 * @param $term String
	 */
	public function showResults( $term ) {
		global $wgOut, $wgDisableTextSearch, $wgContLang, $wgScript;
		wfProfileIn( __METHOD__ );

		$sk = $this->getSkin();

		$search = $this->getSearchEngine();
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->showRedirects = $this->searchRedirects; // BC
		$search->setFeatureData( 'list-redirects', $this->searchRedirects );
		$search->prefix = $this->mPrefix;
		$term = $search->transformSearchTerm($term);

		wfRunHooks( 'SpecialSearchSetupEngine', array( $this, $this->profile, $search ) );

		$this->setupPage( $term );

		if( $wgDisableTextSearch ) {
			global $wgSearchForwardUrl;
			if( $wgSearchForwardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $wgSearchForwardUrl );
				$wgOut->redirect( $url );
				wfProfileOut( __METHOD__ );
				return;
			}
			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'search-external' ) ) .
				Xml::element( 'p', array( 'class' => 'mw-searchdisabled' ), wfMsg( 'searchdisabled' ) ) .
				wfMsg( 'googlesearch',
					htmlspecialchars( $term ),
					htmlspecialchars( 'UTF-8' ),
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

			if($this->fulltext != null)
				$didYouMeanParams['fulltext'] = $this->fulltext;

			$stParams = array_merge(
				$didYouMeanParams,
				$this->powerSearchOptions()
			);

			$suggestionSnippet = $textMatches->getSuggestionSnippet();

			if( $suggestionSnippet == '' )
				$suggestionSnippet = null;

			$suggestLink = $sk->linkKnown(
				$st,
				$suggestionSnippet,
				array(),
				$stParams
			);

			$this->didYouMeanHtml = '<div class="searchdidyoumean">'.wfMsg('search-suggest',$suggestLink).'</div>';
		}
		// start rendering the page
		$wgOut->addHtml(
			Xml::openElement(
				'form',
				array(
					'id' => ( $this->profile === 'advanced' ? 'powersearch' : 'search' ),
					'method' => 'get',
					'action' => $wgScript
				)
			)
		);
		$wgOut->addHtml(
			Xml::openElement( 'table', array( 'id'=>'mw-search-top-table', 'border'=>0, 'cellpadding'=>0, 'cellspacing'=>0 ) ) .
			Xml::openElement( 'tr' ) .
			Xml::openElement( 'td' ) . "\n" .
			$this->shortDialog( $term ) .
			Xml::closeElement('td') .
			Xml::closeElement('tr') .
			Xml::closeElement('table')
		);

		// Sometimes the search engine knows there are too many hits
		if( $titleMatches instanceof SearchResultTooMany ) {
			$wgOut->wrapWikiMsg( "==$1==\n", 'toomanymatches' );
			wfProfileOut( __METHOD__ );
			return;
		}

		$filePrefix = $wgContLang->getFormattedNsText(NS_FILE).':';
		if( trim( $term ) === '' || $filePrefix === trim( $term ) ) {
			$wgOut->addHTML( $this->formHeader( $term, 0, 0 ) );
			$wgOut->addHtml( $this->getProfileForm( $this->profile, $term ) );
			$wgOut->addHTML( '</form>' );
			// Empty query -- straight view of search form
			wfProfileOut( __METHOD__ );
			return;
		}

		// Get number of results
		$titleMatchesNum = $titleMatches ? $titleMatches->numRows() : 0;
		$textMatchesNum = $textMatches ? $textMatches->numRows() : 0;
		// Total initial query matches (possible false positives)
		$num = $titleMatchesNum + $textMatchesNum;

		// Get total actual results (after second filtering, if any)
		$numTitleMatches = $titleMatches && !is_null( $titleMatches->getTotalHits() ) ?
			$titleMatches->getTotalHits() : $titleMatchesNum;
		$numTextMatches = $textMatches && !is_null( $textMatches->getTotalHits() ) ?
			$textMatches->getTotalHits() : $textMatchesNum;

		// get total number of results if backend can calculate it
		$totalRes = 0;
		if($titleMatches && !is_null( $titleMatches->getTotalHits() ) )
			$totalRes += $titleMatches->getTotalHits();
		if($textMatches && !is_null( $textMatches->getTotalHits() ))
			$totalRes += $textMatches->getTotalHits();

		// show number of results and current offset
		$wgOut->addHTML( $this->formHeader( $term, $num, $totalRes ) );
		$wgOut->addHtml( $this->getProfileForm( $this->profile, $term ) );


		$wgOut->addHtml( Xml::closeElement( 'form' ) );
		$wgOut->addHtml( "<div class='searchresults'>" );

		// prev/next links
		if( $num || $this->offset ) {
			// Show the create link ahead
			$this->showCreateLink( $t );
			$prevnext = wfViewPrevNext( $this->offset, $this->limit,
				SpecialPage::getTitleFor( 'Search' ),
				wfArrayToCGI( $this->powerSearchOptions(), array( 'search' => $term ) ),
				max( $titleMatchesNum, $textMatchesNum ) < $this->limit
			);
			//$wgOut->addHTML( "<p class='mw-search-pager-top'>{$prevnext}</p>\n" );
			wfRunHooks( 'SpecialSearchResults', array( $term, &$titleMatches, &$textMatches ) );
		} else {
			wfRunHooks( 'SpecialSearchNoResults', array( $term ) );
		}

		$wgOut->parserOptions()->setEditSection( false );
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
				# $wgOut->wrapWikiMsg( "==$1==\n", 'notextmatches' );
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
		if( $num === 0 ) {
			$wgOut->wrapWikiMsg( "<p class=\"mw-search-nonefound\">\n$1</p>", array( 'search-nonefound', wfEscapeWikiText( $term ) ) );
			$this->showCreateLink( $t );
		}
		$wgOut->addHtml( "</div>" );

		if( $num || $this->offset ) {
			$wgOut->addHTML( "<p class='mw-search-pager-bottom'>{$prevnext}</p>\n" );
		}
		wfProfileOut( __METHOD__ );
	}

	protected function showCreateLink( $t ) {
		global $wgOut;

		// show direct page/create link if applicable
		// Check DBkey !== '' in case of fragment link only.
		$messageName = null;
		if( !is_null($t) && $t->getDBkey() !== '' ) {
			if( $t->isKnown() ) {
				$messageName = 'searchmenu-exists';
			} elseif( $t->userCan( 'create' ) ) {
				$messageName = 'searchmenu-new';
			} else {
				$messageName = 'searchmenu-new-nocreate';
			}
		}
		if( $messageName ) {
			$wgOut->wrapWikiMsg( "<p class=\"mw-search-createlink\">\n$1</p>", array( $messageName, wfEscapeWikiText( $t->getPrefixedText() ) ) );
		} else {
			// preserve the paragraph for margins etc...
			$wgOut->addHtml( '<p></p>' );
		}
	}

	/**
	 *
	 */
	protected function setupPage( $term ) {
		global $wgOut;

		# Should advanced UI be used?
		$this->searchAdvanced = ($this->profile === 'advanced');
		if( strval( $term ) !== ''  ) {
			$wgOut->setPageTitle( wfMsg( 'searchresults') );
			$wgOut->setHTMLTitle( wfMsg( 'pagetitle', wfMsg( 'searchresults-title', $term ) ) );
		}
		// add javascript specific to special:search
		$wgOut->addModules( 'mediawiki.special.search' );
	}

	/**
	 * Extract "power search" namespace settings from the request object,
	 * returning a list of index numbers to search.
	 *
	 * @param $request WebRequest
	 * @return Array
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
	 *
	 * @return Array
	 */
	protected function powerSearchOptions() {
		$opt = array();
		$opt['redirs'] = $this->searchRedirects ? 1 : 0;
		if( $this->profile !== 'advanced' ) {
			$opt['profile'] = $this->profile;
		} else {
			foreach( $this->namespaces as $n ) {
				$opt['ns' . $n] = 1;
			}
		}
		return $opt + $this->extraParams;
	}

	/**
	 * Show whole set of results
	 *
	 * @param $matches SearchResultSet
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
	 *
	 * @param $result SearchResult
	 * @param $terms Array: terms to highlight
	 */
	protected function showHit( $result, $terms ) {
		global $wgLang;
		wfProfileIn( __METHOD__ );

		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- Broken link in search result -->\n";
		}

		$sk = $this->getSkin();
		$t = $result->getTitle();

		$titleSnippet = $result->getTitleSnippet($terms);

		if( $titleSnippet == '' )
			$titleSnippet = null;

		$link_t = clone $t;

		wfRunHooks( 'ShowSearchHitTitle',
					array( &$link_t, &$titleSnippet, $result, $terms, $this ) );

		$link = $this->sk->linkKnown(
			$link_t,
			$titleSnippet
		);

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

		if( !is_null($redirectTitle) ) {
			if( $redirectText == '' )
				$redirectText = null;

			$redirect = "<span class='searchalttitle'>" .
				wfMsg(
					'search-redirect',
					$this->sk->linkKnown(
						$redirectTitle,
						$redirectText
					)
				) .
				"</span>";
		}

		$section = '';

		if( !is_null($sectionTitle) ) {
			if( $sectionText == '' )
				$sectionText = null;

			$section = "<span class='searchalttitle'>" .
				wfMsg(
					'search-section', $this->sk->linkKnown(
						$sectionTitle,
						$sectionText
					)
				) .
				"</span>";
		}

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
		$size = wfMsgExt(
			'search-result-size',
			array( 'parsemag', 'escape' ),
			$wgLang->formatSize( $byteSize ),
			$wgLang->formatNum( $wordCount )
		);

		if( $t->getNamespace() == NS_CATEGORY ) {
			$cat = Category::newFromTitle( $t );
			$size = wfMsgExt(
				'search-result-category-size',
				array( 'parsemag', 'escape' ),
				$wgLang->formatNum( $cat->getPageCount() ),
				$wgLang->formatNum( $cat->getSubcatCount() ),
				$wgLang->formatNum( $cat->getFileCount() )
			);
		}

		$date = $wgLang->timeanddate( $timestamp );

		// link to related articles if supported
		$related = '';
		if( $result->hasRelated() ) {
			$st = SpecialPage::getTitleFor( 'Search' );
			$stParams = array_merge(
				$this->powerSearchOptions(),
				array(
					'search' => wfMsgForContent( 'searchrelated' ) . ':' . $t->getPrefixedText(),
					'fulltext' => wfMsg( 'search' )
				)
			);

			$related = ' -- ' . $sk->linkKnown(
				$st,
				wfMsg('search-relatedarticle'),
				array(),
				$stParams
			);
		}

		// Include a thumbnail for media files...
		if( $t->getNamespace() == NS_FILE ) {
			$img = wfFindFile( $t );
			if( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$desc = wfMsg( 'parentheses', $img->getShortDesc() );
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
		return "<li><div class='mw-search-result-heading'>{$link} {$redirect} {$section}</div> {$extract}\n" .
			"<div class='mw-search-result-data'>{$score}{$size} - {$date}{$related}</div>" .
			"</li>\n";

	}

	/**
	 * Show results from other wikis
	 *
	 * @param $matches SearchResultSet
	 * @param $query String
	 */
	protected function showInterwiki( &$matches, $query ) {
		global $wgContLang;
		wfProfileIn( __METHOD__ );
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );

		$out = "<div id='mw-search-interwiki'><div id='mw-search-interwiki-caption'>".
			wfMsg('search-interwiki-caption')."</div>\n";
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
		// TODO: should support paging in a non-confusing way (not sure how though, maybe via ajax)..
		$out .= "</ul></div>\n";

		// convert the whole thing to desired language variant
		$out = $wgContLang->convert( $out );
		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * Show single interwiki link
	 *
	 * @param $result SearchResult
	 * @param $lastInterwiki String
	 * @param $terms Array
	 * @param $query String
	 * @param $customCaptions Array: iw prefix -> caption
	 */
	protected function showInterwikiHit( $result, $lastInterwiki, $terms, $query, $customCaptions) {
		wfProfileIn( __METHOD__ );

		if( $result->isBrokenTitle() ) {
			wfProfileOut( __METHOD__ );
			return "<!-- Broken link in search result -->\n";
		}

		$t = $result->getTitle();

		$titleSnippet = $result->getTitleSnippet($terms);

		if( $titleSnippet == '' )
			$titleSnippet = null;

		$link = $this->sk->linkKnown(
			$t,
			$titleSnippet
		);

		// format redirect if any
		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet($terms);
		$redirect = '';
		if( !is_null($redirectTitle) ) {
			if( $redirectText == '' )
				$redirectText = null;

			$redirect = "<span class='searchalttitle'>" .
				wfMsg(
					'search-redirect',
					$this->sk->linkKnown(
						$redirectTitle,
						$redirectText
					)
				) .
				"</span>";
		}

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
			$searchLink = $this->sk->linkKnown(
				$searchTitle,
				wfMsg('search-interwiki-more'),
				array(),
				array(
					'search' => $query,
					'fulltext' => 'Search'
				)
			);
			$out .= "</ul><div class='mw-search-interwiki-project'><span class='mw-search-interwiki-more'>
				{$searchLink}</span>{$caption}</div>\n<ul>";
		}

		$out .= "<li>{$link} {$redirect}</li>\n";
		wfProfileOut( __METHOD__ );
		return $out;
	}

	protected function getProfileForm( $profile, $term ) {
		// Hidden stuff
		$opts = array();
		$opts['redirs'] = $this->searchRedirects;
		$opts['profile'] = $this->profile;

		if ( $profile === 'advanced' ) {
			return $this->powerSearchBox( $term, $opts );
		} else {
			$form = '';
			wfRunHooks( 'SpecialSearchProfileForm', array( $this, &$form, $profile, $term, $opts ) );
			return $form;
		}
	}

	/**
	 * Generates the power search box at [[Special:Search]]
	 *
	 * @param $term String: search term
	 * @return String: HTML form
	 */
	protected function powerSearchBox( $term, $opts ) {
		// Groups namespaces into rows according to subject
		$rows = array();
		foreach( SearchEngine::searchableNamespaces() as $namespace => $name ) {
			$subject = MWNamespace::getSubject( $namespace );
			if( !array_key_exists( $subject, $rows ) ) {
				$rows[$subject] = "";
			}
			$name = str_replace( '_', ' ', $name );
			if( $name == '' ) {
				$name = wfMsg( 'blanknamespace' );
			}
			$rows[$subject] .=
				Xml::openElement(
					'td', array( 'style' => 'white-space: nowrap' )
				) .
				Xml::checkLabel(
					$name,
					"ns{$namespace}",
					"mw-search-ns{$namespace}",
					in_array( $namespace, $this->namespaces )
				) .
				Xml::closeElement( 'td' );
		}
		$rows = array_values( $rows );
		$numRows = count( $rows );

		// Lays out namespaces in multiple floating two-column tables so they'll
		// be arranged nicely while still accommodating different screen widths
		$namespaceTables = '';
		for( $i = 0; $i < $numRows; $i += 4 ) {
			$namespaceTables .= Xml::openElement(
				'table',
				array( 'cellpadding' => 0, 'cellspacing' => 0, 'border' => 0 )
			);
			for( $j = $i; $j < $i + 4 && $j < $numRows; $j++ ) {
				$namespaceTables .= Xml::tags( 'tr', null, $rows[$j] );
			}
			$namespaceTables .= Xml::closeElement( 'table' );
		}
		// Show redirects check only if backend supports it
		$redirects = '';
		if( $this->getSearchEngine()->supports( 'list-redirects' ) ) {
			$redirects =
				Xml::checkLabel( wfMsg( 'powersearch-redir' ), 'redirs', 'redirs', $this->searchRedirects );
		}

		$hidden = '';
		unset( $opts['redirs'] );
		foreach( $opts as $key => $value ) {
			$hidden .= Html::hidden( $key, $value );
		}
		// Return final output
		return
			Xml::openElement(
				'fieldset',
				array( 'id' => 'mw-searchoptions', 'style' => 'margin:0em;' )
			) .
			Xml::element( 'legend', null, wfMsg('powersearch-legend') ) .
			Xml::tags( 'h4', null, wfMsgExt( 'powersearch-ns', array( 'parseinline' ) ) ) .
			Xml::tags(
				'div',
				array( 'id' => 'mw-search-togglebox' ),
				Xml::label( wfMsg( 'powersearch-togglelabel' ), 'mw-search-togglelabel' ) .
					Xml::element(
						'input',
						array(
							'type'=>'button',
							'id' => 'mw-search-toggleall',
							'value' => wfMsg( 'powersearch-toggleall' )
						)
					) .
					Xml::element(
						'input',
						array(
							'type'=>'button',
							'id' => 'mw-search-togglenone',
							'value' => wfMsg( 'powersearch-togglenone' )
						)
					)
			) .
			Xml::element( 'div', array( 'class' => 'divider' ), '', false ) .
			$namespaceTables .
			Xml::element( 'div', array( 'class' => 'divider' ), '', false ) .
			$redirects . $hidden .
			Xml::closeElement( 'fieldset' );
	}

	protected function getSearchProfiles() {
		// Builds list of Search Types (profiles)
		$nsAllSet = array_keys( SearchEngine::searchableNamespaces() );

		$profiles = array(
			'default' => array(
				'message' => 'searchprofile-articles',
				'tooltip' => 'searchprofile-articles-tooltip',
				'namespaces' => SearchEngine::defaultNamespaces(),
				'namespace-messages' => SearchEngine::namespacesAsText(
					SearchEngine::defaultNamespaces()
				),
			),
			'images' => array(
				'message' => 'searchprofile-images',
				'tooltip' => 'searchprofile-images-tooltip',
				'namespaces' => array( NS_FILE ),
			),
			'help' => array(
				'message' => 'searchprofile-project',
				'tooltip' => 'searchprofile-project-tooltip',
				'namespaces' => SearchEngine::helpNamespaces(),
				'namespace-messages' => SearchEngine::namespacesAsText(
					SearchEngine::helpNamespaces()
				),
			),
			'all' => array(
				'message' => 'searchprofile-everything',
				'tooltip' => 'searchprofile-everything-tooltip',
				'namespaces' => $nsAllSet,
			),
			'advanced' => array(
				'message' => 'searchprofile-advanced',
				'tooltip' => 'searchprofile-advanced-tooltip',
				'namespaces' => self::NAMESPACES_CURRENT,
			)
		);

		wfRunHooks( 'SpecialSearchProfiles', array( &$profiles ) );

		foreach( $profiles as &$data ) {
			if ( !is_array( $data['namespaces'] ) ) continue;
			sort( $data['namespaces'] );
		}

		return $profiles;
	}

	protected function formHeader( $term, $resultsShown, $totalNum ) {
		global $wgLang;

		$out = Xml::openElement('div', array( 'class' =>  'mw-search-formheader' ) );

		$bareterm = $term;
		if( $this->startsWithImage( $term ) ) {
			// Deletes prefixes
			$bareterm = substr( $term, strpos( $term, ':' ) + 1 );
		}

		$profiles = $this->getSearchProfiles();

		// Outputs XML for Search Types
		$out .= Xml::openElement( 'div', array( 'class' => 'search-types' ) );
		$out .= Xml::openElement( 'ul' );
		foreach ( $profiles as $id => $profile ) {
			if ( !isset( $profile['parameters'] ) ) {
				$profile['parameters'] = array();
			}
			$profile['parameters']['profile'] = $id;

			$tooltipParam = isset( $profile['namespace-messages'] ) ?
				$wgLang->commaList( $profile['namespace-messages'] ) : null;
			$out .= Xml::tags(
				'li',
				array(
					'class' => $this->profile === $id ? 'current' : 'normal'
				),
				$this->makeSearchLink(
					$bareterm,
					array(),
					wfMsg( $profile['message'] ),
					wfMsg( $profile['tooltip'], $tooltipParam ),
					$profile['parameters']
				)
			);
		}
		$out .= Xml::closeElement( 'ul' );
		$out .= Xml::closeElement('div') ;

		// Results-info
		if ( $resultsShown > 0 ) {
			if ( $totalNum > 0 ){
				$top = wfMsgExt( 'showingresultsheader', array( 'parseinline' ),
					$wgLang->formatNum( $this->offset + 1 ),
					$wgLang->formatNum( $this->offset + $resultsShown ),
					$wgLang->formatNum( $totalNum ),
					wfEscapeWikiText( $term ),
					$wgLang->formatNum( $resultsShown )
				);
			} elseif ( $resultsShown >= $this->limit ) {
				$top = wfShowingResults( $this->offset, $this->limit );
			} else {
				$top =  wfMsgExt( 'showingresultsnum', array( 'parseinline' ),
					$wgLang->formatNum( $this->limit ),
					$wgLang->formatNum( $this->offset + 1 ),
					$wgLang->formatNum( $resultsShown )
				);
			}
			$out .= Xml::tags( 'div', array( 'class' => 'results-info' ),
				Xml::tags( 'ul', null, Xml::tags( 'li', null, $top ) )
			);
		}

		$out .= Xml::element( 'div', array( 'style' => 'clear:both' ), '', false );
		$out .= Xml::closeElement('div');

		return $out;
	}

	protected function shortDialog( $term ) {
		$out = Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		// Term box
		$out .= Html::input( 'search', $term, 'search', array(
			'id' => $this->profile === 'advanced' ? 'powerSearchText' : 'searchText',
			'size' => '50',
			'autofocus'
		) ) . "\n";
		$out .= Html::hidden( 'fulltext', 'Search' ) . "\n";
		$out .= Xml::submitButton( wfMsg( 'searchbutton' ) ) . "\n";
		return $out . $this->didYouMeanHtml;
	}

	/**
	 * Make a search link with some target namespaces
	 *
	 * @param $term String
	 * @param $namespaces Array ignored
	 * @param $label String: link's text
	 * @param $tooltip String: link's tooltip
	 * @param $params Array: query string parameters
	 * @return String: HTML fragment
	 */
	protected function makeSearchLink( $term, $namespaces, $label, $tooltip, $params = array() ) {
		$opt = $params;
		foreach( $namespaces as $n ) {
			$opt['ns' . $n] = 1;
		}
		$opt['redirs'] = $this->searchRedirects;

		$stParams = array_merge(
			array(
				'search' => $term,
				'fulltext' => wfMsg( 'search' )
			),
			$opt
		);

		return Xml::element(
			'a',
			array(
				'href' => $this->getTitle()->getLocalURL( $stParams ),
				'title' => $tooltip),
			$label
		);
	}

	/**
	 * Check if query starts with image: prefix
	 *
	 * @param $term String: the string to check
	 * @return Boolean
	 */
	protected function startsWithImage( $term ) {
		global $wgContLang;

		$p = explode( ':', $term );
		if( count( $p ) > 1 ) {
			return $wgContLang->getNsIndex( $p[0] ) == NS_FILE;
		}
		return false;
	}

	/**
	 * Check if query starts with all: prefix
	 *
	 * @param $term String: the string to check
	 * @return Boolean
	 */
	protected function startsWithAll( $term ) {

		$allkeyword = wfMsgForContent('searchall');

		$p = explode( ':', $term );
		if( count( $p ) > 1 ) {
			return $p[0]  == $allkeyword;
		}
		return false;
	}

	/**
	 * @since 1.18
	 */
	public function getSearchEngine() {
		if ( $this->searchEngine === null ) {
			$this->searchEngine = SearchEngine::create();
		}
		return $this->searchEngine;
	}

	/**
	 * Users of hook SpecialSearchSetupEngine can use this to
	 * add more params to links to not lose selection when
	 * user navigates search results.
	 * @since 1.18
	 */
	public function setExtraParam( $key, $value ) {
		$this->extraParams[$key] = $value;
	}

}
