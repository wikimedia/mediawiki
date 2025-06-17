<?php

use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;

/**
 * @group Search
 * @group Database
 *
 * @covers \SearchEngine<extended>
 * @note Coverage will only ever show one of on of the Search* classes
 */
class SearchEngineTest extends MediaWikiLangTestCase {

	protected SearchEngine $search;

	/**
	 * Checks for database type & version.
	 * Will skip current test if DB does not support search.
	 */
	protected function setUp(): void {
		parent::setUp();

		// Search tests require MySQL or SQLite with FTS
		$dbType = $this->getDb()->getType();
		$dbSupported = ( $dbType === 'mysql' )
			|| ( $dbType === 'sqlite' && $this->getDb()->getFulltextSearchModule() == 'FTS3' );

		if ( !$dbSupported ) {
			$this->markTestSkipped( "MySQL or SQLite with FTS3 only" );
		}
		$dbProvider = $this->getServiceContainer()->getConnectionProvider();

		$searchType = SearchEngineFactory::getSearchEngineClass( $dbProvider );
		$this->overrideConfigValues( [
			MainConfigNames::SearchType => $searchType,
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::CapitalLinkOverrides => [
				NS_CATEGORY => false // for testCompletionSearchMustRespectCapitalLinkOverrides
			],
		] );

		$this->search = new $searchType( $dbProvider );
		$this->search->setHookContainer( $this->getServiceContainer()->getHookContainer() );
	}

	protected function tearDown(): void {
		unset( $this->search );

		parent::tearDown();
	}

	public function addDBDataOnce() {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			// @todo cover the case of non-wikitext content in the main namespace
			return;
		}

		// Reset the search type back to default - some extensions may have
		// overridden it.
		$this->overrideConfigValues( [
			MainConfigNames::SearchType => null,
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::CapitalLinkOverrides => [
				NS_CATEGORY => false // for testCompletionSearchMustRespectCapitalLinkOverrides
			],
		] );

		$this->insertPage( 'Not_Main_Page', 'This is not a main page' );
		$this->insertPage(
			'Talk:Not_Main_Page',
			'This is not a talk page to the main page, see [[smithee]]'
		);
		$this->insertPage( 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]' );
		$this->insertPage( 'Talk:Smithee', 'This article sucks.' );
		$this->insertPage( 'Unrelated_page', 'Nothing in this page is about the S word.' );
		$this->insertPage( 'Another_page', 'This page also is unrelated.' );
		$this->insertPage( 'Help:Help', 'Help me!' );
		$this->insertPage( 'Thppt', 'Blah blah' );
		$this->insertPage( 'Alan_Smithee', 'yum' );
		$this->insertPage( 'Pages', 'are\'food' );
		$this->insertPage( 'HalfOneUp', 'AZ' );
		$this->insertPage( 'FullOneUp', 'ＡＺ' );
		$this->insertPage( 'HalfTwoLow', 'az' );
		$this->insertPage( 'FullTwoLow', 'ａｚ' );
		$this->insertPage( 'HalfNumbers', '1234567890' );
		$this->insertPage( 'FullNumbers', '１２３４５６７８９０' );
		$this->insertPage( 'DomainName', 'example.com' );
		$this->insertPage( 'DomainName', 'example.com' );
		$this->insertPage( 'Category:search is not Search', '' );
		$this->insertPage( 'Category:Search is not search', '' );
		$this->insertPage( 'Talk:1', 'Did you know titles can be numbers?' );
	}

	protected function fetchIds( $results ) {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestIncomplete( __CLASS__ . " does no yet support non-wikitext content "
				. "in the main namespace" );
		}
		$this->assertIsObject( $results );

		$matches = [];
		foreach ( $results as $row ) {
			$matches[] = $row->getTitle()->getPrefixedText();
		}
		$results->free();
		# Search is not guaranteed to return results in a certain order;
		# sort them numerically so we will compare simply that we received
		# the expected matches.
		sort( $matches );

		return $matches;
	}

	public function testFullWidth() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'AZ' ) ),
			"Search for normalized from Half-width Upper" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'az' ) ),
			"Search for normalized from Half-width Lower" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'ＡＺ' ) ),
			"Search for normalized from Full-width Upper" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'ａｚ' ) ),
			"Search for normalized from Full-width Lower" );
	}

	public function testTextSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Plain search" );
	}

	public function testWildcardSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$res = $this->search->searchText( 'smith*' );
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $res ),
			"Search with wildcards" );

		$res = $this->search->searchText( 'smithson*' );
		$this->assertEquals(
			[],
			$this->fetchIds( $res ),
			"Search with wildcards must not find unrelated articles" );

		$res = $this->search->searchText( 'smith* smithee' );
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $res ),
			"Search with wildcards can be combined with simple terms" );

		$res = $this->search->searchText( 'smith* "one who smiths"' );
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $res ),
			"Search with wildcards can be combined with phrase search" );
	}

	public function testPhraseSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$res = $this->search->searchText( '"smithee is one who smiths"' );
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $res ),
			"Search a phrase" );

		$res = $this->search->searchText( '"smithee is who smiths"' );
		$this->assertEquals(
			[],
			$this->fetchIds( $res ),
			"Phrase search is not sloppy, search terms must be adjacent" );

		$res = $this->search->searchText( '"is smithee one who smiths"' );
		$this->assertEquals(
			[],
			$this->fetchIds( $res ),
			"Phrase search is ordered" );
	}

	public function testPhraseSearchHighlight() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$phrase = "smithee is one who smiths";
		$res = $this->search->searchText( "\"$phrase\"" );
		$match = $res->getIterator()->current();
		$snippet = 'A <span class="searchmatch">' . $phrase . '</span>';
		$this->assertStringStartsWith( $snippet,
			$match->getTextSnippet(),
			"Highlight a phrase search" );
	}

	public function testTextPowerSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->search->setNamespaces( [ 0, 1, 4 ] );
		$this->assertEquals(
			[
				'Smithee',
				'Talk:Not Main Page',
			],
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Power search" );
	}

	public function testTitleSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->assertEquals(
			[
				'Alan Smithee',
				'Smithee',
			],
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title search" );
	}

	public function testTextTitlePowerSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );
		$this->search->setNamespaces( [ 0, 1, 4 ] );
		$this->assertEquals(
			[
				'Alan Smithee',
				'Smithee',
				'Talk:Smithee',
			],
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title power search" );
	}

	public static function provideCompletionSearchMustRespectCapitalLinkOverrides() {
		return [
			'Searching for "smithee" finds Smithee on NS_MAIN' => [
				'smithee',
				'Smithee',
				[ NS_MAIN ],
			],
			'Searching for "search is" will finds "search is not Search" on NS_CATEGORY' => [
				'search is',
				'Category:search is not Search',
				[ NS_CATEGORY ],
			],
			'Searching for "Search is" will finds "search is not Search" on NS_CATEGORY' => [
				'Search is',
				'Category:Search is not search',
				[ NS_CATEGORY ],
			],
			'Copy-pasted wikilinks with invalid characters will still find the page' => [
				'[[smithee]]',
				'Smithee',
				[ NS_MAIN ],
			],
			'Numeric title works (T365565)' => [
				'1',
				'Talk:1',
				[ NS_TALK ],
			]
		];
	}

	/**
	 * Test that the search query is not munged using wrong CapitalLinks setup
	 * (in other test that the default search backend can benefit from wgCapitalLinksOverride)
	 * Guard against regressions like T208255
	 * @dataProvider provideCompletionSearchMustRespectCapitalLinkOverrides
	 * @covers \SearchEngine::completionSearch
	 * @covers \PrefixSearch::defaultSearchBackend
	 * @param string $search
	 * @param string $expectedSuggestion
	 * @param int[] $namespaces
	 */
	public function testCompletionSearchMustRespectCapitalLinkOverrides(
		$search,
		$expectedSuggestion,
		array $namespaces
	) {
		$this->search->setNamespaces( $namespaces );
		$results = $this->search->completionSearch( $search );
		$this->assertSame( 1, $results->getSize() );
		$this->assertEquals( $expectedSuggestion, $results->getSuggestions()[0]->getText() );
	}

	/**
	 * @covers \SearchEngine::getSearchIndexFields
	 */
	public function testSearchIndexFields() {
		/**
		 * @var SearchEngine $mockEngine
		 */
		$mockEngine = $this->getMockBuilder( SearchEngine::class )
			->onlyMethods( [ 'makeSearchFieldMapping' ] )->getMock();

		$mockFieldBuilder = function ( $name, $type ) {
			$mockField =
				$this->getMockBuilder( SearchIndexFieldDefinition::class )->setConstructorArgs( [
					$name,
					$type,
				] )->getMock();

			$mockField->method( 'getMapping' )->willReturn( [
				'testData' => 'test',
				'name' => $name,
				'type' => $type,
			] );

			$mockField->method( 'merge' )->willReturnSelf();

			return $mockField;
		};

		$mockEngine->expects( $this->atLeastOnce() )
			->method( 'makeSearchFieldMapping' )
			->willReturnCallback( $mockFieldBuilder );

		// Not using mock since PHPUnit mocks do not work properly with references in params
		$this->setTemporaryHook( 'SearchIndexFields',
			static function ( &$fields, SearchEngine $engine ) use ( $mockFieldBuilder ) {
				$fields['testField'] =
					$mockFieldBuilder( "testField", SearchIndexField::INDEX_TYPE_TEXT );
				return true;
			} );
		$mockEngine->setHookContainer( $this->getServiceContainer()->getHookContainer() );

		$fields = $mockEngine->getSearchIndexFields();
		$this->assertArrayHasKey( 'language', $fields );
		$this->assertArrayHasKey( 'category', $fields );
		$this->assertInstanceOf( SearchIndexField::class, $fields['testField'] );

		$mapping = $fields['testField']->getMapping( $mockEngine );
		$this->assertArrayHasKey( 'testData', $mapping );
		$this->assertEquals( 'test', $mapping['testData'] );
	}

	public function hookSearchIndexFields( $mockFieldBuilder, &$fields, SearchEngine $engine ) {
		$fields['testField'] = $mockFieldBuilder( "testField", SearchIndexField::INDEX_TYPE_TEXT );
		return true;
	}

	public function testAugmentorSearch() {
		// T303046
		$this->markTestSkippedIfDbType( 'sqlite' );

		$this->search->setHookContainer(
			$this->createHookContainer( [ 'SearchResultsAugment' => $this->addAugmentors( ... ) ] )
		);

		$this->search->setNamespaces( [ 0, 1, 4 ] );
		$resultSet = $this->search->searchText( 'smithee' );
		$this->search->augmentSearchResults( $resultSet );
		foreach ( $resultSet as $result ) {
			$id = $result->getTitle()->getArticleID();
			$augmentData = "Result:$id:" . $result->getTitle()->getText();
			$augmentData2 = "Result2:$id:" . $result->getTitle()->getText();
			$this->assertEquals( [ 'testSet' => $augmentData, 'testRow' => $augmentData2 ],
				$result->getExtensionData() );
		}
	}

	public function addAugmentors( &$setAugmentors, &$rowAugmentors ) {
		$setAugmentor = $this->createMock( ResultSetAugmentor::class );
		$setAugmentor->expects( $this->once() )
			->method( 'augmentAll' )
			->willReturnCallback( static function ( ISearchResultSet $resultSet ) {
				$data = [];
				/** @var SearchResult $result */
				foreach ( $resultSet as $result ) {
					$id = $result->getTitle()->getArticleID();
					$data[$id] = "Result:$id:" . $result->getTitle()->getText();
				}
				return $data;
			} );
		$setAugmentors['testSet'] = $setAugmentor;

		$rowAugmentor = $this->createMock( ResultAugmentor::class );
		$rowAugmentor->expects( $this->exactly( 2 ) )
			->method( 'augment' )
			->willReturnCallback( static function ( SearchResult $result ) {
				$id = $result->getTitle()->getArticleID();
				return "Result2:$id:" . $result->getTitle()->getText();
			} );
		$rowAugmentors['testRow'] = $rowAugmentor;
	}

	public function testFiltersMissing() {
		$availableResults = [];
		$user = $this->getTestSysop()->getAuthority();
		foreach ( range( 0, 11 ) as $i ) {
			$title = "Search_Result_$i";
			$availableResults[] = $title;
			// pages not created must be filtered
			if ( $i % 2 == 0 ) {
				$this->editPage(
					$title,
					new WikitextContent( 'TestFiltersMissing content' ),
					'TestFiltersMissing summary',
					NS_MAIN,
					$user
				);
			}
		}
		MockCompletionSearchEngine::addMockResults( 'foo', $availableResults );

		$engine = new MockCompletionSearchEngine();
		$engine->setLimitOffset( 10, 0 );
		$engine->setHookContainer( $this->getServiceContainer()->getHookContainer() );
		$results = $engine->completionSearch( 'foo' );
		$this->assertEquals( 5, $results->getSize() );
		$this->assertTrue( $results->hasMoreResults() );

		$engine->setLimitOffset( 10, 10 );
		$results = $engine->completionSearch( 'foo' );
		$this->assertSame( 1, $results->getSize() );
		$this->assertFalse( $results->hasMoreResults() );
	}

	public static function provideDataForParseNamespacePrefix() {
		return [
			'noop' => [
				[
					'query' => 'foo',
				],
				false,
			],
			'empty' => [
				[
					'query' => '',
				],
				false,
			],
			'namespace prefix' => [
				[
					'query' => 'help:test',
				],
				[ 'test', [ NS_HELP ] ],
			],
			'accented namespace prefix with hook' => [
				[
					'query' => 'hélp:test',
					'withHook' => true,
				],
				[ 'test', [ NS_HELP ] ],
			],
			'accented namespace prefix without hook' => [
				[
					'query' => 'hélp:test',
					'withHook' => false,
				],
				false,
			],
			'all with all keyword allowed' => [
				[
					'query' => 'all:test',
					'withAll' => true,
				],
				[ 'test', null ],
			],
			'all with all keyword disallowed' => [
				[
					'query' => 'all:test',
					'withAll' => false,
				],
				false,
			],
			'ns only' => [
				[
					'query' => 'help:',
				],
				[ '', [ NS_HELP ] ],
			],
			'all only' => [
				[
					'query' => 'all:',
					'withAll' => true,
				],
				[ '', null ],
			],
			'all wins over namespace when first' => [
				[
					'query' => 'all:help:test',
					'withAll' => true,
				],
				[ 'help:test', null ],
			],
			'ns wins over all when first' => [
				[
					'query' => 'help:all:test',
					'withAll' => true,
				],
				[ 'all:test', [ NS_HELP ] ],
			],
		];
	}

	/**
	 * @dataProvider provideDataForParseNamespacePrefix
	 */
	public function testParseNamespacePrefix( array $params, $expected ) {
		$this->setTemporaryHook( 'PrefixSearchExtractNamespace', static function ( &$namespaces, &$query ) {
			if ( str_starts_with( $query, 'hélp:' ) ) {
				$namespaces = [ NS_HELP ];
				$query = substr( $query, strlen( 'hélp:' ) );
			}
			return false;
		} );
		$testSet = [];
		if ( isset( $params['withAll'] ) && isset( $params['withHook'] ) ) {
			$testSet[] = $params;
		} elseif ( isset( $params['withAll'] ) ) {
			$testSet[] = $params + [ 'withHook' => true ];
			$testSet[] = $params + [ 'withHook' => false ];
		} elseif ( isset( $params['withHook'] ) ) {
			$testSet[] = $params + [ 'withAll' => true ];
			$testSet[] = $params + [ 'withAll' => false ];
		} else {
			$testSet[] = $params + [ 'withAll' => true, 'withHook' => true ];
			$testSet[] = $params + [ 'withAll' => true, 'withHook' => false ];
			$testSet[] = $params + [ 'withAll' => false, 'withHook' => false ];
			$testSet[] = $params + [ 'withAll' => true, 'withHook' => false ];
		}

		foreach ( $testSet as $test ) {
			$actual = SearchEngine::parseNamespacePrefixes( $test['query'],
				$test['withAll'], $test['withHook'] );
			$this->assertEquals( $expected, $actual, 'with params: ' . print_r( $test, true ) );
		}
	}

	/**
	 * Regression test for T386743.
	 */
	public function testCompletionSearchWithVariants__limitWithVariants() {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		// Note, the following assumes that there are at least 10 special pages whose name starts with "Li".
		// In MW core alone there were ~20 as of February 2025 when this test was written.
		$res = $this->search->completionSearchWithVariants( 'Special:Li' );
		$this->assertSame( 10, $res->getSize() );
	}
}
