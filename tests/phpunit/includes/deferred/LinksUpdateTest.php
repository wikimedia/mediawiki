<?php

use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\LinksUpdate\LinksTable;
use MediaWiki\Deferred\LinksUpdate\LinksTableGroup;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksUpdate
 * @covers \MediaWiki\Deferred\LinksUpdate\CategoryLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ExistenceLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ExternalLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\GenericPageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ImageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\InterwikiLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LangLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTableGroup
 * @covers \MediaWiki\Deferred\LinksUpdate\PageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\PagePropsTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TemplateLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TitleLinksTable
 *
 * @group LinksUpdate
 * @group Database
 */
class LinksUpdateTest extends MediaWikiLangTestCase {
	/** @var int */
	protected static $testingPageId;

	protected function setUp(): void {
		parent::setUp();

		// Set up 'linksupdatetest' as a interwiki prefix for testing
		// See ParserTestRunner:appendInterwikiSetup for similar test code
		static $testInterwikis = [
			[
				'iw_prefix' => 'linksupdatetest',
				'iw_url' => 'http://testing.com/wiki/$1',
				// 'iw_api' => 'http://testing.com/w/api.php',
				'iw_local' => 0,
			],
		];
		$GLOBAL_SCOPE = 2; // See ParserTestRunner::appendInterwikiSetup
		$this->overrideConfigValues( [
			MainConfigNames::InterwikiScopes => $GLOBAL_SCOPE,
			MainConfigNames::InterwikiCache =>
			ClassicInterwikiLookup::buildCdbHash( $testInterwikis, $GLOBAL_SCOPE ),
			MainConfigNames::RCWatchCategoryMembership => true,
		] );
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
	 * @covers \MediaWiki\Parser\ParserOutput::addLink
	 */
	public function testUpdate_pagelinks() {
		/** @var Title $t */
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLink( Title::newFromText( "Foo" ) );
		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addLink( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addLink( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'lt_namespace', 'lt_title' ],
			[ 'pl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Foo' ],
			]
		);
		$this->assertArrayEquals( [
			[ NS_MAIN, 'Foo' ],
			[ NS_MAIN, 'Bar' ],
		], array_map(
			static function ( PageReference $pageReference ) {
				return [ $pageReference->getNamespace(), $pageReference->getDbKey() ];
			},
			$update->getPageReferenceArray( 'pagelinks', LinksTable::INSERTED )
		) );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Baz" ) );
		$po->addLink( Title::newFromText( "Talk:Baz" ) );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'lt_namespace', 'lt_title' ],
			[ 'pl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Baz' ],
				[ NS_TALK, 'Baz' ],
			]
		);
		$this->assertArrayEquals( [
			[ NS_MAIN, 'Baz' ],
			[ NS_TALK, 'Baz' ],
		], array_map(
			static function ( PageReference $pageReference ) {
				return [ $pageReference->getNamespace(), $pageReference->getDbKey() ];
			},
			$update->getPageReferenceArray( 'pagelinks', LinksTable::INSERTED )
		) );
		$this->assertArrayEquals( [
			[ NS_MAIN, 'Foo' ],
		], array_map(
			static function ( PageReference $pageReference ) {
				return [ $pageReference->getNamespace(), $pageReference->getDbKey() ];
			},
			$update->getPageReferenceArray( 'pagelinks', LinksTable::DELETED )
		) );
	}

	public function testUpdate_pagelinks_move() {
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLink( Title::newFromText( "Foo" ) );
		$this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'lt_namespace', 'lt_title', 'pl_from_namespace' ],
			[ 'pl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Foo', NS_MAIN ],
			]
		);

		[ $t, $po ] = $this->makeTitleAndParserOutput( "User:Testing", self::$testingPageId );
		$po->addLink( Title::newFromText( "Foo" ) );
		$this->assertMoveLinksUpdate(
			$t,
			new PageIdentityValue( 2, 0, "Foo", false ),
			$po,
			'pagelinks',
			[ 'lt_namespace', 'lt_title', 'pl_from_namespace' ],
			[ 'pl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Foo', NS_USER ],
			]
		);
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addExternalLink
	 */
	public function testUpdate_externallinks() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addExternalLink( "http://testing.com/wiki/Foo" );
		$po->addExternalLink( "http://testing.com/wiki/Bar" );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			[ 'el_to_domain_index', 'el_to_path' ],
			[ 'el_from' => self::$testingPageId ],
			[
				[ 'http://com.testing.', '/wiki/Bar' ],
				[ 'http://com.testing.', '/wiki/Foo' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Bar",
			"http://testing.com/wiki/Foo"
		], $update->getAddedExternalLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );
		$po->addExternalLink( 'http://testing.com/wiki/Bar' );
		$po->addExternalLink( 'http://testing.com/wiki/Baz' );
		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			[ 'el_to_domain_index', 'el_to_path' ],
			[ 'el_from' => self::$testingPageId ],
			[
				[ 'http://com.testing.', '/wiki/Bar' ],
				[ 'http://com.testing.', '/wiki/Baz' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Baz"
		], $update->getAddedExternalLinks() );
		$this->assertArrayEquals( [
			"http://testing.com/wiki/Foo"
		], $update->getRemovedExternalLinks() );
	}

	public function testUpdate_externallinksWrongOldEntry() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		// Insert invalid entry from T350476
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'externallinks' )
			->row( [
				'el_from' => self::$testingPageId,
				'el_to_domain_index' => 'http://.com.testing.',
				'el_to_path' => '/',
			] )
			->row( [
				'el_from' => self::$testingPageId,
				'el_to_domain_index' => 'http://.',
				'el_to_path' => '/',
			] )
			->row( [
				'el_from' => self::$testingPageId,
				'el_to_domain_index' => '',
				'el_to_path' => null,
			] )
			->execute();

		// Test that the invalid entries are removed on LinksUpdate
		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );
		$po->addExternalLink( 'http://testing.com/wiki/Bar' );
		$po->addExternalLink( 'http://testing.com/wiki/Baz' );
		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			[ 'el_to_domain_index', 'el_to_path' ],
			[ 'el_from' => self::$testingPageId ],
			[
				[ 'http://com.testing.', '/wiki/Bar' ],
				[ 'http://com.testing.', '/wiki/Baz' ],
			]
		);

		$this->assertArrayEquals( [
			'http://testing.com/wiki/Bar',
			'http://testing.com/wiki/Baz',
		], $update->getAddedExternalLinks() );
		$this->assertArrayEquals( [
			'http://testing.com/',
			'http:///',
			'',
		], $update->getRemovedExternalLinks() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addCategory
	 */
	public function testUpdate_categorylinks() {
		/** @var ParserOutput $po */
		$this->overrideConfigValue( MainConfigNames::CategoryCollation, 'uppercase' );

		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addCategory( "Foo", "FOO" );
		$po->addCategory( "Bar", "BAR" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			[ 'cl_from' => self::$testingPageId ],
			[
				[ 'Bar', "BAR\nTESTING" ],
				[ 'Foo', "FOO\nTESTING" ]
			]
		);

		// Check category count
		$this->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ] )
			->assertResultSet( [
				[ 'Bar', 1 ],
				[ 'Foo', 1 ]
			] );

		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addCategory( "Bar", "Bar" );
		$po->addCategory( "Baz", "Baz" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			[ 'cl_from' => self::$testingPageId ],
			[
				[ 'Bar', "BAR\nTESTING" ],
				[ 'Baz', "BAZ\nTESTING" ]
			]
		);

		// Check category count decrement
		$this->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ] )
			->assertResultSet( [
				[ 'Bar', 1 ],
				[ 'Baz', 1 ],
			] );
	}

	public function testOnAddingAndRemovingCategory_recentChangesRowIsAdded() {
		$this->overrideConfigValue( MainConfigNames::CategoryCollation, 'uppercase' );

		$title = Title::newFromText( 'Testing' );
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$wikiPage->doUserEditContent(
			new WikitextContent( '[[Category:Foo]]' ),
			$this->getTestSysop()->getUser(),
			'added category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			Title::newFromText( 'Category:Foo' ),
			[ [ 'Foo', '[[:Testing]] added to category' ] ]
		);

		$wikiPage->doUserEditContent(
			new WikitextContent( '[[Category:Bar]]' ),
			$this->getTestSysop()->getUser(),
			'replaced category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			Title::newFromText( 'Category:Foo' ),
			[
				[ 'Foo', '[[:Testing]] added to category' ],
				[ 'Foo', '[[:Testing]] removed from category' ],
			]
		);

		$this->assertRecentChangeByCategorization(
			Title::newFromText( 'Category:Bar' ),
			[
				[ 'Bar', '[[:Testing]] added to category' ],
			]
		);
	}

	public function testOnAddingAndRemovingCategoryToTemplates_embeddingPagesAreIgnored() {
		$this->overrideConfigValue( MainConfigNames::CategoryCollation, 'uppercase' );

		$templateTitle = Title::newFromText( 'Template:TestingTemplate' );
		$templatePage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $templateTitle );

		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( 'Testing' ) );
		$wikiPage->doUserEditContent(
			new WikitextContent( '{{TestingTemplate}}' ),
			$this->getTestSysop()->getUser(),
			'added template'
		);
		$this->runAllRelatedJobs();

		$otherWikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( 'Some_other_page' ) );
		$otherWikiPage->doUserEditContent(
			new WikitextContent( '{{TestingTemplate}}' ),
			$this->getTestSysop()->getUser(),
			'added template'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			Title::newFromText( 'Baz' ),
			[]
		);

		$templatePage->doUserEditContent(
			new WikitextContent( '[[Category:Baz]]' ),
			$this->getTestSysop()->getUser(),
			'added category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			Title::newFromText( 'Baz' ),
			[ [
				'Baz',
				'[[:Template:TestingTemplate]] added to category, ' .
				'[[Special:WhatLinksHere/Template:TestingTemplate|this page is included within other pages]]'
			] ]
		);
	}

	public function testUpdate_categorylinks_move() {
		$this->overrideConfigValue( MainConfigNames::CategoryCollation, 'uppercase' );

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Old", self::$testingPageId );

		$po->addCategory( "Bar", "BAR" );
		$po->addCategory( "Foo", "FOO" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			[ 'cl_from' => self::$testingPageId ],
			[
				[ 'Bar', "BAR\nOLD" ],
				[ 'Foo', "FOO\nOLD" ],
			]
		);

		// Check category count
		$this->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ] )
			->assertResultSet( [
				[ 'Bar', '1' ],
				[ 'Foo', '1' ],
			] );

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "New", self::$testingPageId );

		$po->addCategory( "Bar", "BAR" );
		$po->addCategory( "Foo", "FOO" );

		// An update to cl_sortkey is not expected if there was no move
		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			[ 'cl_from' => self::$testingPageId ],
			[
				[ 'Bar', "BAR\nOLD" ],
				[ 'Foo', "FOO\nOLD" ],
			]
		);

		// Check category count
		$this->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ] )
			->assertResultSet( [
				[ 'Bar', '1' ],
				[ 'Foo', '1' ],
			] );

		// A category changed on move
		$po->setCategories( [
			"Baz" => "BAZ",
			"Foo" => "FOO",
		] );

		// With move notification, update to cl_sortkey is expected
		$this->assertMoveLinksUpdate(
			$t,
			new PageIdentityValue( 2, 0, "new", false ),
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			[ 'cl_from' => self::$testingPageId ],
			[
				[ 'Baz', "BAZ\nNEW" ],
				[ 'Foo', "FOO\nNEW" ],
			]
		);

		// Check category count
		$this->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ] )
			->assertResultSet( [
				[ 'Baz', '1' ],
				[ 'Foo', '1' ],
			] );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addInterwikiLink
	 */
	public function testUpdate_iwlinks() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$target1 = Title::makeTitleSafe( NS_MAIN, "T1", '', 'linksupdatetest' );
		$target2 = Title::makeTitleSafe( NS_MAIN, "T2", '', 'linksupdatetest' );
		$target3 = Title::makeTitleSafe( NS_MAIN, "T3", '', 'linksupdatetest' );
		$po->addInterwikiLink( $target1 );
		$po->addInterwikiLink( $target2 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'iwlinks',
			[ 'iwl_prefix', 'iwl_title' ],
			[ 'iwl_from' => self::$testingPageId ],
			[
				[ 'linksupdatetest', 'T1' ],
				[ 'linksupdatetest', 'T2' ],
			]
		);

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addInterwikiLink( $target2 );
		$po->addInterwikiLink( $target3 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'iwlinks',
			[ 'iwl_prefix', 'iwl_title' ],
			[ 'iwl_from' => self::$testingPageId ],
			[
				[ 'linksupdatetest', 'T2' ],
				[ 'linksupdatetest', 'T3' ]
			]
		);
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addTemplate
	 */
	public function testUpdate_templatelinks() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$linkTargetLookup = MediaWikiServices::getInstance()->getLinkTargetLookup();

		$target1 = Title::newFromText( "Template:T1" );
		$target2 = Title::newFromText( "Template:T2" );
		$target3 = Title::newFromText( "Template:T3" );

		$po->addTemplate( $target1, 23, 42 );
		$po->addTemplate( $target2, 23, 42 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'templatelinks',
			[ 'tl_target_id' ],
			[ 'tl_from' => self::$testingPageId ],
			[
				[ $linkTargetLookup->acquireLinkTargetId( $target1, $this->getDb() ) ],
				[ $linkTargetLookup->acquireLinkTargetId( $target2, $this->getDb() ) ],
			]
		);

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addTemplate( $target2, 23, 42 );
		$po->addTemplate( $target3, 23, 42 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'templatelinks',
			[ 'tl_target_id' ],
			[ 'tl_from' => self::$testingPageId ],
			[
				[ $linkTargetLookup->acquireLinkTargetId( $target2, $this->getDb() ) ],
				[ $linkTargetLookup->acquireLinkTargetId( $target3, $this->getDb() ) ],
			]
		);
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addImage
	 */
	public function testUpdate_imagelinks() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( new TitleValue( NS_FILE, "1.png" ) );
		$po->addImage( new TitleValue( NS_FILE, "2.png" ) );

		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			'il_to',
			[ 'il_from' => self::$testingPageId ],
			[ [ '1.png' ], [ '2.png' ] ]
		);

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( new TitleValue( NS_FILE, "2.png" ) );
		$po->addImage( new TitleValue( NS_FILE, "3.png" ) );

		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			'il_to',
			[ 'il_from' => self::$testingPageId ],
			[ [ '2.png' ], [ '3.png' ] ]
		);
	}

	public function testUpdate_imagelinks_move() {
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( new TitleValue( NS_FILE, "1.png" ) );
		$po->addImage( new TitleValue( NS_FILE, "2.png" ) );

		$fromNamespace = $t->getNamespace();
		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			[ 'il_to', 'il_from_namespace' ],
			[ 'il_from' => self::$testingPageId ],
			[ [ '1.png', $fromNamespace ], [ '2.png', $fromNamespace ] ]
		);

		$oldT = $t;
		[ $t, $po ] = $this->makeTitleAndParserOutput( "User:Testing", self::$testingPageId );
		$po->addImage( new TitleValue( NS_FILE, "1.png" ) );
		$po->addImage( new TitleValue( NS_FILE, "2.png" ) );

		$fromNamespace = $t->getNamespace();
		$this->assertMoveLinksUpdate(
			$t,
			$oldT->toPageIdentity(),
			$po,
			'imagelinks',
			[ 'il_to', 'il_from_namespace' ],
			[ 'il_from' => self::$testingPageId ],
			[ [ '1.png', $fromNamespace ], [ '2.png', $fromNamespace ] ]
		);
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::addLanguageLink
	 */
	public function testUpdate_langlinks() {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, true );

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLanguageLink( new TitleValue( 0, '1', '', 'De' ) );
		$po->addLanguageLink( new TitleValue( 0, '1', '', 'En' ) );
		$po->addLanguageLink( new TitleValue( 0, '1', '', 'Fr' ) );

		$this->assertLinksUpdate(
			$t,
			$po,
			'langlinks',
			[ 'll_lang', 'll_title' ],
			[ 'll_from' => self::$testingPageId ],
			[
				[ 'De', '1' ],
				[ 'En', '1' ],
				[ 'Fr', '1' ]
			]
		);

		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addLanguageLink( new TitleValue( 0, '2', '', 'En' ) );
		$po->addLanguageLink( new TitleValue( 0, '1', '', 'Fr' ) );

		$this->assertLinksUpdate(
			$t,
			$po,
			'langlinks',
			[ 'll_lang', 'll_title' ],
			[ 'll_from' => self::$testingPageId ],
			[
				[ 'En', '2' ],
				[ 'Fr', '1' ]
			]
		);
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOutput::setPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::setNumericPageProperty
	 * @covers \MediaWiki\Parser\ParserOutput::setUnsortedPageProperty
	 */
	public function testUpdate_page_props() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$fields = [ 'pp_propname', 'pp_value', 'pp_sortkey' ];
		$cond = [ 'pp_page' => self::$testingPageId ];

		$setNumericPageProperty = 'setNumericPageProperty';
		$setUnsortedPageProperty = 'setUnsortedPageProperty';

		$po->$setNumericPageProperty( 'deleted', 1 );
		$po->$setNumericPageProperty( 'changed', 1 );
		$this->assertLinksUpdate(
			$t, $po, 'page_props', $fields, $cond,
			[
				[ 'changed', '1', 1 ],
				[ 'deleted', '1', 1 ]
			]
		);

		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		// Elements of the $expected array are 3-element arrays:
		// First element is the page property name
		// Second element is the page property value
		//    (These are stringified when encoded into the database.)
		// Third element is the sort key (as a float, or null)
		$expected = [];

		$po->$setNumericPageProperty( 'changed', 2 );
		$expected[] = [ 'changed', 2, 2.0 ];

		$f = 4.0 + 1.0 / 4.0;
		$po->$setNumericPageProperty( "float", $f );
		$expected[] = [ "float", $f, $f ];

		$po->$setNumericPageProperty( "int", -7 );
		$expected[] = [ "int", -7, -7.0 ];

		$po->$setUnsortedPageProperty( "string", "33 bar" );
		$expected[] = [ "string", "33 bar", null ];

		// A numeric string *does* get indexed if you use
		// ::setNumericPageProperty
		$po->setNumericPageProperty( "numeric-string", "33" );
		$expected[] = [ "numeric-string", 33, 33.0 ];
		// And similarly a numeric argument won't get indexed if you
		// use ::setUnsortedPageProperty
		$po->setUnsortedPageProperty( "unsorted", 33 );
		$expected[] = [ "unsorted", "33", null ];

		// Note that the ::assertSelect machinery will sort by the columns
		// provided in $fields; in our case we should sort by property name
		usort( $expected, static fn ( $a, $b ): int => $a[0] <=> $b[0] );

		$update = $this->assertLinksUpdate(
			$t, $po, 'page_props', $fields, [ 'pp_page' => self::$testingPageId ], $expected );

		$expectedAssoc = [];
		foreach ( $expected as [ $name, $value ] ) {
			$expectedAssoc[$name] = $value;
		}
		$this->assertArrayEquals( $expectedAssoc, $update->getAddedProperties() );
		$this->assertArrayEquals(
			[
				'changed' => '1',
				'deleted' => '1'
			],
			$update->getRemovedProperties()
		);
	}

	public function testUpdate_existencelinks() {
		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addExistenceDependency( Title::newFromText( "Foo" ) );
		$po->addExistenceDependency( Title::newFromText( "Bar" ) );
		$po->addExistenceDependency( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addExistenceDependency( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addExistenceDependency( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'existencelinks',
			[ 'lt_namespace', 'lt_title' ],
			[ 'exl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Foo' ],
			]
		);
		$this->assertArrayEquals( [
			[ NS_MAIN, 'Foo' ],
			[ NS_MAIN, 'Bar' ],
		], array_map(
			static function ( PageReference $pageReference ) {
				return [ $pageReference->getNamespace(), $pageReference->getDbKey() ];
			},
			$update->getPageReferenceArray( 'existencelinks', LinksTable::INSERTED )
		) );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );
		$po->addExistenceDependency( Title::newFromText( "Bar" ) );
		$po->addExistenceDependency( Title::newFromText( "Baz" ) );
		$po->addExistenceDependency( Title::newFromText( "Talk:Baz" ) );

		$this->assertLinksUpdate(
			$t,
			$po,
			'existencelinks',
			[ 'lt_namespace', 'lt_title' ],
			[ 'exl_from' => self::$testingPageId ],
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Baz' ],
				[ NS_TALK, 'Baz' ],
			]
		);
	}

	// @todo test recursive, too!

	protected function assertLinksUpdate( Title $title, ParserOutput $parserOutput,
		$table, $fields, $condition, array $expectedRows
	) {
		return $this->assertMoveLinksUpdate( $title, null, $parserOutput,
			$table, $fields, $condition, $expectedRows );
	}

	protected function assertMoveLinksUpdate(
		Title $title, ?PageIdentityValue $oldTitle, ParserOutput $parserOutput,
		$table, $fields, $condition, array $expectedRows
	) {
		$update = new LinksUpdate( $title, $parserOutput );
		$update->setStrictTestMode();
		if ( $oldTitle ) {
			$update->setMoveDetails( $oldTitle );
		}
		$this->setTransactionTicket( $update );

		$update->doUpdate();

		$qb = $this->newSelectQueryBuilder()
			->select( $fields )
			->from( $table )
			->where( $condition );
		if ( $table === 'pagelinks' ) {
			$qb->join( 'linktarget', null, 'pl_target_id=lt_id' );
		} elseif ( $table === 'existencelinks' ) {
			$qb->join( 'linktarget', null, 'exl_target_id=lt_id' );
		}
		$qb->assertResultSet( $expectedRows );
		return $update;
	}

	protected function assertRecentChangeByCategorization(
		Title $categoryTitle, $expectedRows
	) {
		$this->newSelectQueryBuilder()
			->select( [ 'rc_title', 'comment_text' ] )
			->from( 'recentchanges' )
			->join( 'comment', null, 'comment_id = rc_comment_id' )
			->where( [
				'rc_type' => RC_CATEGORIZE,
				'rc_namespace' => NS_CATEGORY,
				'rc_title' => $categoryTitle->getDBkey(),
			] )
			->assertResultSet( $expectedRows );
	}

	private function runAllRelatedJobs() {
		$queueGroup = $this->getServiceContainer()->getJobQueueGroup();
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		while ( $job = $queueGroup->pop( 'refreshLinksPrioritized' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		while ( $job = $queueGroup->pop( 'categoryMembershipChange' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
	}

	public function testIsRecursive() {
		[ $title, $po ] = $this->makeTitleAndParserOutput( 'Test', 1 );
		$linksUpdate = new LinksUpdate( $title, $po );
		$this->assertTrue( $linksUpdate->isRecursive(), 'LinksUpdate is recursive by default' );

		$linksUpdate = new LinksUpdate( $title, $po, true );
		$this->assertTrue( $linksUpdate->isRecursive(),
			'LinksUpdate is recursive when asked to be recursive' );

		$linksUpdate = new LinksUpdate( $title, $po, false );
		$this->assertFalse( $linksUpdate->isRecursive(),
			'LinksUpdate is not recursive when asked to be not recursive' );
	}

	/**
	 * Confirm that repeatedly saving the same ParserOutput does not lead to
	 * DELETE/INSERT queries (T299662)
	 */
	public function testNullEdit() {
		$setNumericPageProperty = 'setNumericPageProperty';
		$setUnsortedPageProperty = 'setUnsortedPageProperty';

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addCategory( 'Test', 'Test' );
		$po->addExternalLink( 'http://www.example.com/' );
		$po->addImage( new TitleValue( NS_FILE, 'Test' ) );
		$po->addInterwikiLink( new TitleValue( 0, 'test', '', 'test' ) );
		$po->addLanguageLink( new TitleValue( 0, 'Test', '', 'en' ) );
		$po->addLink( new TitleValue( 0, 'Test' ) );
		$po->$setUnsortedPageProperty( 'string', 'x' );
		$po->$setUnsortedPageProperty( 'numeric-string', '1' );
		$po->$setNumericPageProperty( 'int', 10 );
		$po->$setNumericPageProperty( 'float', 2 / 3 );
		$po->$setUnsortedPageProperty( 'null', '' );

		$update = new LinksUpdate( $t, $po );
		$update->setStrictTestMode();
		$this->setTransactionTicket( $update );
		$update->doUpdate();

		$time1 = $this->getDb()->lastDoneWrites();
		$this->assertGreaterThan( 0, $time1 );

		$update = new class( $t, $po ) extends LinksUpdate {
			protected function updateLinksTimestamp() {
				// Updating the timestamp is allowed, ignore
			}
		};
		$update->setStrictTestMode();
		$update->doUpdate();
		$time2 = $this->getDb()->lastDoneWrites();
		$this->assertSame( $time1, $time2 );
	}

	public static function provideNumericKeys() {
		$tables = TestingAccessWrapper::constant( LinksTableGroup::class, 'CORE_LIST' );
		foreach ( $tables as $tableName => $spec ) {
			yield [ $tableName ];
		}
	}

	/**
	 * Unit test for numeric strings in ParserOutput array keys (T301433)
	 *
	 * @dataProvider provideNumericKeys
	 */
	public function testNumericKeys( $tableName ) {
		$s = '123';
		$i = 123;

		/** @var ParserOutput $po */
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addCategory( $s, $s );
		$po->addExternalLink( 'https://foo.com' );
		$po->addImage( new TitleValue( NS_FILE, $s ) );
		$po->addInterwikiLink( new TitleValue( 0, $s, '', $s ) );
		$po->addLanguageLink( new TitleValue( 0, $s, '', $s ) );
		$po->addLink( new TitleValue( 0, $s ) );
		$po->setUnsortedPageProperty( $s, $s );
		$po->addTemplate( new TitleValue( 0, $s ), 1, 1 );
		$po->addExistenceDependency( new TitleValue( 0, $s ) );

		$update = new LinksUpdate( $t, $po );
		/** @var LinksTableGroup $tg */
		$tg = TestingAccessWrapper::newFromObject( $update )->tableFactory;
		$table = $tg->get( $tableName );
		/** @var LinksTable $tt */
		$tt = TestingAccessWrapper::newFromObject( $table );
		$tableName = $tt->getTableName();
		foreach ( $tt->getNewLinkIDs() as $linkID ) {
			foreach ( (array)$linkID as $component ) {
				$this->assertNotSame( $i, $component,
					"Link ID of table $tableName should not be an integer " );
			}
		}
	}

	/**
	 * Integration test for numeric category names (T301433)
	 */
	public function testNumericCategory() {
		[ $t, $po ] = $this->makeTitleAndParserOutput( "Test 1", self::$testingPageId + 1 );
		$po->addCategory( '123a', '123a' );
		$update = new LinksUpdate( $t, $po );
		$this->setTransactionTicket( $update );
		$update->setStrictTestMode();
		$update->doUpdate();

		[ $t, $po ] = $this->makeTitleAndParserOutput( "Test 2", self::$testingPageId + 2 );
		$po->addCategory( '123', '123' );
		$update = new LinksUpdate( $t, $po );
		$this->setTransactionTicket( $update );
		$update->setStrictTestMode();
		$update->doUpdate();

		$this->newSelectQueryBuilder()
			->select( 'cat_pages' )
			->from( 'category' )
			->where( [ 'cat_title' => '123a' ] )
			->assertFieldValue( '1' );
	}

	private function setTransactionTicket( LinksUpdate $update ) {
		$update->setTransactionTicket(
			$this->getServiceContainer()->getConnectionProvider()->getEmptyTransactionTicket( __METHOD__ )
		);
	}
}
