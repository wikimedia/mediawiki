<?php
/**
 * @group Search
 * @group Database
 */
class PrefixSearchTest extends MediaWikiLangTestCase {

	public function addDBData() {
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
	}

	protected function setUp() {
		parent::setUp();

		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestSkipped( 'Main namespace does not support wikitext.' );
		}

		// Avoid special pages from extensions interferring with the tests
		$this->setMwGlobals( 'wgSpecialPages', array() );
	}

	protected function searchProvision( Array $results = null ) {
		if ( $results === null ) {
			$this->setMwGlobals( 'wgHooks', array() );
		} else {
			$this->setMwGlobals( 'wgHooks', array(
				'PrefixSearchBackend' => array(
					function ( $namespaces, $search, $limit, &$srchres ) use ( $results ) {
						$srchres = $results;
						return false;
					}
				),
			) );
		}
	}

	public static function provideSearch() {
		return array(
			array( array(
				'Empty string',
				'query' => '',
				'results' => array(),
			) ),
			array( array(
				'Main namespace with title prefix',
				'query' => 'Ex',
				'results' => array(
					'Example',
					'Example/Baz',
					'Example Bar',
				),
				// Third result when testing offset
				'offsetresult' => array(
					'Example Foo',
				),
			) ),
			array( array(
				'Talk namespace prefix',
				'query' => 'Talk:',
				'results' => array(
					'Talk:Example',
					'Talk:Sandbox',
				),
			) ),
			array( array(
				'User namespace prefix',
				'query' => 'User:',
				'results' => array(
					'User:Example',
				),
			) ),
			array( array(
				'Special namespace prefix',
				'query' => 'Special:',
				'results' => array(
					'Special:ActiveUsers',
					'Special:AllMessages',
					'Special:AllMyFiles',
				),
				// Third result when testing offset
				'offsetresult' => array(
					'Special:AllMyUploads',
				),
			) ),
			array( array(
				'Special namespace with prefix',
				'query' => 'Special:Un',
				'results' => array(
					'Special:Unblock',
					'Special:UncategorizedCategories',
					'Special:UncategorizedFiles',
				),
				// Third result when testing offset
				'offsetresult' => array(
					'Special:UncategorizedImages',
				),
			) ),
			array( array(
				'Special page name',
				'query' => 'Special:EditWatchlist',
				'results' => array(
					'Special:EditWatchlist',
				),
			) ),
			array( array(
				'Special page subpages',
				'query' => 'Special:EditWatchlist/',
				'results' => array(
					'Special:EditWatchlist/clear',
					'Special:EditWatchlist/raw',
				),
			) ),
			array( array(
				'Special page subpages with prefix',
				'query' => 'Special:EditWatchlist/cl',
				'results' => array(
					'Special:EditWatchlist/clear',
				),
			) ),
		);
	}

	/**
	 * @dataProvider provideSearch
	 * @covers PrefixSearch::search
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearch( Array $case ) {
		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3 );
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
	public function testSearchWithOffset( Array $case ) {
		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3, array(), 1 );

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
		return array(
			array( array(
				'Simple case',
				'provision' => array(
					'Bar',
					'Barcelona',
					'Barbara',
				),
				'query' => 'Bar',
				'results' => array(
					'Bar',
					'Barcelona',
					'Barbara',
				),
			) ),
			array( array(
				'Exact match not on top (bug 70958)',
				'provision' => array(
					'Barcelona',
					'Bar',
					'Barbara',
				),
				'query' => 'Bar',
				'results' => array(
					'Bar',
					'Barcelona',
					'Barbara',
				),
			) ),
			array( array(
				'Exact match missing (bug 70958)',
				'provision' => array(
					'Barcelona',
					'Barbara',
					'Bart',
				),
				'query' => 'Bar',
				'results' => array(
					'Bar',
					'Barcelona',
					'Barbara',
				),
			) ),
			array( array(
				'Exact match missing and not existing',
				'provision' => array(
					'Exile',
					'Exist',
					'External',
				),
				'query' => 'Ex',
				'results' => array(
					'Exile',
					'Exist',
					'External',
				),
			) ),
			array( array(
				"Exact match shouldn't override already found match if " .
					"exact is redirect and found isn't",
				'provision' => array(
					// Target of the exact match is low in the list
					'Redirect Test Worse Result',
					'Redirect Test',
				),
				'query' => 'redirect test',
				'results' => array(
					// Redirect target is pulled up and exact match isn't added
					'Redirect Test',
					'Redirect Test Worse Result',
				),
			) ),
			array( array(
				"Exact match shouldn't override already found match if " .
					"both exact match and found match are redirect",
				'provision' => array(
					// Another redirect to the same target as the exact match
					// is low in the list
					'Redirect Test2 Worse Result',
					'Redirect test2',
				),
				'query' => 'redirect TEST2',
				'results' => array(
					// Found redirect is pulled to the top and exact match isn't
					// added
					'Redirect test2',
					'Redirect Test2 Worse Result',
				),
			) ),
			array( array(
				"Exact match should override any already found matches that " .
					"are redirects to it",
				'provision' => array(
					// Another redirect to the same target as the exact match
					// is low in the list
					'Redirect Test Worse Result',
					'Redirect test',
				),
				'query' => 'Redirect Test',
				'results' => array(
					// Found redirect is pulled to the top and exact match isn't
					// added
					'Redirect Test',
					'Redirect Test Worse Result',
				),
			) ),
		);
	}

	/**
	 * @dataProvider provideSearchBackend
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearchBackend( Array $case ) {
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
