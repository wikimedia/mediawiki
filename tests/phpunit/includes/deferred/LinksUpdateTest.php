<?php

use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers LinksUpdate
 * @group LinksUpdate
 * @group Database
 * ^--- make sure temporary tables are used.
 */
class LinksUpdateTest extends MediaWikiLangTestCase {
	protected static $testingPageId;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge( $this->tablesUsed,
			[
				'interwiki',
				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks',
				'recentchanges',
			]
		);
	}

	protected function setUp() : void {
		parent::setUp();
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'interwiki',
			[ 'iw_prefix' ],
			[
				'iw_prefix' => 'linksupdatetest',
				'iw_url' => 'http://testing.com/wiki/$1',
				'iw_api' => 'http://testing.com/w/api.php',
				'iw_local' => 0,
				'iw_trans' => 0,
				'iw_wikiid' => 'linksupdatetest',
			]
		);
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );
	}

	public function addDBDataOnce() {
		$res = $this->insertPage( 'Testing' );
		self::$testingPageId = $res['id'];
		$this->insertPage( 'Some_other_page' );
		$this->insertPage( 'Template:TestingTemplate' );
	}

	protected function makeTitleAndParserOutput( $name, $id ) {
		// Force the value returned by getArticleID, even is
		// READ_LATEST is passed.

		/** @var Title|MockObject $t */
		$t = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getArticleID' ] )
			->getMock();
		$t->method( 'getArticleID' )->willReturn( $id );

		$tAccess = TestingAccessWrapper::newFromObject( $t );
		$tAccess->secureAndSplit( $name );

		$po = new ParserOutput();
		$po->setTitleText( $name );

		return [ $t, $po ];
	}

	/**
	 * @covers ParserOutput::addLink
	 */
	public function testUpdate_pagelinks() {
		/** @var Title $t */
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLink( Title::newFromText( "Foo" ) );
		$po->addLink( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addLink( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addLink( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			'pl_namespace,
			pl_title',
			'pl_from = ' . self::$testingPageId,
			[ [ NS_MAIN, 'Foo' ] ]
		);
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Foo' ),  // newFromText doesn't yield the same internal state....
		], $update->getAddedLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Talk:Bar" ) );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			'pl_namespace,
			pl_title',
			'pl_from = ' . self::$testingPageId,
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_TALK, 'Bar' ],
			]
		);
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Bar' ),
			Title::makeTitle( NS_TALK, 'Bar' ),
		], $update->getAddedLinks() );
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Foo' ),
		], $update->getRemovedLinks() );
	}

	/**
	 * @covers ParserOutput::addExternalLink
	 * @covers LinksUpdate::getAddedExternalLinks
	 * @covers LinksUpdate::getRemovedExternalLinks
	 */
	public function testUpdate_externallinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addExternalLink( "http://testing.com/wiki/Foo" );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			'el_to, el_index',
			'el_from = ' . self::$testingPageId,
			[
				[ 'http://testing.com/wiki/Foo', 'http://com.testing./wiki/Foo' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Foo"
		], $update->getAddedExternalLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );
		$po->addExternalLink( 'http://testing.com/wiki/Bar' );
		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			'el_to, el_index',
			'el_from = ' . self::$testingPageId,
			[
				[ 'http://testing.com/wiki/Bar', 'http://com.testing./wiki/Bar' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Bar"
		], $update->getAddedExternalLinks() );
		$this->assertArrayEquals( [
			"http://testing.com/wiki/Foo"
		], $update->getRemovedExternalLinks() );
	}

	/**
	 * @covers ParserOutput::addCategory
	 */
	public function testUpdate_categorylinks() {
		/** @var ParserOutput $po */
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addCategory( "Foo", "FOO" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			'cl_to, cl_sortkey',
			'cl_from = ' . self::$testingPageId,
			[ [ 'Foo', "FOO\nTESTING" ] ]
		);
	}

	public function testOnAddingAndRemovingCategory_recentChangesRowIsAdded() {
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		$title = Title::newFromText( 'Testing' );
		$wikiPage = new WikiPage( $title );
		$wikiPage->doEditContent( new WikitextContent( '[[Category:Foo]]' ), 'added category' );
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Foo' ),
			[ [ 'Foo', '[[:Testing]] added to category' ] ]
		);

		$wikiPage->doEditContent( new WikitextContent( '[[Category:Bar]]' ), 'replaced category' );
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Foo' ),
			[
				[ 'Foo', '[[:Testing]] added to category' ],
				[ 'Foo', '[[:Testing]] removed from category' ],
			]
		);

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Bar' ),
			[
				[ 'Bar', '[[:Testing]] added to category' ],
			]
		);
	}

	public function testOnAddingAndRemovingCategoryToTemplates_embeddingPagesAreIgnored() {
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		$templateTitle = Title::newFromText( 'Template:TestingTemplate' );
		$templatePage = new WikiPage( $templateTitle );

		$wikiPage = new WikiPage( Title::newFromText( 'Testing' ) );
		$wikiPage->doEditContent( new WikitextContent( '{{TestingTemplate}}' ), 'added template' );
		$this->runAllRelatedJobs();

		$otherWikiPage = new WikiPage( Title::newFromText( 'Some_other_page' ) );
		$otherWikiPage->doEditContent( new WikitextContent( '{{TestingTemplate}}' ), 'added template' );
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$templateTitle,
			$templatePage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Baz' ),
			[]
		);

		$templatePage->doEditContent( new WikitextContent( '[[Category:Baz]]' ), 'added category' );
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$templateTitle,
			$templatePage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Baz' ),
			[ [
				'Baz',
				'[[:Template:TestingTemplate]] added to category, ' .
				'[[Special:WhatLinksHere/Template:TestingTemplate|this page is included within other pages]]'
			] ]
		);
	}

	/**
	 * @covers ParserOutput::addInterwikiLink
	 */
	public function testUpdate_iwlinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$target = Title::makeTitleSafe( NS_MAIN, "Foo", '', 'linksupdatetest' );
		$po->addInterwikiLink( $target );

		$this->assertLinksUpdate(
			$t,
			$po,
			'iwlinks',
			'iwl_prefix, iwl_title',
			'iwl_from = ' . self::$testingPageId,
			[ [ 'linksupdatetest', 'Foo' ] ]
		);
	}

	/**
	 * @covers ParserOutput::addTemplate
	 */
	public function testUpdate_templatelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addTemplate( Title::newFromText( "Template:Foo" ), 23, 42 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'templatelinks',
			'tl_namespace,
			tl_title',
			'tl_from = ' . self::$testingPageId,
			[ [ NS_TEMPLATE, 'Foo' ] ]
		);
	}

	/**
	 * @covers ParserOutput::addImage
	 */
	public function testUpdate_imagelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( "Foo.png" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			'il_to',
			'il_from = ' . self::$testingPageId,
			[ [ 'Foo.png' ] ]
		);
	}

	/**
	 * @covers ParserOutput::addLanguageLink
	 */
	public function testUpdate_langlinks() {
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLanguageLink( Title::newFromText( "en:Foo" )->getFullText() );

		$this->assertLinksUpdate(
			$t,
			$po,
			'langlinks',
			'll_lang, ll_title',
			'll_from = ' . self::$testingPageId,
			[ [ 'En', 'Foo' ] ]
		);
	}

	/**
	 * @covers ParserOutput::setProperty
	 */
	public function testUpdate_page_props() {
		global $wgPagePropsHaveSortkey;

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$fields = [ 'pp_propname', 'pp_value' ];
		$expected = [];

		$po->setProperty( "bool", true );
		$expected[] = [ "bool", true ];

		$po->setProperty( "float", 4.0 + 1.0 / 4.0 );
		$expected[] = [ "float", 4.0 + 1.0 / 4.0 ];

		$po->setProperty( "int", -7 );
		$expected[] = [ "int", -7 ];

		$po->setProperty( "string", "33 bar" );
		$expected[] = [ "string", "33 bar" ];

		// compute expected sortkey values
		if ( $wgPagePropsHaveSortkey ) {
			$fields[] = 'pp_sortkey';

			foreach ( $expected as &$row ) {
				$value = $row[1];

				if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
					$row[] = floatval( $value );
				} else {
					$row[] = null;
				}
			}
		}

		$this->assertLinksUpdate(
			$t, $po, 'page_props', $fields, 'pp_page = ' . self::$testingPageId, $expected );
	}

	public function testUpdate_page_props_without_sortkey() {
		$this->setMwGlobals( 'wgPagePropsHaveSortkey', false );

		$this->testUpdate_page_props();
	}

	// @todo test recursive, too!

	protected function assertLinksUpdate( Title $title, ParserOutput $parserOutput,
		$table, $fields, $condition, array $expectedRows
	) {
		$update = new LinksUpdate( $title, $parserOutput );

		$update->doUpdate();

		$this->assertSelect( $table, $fields, $condition, $expectedRows );
		return $update;
	}

	protected function assertRecentChangeByCategorization(
		Title $pageTitle, ParserOutput $parserOutput, Title $categoryTitle, $expectedRows
	) {
		$this->assertSelect(
			[ 'recentchanges', 'comment' ],
			'rc_title, comment_text',
			[
				'rc_type' => RC_CATEGORIZE,
				'rc_namespace' => NS_CATEGORY,
				'rc_title' => $categoryTitle->getDBkey(),
				'comment_id = rc_comment_id',
			],
			$expectedRows
		);
	}

	private function runAllRelatedJobs() {
		$queueGroup = JobQueueGroup::singleton();
		while ( $job = $queueGroup->pop( 'refreshLinksPrioritized' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
		while ( $job = $queueGroup->pop( 'categoryMembershipChange' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
	}

	public function testIsRecursive() {
		list( $title, $po ) = $this->makeTitleAndParserOutput( 'Test', 1 );
		$linksUpdate = new LinksUpdate( $title, $po );
		$this->assertTrue( $linksUpdate->isRecursive(), 'LinksUpdate is recursive by default' );

		$linksUpdate = new LinksUpdate( $title, $po, true );
		$this->assertTrue( $linksUpdate->isRecursive(),
			'LinksUpdate is recursive when asked to be recursive' );

		$linksUpdate = new LinksUpdate( $title, $po, false );
		$this->assertFalse( $linksUpdate->isRecursive(),
			'LinksUpdate is not recursive when asked to be not recursive' );
	}
}
