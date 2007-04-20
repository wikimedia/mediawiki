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
 * @addtogroup SpecialPage
 */

/**
 * Entry point
 *
 * @param $par String: (default '')
 */
function wfSpecialSearch( $par = '' ) {
	global $wgRequest, $wgUser;

	$search = $wgRequest->getText( 'search', $par );
	$searchPage = new SpecialSearch( $wgRequest, $wgUser );
	if( $wgRequest->getVal( 'fulltext' ) ||
		!is_null( $wgRequest->getVal( 'offset' ) ) ||
		!is_null ($wgRequest->getVal( 'searchx' ) ) ) {
		$searchPage->showResults( $search );
	} else {
		$searchPage->goResult( $search );
	}
}

/**
 * implements Special:Search - Run text & title search and display the output
 * @addtogroup SpecialPage
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

		if( $request->getCheck( 'searchx' ) ) {
			$this->namespaces = $this->powerSearch( $request );
		} else {
			$this->namespaces = $this->userNamespaces( $user );
		}

		$this->searchRedirects = $request->getcheck( 'redirs' ) ? true : false;
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
		$wgOut->addWikiText( wfMsg( 'noexactmatch', wfEscapeWikiText( $term ) ) );

		return $this->showResults( $term );
	}

	/**
	 * @param string $term
	 * @public
	 */
	function showResults( $term ) {
		$fname = 'SpecialSearch::showResults';
		wfProfileIn( $fname );

		$this->setupPage( $term );

		global $wgOut;
		$wgOut->addWikiText( wfMsg( 'searchresulttext' ) );

		#if ( !$this->parseQuery() ) {
		if( '' === trim( $term ) ) {
			$wgOut->setSubtitle( '' );
			$wgOut->addHTML( $this->powerSearchBox( $term ) );
			wfProfileOut( $fname );
			return;
		}

		global $wgDisableTextSearch;
		if ( $wgDisableTextSearch ) {
			global $wgForwardSearchUrl;
			if( $wgForwardSearchUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $wgForwardSearchUrl );
				$wgOut->redirect( $url );
				return;
			}
			global $wgInputEncoding;
			$wgOut->addHTML( wfMsg( 'searchdisabled' ) );
			$wgOut->addHTML(
				wfMsg( 'googlesearch',
					htmlspecialchars( $term ),
					htmlspecialchars( $wgInputEncoding ),
					htmlspecialchars( wfMsg( 'searchbutton' ) )
				)
			);
			wfProfileOut( $fname );
			return;
		}

		$search = SearchEngine::create();
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->showRedirects = $this->searchRedirects;
		$titleMatches = $search->searchTitle( $term );
		$textMatches = $search->searchText( $term );

		$num = ( $titleMatches ? $titleMatches->numRows() : 0 )
			+ ( $textMatches ? $textMatches->numRows() : 0);
		if ( $num > 0 ) {
			if ( $num >= $this->limit ) {
				$top = wfShowingResults( $this->offset, $this->limit );
			} else {
				$top = wfShowingResultsNum( $this->offset, $this->limit, $num );
			}
			$wgOut->addHTML( "<p>{$top}</p>\n" );
		}

		if( $num || $this->offset ) {
			$prevnext = wfViewPrevNext( $this->offset, $this->limit,
				SpecialPage::getTitleFor( 'Search' ),
				wfArrayToCGI(
					$this->powerSearchOptions(),
					array( 'search' => $term ) ) );
			$wgOut->addHTML( "<br />{$prevnext}\n" );
		}

		if( $titleMatches ) {
			if( $titleMatches->numRows() ) {
				$wgOut->addWikiText( '==' . wfMsg( 'titlematches' ) . "==\n" );
				$wgOut->addHTML( $this->showMatches( $titleMatches ) );
			} else {
				$wgOut->addWikiText( '==' . wfMsg( 'notitlematches' ) . "==\n" );
			}
		}

		if( $textMatches ) {
			if( $textMatches->numRows() ) {
				$wgOut->addWikiText( '==' . wfMsg( 'textmatches' ) . "==\n" );
				$wgOut->addHTML( $this->showMatches( $textMatches ) );
			} elseif( $num == 0 ) {
				# Don't show the 'no text matches' if we received title matches
				$wgOut->addWikiText( '==' . wfMsg( 'notextmatches' ) . "==\n" );
			}
		}

		if ( $num == 0 ) {
			$wgOut->addWikiText( wfMsg( 'nonefound' ) );
		}
		if( $num || $this->offset ) {
			$wgOut->addHTML( "<p>{$prevnext}</p>\n" );
		}
		$wgOut->addHTML( $this->powerSearchBox( $term ) );
		wfProfileOut( $fname );
	}

	#------------------------------------------------------------------
	# Private methods below this line

	/**
	 *
	 */
	function setupPage( $term ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'searchresults' ) );
		$subtitlemsg = ( Title::newFromText($term) ? 'searchsubtitle' : 'searchsubtitleinvalid' );
		$wgOut->setSubtitle( $wgOut->parse( wfMsg( $subtitlemsg, wfEscapeWikiText($term) ) ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
	}

	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param User $user
	 * @return array
	 * @private
	 */
	function userNamespaces( &$user ) {
		$arr = array();
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			if( $user->getOption( 'searchNs' . $ns ) ) {
				$arr[] = $ns;
			}
		}
		return $arr;
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
		$opt['searchx'] = 1;
		return $opt;
	}

	/**
	 * @param SearchResultSet $matches
	 * @param string $terms partial regexp for highlighting terms
	 */
	function showMatches( &$matches ) {
		$fname = 'SpecialSearch::showMatches';
		wfProfileIn( $fname );

		global $wgContLang;
		$tm = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$terms = implode( '|', $tm );

		$off = $this->offset + 1;
		$out = "<ol start='{$off}'>\n";

		while( $result = $matches->next() ) {
			$out .= $this->showHit( $result, $terms );
		}
		$out .= "</ol>\n";

		// convert the whole thing to desired language variant
		global $wgContLang;
		$out = $wgContLang->convert( $out );
		wfProfileOut( $fname );
		return $out;
	}

	/**
	 * Format a single hit result
	 * @param SearchResult $result
	 * @param string $terms partial regexp for highlighting terms
	 */
	function showHit( $result, $terms ) {
		$fname = 'SpecialSearch::showHit';
		wfProfileIn( $fname );
		global $wgUser, $wgContLang, $wgLang;

		$t = $result->getTitle();
		if( is_null( $t ) ) {
			wfProfileOut( $fname );
			return "<!-- Broken link in search result -->\n";
		}
		$sk = $wgUser->getSkin();

		$contextlines = $wgUser->getOption( 'contextlines',  5 );
		$contextchars = $wgUser->getOption( 'contextchars', 50 );

		$link = $sk->makeKnownLinkObj( $t );
		$revision = Revision::newFromTitle( $t );
		$text = $revision->getText();
		$size = wfMsgExt( 'nbytes', array( 'parsemag', 'escape'),
			$wgLang->formatNum( strlen( $text ) ) );

		$lines = explode( "\n", $text );

		$max = intval( $contextchars ) + 1;
		$pat1 = "/(.*)($terms)(.{0,$max})/i";

		$lineno = 0;

		$extract = '';
		wfProfileIn( "$fname-extract" );
		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) {
				break;
			}
			++$lineno;
			$m = array();
			if ( ! preg_match( $pat1, $line, $m ) ) {
				continue;
			}
			--$contextlines;
			$pre = $wgContLang->truncate( $m[1], -$contextchars, '...' );

			if ( count( $m ) < 3 ) {
				$post = '';
			} else {
				$post = $wgContLang->truncate( $m[3], $contextchars, '...' );
			}

			$found = $m[2];

			$line = htmlspecialchars( $pre . $found . $post );
			$pat2 = '/(' . $terms . ")/i";
			$line = preg_replace( $pat2,
			  "<span class='searchmatch'>\\1</span>", $line );

			$extract .= "<br /><small>{$lineno}: {$line}</small>\n";
		}
		wfProfileOut( "$fname-extract" );
		wfProfileOut( $fname );
		return "<li>{$link} ({$size}){$extract}</li>\n";
	}

	function powerSearchBox( $term ) {
		$namespaces = '';
		foreach( SearchEngine::searchableNamespaces() as $ns => $name ) {
			$checked = in_array( $ns, $this->namespaces )
				? ' checked="checked"'
				: '';
			$name = str_replace( '_', ' ', $name );
			if( '' == $name ) {
				$name = wfMsg( 'blanknamespace' );
			}
			$namespaces .= " <label><input type='checkbox' value=\"1\" name=\"" .
			  "ns{$ns}\"{$checked} />{$name}</label>\n";
		}

		$checked = $this->searchRedirects
			? ' checked="checked"'
			: '';
		$redirect = "<input type='checkbox' value='1' name=\"redirs\"{$checked} />\n";

		$searchField = '<input type="text" name="search" value="' .
			htmlspecialchars( $term ) ."\" size=\"16\" />\n";

		$searchButton = '<input type="submit" name="searchx" value="' .
		  htmlspecialchars( wfMsg('powersearch') ) . "\" />\n";

		$ret = wfMsg( 'powersearchtext',
			$namespaces, $redirect, $searchField,
			'', '', '', '', '', # Dummy placeholders
			$searchButton );

		$title = SpecialPage::getTitleFor( 'Search' );
		$action = $title->escapeLocalURL();
		return "<br /><br />\n<form id=\"powersearch\" method=\"get\" " .
		  "action=\"$action\">\n{$ret}\n</form>\n";
	}
}

?>
