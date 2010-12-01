<?php

require_once( dirname( __FILE__ ) . '/SearchEngineTest.php' );

/**
 * @group Search
 * @group Destructive
 */
class SearchDbTest extends SearchEngineTest {
	var $db;

	function setUp() {
		// Get a database connection or skip test
		$this->db = wfGetDB( DB_MASTER );
		if ( !$this->db  ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
		}

		parent::setup();

		// Initialize search database with data
		$GLOBALS['wgContLang'] = new Language;
		$this->insertSearchData();

		$this->insertSearchData();
		$searchType = preg_replace( "/Database/", "Search",
								   get_class( $this->db ) );
		$this->search = new $searchType( $this->db );
	}

	function tearDown() {
		$this->removeSearchData();
		unset( $this->search );
	}
}


