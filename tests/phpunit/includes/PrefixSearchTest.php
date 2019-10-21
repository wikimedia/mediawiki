<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Search
 * @group Database
 * @covers PrefixSearch
 */
class PrefixSearchTest extends MediaWikiLangTestCase {
	const NS_NONCAP = 12346;

	private $originalHandlers;

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

		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'Bar' ) );
		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'Upper' ) );
		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'sandbox' ) );
	}

	protected function setUp() {
		parent::setUp();

		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestSkipped( 'Main namespace does not support wikitext.' );
		}

		// Avoid special pages from extensions interfering with the tests
		$this->setMwGlobals( [
			'wgSpecialPages' => [],
			'wgHooks' => [],
			'wgExtraNamespaces' => [ self::NS_NONCAP => 'NonCap' ],
			'wgCapitalLinkOverrides' => [ self::NS_NONCAP => false ],
		] );

		$this->originalHandlers = TestingAccessWrapper::newFromClass( Hooks::class )->handlers;
		TestingAccessWrapper::newFromClass( Hooks::class )->handlers = [];
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
				'query' => 'Ex',
				'results' => [
					'Example',
					'Example/Baz',
					'Example Bar',
				],
				// Third result when testing offset
				'offsetresult' => [
					'Example Foo',
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
			[ [
				'Namespace with case sensitive first letter',
				'query' => 'NonCap:upper',
				'results' => []
			] ],
			[ [
				'Multinamespace search',
				'query' => 'B',
				'results' => [
					'Bar',
					'NonCap:Bar',
				],
				'namespaces' => [ NS_MAIN, self::NS_NONCAP ],
			] ],
			[ [
				'Multinamespace search with lowercase first letter',
				'query' => 'sand',
				'results' => [
					'Sandbox',
					'NonCap:sandbox',
				],
				'namespaces' => [ NS_MAIN, self::NS_NONCAP ],
			] ],
		];
	}

	/**
	 * @dataProvider provideSearch
	 * @covers PrefixSearch::search
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearch( array $case ) {
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
		$this->searchProvision( null );

		$namespaces = $case['namespaces'] ?? [];

		if ( wfGetDB( DB_REPLICA )->getType() === 'postgres' ) {
			// Postgres will sort lexicographically on utf8 code units (" " before "/")
			sort( $case['results'], SORT_STRING );
		}

		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3, $namespaces );
		$this->assertEquals(
			$case['results'],
			$results,
			$case[0]
		);
	}

	/**
	 * @dataProvider provideSearch
	 * @covers PrefixSearch::search
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearchWithOffset( array $case ) {
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
		$this->searchProvision( null );

		$namespaces = $case['namespaces'] ?? [];

		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3, $namespaces, 1 );

		if ( wfGetDB( DB_REPLICA )->getType() === 'postgres' ) {
			// Postgres will sort lexicographically on utf8 code units (" " before "/")
			sort( $case['results'], SORT_STRING );
		}

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
				'Exact match not on top (T72958)',
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
				'Exact match missing (T72958)',
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
				'Exact match missing and not existing',
				'provision' => [
					'Exile',
					'Exist',
					'External',
				],
				'query' => 'Ex',
				'results' => [
					'Exile',
					'Exist',
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
				],
			] ],
		];
	}

	/**
	 * @dataProvider provideSearchBackend
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearchBackend( array $case ) {
		$this->searchProvision( $case['provision'] );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3 );
		$this->assertEquals(
			$case['results'],
			$results,
			$case[0]
		);
	}
}
