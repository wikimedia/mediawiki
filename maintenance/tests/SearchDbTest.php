<?php
require_once( 'SearchEngineTest.php' );

class SearchDbTest extends SearchEngineTest {
	var $db;

	function setUp() {
		global $wgDBprefix, $wgDBtype;

		if($wgDBprefix === "parsertest_" ||
		   ($wgDBtype === 'oracle' && $wgDBprefix === 'pt_')) {
			$this->markTestSkipped("This test can't (yet?) be run with the parser tests");
		}

		$GLOBALS['wgContLang'] = new Language;
		$this->db = $this->buildTestDatabase(
			array( 'page', 'revision', 'text', 'searchindex', 'user' ) );
		if( $this->db ) {
			$this->insertSearchData();
		}
		$searchType = preg_replace("/Database/", "Search", get_class($this->db));
		$this->search = new $searchType( $this->db );
	}

	function tearDown() {
		if( !is_null( $this->db ) ) {
			wfGetLB()->closeConnecton( $this->db );
		}
		unset( $this->db );
		unset( $this->search );
	}
}


