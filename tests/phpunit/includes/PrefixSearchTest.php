<?php
/**
 * @group Search
 * @group Database
 * @covers PrefixSearch
 */
class PrefixSearchTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

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

	public function addDBData() {
		$this->insertPage( 'Sandbox' );
		$this->insertPage( 'Bar' );
		$this->insertPage( 'Example' );
		$this->insertPage( 'Example Bar' );
		$this->insertPage( 'Example Foo' );
		$this->insertPage( 'Example Foo/Bar' );
		$this->insertPage( 'Example/Baz' );

		$this->insertPage( 'Talk:Sandbox' );
		$this->insertPage( 'Talk:Example' );

		$this->insertPage( 'User:Example' );
	}

	/**
	 * @covers PrefixSearch::search
	 * @covers PrefixSearch::searchBackend
	 */
	public function testSearch() {
		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( '', 3 );
		$this->assertEquals(
			array(),
			$results,
			'Empty string'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Ex', 3 );
		$this->assertEquals(
			array(
				'Example',
				'Example/Baz',
				'Example Bar',
			),
			$results,
			'Main namespace with title prefix'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Talk:', 3 );
		$this->assertEquals(
			array(
				'Talk:Example',
				'Talk:Sandbox',
			),
			$results,
			'Talk namespace prefix'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'User:', 3 );
		$this->assertEquals(
			array(
				'User:Example',
			),
			$results,
			'User namespace prefix'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Special:', 3 );
		$this->assertEquals(
			array(
				'Special:ActiveUsers',
				'Special:AllMessages',
				'Special:AllMyFiles',
			),
			$results,
			'Special namespace prefix'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Special:Un', 3 );
		$this->assertEquals(
			array(
				'Special:Unblock',
				'Special:UncategorizedCategories',
				'Special:UncategorizedFiles',
			),
			$results,
			'Special namespace with prefix'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Special:EditWatchlist', 3 );
		$this->assertEquals(
			array(
				'Special:EditWatchlist',
			),
			$results,
			'Special page name'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Special:EditWatchlist/', 3 );
		$this->assertEquals(
			array(
				'Special:EditWatchlist/clear',
				'Special:EditWatchlist/raw',
			),
			$results,
			'Special page subpages'
		);

		$this->searchProvision( null );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Special:EditWatchlist/cl', 3 );
		$this->assertEquals(
			array(
				'Special:EditWatchlist/clear',
			),
			$results,
			'Special page subpages with prefix'
		);
	}

	/**
	 * @covers PrefixSearch::searchBackend
	 */
	public function testBug70958() {
		$this->searchProvision( array(
			'Bar',
			'Barcelona',
			'Barbara',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Bar', 3 );
		$this->assertEquals(
			array(
				'Bar',
				'Barcelona',
				'Barbara',
			),
			$results,
			'Simple case'
		);

		$this->searchProvision( array(
			'Barcelona',
			'Bar',
			'Barbara',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Bar', 3 );
		$this->assertEquals(
			array(
				'Bar',
				'Barcelona',
				'Barbara',
			),
			$results,
			'Exact match not on top'
		);

		$this->searchProvision( array(
			'Barcelona',
			'Barbara',
			'Bart',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Bar', 3 );
		$this->assertEquals(
			array(
				'Bar',
				'Barcelona',
				'Barbara',
			),
			$results,
			'Exact match missing'
		);

		$this->searchProvision( array(
			'Exile',
			'Exist',
			'External',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Ex', 3 );
		$this->assertEquals(
			array(
				'Exile',
				'Exist',
				'External',
			),
			$results,
			'Exact match missing and not existing'
		);
	}
}
