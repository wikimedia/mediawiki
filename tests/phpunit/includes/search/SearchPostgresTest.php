<?php

/**
 * @group Search
 * @group Database
 */
class SearchPostgresTest extends MediaWikiTestCase {
	protected $search;

	function tearDown() {
		unset( $this->search );
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

	function testParseQuery() {
		/* Actual results as of 7226f51ece09fd613b79d97a549efc68266b6226. */
		$parseQueryTests = array( "abc" => "'abc'",
								  "b and c" => "'b & c'",
								  "b or c" => "'b | c'",
								  "not a" => "'! a'",
								  /* Bug 35043. */
								  "'" => "''''''''''",
								 );

		$expected = $actual = array();
		foreach ( $parseQueryTests as $k => $v ) {
			$expected[] = $v;
			$actual[] = $this->search->parseQuery( $k );
		}

		$this->assertEquals(
			$expected,
			$actual,
			"parseQuery() transformations failed" );
	}

}
