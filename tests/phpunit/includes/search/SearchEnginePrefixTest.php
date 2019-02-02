<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Search
 * @group Database
 */
class SearchEnginePrefixTest extends MediaWikiLangTestCase {
	private $originalHandlers;

	/**
	 * @var SearchEngine
	 */
	private $search;

	public function addDBDataOnce() {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			// tests are skipped if NS_MAIN is not wikitext
			return;
		}

		$this->insertPage( 'Sandbox' );
		$this->insertPage( 'Bar' );
		$this->insertPage( 'Example' );
		$this->insertPage( 'Example Bar' );
		$this->insertPage( 'Example Foo' );
		$this->insertPage( 'Example Foo/Bar' );
		$this->insertPage( 'Example/Baz' );
		$this->insertPage( 'Sample' );
		$this->insertPage( 'Sample Ban' );
		$this->insertPage( 'Sample Eat' );
		$this->insertPage( 'Sample Who' );
		$this->insertPage( 'Sample Zoo' );
		$this->insertPage( 'Redirect test', '#REDIRECT [[Redirect Test]]' );
		$this->insertPage( 'Redirect Test' );
		$this->insertPage( 'Redirect Test Worse Result' );
		$this->insertPage( 'Redirect test2', '#REDIRECT [[Redirect Test2]]' );
		$this->insertPage( 'Redirect TEST2', '#REDIRECT [[Redirect Test2]]' );
		$this->insertPage( 'Redirect Test2' );
		$this->insertPage( 'Redirect Test2 Worse Result' );

		$this->insertPage( 'Talk:Sandbox' );
		$this->insertPage( 'Talk:Example' );

		$this->insertPage( 'User:Example' );
		$this->insertPage( 'Barcelona' );
		$this->insertPage( 'Barbara' );
		$this->insertPage( 'External' );
	}

	protected function setUp() {
		parent::setUp();

		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestSkipped( 'Main namespace does not support wikitext.' );
		}

		// Avoid special pages from extensions interferring with the tests
		$this->setMwGlobals( [
			'wgSpecialPages' => [],
			'wgHooks' => [],
		] );

		$this->search = MediaWikiServices::getInstance()->newSearchEngine();
		$this->search->setNamespaces( [] );

		$this->originalHandlers = TestingAccessWrapper::newFromClass( Hooks::class )->handlers;
		TestingAccessWrapper::newFromClass( Hooks::class )->handlers = [];

		$this->overrideMwServices();
	}

	public function tearDown() {
		parent::tearDown();

		TestingAccessWrapper::newFromClass( Hooks::class )->handlers = $this->originalHandlers;
	}

	protected function searchProvision( array $results = null ) {
		if ( $results === null ) {
			$this->setMwGlobals( 'wgHooks', [] );
		} else {
			$this->setMwGlobals( 'wgHooks', [
				'PrefixSearchBackend' => [
					function ( $namespaces, $search, $limit, &$srchres ) use ( $results ) {
						$srchres = $results;
						return false;
					}
				],
			] );
		}
	}

	public static function provideSearch() {
		return [
			[ [
				'Empty string',
				'query' => '',
				'results' => [],
			] ],
			[ [
				'Main namespace with title prefix',
				'query' => 'Sa',
				'results' => [
					'Sample',
					'Sample Ban',
					'Sample Eat',
				],
				// Third result when testing offset
				'offsetresult' => [
					'Sample Who',
				],
			] ],
			[ [
				'Talk namespace prefix',
				'query' => 'Talk:',
				'results' => [
					'Talk:Example',
					'Talk:Sandbox',
				],
			] ],
			[ [
				'User namespace prefix',
				'query' => 'User:',
				'results' => [
					'User:Example',
				],
			] ],
			[ [
				'Special namespace prefix',
				'query' => 'Special:',
				'results' => [
					'Special:ActiveUsers',
					'Special:AllMessages',
					'Special:AllMyUploads',
				],
				// Third result when testing offset
				'offsetresult' => [
					'Special:AllPages',
				],
			] ],
			[ [
				'Special namespace with prefix',
				'query' => 'Special:Un',
				'results' => [
					'Special:Unblock',
					'Special:UncategorizedCategories',
					'Special:UncategorizedFiles',
				],
				// Third result when testing offset
				'offsetresult' => [
					'Special:UncategorizedPages',
				],
			] ],
			[ [
				'Special page name',
				'query' => 'Special:EditWatchlist',
				'results' => [
					'Special:EditWatchlist',
				],
			] ],
			[ [
				'Special page subpages',
				'query' => 'Special:EditWatchlist/',
				'results' => [
					'Special:EditWatchlist/clear',
					'Special:EditWatchlist/raw',
				],
			] ],
			[ [
				'Special page subpages with prefix',
				'query' => 'Special:EditWatchlist/cl',
				'results' => [
					'Special:EditWatchlist/clear',
				],
			] ],
		];
	}

	/**
	 * @dataProvider provideSearch
	 * @covers SearchEngine::defaultPrefixSearch
	 */
	public function testSearch( array $case ) {
		$this->search->setLimitOffset( 3 );
		$results = $this->search->defaultPrefixSearch( $case['query'] );
		$results = array_map( function ( Title $t ) {
			return $t->getPrefixedText();
		}, $results );

		$this->assertEquals(
			$case['results'],
			$results,
			$case[0]
		);
	}

	/**
	 * @dataProvider provideSearch
	 * @covers SearchEngine::defaultPrefixSearch
	 */
	public function testSearchWithOffset( array $case ) {
		$this->search->setLimitOffset( 3, 1 );
		$results = $this->search->defaultPrefixSearch( $case['query'] );
		$results = array_map( function ( Title $t ) {
			return $t->getPrefixedText();
		}, $results );

		// We don't expect the first result when offsetting
		array_shift( $case['results'] );
		// And sometimes we expect a different last result
		$expected = isset( $case['offsetresult'] ) ?
			array_merge( $case['results'], $case['offsetresult'] ) :
			$case['results'];

		$this->assertEquals(
			$expected,
			$results,
			$case[0]
		);
	}

	public static function provideSearchBackend() {
		return [
			[ [
				'Simple case',
				'provision' => [
					'Bar',
					'Barcelona',
					'Barbara',
				],
				'query' => 'Bar',
				'results' => [
					'Bar',
					'Barcelona',
					'Barbara',
				],
			] ],
			[ [
				'Exact match not in first result should be moved to the first result (T72958)',
				'provision' => [
					'Barcelona',
					'Bar',
					'Barbara',
				],
				'query' => 'Bar',
				'results' => [
					'Bar',
					'Barcelona',
					'Barbara',
				],
			] ],
			[ [
				'Exact match missing from results should be added as first result (T72958)',
				'provision' => [
					'Barcelona',
					'Barbara',
					'Bart',
				],
				'query' => 'Bar',
				'results' => [
					'Bar',
					'Barcelona',
					'Barbara',
				],
			] ],
			[ [
				'Exact match missing and not existing pages should be dropped',
				'provision' => [
					'Exile',
					'Exist',
					'External',
				],
				'query' => 'Ex',
				'results' => [
					'External',
				],
			] ],
			[ [
				"Exact match shouldn't override already found match if " .
					"exact is redirect and found isn't",
				'provision' => [
					// Target of the exact match is low in the list
					'Redirect Test Worse Result',
					'Redirect Test',
				],
				'query' => 'redirect test',
				'results' => [
					// Redirect target is pulled up and exact match isn't added
					'Redirect Test',
					'Redirect Test Worse Result',
				],
			] ],
			[ [
				"Exact match shouldn't override already found match if " .
					"both exact match and found match are redirect",
				'provision' => [
					// Another redirect to the same target as the exact match
					// is low in the list
					'Redirect Test2 Worse Result',
					'Redirect test2',
				],
				'query' => 'redirect TEST2',
				'results' => [
					// Found redirect is pulled to the top and exact match isn't
					// added
					'Redirect test2',
					'Redirect Test2 Worse Result',
				],
			] ],
			[ [
				"Exact match should override any already found matches that " .
					"are redirects to it",
				'provision' => [
					// Another redirect to the same target as the exact match
					// is low in the list
					'Redirect Test Worse Result',
					'Redirect test',
				],
				'query' => 'Redirect Test',
				'results' => [
					// Found redirect is pulled to the top and exact match isn't
					// added
					'Redirect Test',
					'Redirect Test Worse Result',
					'Redirect test',
				],
			] ],
			[ [
				"Extra results must not be returned",
				'provision' => [
					'Example',
					'Example Bar',
					'Example Foo',
					'Example Foo/Bar'
				],
				'query' => 'foo',
				'results' => [
					'Example',
					'Example Bar',
					'Example Foo',
				],
			] ],
		];
	}

	/**
	 * @dataProvider provideSearchBackend
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearchBackend( array $case ) {
		$search = $this->mockSearchWithResults( $case['provision'] );
		$results = $search->completionSearch( $case['query'] );

		$results = $results->map( function ( SearchSuggestion $s ) {
			return $s->getText();
		} );

		$this->assertEquals(
			$case['results'],
			$results,
			$case[0]
		);
	}

	public function paginationProvider() {
		$res = [ 'Example', 'Example Bar', 'Example Foo', 'Example Foo/Bar' ];
		return [
			'With less than requested results no pagination' => [
				false, array_slice( $res, 0, 2 ),
			],
			'With same as requested results no pagination' => [
				false, array_slice( $res, 0, 3 ),
			],
			'With extra result returned offer pagination' => [
				true, $res,
			],
		];
	}

	/**
	 * @dataProvider paginationProvider
	 * @covers SearchSuggestionSet::hasMoreResults
	 */
	public function testPagination( $hasMoreResults, $provision ) {
		$search = $this->mockSearchWithResults( $provision );
		$results = $search->completionSearch( 'irrelevant' );

		$this->assertEquals( $hasMoreResults, $results->hasMoreResults() );
	}

	private function mockSearchWithResults( $titleStrings, $limit = 3 ) {
		$search = $stub = $this->getMockBuilder( SearchEngine::class )
			->setMethods( [ 'completionSearchBackend' ] )->getMock();

		$return = SearchSuggestionSet::fromStrings( $titleStrings );

		$search->expects( $this->any() )
			->method( 'completionSearchBackend' )
			->will( $this->returnValue( $return ) );

		$search->setLimitOffset( $limit );
		return $search;
	}
}
