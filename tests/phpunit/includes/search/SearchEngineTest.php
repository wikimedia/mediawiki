<?php

/**
 * This class is not directly tested. Instead it is extended by SearchDbTest.
 * @group Search
 * @group Database
 */
class SearchEngineTest extends MediaWikiTestCase {
	protected $search, $pageList;

	function tearDown() {
		unset( $this->search );
	}

	/*
	 * Checks for database type & version.
	 * Will skip current test if DB does not support search.
	 */
	function setUp() {
		parent::setUp();
		// Search tests require MySQL or SQLite with FTS
		# Get database type and version
		$dbType = $this->db->getType();
		$dbSupported =
			($dbType === 'mysql')
			|| ( $dbType === 'sqlite' && $this->db->getFulltextSearchModule() == 'FTS3' );

		if( !$dbSupported ) {
			$this->markTestSkipped( "MySQL or SQLite with FTS3 only" );
		}

		$searchType = $this->db->getSearchEngine();
		$this->search = new $searchType( $this->db );
	}

	function pageExists( $title ) {
		return false;
	}

	function addDBData() {
		if ( $this->pageExists( 'Not_Main_Page' ) ) {
			return;
		}
		$this->insertPage( "Not_Main_Page",	"This is not a main page", 0 );
		$this->insertPage( 'Talk:Not_Main_Page',	'This is not a talk page to the main page, see [[smithee]]', 1 );
		$this->insertPage( 'Smithee',	'A smithee is one who smiths. See also [[Alan Smithee]]', 0 );
		$this->insertPage( 'Talk:Smithee',	'This article sucks.', 1 );
		$this->insertPage( 'Unrelated_page',	'Nothing in this page is about the S word.', 0 );
		$this->insertPage( 'Another_page',	'This page also is unrelated.', 0 );
		$this->insertPage( 'Help:Help',		'Help me!', 4 );
		$this->insertPage( 'Thppt',		'Blah blah', 0 );
		$this->insertPage( 'Alan_Smithee',	'yum', 0 );
		$this->insertPage( 'Pages',		'are\'food', 0 );
		$this->insertPage( 'HalfOneUp',	'AZ', 0 );
		$this->insertPage( 'FullOneUp',	'ＡＺ', 0 );
		$this->insertPage( 'HalfTwoLow',	'az', 0 );
		$this->insertPage( 'FullTwoLow',	'ａｚ', 0 );
		$this->insertPage( 'HalfNumbers',	'1234567890', 0 );
		$this->insertPage( 'FullNumbers',	'１２３４５６７８９０', 0 );
		$this->insertPage( 'DomainName',	'example.com', 0 );
	}

	function fetchIds( $results ) {
		$this->assertTrue( is_object( $results ) );

		$matches = array();
		while ( $row = $results->next() ) {
			$matches[] = $row->getTitle()->getPrefixedText();
		}
		$results->free();
		# Search is not guaranteed to return results in a certain order;
		# sort them numerically so we will compare simply that we received
		# the expected matches.
		sort( $matches );
		return $matches;
	}

	/**
	 * Insert a new page
	 *
	 * @param $pageName String: page name
	 * @param $text String: page's content
	 * @param $n Integer: unused
	 */
	function insertPage( $pageName, $text, $ns ) {
		$dbw = $this->db;
		$title = Title::newFromText( $pageName );

		$user = User::newFromName( 'WikiSysop' );
		$comment = 'Search Test';

		// avoid memory leak...?
		$linkCache = LinkCache::singleton();
		$linkCache->clear();

		$article = new Article( $title );
		$article->doEdit( $text, $comment, 0, false, $user );

		$this->pageList[] = array( $title, $article->getId() );

		return true;
	}

	function testFullWidth() {
		$this->assertEquals(
			array( 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ),
			$this->fetchIds( $this->search->searchText( 'AZ' ) ),
			"Search for normalized from Half-width Upper" );
		$this->assertEquals(
			array( 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ),
			$this->fetchIds( $this->search->searchText( 'az' ) ),
			"Search for normalized from Half-width Lower" );
		$this->assertEquals(
			array( 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ),
			$this->fetchIds( $this->search->searchText( 'ＡＺ' ) ),
			"Search for normalized from Full-width Upper" );
		$this->assertEquals(
			array( 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ),
			$this->fetchIds( $this->search->searchText( 'ａｚ' ) ),
			"Search for normalized from Full-width Lower" );
	}

	function testTextSearch() {
		$this->assertEquals(
				array( 'Smithee' ),
				$this->fetchIds( $this->search->searchText( 'smithee' ) ),
				"Plain search failed" );
	}

	function testTextPowerSearch() {
		$this->search->setNamespaces( array( 0, 1, 4 ) );
		$this->assertEquals(
			array(
				'Smithee',
				'Talk:Not Main Page',
			),
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Power search failed" );
	}

	function testTitleSearch() {
		$this->assertEquals(
			array(
				'Alan Smithee',
				'Smithee',
			),
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title search failed" );
	}

	function testTextTitlePowerSearch() {
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
