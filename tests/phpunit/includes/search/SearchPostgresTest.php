<?php

/**
 * @group Search
 * @group Database
 */
class SearchPostgresTest extends MediaWikiTestCase {
	protected $search;

	function tearDown() {
		unset( $this->search );

		parent::tearDown();
	}

	/**
	 * Will skip current test if not PostgreSQL.
	 */
	function setUp() {
		parent::setUp();
		# Get database type and version
		$dbType = $this->db->getType();

		if ( $dbType != 'postgres' ) {
			$this->markTestSkipped( "PostgreSQL only" );
		}

		$searchType = $this->db->getSearchEngine();
		$this->search = new $searchType( $this->db );
	}

	/**
	 * @dataProvider queryTests
	 */
	function testParseAndSearchQuery( $term, $expectedQuery, $expectedSearchTerms ) {
		$actualQuery = $this->search->parseQuery( $term );
		$this->search->searchQuery( $term, 'titlevector', 'page_title' );
		$actualSearchTerms = $this->search->searchTerms;

		$this->assertEquals(
			$expectedQuery,
			$actualQuery,
			"parseQuery() failed" );

		$this->assertEquals(
			$expectedSearchTerms,
			$actualSearchTerms,
			"searchQuery() failed" );
	}

	function queryTests() {
		return [ [ "abc", "'abc'", [ "abc" ] ],
				[ "b and c", "'b & c'", [ "b", "c" ] ],
				[ "b or c", "'b | c'", [ "b", "c" ] ],
				[ "not a", "'! a'", [ "a" ] ],
				/* Bug T37043. */
				[ "'", "''''''''''''", [] ],
				];
	}
}
