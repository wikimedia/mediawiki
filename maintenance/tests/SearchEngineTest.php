<?php

require_once( 'MediaWiki_Setup.php' );

/**
 * @group Stub
 */
class SearchEngineTest extends MediaWiki_Setup {
	var $db, $search;
	private $count = 0;

 	function insertSearchData() {
		$this->insertPage("Main_Page",      "This is a main page", 0);
		$this->insertPage('Main_Page',      'This is a talk page to the main page, see [[smithee]]', 1);
		$this->insertPage('Smithee', 		'A smithee is one who smiths. See also [[Alan Smithee]]', 0);
		$this->insertPage('Smithee',		'This article sucks.', 1);
		$this->insertPage('Unrelated_page',	'Nothing in this page is about the S word.', 0);
		$this->insertPage('Another_page',	'This page also is unrelated.', 0);
		$this->insertPage('Help',			'Help me!', 4);
		$this->insertPage('Thppt',			'Blah blah', 0);
		$this->insertPage('Alan_Smithee',	'yum', 0);
		$this->insertPage('Pages',			'are food', 0);
		$this->insertPage('DblPageOne',		'ＡＢＣＤＥＦ', 0);
		$this->insertPage('DblPageTwo',		'ＡＢＣＤＥ', 0);
		$this->insertPage('DblPageTwoLow',  'ａｂｃｄｅ', 0);
	}

	function normalize( $text ) {
		return strtolower(preg_replace("/[^[:alnum:] ]/", " ", $text));
	}

	function insertPage( $pageName, $text, $ns ) {
		$this->count++;
		$this->db->safeQuery( 'INSERT INTO ! (page_id,page_namespace,page_title,page_latest) VALUES (?,?,?,?)',
			$this->db->tableName( 'page' ), $this->count, $ns, $pageName, $this->count );
		$this->db->safeQuery( 'INSERT INTO ! (rev_id,rev_page) VALUES (?, ?)',
			$this->db->tableName( 'revision' ), $this->count, $this->count );
		$this->db->safeQuery( 'INSERT INTO ! (old_id,old_text) VALUES (?, ?)',
			$this->db->tableName( 'text' ), $this->count, $text );
		$this->db->safeQuery( 'INSERT INTO ! (si_page,si_title,si_text) VALUES (?, ?, ?)',
			$this->db->tableName( 'searchindex' ), $this->count,
			$this->normalize( $pageName ), $this->normalize( $text ) );
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
		if( is_null( $this->db ) ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
		}
		$this->assertEquals(
			array( 'Smithee' ),
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Plain search failed" );
	}

	function testTextPowerSearch() {
		if( is_null( $this->db ) ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
		}
		$this->search->setNamespaces( array( 0, 1, 4 ) );
		$this->assertEquals(
			array(
				'Smithee',
				'Talk:Main Page',
			),
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Power search failed" );
	}

	function testTitleSearch() {
		if( is_null( $this->db ) ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
		}
		$this->assertEquals(
			array(
				'Alan Smithee',
				'Smithee',
			),
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title search failed" );
	}

	function testTextTitlePowerSearch() {
		if( is_null( $this->db ) ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
		}
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



