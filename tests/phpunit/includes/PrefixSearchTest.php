<?php
/**
 * @group Search
 * @group Database
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

		$this->insertPage( 'Example' );
		$this->insertPage( 'Example Bar' );
		$this->insertPage( 'Example Foo' );
		$this->insertPage( 'Example Foo/Bar' );
		$this->insertPage( 'Example/Baz' );

		$this->insertPage( 'Talk:Sandbox' );
		$this->insertPage( 'Talk:Example' );

		$this->insertPage( 'User:Example' );
	}

	public static function provideSearch() {
		return array(
			array( array(
				'Empty string',
				'provision' => null,
				'query' => '',
				'results' => array(),
			) ),
			array( array(
				'Main namespace with title prefix',
				'provision' => null,
				'query' => 'Ex',
				'results' => array(
					'Example',
					'Example/Baz',
					'Example Bar',
				),
			) ),
			array( array(
				'Talk namespace prefix',
				'provision' => null,
				'query' => 'Talk:',
				'results' => array(
					'Talk:Example',
					'Talk:Sandbox',
				),
			) ),
			array( array(
				'User namespace prefix',
				'provision' => null,
				'query' => 'User:',
				'results' => array(
					'User:Example',
				),
			) ),
			array( array(
				'Special namespace prefix',
				'provision' => null,
				'query' => 'Special:',
				'results' => array(
					'Special:ActiveUsers',
					'Special:AllMessages',
					'Special:AllMyFiles',
				),
			) ),
			array( array(
				'Special namespace with prefix',
				'provision' => null,
				'query' => 'Special:Un',
				'results' => array(
					'Special:Unblock',
					'Special:UncategorizedCategories',
					'Special:UncategorizedFiles',
				),
			) ),
			array( array(
				'Special page name',
				'provision' => null,
				'query' => 'Special:EditWatchlist',
				'results' => array(
					'Special:EditWatchlist',
				),
			) ),
			array( array(
				'Special page subpages',
				'provision' => null,
				'query' => 'Special:EditWatchlist/',
				'results' => array(
					'Special:EditWatchlist/clear',
					'Special:EditWatchlist/raw',
				),
			) ),
			array( array(
				'Special page subpages with prefix',
				'provision' => null,
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
