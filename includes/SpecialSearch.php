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
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Run text & title search and display the output
 * @package MediaWiki
 * @subpackage SpecialPage
 */

require_once( 'SearchEngine.php' );

function wfSpecialSearch( $par='' ) {
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


class SpecialSearch {
	/**
	 * Set up basic search parameters from the request and user settings.
	 * Typically you'll pass $wgRequest and $wgUser.
	 *
	 * @param WebRequest $request
	 * @param User $user
	 * @access public
	 */
	function SpecialSearch( &$request, &$user ) {
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, 'searchlimit' );
		
		if( $request->getCheck( 'searchx' ) ) {
			$this->namespaces = $this->powerSearch( $request );
		} else {
			$this->namespaces = $this->userNamespaces( $user );
		}
		
		$this->searchRedirects = false;
	}
	
	/**
	 * If an exact title match can be found, jump straight ahead to
	 * @param string $term
	 * @access public
	 */
	function goResult( $term ) {
		global $wgOut;
		global $wgGoToEdit;
		
		$this->setupPage( $term );

		# Try to go to page as entered.
		#
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
		if( is_null( $t ) ) {
			$editurl = ''; # hrm...
		} else {
			# If the feature is enabled, go straight to the edit page
			if ( $wgGoToEdit ) {
				$wgOut->redirect( $t->getFullURL( 'action=edit' ) );
				return;
			} else {
				$editurl = $t->escapeLocalURL( 'action=edit' );
			}
		}
		# FIXME: HTML in wiki message
		$wgOut->addHTML( '<p>' . wfMsg('nogomatch', $editurl, htmlspecialchars( $term ) ) . "</p>\n" );

		return $this->showResults( $term );
	}
	
	/**
	 * @param string $term
	 * @access public
	 */
	function showResults( $term ) {
		$this->setupPage( $term );
		
		global $wgUser, $wgOut;
		$sk = $wgUser->getSkin();
		$wgOut->addWikiText( wfMsg( 'searchresulttext' ) );
		
		#if ( !$this->parseQuery() ) {
		if( '' === trim( $term ) ) {
			$wgOut->addWikiText(
				'==' . wfMsg( 'badquery' ) . "==\n" .
				wfMsg( 'badquerytext' ) );
			return;
		}
		
		global $wgDisableTextSearch;
		if ( $wgDisableTextSearch ) {
			global $wgInputEncoding;
			$wgOut->addHTML( wfMsg( 'searchdisabled' ) );
			$wgOut->addHTML( wfMsg( 'googlesearch',
				htmlspecialchars( $term ),
				htmlspecialchars( $wgInputEncoding ) ) );
			return;
		}

		$search =& $this->getSearchEngine();
		$titleMatches = $search->searchTitle( $term );
		$textMatches = $search->searchText( $term );
		
		$num = $titleMatches->numRows() + $textMatches->numRows();
		if ( $num >= $this->limit ) {
			$top = wfShowingResults( $this->offset, $this->limit );
		} else {
			$top = wfShowingResultsNum( $this->offset, $this->limit, $num );
		}
		$wgOut->addHTML( "<p>{$top}</p>\n" );

		if( $num || $this->offset ) {
			$prevnext = wfViewPrevNext( $this->offset, $this->limit,
				'Special:Search',
				wfArrayToCGI(
					$this->powerSearchOptions(),
					array( 'search' => $term ) ) );
			$wgOut->addHTML( "<br />{$prevnext}\n" );
		}

		$terms = implode( '|', $search->termMatches() );
		
		if( $titleMatches->numRows() ) {
			$wgOut->addWikiText( '==' . wfMsg( 'titlematches' ) . "==\n" );
			$wgOut->addHTML( $this->showMatches( $titleMatches, $terms ) );
		} else {
			$wgOut->addWikiText( '==' . wfMsg( 'notitlematches' ) . "==\n" );
		}
		
		if( $textMatches->numRows() ) {
			$wgOut->addWikiText( '==' . wfMsg( 'textmatches' ) . "==\n" );
			$wgOut->addHTML( $this->showMatches( $textMatches, $terms ) );
		} elseif( $num == 0 ) {
			# Don't show the 'no text matches' if we received title matches
			$wgOut->addWikiText( '==' . wfMsg( 'notextmatches' ) . "==\n" );
		}
		
		if ( $num == 0 ) {
			$wgOut->addWikiText( wfMsg( 'nonefound' ) );
		}
		if( $num || $this->offset ) {
			$wgOut->addHTML( "<p>{$prevnext}</p>\n" );
		}
		$wgOut->addHTML( $this->powerSearchBox( $term ) );
	}
	
	#------------------------------------------------------------------
	# Private methods below this line
	
	/**
	 * 
	 */
	function setupPage( $term ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'searchresults' ) );
		$wgOut->setSubtitle( wfMsg( 'searchquery', htmlspecialchars( $term ) ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
	}

	/**
	 * Load up the appropriate search engine class for the currently
	 * active database backend, and return a configured instance.
	 *
	 * @return SearchEngine
	 * @access private
	 */
	function &getSearchEngine() {
		global $wgDBtype, $wgDBmysql4, $wgSearchType;
		if( $wgDBtype == 'mysql' ) {
			if( $wgDBmysql4 ) {
				$class = 'SearchMySQL4';
				require_once( 'SearchMySQL4.php' );
			} else {
				$class = 'SearchMysql3';
				require_once( 'SearchMySQL3.php' );
			}
		} else {
			$class = 'SearchEngineDummy';
		}
		$search = new $class( wfGetDB( DB_SLAVE ) );
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		return $search;
	}
	
	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param User $user
	 * @return array
	 * @access private
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
	 * @access private
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
	 * @access private
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
	 * @param ResultWrapper $matches
	 * @param string $terms partial regexp for highlighting terms
	 */
	function showMatches( &$matches, $terms ) {
		global $wgOut;
		$off = $this->offset + 1;
		$out = "<ol start='{$off}'>\n";

		while( $row = $matches->fetchObject() ) {
			$out .= $this->showHit( $row, $terms );
		}
		$out .= "</ol>\n";
		return $out;
	}
	
	/**
	 * Format a single hit result
	 * @param object $row
	 * @param string $terms partial regexp for highlighting terms
	 */
	function showHit( $row, $terms ) {
		global $wgUser, $wgContLang;

		$t = Title::makeName( $row->cur_namespace, $row->cur_title );
		if( is_null( $t ) ) {
			return "<!-- Broken link in search result -->\n";
		}
		$sk = $wgUser->getSkin();

		$contextlines = $wgUser->getOption( 'contextlines' );
		if ( '' == $contextlines ) { $contextlines = 5; }
		$contextchars = $wgUser->getOption( 'contextchars' );
		if ( '' == $contextchars ) { $contextchars = 50; }

		$link = $sk->makeKnownLink( $t, '' );
		$size = wfMsg( 'nbytes', strlen( $row->cur_text ) );

		$lines = explode( "\n", $row->cur_text );
		$pat1 = "/(.*)($terms)(.*)/i";
		$lineno = 0;
		
		$extract = '';
		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) {
				break;
			}
			++$lineno;
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
		
		$searchField = "<input type='text' name=\"search\" value=\"" .
			htmlspecialchars( $term ) ."\" width=\"80\" />\n";
		
		$searchButton = '<input type="submit" name="searchx" value="' .
		  htmlspecialchars( wfMsg('powersearch') ) . "\" />\n";
		
		$ret = wfMsg( 'powersearchtext',
			$namespaces, $redirect, $searchField,
			'', '', '', '', '', # Dummy placeholders
			$searchButton );
		
		$title = Title::makeTitle( NS_SPECIAL, 'Search' );
		$action = $title->escapeLocalURL();
		return "<br /><br />\n<form id=\"powersearch\" method=\"get\" " .
		  "action=\"$action\">\n{$ret}\n</form>\n";
	}
}

?>
