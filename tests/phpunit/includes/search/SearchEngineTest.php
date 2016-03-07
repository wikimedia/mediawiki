<?php

/**
 * @group Search
 * @group Database
 *
 * @covers SearchEngine<extended>
 * @note Coverage will only ever show one of on of the Search* classes
 */
class SearchEngineTest extends MediaWikiLangTestCase {

	/**
	 * @var SearchEngine
	 */
	protected $search;

	/**
	 * Checks for database type & version.
	 * Will skip current test if DB does not support search.
	 */
	protected function setUp() {
		parent::setUp();

		// Search tests require MySQL or SQLite with FTS
		$dbType = $this->db->getType();
		$dbSupported = ( $dbType === 'mysql' )
			|| ( $dbType === 'sqlite' && $this->db->getFulltextSearchModule() == 'FTS3' );

		if ( !$dbSupported ) {
			$this->markTestSkipped( "MySQL or SQLite with FTS3 only" );
		}

		$searchType = $this->db->getSearchEngine();
		$this->setMwGlobals( [
			'wgSearchType' => $searchType
		] );

		$this->search = new $searchType( $this->db );
	}

	protected function tearDown() {
		unset( $this->search );

		parent::tearDown();
	}

	public function addDBDataOnce() {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			// @todo cover the case of non-wikitext content in the main namespace
			return;
		}

		$this->insertPage( 'Not_Main_Page', 'This is not a main page' );
		$this->insertPage(
			'Talk:Not_Main_Page',
			'This is not a talk page to the main page, see [[smithee]]'
		);
		$this->insertPage( 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]' );
		$this->insertPage( 'Talk:Smithee', 'This article sucks.' );
		$this->insertPage( 'Unrelated_page', 'Nothing in this page is about the S word.' );
		$this->insertPage( 'Another_page', 'This page also is unrelated.' );
		$this->insertPage( 'Help:Help', 'Help me!' );
		$this->insertPage( 'Thppt', 'Blah blah' );
		$this->insertPage( 'Alan_Smithee', 'yum' );
		$this->insertPage( 'Pages', 'are\'food' );
		$this->insertPage( 'HalfOneUp', 'AZ' );
		$this->insertPage( 'FullOneUp', 'ＡＺ' );
		$this->insertPage( 'HalfTwoLow', 'az' );
		$this->insertPage( 'FullTwoLow', 'ａｚ' );
		$this->insertPage( 'HalfNumbers', '1234567890' );
		$this->insertPage( 'FullNumbers', '１２３４５６７８９０' );
		$this->insertPage( 'DomainName', 'example.com' );
	}

	protected function fetchIds( $results ) {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestIncomplete( __CLASS__ . " does no yet support non-wikitext content "
				. "in the main namespace" );
		}
		$this->assertTrue( is_object( $results ) );

		$matches = [];
		$row = $results->next();
		while ( $row ) {
			$matches[] = $row->getTitle()->getPrefixedText();
			$row = $results->next();
		}
		$results->free();
		# Search is not guaranteed to return results in a certain order;
		# sort them numerically so we will compare simply that we received
		# the expected matches.
		sort( $matches );

		return $matches;
	}

	public function testFullWidth() {
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'AZ' ) ),
			"Search for normalized from Half-width Upper" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'az' ) ),
			"Search for normalized from Half-width Lower" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'ＡＺ' ) ),
			"Search for normalized from Full-width Upper" );
		$this->assertEquals(
			[ 'FullOneUp', 'FullTwoLow', 'HalfOneUp', 'HalfTwoLow' ],
			$this->fetchIds( $this->search->searchText( 'ａｚ' ) ),
			"Search for normalized from Full-width Lower" );
	}

	public function testTextSearch() {
		$this->assertEquals(
			[ 'Smithee' ],
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Plain search failed" );
	}

	public function testTextPowerSearch() {
		$this->search->setNamespaces( [ 0, 1, 4 ] );
		$this->assertEquals(
			[
				'Smithee',
				'Talk:Not Main Page',
			],
			$this->fetchIds( $this->search->searchText( 'smithee' ) ),
			"Power search failed" );
	}

	public function testTitleSearch() {
		$this->assertEquals(
			[
				'Alan Smithee',
				'Smithee',
			],
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title search failed" );
	}

	public function testTextTitlePowerSearch() {
		$this->search->setNamespaces( [ 0, 1, 4 ] );
		$this->assertEquals(
			[
				'Alan Smithee',
				'Smithee',
				'Talk:Smithee',
			],
			$this->fetchIds( $this->search->searchTitle( 'smithee' ) ),
			"Title power search failed" );
	}

}
