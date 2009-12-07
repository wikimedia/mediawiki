<?php

require_once 'MediaWiki_TestCase.php';

/** @todo document */
class SearchEngineTest extends MediaWiki_TestCase {
	var $db, $search;

	function insertSearchData() {
		$this->db->safeQuery( <<<SQL
		INSERT INTO ! (page_id,page_namespace,page_title,page_latest)
		VALUES (1, 0, 'Main_Page', 1),
			   (2, 1, 'Main_Page', 2),
			   (3, 0, 'Smithee', 3),
			   (4, 1, 'Smithee', 4),
			   (5, 0, 'Unrelated_page', 5),
			   (6, 0, 'Another_page', 6),
			   (7, 4, 'Help', 7),
			   (8, 0, 'Thppt', 8),
			   (9, 0, 'Alan_Smithee', 9),
			   (10, 0, 'Pages', 10)
SQL
			, $this->db->tableName( 'page' ) );
		$this->db->safeQuery( <<<SQL
		INSERT INTO ! (rev_id,rev_page)
		VALUES (1, 1),
		       (2, 2),
		       (3, 3),
		       (4, 4),
		       (5, 5),
		       (6, 6),
		       (7, 7),
		       (8, 8),
		       (9, 9),
		       (10, 10)
SQL
			, $this->db->tableName( 'revision' ) );
		$this->db->safeQuery( <<<SQL
		INSERT INTO ! (old_id,old_text)
		VALUES (1, 'This is a main page'),
			   (2, 'This is a talk page to the main page, see [[smithee]]'),
			   (3, 'A smithee is one who smiths. See also [[Alan Smithee]]'),
			   (4, 'This article sucks.'),
			   (5, 'Nothing in this page is about the S word.'),
			   (6, 'This page also is unrelated.'),
			   (7, 'Help me!'),
			   (8, 'Blah blah'),
			   (9, 'yum'),
			   (10,'are food')
SQL
			, $this->db->tableName( 'text' ) );
		$this->db->safeQuery( <<<SQL
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
SQL
			, $this->db->tableName( 'searchindex' ) );
	}

	function fetchIds( $results ) {
		$matches = array();
		while( $row = $results->next() ) {
			$matches[] = $row->getTitle()->getPrefixedText();
		}
		$results->free();
		# Search is not guaranteed to return results in a certain order;
		# sort them numerically so we will compare simply that we received
		# the expected matches.
		sort( $matches );
		return $matches;
	}

	function testTextSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->assertEquals(
				array( 'Smithee' ),
				$this->fetchIds( $this->search->searchText( 'smithee' ) ),
				"Plain search failed" );
		}
	}

	function testTextPowerSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->search->setNamespaces( array( 0, 1, 4 ) );
			$this->assertEquals(
				array(
					'Smithee',
					'Talk:Main Page',
				),
				$this->fetchIds( $this->search->searchText( 'smithee' ) ),
				"Power search failed" );
		}
	}

	function testTitleSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->assertEquals(
				array(
					'Alan Smithee',
					'Smithee',
				),
				$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
				"Title search failed" );
		}
	}

	function testTextTitlePowerSearch() {
		$this->assertFalse( is_null( $this->db ), "Can't find a database to test with." );
		if( !is_null( $this->db ) ) {
			$this->search->setNamespaces( array( 0, 1, 4 ) );
			$this->assertEquals(
				array(
					'Alan Smithee',
					'Smithee',
					'Talk:Smithee',
				),
				$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
				"Title power search failed" );
		}
	}

}



