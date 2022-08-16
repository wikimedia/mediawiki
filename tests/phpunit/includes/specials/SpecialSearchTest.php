<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Test class for SpecialSearch class
 * Copyright © 2012, Antoine Musso
 *
 * @author Antoine Musso
 * @group Database
 */
class SpecialSearchTest extends MediaWikiIntegrationTestCase {

	private function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialSearch(
			$services->getSearchEngineConfig(),
			$services->getSearchEngineFactory(),
			$services->getNamespaceInfo(),
			$services->getContentHandlerFactory(),
			$services->getInterwikiLookup(),
			$services->getReadOnlyMode(),
			$services->getUserOptionsManager(),
			$services->getLanguageConverterFactory()
		);
	}

	/**
	 * @covers SpecialSearch::load
	 */
	public function testAlternativeBackend() {
		$this->overrideConfigValue( MainConfigNames::SearchTypeAlternatives, [ 'MockSearchEngine' ] );

		$ctx = new RequestContext();
		$ctx->setRequest( new FauxRequest( [
			'search' => 'foo',
			'srbackend' => 'MockSearchEngine',
		] ) );
		$search = $this->newSpecialPage();
		$search->setContext( $ctx );

		$search->load();

		# Without the parameter srbackend it would be a SearchEngineDummy
		$this->assertInstanceOf( MockSearchEngine::class, $search->getSearchEngine() );
	}

	/**
	 * @covers SpecialSearch::load
	 * @covers SpecialSearch::showResults
	 */
	public function testValidateSortOrder() {
		$ctx = new RequestContext();
		$ctx->setRequest( new FauxRequest( [
			'search' => 'foo',
			'fulltext' => 1,
			'sort' => 'invalid',
		] ) );
		$sp = Title::makeTitle( NS_SPECIAL, 'Search' );
		$this->getServiceContainer()
			->getSpecialPageFactory()
			->executePath( $sp, $ctx );
		$html = $ctx->getOutput()->getHTML();
		$this->assertRegExp( '/class="mw-message-box-warning/', $html, 'must contain warnings' );
		$this->assertRegExp( '/Sort order of invalid is unrecognized/',
			$html, 'must tell user sort order is invalid' );
	}

	/**
	 * @covers SpecialSearch::load
	 * @dataProvider provideSearchOptionsTests
	 * @param array $requested Request parameters. For example:
	 *   [ 'ns5' => true, 'ns6' => true ]. Null to use default options.
	 * @param array $userOptions User options to test with. For example:
	 *   [ 'searchNs5' => 1 ];. Null to use default options.
	 * @param string $expectedProfile An expected search profile name
	 * @param array $expectedNS Expected namespaces
	 * @param string $message
	 */
	public function testProfileAndNamespaceLoading( $requested, $userOptions,
		$expectedProfile, $expectedNS, $message = 'Profile name and namespaces mismatches!'
	) {
		$context = new RequestContext;
		$context->setUser(
			$this->newUserWithSearchNS( $userOptions )
		);
		/*
		$context->setRequest( new FauxRequest( [
			'ns5'=>true,
			'ns6'=>true,
		] ));
		 */
		$context->setRequest( new FauxRequest( $requested ) );
		$search = $this->newSpecialPage();
		$search->setContext( $context );
		$search->load();

		/**
		 * Verify profile name and namespace in the same assertion to make
		 * sure we will be able to fully compare the above code. PHPUnit stop
		 * after an assertion fail.
		 */
		$this->assertEquals(
			[ /** Expected: */
				'ProfileName' => $expectedProfile,
				'Namespaces' => $expectedNS,
			],
			[ /** Actual: */
				'ProfileName' => $search->getProfile(),
				'Namespaces' => $search->getNamespaces(),
			],
			$message
		);
	}

	public static function provideSearchOptionsTests() {
		$defaultNS = MediaWikiServices::getInstance()->getSearchEngineConfig()->defaultNamespaces();
		$EMPTY_REQUEST = [];
		$NO_USER_PREF = null;

		return [
			/**
			 * Parameters:
			 *     <Web Request>, <User options>
			 * Followed by expected values:
			 *     <ProfileName>, <NSList>
			 * Then an optional message.
			 */
			[
				$EMPTY_REQUEST, $NO_USER_PREF,
				'default', $defaultNS,
				'T35270: No request nor user preferences should give default profile'
			],
			[
				[ 'ns5' => 1 ], $NO_USER_PREF,
				'advanced', [ 5 ],
				'Web request with specific NS should override user preference'
			],
			[
				$EMPTY_REQUEST, [
				'searchNs2' => 1,
				'searchNs14' => 1,
			] + array_fill_keys( array_map( static function ( $ns ) {
				return "searchNs$ns";
			}, $defaultNS ), 0 ),
				'advanced', [ 2, 14 ],
				'T35583: search with no option should honor User search preferences'
					. ' and have all other namespace disabled'
			],
		];
	}

	/**
	 * Helper to create a new User object with given options
	 * User remains anonymous though
	 * @param array|null $opt
	 * @return User
	 */
	protected function newUserWithSearchNS( $opt = null ) {
		$u = User::newFromId( 0 );
		if ( $opt === null ) {
			return $u;
		}
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		foreach ( $opt as $name => $value ) {
			$userOptionsManager->setOption( $u, $name, $value );
		}

		return $u;
	}

	/**
	 * Verify we do not expand search term in <title> on search result page
	 * https://gerrit.wikimedia.org/r/4841
	 * @covers SpecialSearch::setupPage
	 */
	public function testSearchTermIsNotExpanded() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->overrideConfigValue( MainConfigNames::SearchType, null );

		# Initialize [[Special::Search]]
		$ctx = new RequestContext();
		$term = '{{SITENAME}}';
		$ctx->setRequest( new FauxRequest( [ 'search' => $term, 'fulltext' => 1 ] ) );
		$ctx->setTitle( Title::newFromText( 'Special:Search' ) );
		$search = $this->newSpecialPage();
		$search->setContext( $ctx );

		# Simulate a user searching for a given term
		$search->execute( '' );

		# Lookup the HTML page title set for that page
		$pageTitle = $search
			->getContext()
			->getOutput()
			->getHTMLTitle();

		# Compare :-]
		$this->assertRegExp(
			'/' . preg_quote( $term, '/' ) . '/',
			$pageTitle,
			"Search term '{$term}' should not be expanded in Special:Search <title>"
		);
	}

	public function provideRewriteQueryWithSuggestion() {
		return [
			[
				'With suggestion and no rewritten query shows did you mean',
				'/Did you mean: <a[^>]+>first suggestion/',
				'first suggestion',
				null,
				[ Title::newMainPage() ]
			],

			[
				'With rewritten query informs user of change',
				'/Showing results for <a[^>]+>first suggestion/',
				'asdf',
				'first suggestion',
				[ Title::newMainPage() ]
			],

			[
				'When both queries have no results user gets no results',
				'/There were no results matching the query/',
				'first suggestion',
				'first suggestion',
				[]
			],

			[
				'Prev/next links are using the rewritten query',
				'/search=rewritten\+query" title="Next 20 results"/',
				'original query',
				'rewritten query',
				array_fill( 0, 100, Title::newMainPage() )
			],

			[
				'Show x results per page link uses the rewritten query',
				'/search=rewritten\+query" title="Show \d+ results/',
				'original query',
				'rewritten query',
				array_fill( 0, 100, Title::newMainPage() )
			],
		];
	}

	/**
	 * @dataProvider provideRewriteQueryWithSuggestion
	 * @covers SpecialSearch::showResults
	 */
	public function testRewriteQueryWithSuggestion(
		$message,
		$expectRegex,
		$suggestion,
		$rewrittenQuery,
		array $resultTitles
	) {
		$results = array_map( static function ( $title ) {
			return SearchResult::newFromTitle( $title );
		}, $resultTitles );

		$searchResults = new SpecialSearchTestMockResultSet(
			$suggestion,
			$rewrittenQuery,
			$results
		);

		$mockSearchEngine = $this->mockSearchEngine( $searchResults );
		$services = $this->getServiceContainer();
		$search = $this->getMockBuilder( SpecialSearch::class )
			->setConstructorArgs( [
				$services->getSearchEngineConfig(),
				$services->getSearchEngineFactory(),
				$services->getNamespaceInfo(),
				$services->getContentHandlerFactory(),
				$services->getInterwikiLookup(),
				$services->getReadOnlyMode(),
				$services->getUserOptionsManager(),
				$services->getLanguageConverterFactory()
			] )
			->onlyMethods( [ 'getSearchEngine' ] )
			->getMock();
		$search->method( 'getSearchEngine' )
			->willReturn( $mockSearchEngine );

		$search->getContext()->setTitle( Title::makeTitle( NS_SPECIAL, 'Search' ) );
		$search->getContext()->setLanguage( 'en' );
		$search->load();
		$search->showResults( 'this is a fake search' );

		$html = $search->getContext()->getOutput()->getHTML();
		foreach ( (array)$expectRegex as $regex ) {
			$this->assertRegExp( $regex, $html, $message );
		}
	}

	public function provideLimitPreference() {
		return [
			[ 20, 20 ],
			[ 101, null ],
		];
	}

	/**
	 * @dataProvider provideLimitPreference
	 * @covers SpecialSearch::showResults
	 */
	public function testLimitPreference(
		$optionValue,
		$expectedLimit
	) {
		$results = array_fill( 0, 100, SearchResult::newFromTitle( Title::newMainPage() ) );

		$searchResults = new SpecialSearchTestMockResultSet(
			'?',
			'!',
			$results
		);

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$user = User::newFromName( 'UTSysop' );
		$userOptionsManager->setOption( $user, 'searchlimit', $optionValue );
		$user->saveSettings();

		$mockSearchEngine = $this->mockSearchEngine( $searchResults );
		$services = $this->getServiceContainer();
		$search = $this->getMockBuilder( SpecialSearch::class )
			->setConstructorArgs( [
				$services->getSearchEngineConfig(),
				$services->getSearchEngineFactory(),
				$services->getNamespaceInfo(),
				$services->getContentHandlerFactory(),
				$services->getInterwikiLookup(),
				$services->getReadOnlyMode(),
				$userOptionsManager,
				$services->getLanguageConverterFactory()
			] )
			->onlyMethods( [ 'getSearchEngine' ] )
			->getMock();
		$search->method( 'getSearchEngine' )
			->willReturn( $mockSearchEngine );

		$search->getContext()->setTitle( Title::makeTitle( NS_SPECIAL, 'Search' ) );
		$search->getContext()->setUser( $user );
		$search->getContext()->setLanguage( 'en' );
		$search->load();
		$search->showResults( 'this is a fake search' );

		$html = $search->getContext()->getOutput()->getHTML();
		if ( $expectedLimit === null ) {
			$this->assertNotRegExp( "/ title=\"Next \\d+ results\"/", $html );
		} else {
			$this->assertRegExp( "/ title=\"Next $expectedLimit results\"/", $html );
		}
	}

	protected function mockSearchEngine( SpecialSearchTestMockResultSet $results ) {
		$mock = $this->getMockBuilder( SearchEngine::class )
			->onlyMethods( [ 'searchText', 'searchTitle', 'getNearMatcher' ] )
			->getMock();

		$mock->method( 'searchText' )
			->willReturn( $results );

		$nearMatcherMock = $this->getMockBuilder( SearchNearMatcher::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getNearMatch' ] )
			->getMock();

		$nearMatcherMock->method( 'getNearMatch' )
			->willReturn( $results->getFirstResult() );

		$mock->method( 'getNearMatcher' )
			->willReturn( $nearMatcherMock );

		$mock->setHookContainer( $this->getServiceContainer()->getHookContainer() );

		return $mock;
	}

	/**
	 * @covers SpecialSearch::execute
	 */
	public function testSubPageRedirect() {
		$this->overrideConfigValue( MainConfigNames::Script, '/w/index.php' );

		$ctx = new RequestContext;
		$sp = Title::newFromText( 'Special:Search/foo_bar' );
		$this->getServiceContainer()->getSpecialPageFactory()->executePath( $sp, $ctx );
		$url = $ctx->getOutput()->getRedirect();

		$parts = parse_url( $url );
		$this->assertEquals( '/w/index.php', $parts['path'] );
		parse_str( $parts['query'], $query );
		$this->assertEquals( 'Special:Search', $query['title'] );
		$this->assertEquals( 'foo bar', $query['search'] );
	}

	/**
	 * If the 'search-match-redirect' user pref is false, then SpecialSearch::goResult() should
	 * return null
	 *
	 * @covers SpecialSearch::goResult
	 */
	public function testGoResult_userPrefRedirectOn() {
		$context = new RequestContext;
		$context->setUser(
			$this->newUserWithSearchNS( [ 'search-match-redirect' => false ] )
		);
		$context->setRequest(
			new FauxRequest( [ 'search' => 'TEST_SEARCH_PARAM', 'fulltext' => 1 ] )
		);
		$search = $this->newSpecialPage();
		$search->setContext( $context );
		$search->load();

		$this->assertNull( $search->goResult( 'TEST_SEARCH_PARAM' ) );
	}

	/**
	 * If the 'search-match-redirect' user pref is true, then SpecialSearch::goResult() should
	 * NOT return null if there is a near match found for the search term
	 *
	 * @covers SpecialSearch::goResult
	 */
	public function testGoResult_userPrefRedirectOff() {
		// mock the search engine so it returns a near match for an arbitrary search term
		$searchResults = new SpecialSearchTestMockResultSet(
			'TEST_SEARCH_SUGGESTION',
			'',
			[ SearchResult::newFromTitle( Title::newMainPage() ) ]
		);
		$mockSearchEngine = $this->mockSearchEngine( $searchResults );
		$services = $this->getServiceContainer();
		$search = $this->getMockBuilder( SpecialSearch::class )
			->setConstructorArgs( [
				$services->getSearchEngineConfig(),
				$services->getSearchEngineFactory(),
				$services->getNamespaceInfo(),
				$services->getContentHandlerFactory(),
				$services->getInterwikiLookup(),
				$services->getReadOnlyMode(),
				$services->getUserOptionsManager(),
				$services->getLanguageConverterFactory()
			] )
			->onlyMethods( [ 'getSearchEngine' ] )
			->getMock();
		$search->method( 'getSearchEngine' )
			->willReturn( $mockSearchEngine );

		// set up a mock user with 'search-match-redirect' set to true
		$context = new RequestContext;
		$context->setUser(
			$this->newUserWithSearchNS( [ 'search-match-redirect' => true ] )
		);
		$context->setRequest(
			new FauxRequest( [ 'search' => 'TEST_SEARCH_PARAM', 'fulltext' => 1 ] )
		);
		$search->setContext( $context );
		$search->load();

		$this->assertNotNull( $search->goResult( 'TEST_SEARCH_PARAM' ) );
	}

	/**
	 * @covers SpecialSearch::showResults
	 * @throws MWException
	 */
	public function test_create_link_not_shown_if_variant_link_is_known() {
		$searchTerm = "Test create link not shown if variant link is known";
		$variantLink = "the replaced link variant text should not be visible";

		$variantTitle = $this->createNoOpMock( Title::class, [ 'isKnown', 'getPrefixedText',
			'getDBkey', 'isExternal' ] );

		$variantTitle->method( "isKnown" )->willReturn( true );
		$variantTitle->method( "isExternal" )->willReturn( false );
		$variantTitle->method( "getDBkey" )->willReturn( $searchTerm . " (variant)" );
		$variantTitle->method( "getPrefixedText" )->willReturn( $searchTerm . " (variant)" );

		$specialSearchFactory = function () use ( $variantTitle, $variantLink, $searchTerm ) {
			$languageConverter = $this->createMock( ILanguageConverter::class );
			$languageConverter->method( 'hasVariants' )->willReturn( true );
			$languageConverter->expects( $this->once() )
				->method( 'findVariantLink' )
				->willReturnCallback(
					static function ( &$link, &$nt, $unused = false ) use ( $searchTerm, $variantTitle, $variantLink ) {
						if ( $link === $searchTerm ) {
							$link = $variantLink;
							$nt = $variantTitle;
						}
					}
				);
			$languageConverterFactory = $this->createMock( LanguageConverterFactory::class );
			$languageConverterFactory->method( 'getLanguageConverter' )
				->willReturn( $languageConverter );

			$mockSearchEngineFactory = $this->createMock( SearchEngineFactory::class );
			$mockSearchEngineFactory->method( "create" )
				->willReturn( $this->mockSearchEngine( new SpecialSearchTestMockResultSet() ) );

			$services = $this->getServiceContainer();
			$specialSearch = new SpecialSearch(
				$services->getSearchEngineConfig(),
				$mockSearchEngineFactory,
				$services->getNamespaceInfo(),
				$services->getContentHandlerFactory(),
				$services->getInterwikiLookup(),
				$services->getReadOnlyMode(),
				$services->getUserOptionsManager(),
				$languageConverterFactory
			);
			$context = new RequestContext();
			$context->setRequest( new FauxRequest() );
			$context->setTitle( Title::makeTitle( NS_SPECIAL, 'Search' ) );
			$specialSearch->setContext( $context );
			$specialSearch->load();
			return $specialSearch;
		};
		$specialSearch = $specialSearchFactory();
		$specialSearch->showResults( $searchTerm );
		$html = $specialSearch->getContext()->getOutput()->getHTML();
		$this->assertStringNotContainsString( $variantLink, $html );
		$this->assertStringContainsString( 'class="mw-search-exists"', $html );
		$this->assertStringNotContainsString( 'class="mw-search-createlink"', $html );

		$specialSearch = $specialSearchFactory();
		$specialSearch->showResults( $searchTerm . "_search_create_link" );
		$html = $specialSearch->getContext()->getOutput()->getHTML();
		$this->assertStringContainsString( 'class="mw-search-createlink"', $html );
		$this->assertStringNotContainsString( 'class="mw-search-exists"', $html );
	}
}

class SpecialSearchTestMockResultSet extends SearchResultSet {
	protected $results;
	protected $suggestion;

	public function __construct(
		$suggestion = null,
		$rewrittenQuery = null,
		array $results = [],
		$containedSyntax = false
	) {
		$this->suggestion = $suggestion;
		$this->rewrittenQuery = $rewrittenQuery;
		$this->results = $results;
		$this->containedSyntax = $containedSyntax;
	}

	public function expandResults() {
		return $this->results;
	}

	public function getTotalHits() {
		return $this->numRows();
	}

	public function hasSuggestion() {
		return $this->suggestion !== null;
	}

	public function getSuggestionQuery() {
		return $this->suggestion;
	}

	public function getSuggestionSnippet() {
		return $this->suggestion;
	}

	public function hasRewrittenQuery() {
		return $this->rewrittenQuery !== null;
	}

	public function getQueryAfterRewrite() {
		return $this->rewrittenQuery;
	}

	public function getQueryAfterRewriteSnippet() {
		return htmlspecialchars( $this->rewrittenQuery );
	}

	public function getFirstResult() {
		if ( count( $this->results ) === 0 ) {
			return null;
		}
		return $this->results[0]->getTitle();
	}
}
