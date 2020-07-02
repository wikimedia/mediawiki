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
use MediaWiki\Search\SearchWidgets\BasicSearchResultSetWidget;
use MediaWiki\Search\SearchWidgets\FullSearchResultWidget;
use MediaWiki\Search\SearchWidgets\InterwikiSearchResultSetWidget;
use MediaWiki\Search\SearchWidgets\InterwikiSearchResultWidget;
use MediaWiki\Search\SearchWidgets\SimpleSearchResultSetWidget;
use MediaWiki\Search\SearchWidgets\SimpleSearchResultWidget;

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
	 * @var string
	 */
	protected $sort = SearchEngine::DEFAULT_SORT;

	/**
	 * @var bool
	 */
	protected $runSuggestion = true;

	/**
	 * Search engine configurations.
	 * @var SearchEngineConfig
	 */
	protected $searchConfig;

	/**
	 * @var Status Holds any parameter validation errors that should
	 *  be displayed back to the user.
	 */
	private $loadStatus;

	private const NAMESPACES_CURRENT = 'sense';

	public function __construct() {
		parent::__construct( 'Search' );
		$this->searchConfig = MediaWikiServices::getInstance()->getSearchEngineConfig();
	}

	/**
	 * Entry point
	 *
	 * @param string|null $par
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
		if ( $par !== null && $par !== '' && $term === '' ) {
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
		if ( $request->getCheck( 'nsRemember' ) ) {
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
		if ( !$request->getVal( 'fulltext' ) && !$request->getCheck( 'offset' ) ) {
			$url = $this->goResult( $term );
			if ( $url !== null ) {
				// successful 'go'
				$out->redirect( $url );
				return;
			}
			// No match. If it could plausibly be a title
			// run the No go match hook.
			$title = Title::newFromText( $term );
			if ( $title !== null ) {
				$this->getHookRunner()->onSpecialSearchNogomatch( $title );
			}
		}

		$this->setupPage( $term );

		if ( $this->getConfig()->get( 'DisableTextSearch' ) ) {
			$searchForwardUrl = $this->getConfig()->get( 'SearchForwardUrl' );
			if ( $searchForwardUrl ) {
				$url = str_replace( '$1', urlencode( $term ), $searchForwardUrl );
				$out->redirect( $url );
			} else {
				$this->showGoogleSearch( $term );
			}

			return;
		}

		$this->showResults( $term );
	}

	/**
	 * Output a google search form if search is disabled
	 *
	 * @param string $term Search term
	 * @todo FIXME Maybe we should get rid of this raw html message at some future time
	 * @suppress SecurityCheck-XSS
	 */
	private function showGoogleSearch( $term ) {
		$this->getOutput()->addHTML(
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

	/**
	 * Set up basic search parameters from the request and user settings.
	 *
	 * @see tests/phpunit/includes/specials/SpecialSearchTest.php
	 */
	public function load() {
		$this->loadStatus = new Status();

		$request = $this->getRequest();
		list( $this->limit, $this->offset ) = $request->getLimitOffsetForUser(
			$this->getUser(),
			20,
			''
		);
		$this->mPrefix = $request->getVal( 'prefix', '' );
		if ( $this->mPrefix !== '' ) {
			$this->setExtraParam( 'prefix', $this->mPrefix );
		}

		$sort = $request->getVal( 'sort', SearchEngine::DEFAULT_SORT );
		$validSorts = $this->getSearchEngine()->getValidSorts();
		if ( !in_array( $sort, $validSorts ) ) {
			$this->loadStatus->warning( 'search-invalid-sort-order', $sort,
				implode( ', ', $validSorts ) );
		} elseif ( $sort !== $this->sort ) {
			$this->sort = $sort;
			$this->setExtraParam( 'sort', $this->sort );
		}

		$user = $this->getUser();

		# Extract manually requested namespaces
		$nslist = $this->powerSearch( $request );
		if ( $nslist === [] ) {
			# Fallback to user preference
			$nslist = $this->searchConfig->userNamespaces( $user );
		}

		$profile = null;
		if ( $nslist === [] ) {
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
		} elseif ( isset( $profiles[$profile]['namespaces'] ) ) {
			$this->namespaces = $profiles[$profile]['namespaces'];
		} else {
			// Unknown profile requested
			$this->loadStatus->warning( 'search-unknown-profile', $profile );
			$profile = 'default';
			$this->namespaces = $profiles['default']['namespaces'];
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
		if ( Title::newFromText( $term ) === null ) {
			return null;
		}
		# If there's an exact or very near match, jump right there.
		$title = $this->getSearchEngine()
			->getNearMatcher( $this->getConfig() )->getNearMatch( $term );
		if ( $title === null ) {
			return null;
		}
		$url = null;
		if ( !$this->getHookRunner()->onSpecialSearchGoResult( $term, $title, $url ) ) {
			return null;
		}

		if (
			// If there is a preference set to NOT redirect on exact page match
			// then return null (which prevents direction)
			!$this->redirectOnExactMatch()
			// BUT ...
			// ... ignore no-redirect preference if the exact page match is an interwiki link
			&& !$title->isExternal()
			// ... ignore no-redirect preference if the exact page match is NOT in the main
			// namespace AND there's a namespace in the search string
			&& !( $title->getNamespace() !== NS_MAIN && strpos( $term, ':' ) > 0 )
		) {
			return null;
		}

		return $url ?? $title->getFullUrlForRedirect();
	}

	private function redirectOnExactMatch() {
		global $wgSearchMatchRedirectPreference;
		if ( !$wgSearchMatchRedirectPreference ) {
			// If the preference for whether to redirect is disabled, use the default setting
			$defaultOptions = $this->getUser()->getDefaultOptions();
			return $defaultOptions['search-match-redirect'];
		} else {
			// Otherwise use the user's preference
			return $this->getUser()->getOption( 'search-match-redirect' );
		}
	}

	/**
	 * @param string $term
	 */
	public function showResults( $term ) {
		if ( $this->searchEngineType !== null ) {
			$this->setExtraParam( 'srbackend', $this->searchEngineType );
		}

		$out = $this->getOutput();
		$widgetOptions = $this->getConfig()->get( 'SpecialSearchFormOptions' );
		$formWidget = new MediaWiki\Search\SearchWidgets\SearchFormWidget(
			$this,
			$this->searchConfig,
			$this->getHookContainer(),
			$this->getSearchProfiles()
		);
		$filePrefix = MediaWikiServices::getInstance()->getContentLanguage()->
			getFormattedNsText( NS_FILE ) . ':';
		if ( trim( $term ) === '' || $filePrefix === trim( $term ) ) {
			// Empty query -- straight view of search form
			if ( !$this->getHookRunner()->onSpecialSearchResultsPrepend( $this, $out, $term ) ) {
				# Hook requested termination
				return;
			}
			$out->enableOOUI();
			// The form also contains the 'Showing results 0 - 20 of 1234' so we can
			// only do the form render here for the empty $term case. Rendering
			// the form when a search is provided is repeated below.
			$out->addHTML( $formWidget->render(
				$this->profile, $term, 0, 0, $this->offset, $this->isPowerSearch(), $widgetOptions
			) );
			return;
		}

		$engine = $this->getSearchEngine();
		$engine->setFeatureData( 'rewrite', $this->runSuggestion );
		$engine->setLimitOffset( $this->limit, $this->offset );
		$engine->setNamespaces( $this->namespaces );
		$engine->setSort( $this->sort );
		$engine->prefix = $this->mPrefix;

		$this->getHookRunner()->onSpecialSearchSetupEngine( $this, $this->profile, $engine );
		if ( !$this->getHookRunner()->onSpecialSearchResultsPrepend( $this, $out, $term ) ) {
			# Hook requested termination
			return;
		}

		$title = Title::newFromText( $term );
		$showSuggestion = $title === null || !$title->isKnown();
		$engine->setShowSuggestion( $showSuggestion );

		$rewritten = $engine->replacePrefixes( $term );
		if ( $rewritten !== $term ) {
			wfDeprecatedMsg( 'SearchEngine::replacePrefixes()  was overridden by ' .
				get_class( $engine ) . ', this is deprecated since MediaWiki 1.32',
				'1.32', false, false );
		}

		// fetch search results
		$titleMatches = $engine->searchTitle( $rewritten );
		$textMatches = $engine->searchText( $rewritten );

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
			if ( $textMatchesNum > 0 ) {
				$engine->augmentSearchResults( $textMatches );
			}
		}
		$num = $titleMatchesNum + $textMatchesNum;
		$totalRes = $numTitleMatches + $numTextMatches;

		// start rendering the page
		$out->enableOOUI();
		$out->addHTML( $formWidget->render(
			$this->profile, $term, $num, $totalRes, $this->offset, $this->isPowerSearch(), $widgetOptions
		) );

		// did you mean... suggestions
		if ( $textMatches ) {
			$dymWidget = new MediaWiki\Search\SearchWidgets\DidYouMeanWidget( $this );
			$out->addHTML( $dymWidget->render( $term, $textMatches ) );
		}

		$hasSearchErrors = $textStatus && $textStatus->getErrors() !== [];
		$hasOtherResults = $textMatches &&
			$textMatches->hasInterwikiResults( ISearchResultSet::INLINE_RESULTS );

		if ( $textMatches && $textMatches->hasInterwikiResults( ISearchResultSet::SECONDARY_RESULTS ) ) {
			$out->addHTML( '<div class="searchresults mw-searchresults-has-iw">' );
		} else {
			$out->addHTML( '<div class="searchresults">' );
		}

		if ( $hasSearchErrors || $this->loadStatus->getErrors() ) {
			if ( $textStatus === null ) {
				$textStatus = $this->loadStatus;
			} else {
				$textStatus->merge( $this->loadStatus );
			}
			list( $error, $warning ) = $textStatus->splitByErrorType();
			if ( $error->getErrors() ) {
				$out->addHTML( Html::errorBox(
					$error->getHTML( 'search-error' )
				) );
			}
			if ( $warning->getErrors() ) {
				$out->addHTML( Html::warningBox(
					$warning->getHTML( 'search-warning' )
				) );
			}
		}

		// Show the create link ahead
		$this->showCreateLink( $title, $num, $titleMatches, $textMatches );

		$this->getHookRunner()->onSpecialSearchResults( $term, $titleMatches, $textMatches );

		// If we have no results and have not already displayed an error message
		if ( $num === 0 && !$hasSearchErrors ) {
			$out->wrapWikiMsg( "<p class=\"mw-search-nonefound\">\n$1</p>", [
				$hasOtherResults ? 'search-nonefound-thiswiki' : 'search-nonefound',
				wfEscapeWikiText( $term )
			] );
		}

		// Although $num might be 0 there can still be secondary or inline
		// results to display.
		$linkRenderer = $this->getLinkRenderer();
		$mainResultWidget = new FullSearchResultWidget(
			$this, $linkRenderer, $this->getHookContainer() );

		// Default (null) on. Can be explicitly disabled.
		if ( $engine->getFeatureData( 'enable-new-crossproject-page' ) !== false ) {
			$sidebarResultWidget = new InterwikiSearchResultWidget( $this, $linkRenderer );
			$sidebarResultsWidget = new InterwikiSearchResultSetWidget(
				$this,
				$sidebarResultWidget,
				$linkRenderer,
				MediaWikiServices::getInstance()->getInterwikiLookup(),
				$engine->getFeatureData( 'show-multimedia-search-results' )
			);
		} else {
			$sidebarResultWidget = new SimpleSearchResultWidget( $this, $linkRenderer );
			$sidebarResultsWidget = new SimpleSearchResultSetWidget(
				$this,
				$sidebarResultWidget,
				$linkRenderer,
				MediaWikiServices::getInstance()->getInterwikiLookup()
			);
		}

		$widget = new BasicSearchResultSetWidget( $this, $mainResultWidget, $sidebarResultsWidget );

		$out->addHTML( $widget->render(
			$term, $this->offset, $titleMatches, $textMatches
		) );

		$out->addHTML( '<div class="mw-search-visualclear"></div>' );

		// prev/next links
		if ( $totalRes > $this->limit || $this->offset ) {
			// Allow matches to define the correct offset, as interleaved
			// AB testing may require a different next page offset.
			if ( $textMatches && $textMatches->getOffset() !== null ) {
				$offset = $textMatches->getOffset();
			} else {
				$offset = $this->offset;
			}

			$prevNext = $this->buildPrevNextNavigation(
				$offset,
				$this->limit,
				$this->powerSearchOptions() + [ 'search' => $term ],
				$this->limit + $this->offset >= $totalRes
			);
			$out->addHTML( "<p class='mw-search-pager-bottom'>{$prevNext}</p>\n" );
		}

		// Close <div class='searchresults'>
		$out->addHTML( "</div>" );

		$this->getHookRunner()->onSpecialSearchResultsAppend( $this, $out, $term );
	}

	/**
	 * @param Title $title
	 * @param int $num The number of search results found
	 * @param null|ISearchResultSet $titleMatches Results from title search
	 * @param null|ISearchResultSet $textMatches Results from text search
	 */
	protected function showCreateLink( $title, $num, $titleMatches, $textMatches ) {
		// show direct page/create link if applicable

		// Check DBkey !== '' in case of fragment link only.
		if ( $title === null || $title->getDBkey() === ''
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
			} elseif (
				MediaWikiServices::getInstance()
					->getContentHandlerFactory()
					->getContentHandler( $title->getContentModel() )
					->supportsDirectEditing()
				&& MediaWikiServices::getInstance()->getPermissionManager()->quickUserCan( 'create',
					$this->getUser(), $title )
				&& MediaWikiServices::getInstance()->getPermissionManager()->quickUserCan( 'edit',
					$this->getUser(), $title )
			) {
				$messageName = 'searchmenu-new';
			}
		}

		$params = [
			$messageName,
			wfEscapeWikiText( $title->getPrefixedText() ),
			Message::numParam( $num )
		];
		$this->getHookRunner()->onSpecialSearchCreateLink( $title, $params );

		// Extensions using the hook might still return an empty $messageName
		// @phan-suppress-next-line PhanRedundantCondition Set by hook
		if ( $messageName ) {
			$this->getOutput()->wrapWikiMsg( "<p class=\"$linkClass\">\n$1</p>", $params );
		} else {
			// preserve the paragraph for margins etc...
			$this->getOutput()->addHTML( '<p></p>' );
		}
	}

	/**
	 * Sets up everything for the HTML output page including styles, javascript,
	 * page title, etc.
	 *
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
				->plaintextParams( $this->msg( 'searchresults-title' )->plaintextParams( $term )->text() )
				->inContentLanguage()->text()
			);
		}

		if ( $this->mPrefix !== '' ) {
			$subtitle = $this->msg( 'search-filter-title-prefix' )->plaintextParams( $this->mPrefix );
			$params = $this->powerSearchOptions();
			unset( $params['prefix'] );
			$params += [
				'search' => $term,
				'fulltext' => 1,
			];

			$subtitle .= ' (';
			$subtitle .= Xml::element(
				'a',
				[
					'href' => $this->getPageTitle()->getLocalURL( $params ),
					'title' => $this->msg( 'search-filter-title-prefix-reset' )->text(),
				],
				$this->msg( 'search-filter-title-prefix-reset' )->text()
			);
			$subtitle .= ')';
			$out->setSubtitle( $subtitle );
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
	 * @param WebRequest &$request
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
	 * TODO: Instead of exposing this publicly, could we instead expose
	 *  a function for creating search links?
	 *
	 * @return array
	 */
	public function powerSearchOptions() {
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
			foreach ( MediaWikiServices::getInstance()->getNamespaceInfo()->getValidNamespaces()
				as $n
			) {
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
	 * @return array[]
	 * @phan-return array<string,array{message:string,tooltip:string,namespaces:int|string|(int|string)[],namespace-messages?:string[]}>
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

		$this->getHookRunner()->onSpecialSearchProfiles( $profiles );

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
			$services = MediaWikiServices::getInstance();
			$this->searchEngine = $this->searchEngineType ?
				$services->getSearchEngineFactory()->create( $this->searchEngineType ) :
				$services->newSearchEngine();
		}

		return $this->searchEngine;
	}

	/**
	 * Current search profile.
	 * @return null|string
	 */
	public function getProfile() {
		return $this->profile;
	}

	/**
	 * Current namespaces.
	 * @return array
	 */
	public function getNamespaces() {
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

	/**
	 * The prefix value send to Special:Search using the 'prefix' URI param
	 * It means that the user is willing to search for pages whose titles start with
	 * this prefix value.
	 * (Used by the InputBox extension)
	 *
	 * @return string
	 */
	public function getPrefix() {
		return $this->mPrefix;
	}

	protected function getGroupName() {
		return 'pages';
	}
}
