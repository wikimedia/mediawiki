<?php

/**
 * @group Search
 * @group Database
 *
 * @covers PrefixSearch
 */
class PrefixSearchTest extends MediaWikiLangTestCase {
	protected function setUp() {
		parent::setUp();

		// Avoid special pages from extensions interferring with the tests
		$this->setMwGlobals( 'wgSpecialPages', array() );
	}

	protected function insertPage( $title ) {
		$page = WikiPage::factory( Title::newFromText( $title ) );
		$page->doEditContent(
			new WikitextContent( 'UTContent' ),
			'UTPageSummary',
			EDIT_NEW,
			/* baseRevId = */ false,
			User::newFromName( 'UTSysop' )
		);
	}

	public function testTitleSearch() {
		$this->insertPage( 'Example' );
		$this->insertPage( 'Sandbox' );
		$this->insertPage( 'Sandbox/Foo' );
		$this->insertPage( 'Sandbox/Bar' );
		$this->insertPage( 'Sandbox/Baz' );
		$this->insertPage( 'Sandbox/Quux' );
		$this->insertPage( 'Talk:Sandbox' );
		$this->insertPage( 'Talk:Example' );
		$this->insertPage( 'User:Example' );
		$this->insertPage( 'User talk:Example' );

		$this->assertEquals(
			PrefixSearch::titleSearch( '', 2 ),
			array(),
			'Empty'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Sa', 3 ),
			array(
				'Sandbox',
				'Sandbox/Bar',
				'Sandbox/Baz',
			),
			'Main namespace with title prefix'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Talk:', 3 ),
			array(
				'Talk:Example',
				'Talk:Sandbox',
			),
			'Talk namespace prefix'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'User:', 3 ),
			array( 'User:Example' ),
			'User namespace prefix'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Special:', 3 ),
			array(
				'Special:ActiveUsers',
				'Special:AllMessages',
				'Special:AllMyFiles',
			),
			'Special namespace prefix'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Special:Un', 3 ),
			array(
				'Special:Unblock',
				'Special:UncategorizedCategories',
				'Special:UncategorizedFiles',
			),
			'Special namespace with title prefix'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Special:EditWatchlist', 3 ),
			array(
				'Special:EditWatchlist',
			),
			'Special page name'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Special:EditWatchlist/', 3 ),
			array(
				'Special:EditWatchlist/clear',
				'Special:EditWatchlist/raw',
			),
			'Special page name with subpage root'
		);

		$this->assertEquals(
			PrefixSearch::titleSearch( 'Special:EditWatchlist/cl', 3 ),
			array(
				'Special:EditWatchlist/clear',
			),
			'Special page name with subpage prefix'
		);
	}
}
