<?php

/**
 * @group Database
 * ^--- make sure temporary tables are used.
 */
class LinksUpdateTest extends MediaWikiTestCase {

	function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge( $this->tablesUsed,
			array(
				'interwiki',
				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks'
			)
		);
	}

	protected function setUp() {
		parent::setUp();
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'interwiki',
			array( 'iw_prefix' ),
			array(
				'iw_prefix' => 'linksupdatetest',
				'iw_url' => 'http://testing.com/wiki/$1',
				'iw_api' => 'http://testing.com/w/api.php',
				'iw_local' => 0,
				'iw_trans' => 0,
				'iw_wikiid' => 'linksupdatetest',
			)
		);
	}

	protected function makeTitleAndParserOutput( $name, $id ) {
		$t = Title::newFromText( $name );
		$t->mArticleID = $id; # XXX: this is fugly

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		return array( $t, $po );
	}

	/**
	 * @covers ParserOutput::addLink
	 */
	public function testUpdate_pagelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addLink( Title::newFromText( "Foo" ) );
		$po->addLink( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addLink( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addLink( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$update = $this->assertLinksUpdate( $t, $po, 'pagelinks', 'pl_namespace, pl_title', 'pl_from = 111', array(
			array( NS_MAIN, 'Foo' ),
		) );
		$this->assertArrayEquals( array(
			Title::makeTitle( NS_MAIN, 'Foo' ),  // newFromText doesn't yield the same internal state....
		), $update->getAddedLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Talk:Bar" ) );

		$update = $this->assertLinksUpdate( $t, $po, 'pagelinks', 'pl_namespace, pl_title', 'pl_from = 111', array(
			array( NS_MAIN, 'Bar' ),
			array( NS_TALK, 'Bar' ),
		) );
		$this->assertArrayEquals( array(
			Title::makeTitle( NS_MAIN, 'Bar' ),
			Title::makeTitle( NS_TALK, 'Bar' ),
		), $update->getAddedLinks() );
		$this->assertArrayEquals( array(
			Title::makeTitle( NS_MAIN, 'Foo' ),
		), $update->getRemovedLinks() );
	}

	/**
	 * @covers ParserOutput::addExternalLink
	 */
	public function testUpdate_externallinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addExternalLink( "http://testing.com/wiki/Foo" );

		$this->assertLinksUpdate( $t, $po, 'externallinks', 'el_to, el_index', 'el_from = 111', array(
			array( 'http://testing.com/wiki/Foo', 'http://com.testing./wiki/Foo' ),
		) );
	}

	/**
	 * @covers ParserOutput::addCategory
	 */
	public function testUpdate_categorylinks() {
		/** @var ParserOutput $po */
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addCategory( "Foo", "FOO" );

		$this->assertLinksUpdate( $t, $po, 'categorylinks', 'cl_to, cl_sortkey', 'cl_from = 111', array(
			array( 'Foo', "FOO\nTESTING" ),
		) );
	}

	/**
	 * @covers ParserOutput::addInterwikiLink
	 */
	public function testUpdate_iwlinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$target = Title::makeTitleSafe( NS_MAIN, "Foo", '', 'linksupdatetest' );
		$po->addInterwikiLink( $target );

		$this->assertLinksUpdate( $t, $po, 'iwlinks', 'iwl_prefix, iwl_title', 'iwl_from = 111', array(
			array( 'linksupdatetest', 'Foo' ),
		) );
	}

	/**
	 * @covers ParserOutput::addTemplate
	 */
	public function testUpdate_templatelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addTemplate( Title::newFromText( "Template:Foo" ), 23, 42 );

		$this->assertLinksUpdate( $t, $po, 'templatelinks', 'tl_namespace, tl_title', 'tl_from = 111', array(
			array( NS_TEMPLATE, 'Foo' ),
		) );
	}

	/**
	 * @covers ParserOutput::addImage
	 */
	public function testUpdate_imagelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addImage( "Foo.png" );

		$this->assertLinksUpdate( $t, $po, 'imagelinks', 'il_to', 'il_from = 111', array(
			array( 'Foo.png' ),
		) );
	}

	/**
	 * @covers ParserOutput::addLanguageLink
	 */
	public function testUpdate_langlinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addLanguageLink( Title::newFromText( "en:Foo" )->getFullText() );

		$this->assertLinksUpdate( $t, $po, 'langlinks', 'll_lang, ll_title', 'll_from = 111', array(
			array( 'En', 'Foo' ),
		) );
	}

	/**
	 * @covers ParserOutput::setProperty
	 */
	public function testUpdate_page_props() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->setProperty( "foo", "bar" );

		$this->assertLinksUpdate( $t, $po, 'page_props', 'pp_propname, pp_value', 'pp_page = 111', array(
			array( 'foo', 'bar' ),
		) );
	}

	// @todo test recursive, too!

	protected function assertLinksUpdate( Title $title, ParserOutput $parserOutput, $table, $fields, $condition, array $expectedRows ) {
		$update = new LinksUpdate( $title, $parserOutput );

		//NOTE: make sure LinksUpdate does not generate warnings when called inside a transaction.
		$update->beginTransaction();
		$update->doUpdate();
		$update->commitTransaction();

		$this->assertSelect( $table, $fields, $condition, $expectedRows );
		return $update;
	}
}
