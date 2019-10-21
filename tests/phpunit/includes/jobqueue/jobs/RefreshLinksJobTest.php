<?php

use MediaWiki\MediaWikiServices;

/**
 * @covers RefreshLinksJob
 *
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 */
class RefreshLinksJobTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();

		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';

		$this->tablesUsed[] = 'pagelinks';
		$this->tablesUsed[] = 'categorylinks';
	}

	/**
	 * @param string $name
	 * @param Content[] $content
	 *
	 * @return WikiPage
	 */
	private function createPage( $name, array $content ) {
		$title = Title::makeTitle( $this->getDefaultWikitextNS(), $name );
		$page = WikiPage::factory( $title );

		$updater = $page->newPageUpdater( $this->getTestUser()->getUser() );

		foreach ( $content as $slot => $cnt ) {
			$updater->setContent( $slot, $cnt );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( 'Test' ) );

		return $page;
	}

	// TODO: test multi-page
	// TODO: test recursive
	// TODO: test partition

	public function testRunForSinglePage() {
		MediaWikiServices::getInstance()->getSlotRoleRegistry()->defineRoleWithModel(
			'aux',
			CONTENT_MODEL_WIKITEXT
		);

		$mainContent = new WikitextContent( 'MAIN [[Kittens]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Goats]]' );
		$page = $this->createPage( __METHOD__, [ 'main' => $mainContent, 'aux' => $auxContent ] );

		// clear state
		$parserCache = MediaWikiServices::getInstance()->getParserCache();
		$parserCache->deleteOptionsKey( $page );

		$this->db->delete( 'pagelinks', '*', __METHOD__ );
		$this->db->delete( 'categorylinks', '*', __METHOD__ );

		// run job
		$job = new RefreshLinksJob( $page->getTitle(), [ 'parseThreshold' => 0 ] );
		$job->run();

		$this->assertSelect(
			'pagelinks',
			'pl_title',
			[ 'pl_from' => $page->getId() ],
			[ [ 'Kittens' ] ]
		);
		$this->assertSelect(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $page->getId() ],
			[ [ 'Goats' ] ]
		);
	}

	public function testRunForMultiPage() {
		MediaWikiServices::getInstance()->getSlotRoleRegistry()->defineRoleWithModel(
			'aux',
			CONTENT_MODEL_WIKITEXT
		);

		$fname = __METHOD__;

		$mainContent = new WikitextContent( 'MAIN [[Kittens]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Goats]]' );
		$page1 = $this->createPage( "$fname-1", [ 'main' => $mainContent, 'aux' => $auxContent ] );

		$mainContent = new WikitextContent( 'MAIN [[Dogs]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Hamsters]]' );
		$page2 = $this->createPage( "$fname-2", [ 'main' => $mainContent, 'aux' => $auxContent ] );

		// clear state
		$parserCache = MediaWikiServices::getInstance()->getParserCache();
		$parserCache->deleteOptionsKey( $page1 );
		$parserCache->deleteOptionsKey( $page2 );

		$this->db->delete( 'pagelinks', '*', __METHOD__ );
		$this->db->delete( 'categorylinks', '*', __METHOD__ );

		// run job
		$job = new RefreshLinksJob(
			Title::newMainPage(),
			[ 'pages' => [ [ 0, "$fname-1" ], [ 0, "$fname-2" ] ] ]
		);
		$job->run();

		$this->assertSelect(
			'pagelinks',
			'pl_title',
			[ 'pl_from' => $page1->getId() ],
			[ [ 'Kittens' ] ]
		);
		$this->assertSelect(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $page1->getId() ],
			[ [ 'Goats' ] ]
		);
		$this->assertSelect(
			'pagelinks',
			'pl_title',
			[ 'pl_from' => $page2->getId() ],
			[ [ 'Dogs' ] ]
		);
		$this->assertSelect(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $page2->getId() ],
			[ [ 'Hamsters' ] ]
		);
	}
}
