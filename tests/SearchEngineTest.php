<?php

$IP = '..';
require_once( 'PHPUnit.php' );
require_once( '../includes/Defines.php' );
require_once( '../includes/DefaultSettings.php' );
require_once( '../includes/Profiling.php' );
require_once( '../includes/MagicWord.php' );
require_once( '../languages/Language.php' );
require_once( '../languages/LanguageUtf8.php' );

require_once( '../includes/SearchEngine.php' );

class SearchEngine_TestCase extends PHPUnit_TestCase {
	var $db, $search;
	
	function insertSearchData() {
		$this->db->safeQuery( <<<END
		INSERT INTO ! (cur_id,cur_namespace,cur_title,cur_text)
		VALUES (1, 0, 'Main_Page', 'This is a main page'),
			   (2, 1, 'Main_Page', 'This is a talk page to the main page, see [[smithee]]'),
			   (3, 0, 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]'),
			   (4, 1, 'Smithee', 'This article sucks.'),
			   (5, 0, 'Unrelated_page', 'Nothing in this page is about the S word.'),
			   (6, 0, 'Another_page', 'This page also is unrelated.'),
			   (7, 4, 'Help', 'Help me!'),
			   (8, 0, 'Thppt', 'Blah blah'),
			   (9, 0, 'Alan_Smithee', 'yum'),
			   (10, 0, 'Pages', 'are food')
END
			, $this->db->tableName( 'cur' ) );
		$this->db->safeQuery( <<<END
		INSERT INTO ! (si_page,si_title,si_text)
		VALUES (1, 'main page', 'this is a main page'),
			   (2, 'main page', 'this is a talk page to the main page, see smithee'),
			   (3, 'smithee', 'a smithee is one who smiths see also alan smithee'),
			   (4, 'smithee', 'this article sucks'),
			   (5, 'unrelated page', 'nothing in this page is about the s word'),
			   (6, 'another page', 'this page also is unrelated'),
			   (7, 'help', 'help me'),
			   (8, 'thppt', 'blah blah'),
			   (9, 'alan smithee', 'yum'),
			   (10, 'pages', 'are food')
END
			, $this->db->tableName( 'searchindex' ) );
	}
	
	function fetchIds( &$results ) {
		$matches = array();
		while( $row = $results->fetchObject() ) {
			$matches[] = IntVal( $row->cur_id );
		}
		$results->free();
		return $matches;
	}
	
	function testTextSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->assertEquals(
				array( 3 ),
				$this->fetchIds( $this->search->searchText( 'smithee' ) ),
				"Plain search failed" );
		}
	}
	
	function testTextPowerSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->search->setNamespaces( array( 0, 1, 4 ) );
			$this->assertEquals(
				array( 2, 3 ),
				$this->fetchIds( $this->search->searchText( 'smithee' ) ),
				"Power search failed" );
		}
	}
	
	function testTitleSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->assertEquals(
				array( 3, 9 ),
				$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
				"Title search failed" );
		}
	}
	
	function testTextTitlePowerSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->search->setNamespaces( array( 0, 1, 4 ) );
			$this->assertEquals(
				array( 3, 4, 9 ),
				$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
				"Title power search failed" );
		}
	}
	
}


?>