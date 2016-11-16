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

use MediaWiki\MediaWikiServices;

/**
 * implements Special:Search - Run text & title search and display the output
 * @ingroup SpecialPage
 */
class SpecialSearch extends SpecialPage {
	/**
	 * Current search profile. Search profile is just a name that identifies
	 * the active search tab on the search page (content, discussions...)
	 * For users tt replaces the set of enabled namespaces from the query
	 * string when applicable. Extensions can add new profiles with hooks
	 * with custom search options just for that profile.
	 * @var null|string
	 */
	protected $profile;

	/** @var SearchEngine Search engine */
	protected $searchEngine;

	/** @var string Search engine type, if not default */
	protected $searchEngineType;

	/** @var array For links */
	protected $extraParams = [];

	/**
	 * @var string The prefix url parameter. Set on the searcher and the
	 * is expected to treat it as prefix filter on titles.
	 */
	protected $mPrefix;

	/**
	 * @var int
	 */
	protected $limit, $offset;

	/**
	 * @var array
	 */
	protected $namespaces;

	/**
	 * @var string
	 */
	protected $fulltext;

	/**
	 * @var bool
	 */
	protected $runSuggestion = true;

	/**
	 * Names of the wikis, in format: Interwiki prefix -> caption
	 * @var array
	 */
	protected $customCaptions;

	/**
	 * Search engine configurations.
	 * @var SearchEngineConfig
	 */
	protected $searchConfig;

	const NAMESPACES_CURRENT = 'sense';

	public function __construct() {
		parent::__construct( 'Search' );
		$this->searchConfig = MediaWikiServices::getInstance()->getSearchEngineConfig();
	}

	/**
	 * Entry point
	 *
	 * @param string $par
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();

		// Fetch the search term
		$term = str_replace( "\n", " ", $request->getText( 'search' ) );

		// Historically search terms have been accepted not only in the search query
		// parameter, but also as part of the primary url. This can have PII implications
		// in releasing page view data. As such issue a 301 redirect to the correct
		// URL.
		if ( strlen( $par ) && !strlen( $term ) ) {
			$query = $request->getValues();
			unset( $query['title'] );
			// Strip underscores from title parameter; most of the time we'll want
			// text form here. But don't strip underscores from actual text params!
			$query['search'] = str_replace( '_', ' ', $par );
			$out->redirect( $this->getPageTitle()->getFullURL( $query ), 301 );
			return;
		}

		// Need to load selected namespaces before handling nsRemember
		$this->load();
		// TODO: This performs database actions on GET request, which is going to
		// be a problem for our multi-datacenter work.
		if ( !is_null( $request->getVal( 'nsRemember' ) ) ) {
			$this->saveNamespaces();
			// Remove the token from the URL to prevent the user from inadvertently
			// exposing it (e.g. by pasting it into a public wiki page) or undoing
			// later settings changes (e.g. by reloading the page).
			$query = $request->getValues();
			unset( $query['title'], $query['nsRemember'] );
			$out->redirect( $this->getPageTitle()->getFullURL( $query ) );
			return;
		}

		$this->searchEngineType = $request->getVal( 'srbackend' );
		if (
			!$request->getVal( 'fulltext' ) &&
			$request->getVal( 'offset' ) === null
		) {
			$url = $this->goResult( $term );
			if ( $url !== null ) {
				// succesfull 'go'
				$out->redirect( $url );
				return;
			}
		}

		$this->setupPage( $term );

		if ( $this->getConfig()->get( 'DisableTextSearch' ) ) {
			$searchFowardUrl = $this->getConfig()->get( 'SearchForwardUrl' );
			if ( $searchFowardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $searchFowardUrl );
				$out->redirect( $url );
			} else {
				$out->addHTML(
					"<fieldset>" .
						"<legend>" .
							$this->msg( 'search-external' )->escaped() .
						"</legend>" .
						"<p class='mw-searchdisabled'>" .
							$this->msg( 'searchdisabled' )->escaped() .
						"</p>" .
						$this->msg( 'googlesearch' )->rawParams(
							htmlspecialchars( $term ),
							'UTF-8',
							$this->msg( 'searchbutton' )->escaped()
						)->text() .
					"</fieldset>"
				);
			}

			return;
		}

		$this->showResults( $term );
	}

	/**
	 * Set up basic search parameters from the request and user settings.
	 *
	 * @see tests/phpunit/includes/specials/SpecialSearchTest.php
	 */
	public function load() {
		$request = $this->getRequest();
		list( $this->limit, $this->offset ) = $request->getLimitOffset( 20, '' );
		$this->mPrefix = $request->getVal( 'prefix', '' );

		$user = $this->getUser();

		# Extract manually requested namespaces
		$nslist = $this->powerSearch( $request );
		if ( !count( $nslist ) ) {
			# Fallback to user preference
			$nslist = $this->searchConfig->userNamespaces( $user );
		}

		$profile = null;
		if ( !count( $nslist ) ) {
			$profile = 'default';
		}

		$profile = $request->getVal( 'profile', $profile );
		$profiles = $this->getSearchProfiles();
		if ( $profile === null ) {
			// BC with old request format
			$profile = 'advanced';
			foreach ( $profiles as $key => $data ) {
				if ( $nslist === $data['namespaces'] && $key !== 'advanced' ) {
					$profile = $key;
				}
			}
			$this->namespaces = $nslist;
		} elseif ( $profile === 'advanced' ) {
			$this->namespaces = $nslist;
		} else {
			if ( isset( $profiles[$profile]['namespaces'] ) ) {
				$this->namespaces = $profiles[$profile]['namespaces'];
			} else {
				// Unknown profile requested
				$profile = 'default';
				$this->namespaces = $profiles['default']['namespaces'];
			}
		}

		$this->fulltext = $request->getVal( 'fulltext' );
		$this->runSuggestion = (bool)$request->getVal( 'runsuggestion', true );
		$this->profile = $profile;
	}

	/**
	 * If an exact title match can be found, jump straight ahead to it.
	 *
	 * @param string $term
	 * @return string|null The url to redirect to, or null if no redirect.
	 */
	public function goResult( $term ) {
		# If the string cannot be used to create a title
		if ( is_null( Title::newFromText( $term ) ) ) {
			return null;
		}
		# If there's an exact or very near match, jump right there.
		$title = $this->getSearchEngine()
			->getNearMatcher( $this->getConfig() )->getNearMatch( $term );
		if ( is_null( $title ) ) {
			return null;
		}
		$url = null;
		if ( !Hooks::run( 'SpecialSearchGoResult', [ $term, $title, &$url ] ) ) {
			return null;
		}

		return $url === null ? $title->getFullURL() : $url;
	}

	/**
	 * @param string $term
	 */
	public function showResults( $term ) {
		global $wgContLang;

		if ( $this->searchEngineType !== null ) {
			$this->setExtraParam( 'srbackend', $this->searchEngineType );
		}

		$out = $this->getOutput();
		$formWidget = new MediaWiki\Widget\Search\SearchFormWidget(
			$this,
			$this->searchConfig,
			$this->getSearchProfiles()
		);
		$filePrefix = $wgContLang->getFormattedNsText( NS_FILE ) . ':';
		if ( trim( $term ) === '' || $filePrefix === trim( $term ) ) {
			// Empty query -- straight view of search form
			if ( !Hooks::run( 'SpecialSearchResultsPrepend', [ $this, $out, $term ] ) ) {
				# Hook requested termination
				return;
			}
			$out->enableOOUI();
			// The form also contains the 'Showing results 0 - 20 of 1234' so we can
			// only do the form render here for the empty $term case. Rendering
			// the form when a search is provided is repeated below.
			$out->addHTML( $formWidget->render(
				$this->profile, $term, 0, 0, $this->offset, $this->isPowerSearch()
			) );
			return;
		}

		$search = $this->getSearchEngine();
		$search->setFeatureData( 'rewrite', $this->runSuggestion );
		$search->setLimitOffset( $this->limit, $this->offset );
		$search->setNamespaces( $this->namespaces );
		$search->prefix = $this->mPrefix;
		$term = $search->transformSearchTerm( $term );

		Hooks::run( 'SpecialSearchSetupEngine', [ $this, $this->profile, $search ] );
		if ( !Hooks::run( 'SpecialSearchResultsPrepend', [ $this, $out, $term ] ) ) {
			# Hook requested termination
			return;
		}

		$title = Title::newFromText( $term );
		$showSuggestion = $title === null || !$title->isKnown();
		$search->setShowSuggestion( $showSuggestion );

		// fetch search results
		$rewritten = $search->replacePrefixes( $term );

		$titleMatches = $search->searchTitle( $rewritten );
		$textMatches = $search->searchText( $rewritten );

		$textStatus = null;
		if ( $textMatches instanceof Status ) {
			$textStatus = $textMatches;
			$textMatches = $textStatus->getValue();
		}

		// Get number of results
		$titleMatchesNum = $textMatchesNum = $numTitleMatches = $numTextMatches = 0;
		if ( $titleMatches ) {
			$titleMatchesNum = $titleMatches->numRows();
			$numTitleMatches = $titleMatches->getTotalHits();
		}
		if ( $textMatches ) {
			$textMatchesNum = $textMatches->numRows();
			$numTextMatches = $textMatches->getTotalHits();
		}
		$num = $titleMatchesNum + $textMatchesNum;
		$totalRes = $numTitleMatches + $numTextMatches;

		// start rendering the page
		$out->enableOOUI();
		$out->addHTML( $formWidget->render(
			$this->profile, $term, $num, $totalRes, $this->offset, $this->isPowerSearch()
		) );

		// did you mean... suggestions
		if ( $textMatches ) {
			if ( $textMatches->hasRewrittenQuery() ) {
				$out->addHTML( $this->getDidYouMeanRewrittenHtml( $term, $textMatches ) );
			} elseif ( $textMatches->hasSuggestion() ) {
				$out->addHTML( $this->getDidYouMeanHtml( $textMatches ) );
			}
		}

		$out->addHTML( "<div class='searchresults'>" );

		$hasErrors = $textStatus && $textStatus->getErrors();
		$hasOtherResults = $textMatches &&
			$textMatches->hasInterwikiResults( SearchResultSet::INLINE_RESULTS );

		if ( $hasErrors ) {
			list( $error, $warning ) = $textStatus->splitByErrorType();
			if ( $error->getErrors() ) {
				$out->addHTML( Html::rawElement(
					'div',
					[ 'class' => 'errorbox' ],
					$error->getHTML( 'search-error' )
				) );
			}
			if ( $warning->getErrors() ) {
				$out->addHTML( Html::rawElement(
					'div',
					[ 'class' => 'warningbox' ],
					$warning->getHTML( 'search-warning' )
				) );
			}
		}

		// Show the create link ahead
		$this->showCreateLink( $title, $num, $titleMatches, $textMatches );

		// If we have no results and have not already displayed an error message
		if ( $num === 0 && !$hasErrors ) {
			$out->wrapWikiMsg( "<p class=\"mw-search-nonefound\">\n$1</p>", [
				$hasOtherResults ? 'search-nonefound-thiswiki' : 'search-nonefound',
				wfEscapeWikiText( $term )
			] );
		}

		Hooks::run( 'SpecialSearchResults', [ $term, $titleMatches, $textMatches ] );

		$out->parserOptions()->setEditSection( false );
		if ( $titleMatches ) {
			if ( $numTitleMatches > 0 ) {
				$out->wrapWikiMsg( "==$1==\n", 'titlematches' );
				$out->addHTML( $this->showMatches( $titleMatches ) );
			}
			$titleMatches->free();
		}

		if ( $textMatches ) {
			// output appropriate heading
			if ( $numTextMatches > 0 && $numTitleMatches > 0 ) {
				$out->addHTML( '<div class="mw-search-visualclear"></div>' );
				// if no title matches the heading is redundant
				$out->wrapWikiMsg( "==$1==\n", 'textmatches' );
			}

			// show results
			if ( $numTextMatches > 0 ) {
				$search->augmentSearchResults( $textMatches );
				$out->addHTML( $this->showMatches( $textMatches ) );
			}

			// show secondary interwiki results if any
			if ( $textMatches->hasInterwikiResults( SearchResultSet::SECONDARY_RESULTS ) ) {
				$out->addHTML( $this->showInterwiki( $textMatches->getInterwikiResults(
						SearchResultSet::SECONDARY_RESULTS ), $term ) );
			}
		}

		if ( $hasOtherResults ) {
			foreach ( $textMatches->getInterwikiResults( SearchResultSet::INLINE_RESULTS )
						as $interwiki => $interwikiResult ) {
				if ( $interwikiResult instanceof Status || $interwikiResult->numRows() == 0 ) {
					// ignore bad interwikis for now
					continue;
				}
				// TODO: wiki header
				$out->addHTML( $this->showMatches( $interwikiResult, $interwiki ) );
			}
		}

		if ( $textMatches ) {
			$textMatches->free();
		}

		$out->addHTML( '<div class="mw-search-visualclear"></div>' );

		// prev/next links
		if ( $totalRes > $this->limit || $this->offset ) {
			$prevnext = $this->getLanguage()->viewPrevNext(
				$this->getPageTitle(),
				$this->offset,
				$this->limit,
				$this->powerSearchOptions() + [ 'search' => $term ],
				$this->limit + $this->offset >= $totalRes
			);
			$out->addHTML( "<p class='mw-search-pager-bottom'>{$prevnext}</p>\n" );
		}

		// Close <div class='searchresults'>
		$out->addHTML( "</div>" );

		Hooks::run( 'SpecialSearchResultsAppend', [ $this, $out, $term ] );
	}

	/**
	 * Produce wiki header for interwiki results
	 * @param string $interwiki Interwiki name
	 * @param SearchResultSet $interwikiResult The result set
	 * @return string
	 */
	protected function interwikiHeader( $interwiki, $interwikiResult ) {
		// TODO: we need to figure out how to name wikis correctly
		$wikiMsg = $this->msg( 'search-interwiki-results-' . $interwiki )->parse();
		return "<p class=\"mw-search-interwiki-header mw-search-visualclear\">\n$wikiMsg</p>";
	}

	/**
	 * Generates HTML shown to the user when we have a suggestion about a query
	 * that might give more results than their current query.
	 */
	protected function getDidYouMeanHtml( SearchResultSet $textMatches ) {
		# mirror Go/Search behavior of original request ..
		$params = [ 'search' => $textMatches->getSuggestionQuery() ];
		if ( $this->fulltext === null ) {
			$params['fulltext'] = 'Search';
		} else {
			$params['fulltext'] = $this->fulltext;
		}
		$stParams = array_merge( $params, $this->powerSearchOptions() );

		$suggest = Linker::linkKnown(
			$this->getPageTitle(),
			$textMatches->getSuggestionSnippet() ?: null,
			[ 'id' => 'mw-search-DYM-suggestion' ],
			$stParams
		);

		# HTML of did you mean... search suggestion link
		return Html::rawElement(
			'div',
			[ 'class' => 'searchdidyoumean' ],
			$this->msg( 'search-suggest' )->rawParams( $suggest )->parse()
		);
	}

	/**
	 * Generates HTML shown to user when their query has been internally rewritten,
	 * and the results of the rewritten query are being returned.
	 *
	 * @param string $term The users search input
	 * @param SearchResultSet $textMatches The response to the users initial search request
	 * @return string HTML linking the user to their original $term query, and the one
	 *  suggested by $textMatches.
	 */
	protected function getDidYouMeanRewrittenHtml( $term, SearchResultSet $textMatches ) {
		// Showing results for '$rewritten'
		// Search instead for '$orig'

		$params = [ 'search' => $textMatches->getQueryAfterRewrite() ];
		if ( $this->fulltext === null ) {
			$params['fulltext'] = 'Search';
		} else {
			$params['fulltext'] = $this->fulltext;
		}
		$stParams = array_merge( $params, $this->powerSearchOptions() );

		$rewritten = Linker::linkKnown(
			$this->getPageTitle(),
			$textMatches->getQueryAfterRewriteSnippet() ?: null,
			[ 'id' => 'mw-search-DYM-rewritten' ],
			$stParams
		);

		$stParams['search'] = $term;
		$stParams['runsuggestion'] = 0;
		$original = Linker::linkKnown(
			$this->getPageTitle(),
			htmlspecialchars( $term ),
			[ 'id' => 'mw-search-DYM-original' ],
			$stParams
		);

		return Html::rawElement(
			'div',
			[ 'class' => 'searchdidyoumean' ],
			$this->msg( 'search-rewritten' )->rawParams( $rewritten, $original )->escaped()
		);
	}

	/**
	 * @param Title $title
	 * @param int $num The number of search results found
	 * @param null|SearchResultSet $titleMatches Results from title search
	 * @param null|SearchResultSet $textMatches Results from text search
	 */
	protected function showCreateLink( $title, $num, $titleMatches, $textMatches ) {
		// show direct page/create link if applicable

		// Check DBkey !== '' in case of fragment link only.
		if ( is_null( $title ) || $title->getDBkey() === ''
			|| ( $titleMatches !== null && $titleMatches->searchContainedSyntax() )
			|| ( $textMatches !== null && $textMatches->searchContainedSyntax() )
		) {
			// invalid title
			// preserve the paragraph for margins etc...
			$this->getOutput()->addHTML( '<p></p>' );

			return;
		}

		$messageName = 'searchmenu-new-nocreate';
		$linkClass = 'mw-search-createlink';

		if ( !$title->isExternal() ) {
			if ( $title->isKnown() ) {
				$messageName = 'searchmenu-exists';
				$linkClass = 'mw-search-exists';
			} elseif ( $title->quickUserCan( 'create', $this->getUser() ) ) {
				$messageName = 'searchmenu-new';
			}
		}

		$params = [
			$messageName,
			wfEscapeWikiText( $title->getPrefixedText() ),
			Message::numParam( $num )
		];
		Hooks::run( 'SpecialSearchCreateLink', [ $title, &$params ] );

		// Extensions using the hook might still return an empty $messageName
		if ( $messageName ) {
			$this->getOutput()->wrapWikiMsg( "<p class=\"$linkClass\">\n$1</p>", $params );
		} else {
			// preserve the paragraph for margins etc...
			$this->getOutput()->addHTML( '<p></p>' );
		}
	}

	/**
	 * @param string $term
	 */
	protected function setupPage( $term ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();
		// TODO: Is this true? The namespace remember uses a user token
		// on save.
		$out->allowClickjacking();
		$this->addHelpLink( 'Help:Searching' );

		if ( strval( $term ) !== '' ) {
			$out->setPageTitle( $this->msg( 'searchresults' ) );
			$out->setHTMLTitle( $this->msg( 'pagetitle' )
				->rawParams( $this->msg( 'searchresults-title' )->rawParams( $term )->text() )
				->inContentLanguage()->text()
			);
		}

		$out->addJsConfigVars( [ 'searchTerm' => $term ] );
		$out->addModules( 'mediawiki.special.search' );
		$out->addModuleStyles( [
			'mediawiki.special', 'mediawiki.special.search.styles', 'mediawiki.ui', 'mediawiki.ui.button',
			'mediawiki.ui.input', 'mediawiki.widgets.SearchInputWidget.styles',
		] );
	}

	/**
	 * Return true if current search is a power (advanced) search
	 *
	 * @return bool
	 */
	protected function isPowerSearch() {
		return $this->profile === 'advanced';
	}

	/**
	 * Extract "power search" namespace settings from the request object,
	 * returning a list of index numbers to search.
	 *
	 * @param WebRequest $request
	 * @return array
	 */
	protected function powerSearch( &$request ) {
		$arr = [];
		foreach ( $this->searchConfig->searchableNamespaces() as $ns => $name ) {
			if ( $request->getCheck( 'ns' . $ns ) ) {
				$arr[] = $ns;
			}
		}

		return $arr;
	}

	/**
	 * Reconstruct the 'power search' options for links
	 *
	 * @return array
	 */
	protected function powerSearchOptions() {
		$opt = [];
		if ( $this->isPowerSearch() ) {
			foreach ( $this->namespaces as $n ) {
				$opt['ns' . $n] = 1;
			}
		} else {
			$opt['profile'] = $this->profile;
		}

		return $opt + $this->extraParams;
	}

	/**
	 * Save namespace preferences when we're supposed to
	 *
	 * @return bool Whether we wrote something
	 */
	protected function saveNamespaces() {
		$user = $this->getUser();
		$request = $this->getRequest();

		if ( $user->isLoggedIn() &&
			$user->matchEditToken(
				$request->getVal( 'nsRemember' ),
				'searchnamespace',
				$request
			) && !wfReadOnly()
		) {
			// Reset namespace preferences: namespaces are not searched
			// when they're not mentioned in the URL parameters.
			foreach ( MWNamespace::getValidNamespaces() as $n ) {
				$user->setOption( 'searchNs' . $n, false );
			}
			// The request parameters include all the namespaces to be searched.
			// Even if they're the same as an existing profile, they're not eaten.
			foreach ( $this->namespaces as $n ) {
				$user->setOption( 'searchNs' . $n, true );
			}

			DeferredUpdates::addCallableUpdate( function () use ( $user ) {
				$user->saveSettings();
			} );

			return true;
		}

		return false;
	}

	/**
	 * Show whole set of results
	 *
	 * @param SearchResultSet $matches
	 * @param string $interwiki Interwiki name
	 *
	 * @return string
	 */
	protected function showMatches( $matches, $interwiki = null ) {
		global $wgContLang;

		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$out = '';
		$result = $matches->next();
		$pos = $this->offset;

		if ( $result && $interwiki ) {
			$out .= $this->interwikiHeader( $interwiki, $matches );
		}

		$out .= "<ul class='mw-search-results'>\n";
		$widget = new \MediaWiki\Widget\Search\FullSearchResultWidget( $this );
		while ( $result ) {
			$out .= $widget->render( $result, $terms, $pos++ );
			$result = $matches->next();
		}
		$out .= "</ul>\n";

		// convert the whole thing to desired language variant
		$out = $wgContLang->convert( $out );

		return $out;
	}

	/**
	 * Extract custom captions from search-interwiki-custom message
	 */
	protected function getCustomCaptions() {
		if ( is_null( $this->customCaptions ) ) {
			$this->customCaptions = [];
			// format per line <iwprefix>:<caption>
			$customLines = explode( "\n", $this->msg( 'search-interwiki-custom' )->text() );
			foreach ( $customLines as $line ) {
				$parts = explode( ":", $line, 2 );
				if ( count( $parts ) == 2 ) { // validate line
					$this->customCaptions[$parts[0]] = $parts[1];
				}
			}
		}
	}

	/**
	 * Show results from other wikis
	 *
	 * @param SearchResultSet|array $matches
	 * @param string $query
	 *
	 * @return string
	 */
	protected function showInterwiki( $matches, $query ) {
		global $wgContLang;

		// work out custom project captions
		$this->getCustomCaptions();

		if ( !is_array( $matches ) ) {
			$matches = [ $matches ];
		}

		$iwResults = [];
		foreach ( $matches as $set ) {
			$result = $set->next();
			while ( $result ) {
				if ( !$result->isBrokenTitle() ) {
					$iwResults[$result->getTitle()->getInterwiki()][] = $result;
				}
				$result = $set->next();
			}
		}

		$out = '';
		$widget = new MediaWiki\Widget\Search\SimpleSearchResultWidget( $this );
		foreach ( $iwResults as $iwPrefix => $results ) {
			$out .= $this->iwHeaderHtml( $iwPrefix );
			$out .= "<ul class='mw-search-iwresults'>";
			foreach ( $results as $result ) {
				$out .= $widget->render( $result );
			}
			$ot .= "</ul>";
		}

		$out =
			"<div id='mw-search-interwiki'>" .
				"<div id='mw-search-interwiki-caption'>" .
					$this->msg( 'search-interwiki-caption' )->text() .
				"</div>" .
				$out .
			"</div>";

		// convert the whole thing to desired language variant
		return $wgContLang->convert( $out );
	}

	protected function iwHeaderHtml( $iwPrefix ) {
		if ( isset( $this->customCaptions[$iwPrefix] ) ) {
			$caption = $this->customCaptions[$iwPrefix];
		} else {
			$iwLookup = MediaWiki\MediaWikiServices::getInstance()->getInterwikiLookup();
			$interwiki = $iwLookup->fetch( $iwPrefix );
			$parsed = wfParseUrl( wfExpandUrl( $interwiki ? $interwiki->getURL() : '/' ) );
			$caption = $this->msg( 'search-interwiki-default', $parsed['host'] )->text();
		}
		$searchLink = Linker::linkKnown(
			Title::newFromText( "$iwPrefix:Special:Search" ),
			$this->msg( 'search-interwiki-more' )->text(),
			[],
			[
				'search' => $query,
				'fulltext' => 1,
			]
		);
		return
			"<div class='mw-search-interwiki-project'>" .
				"<span class='mw-search-interwiki-more'>{$searchLink}</span>" .
				$caption .
			"</div>";
	}

	/**
	 * @return array
	 */
	protected function getSearchProfiles() {
		// Builds list of Search Types (profiles)
		$nsAllSet = array_keys( $this->searchConfig->searchableNamespaces() );
		$defaultNs = $this->searchConfig->defaultNamespaces();
		$profiles = [
			'default' => [
				'message' => 'searchprofile-articles',
				'tooltip' => 'searchprofile-articles-tooltip',
				'namespaces' => $defaultNs,
				'namespace-messages' => $this->searchConfig->namespacesAsText(
					$defaultNs
				),
			],
			'images' => [
				'message' => 'searchprofile-images',
				'tooltip' => 'searchprofile-images-tooltip',
				'namespaces' => [ NS_FILE ],
			],
			'all' => [
				'message' => 'searchprofile-everything',
				'tooltip' => 'searchprofile-everything-tooltip',
				'namespaces' => $nsAllSet,
			],
			'advanced' => [
				'message' => 'searchprofile-advanced',
				'tooltip' => 'searchprofile-advanced-tooltip',
				'namespaces' => self::NAMESPACES_CURRENT,
			]
		];

		Hooks::run( 'SpecialSearchProfiles', [ &$profiles ] );

		foreach ( $profiles as &$data ) {
			if ( !is_array( $data['namespaces'] ) ) {
				continue;
			}
			sort( $data['namespaces'] );
		}

		return $profiles;
	}

	/**
	 * @since 1.18
	 *
	 * @return SearchEngine
	 */
	public function getSearchEngine() {
		if ( $this->searchEngine === null ) {
			$this->searchEngine = $this->searchEngineType ?
				MediaWikiServices::getInstance()->getSearchEngineFactory()->create( $this->searchEngineType ) :
				MediaWikiServices::getInstance()->newSearchEngine();
		}

		return $this->searchEngine;
	}

	/**
	 * Current search profile.
	 * @return null|string
	 */
	function getProfile() {
		return $this->profile;
	}

	/**
	 * Current namespaces.
	 * @return array
	 */
	function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * Users of hook SpecialSearchSetupEngine can use this to
	 * add more params to links to not lose selection when
	 * user navigates search results.
	 * @since 1.18
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setExtraParam( $key, $value ) {
		$this->extraParams[$key] = $value;
	}

	protected function getGroupName() {
		return 'pages';
	}
}
