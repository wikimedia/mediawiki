<?php

/**
 *
 * @group Database
 * ^--- make sure temporary tables are used.
 */
class LinksUpdateTest extends MediaWikiTestCase {

	function  __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed = array_merge ( $this->tablesUsed,
											array( 'interwiki',

												'page_props',
												'pagelinks',
												'categorylinks',
												'langlinks',
												'externallinks',
												'imagelinks',
												'templatelinks',
												'iwlinks' ) );
	}

	function setUp() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'interwiki',
						array('iw_prefix'),
						array( 'iw_prefix' => 'linksupdatetest',
						       'iw_url' => 'http://testing.com/wiki/$1',
						       'iw_api' => 'http://testing.com/w/api.php',
						       'iw_local' => 0,
						       'iw_trans' => 0,
						       'iw_wikiid' => 'linksupdatetest',
						) );
	}

	protected function makeTitleAndParserOutput( $name, $id ) {
		$t = Title::newFromText( $name );
		$t->mArticleID = $id; # XXX: this is fugly

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		return array( $t, $po );
	}

	public function testUpdate_pagelinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addLink( Title::newFromText( "Foo" ) );
		$po->addLink( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addLink( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addLink( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$this->assertLinksUpdate( $t, $po, 'pagelinks', 'pl_namespace, pl_title', 'pl_from = 111', array(
			array( NS_MAIN, 'Foo' ),
		) );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		$po->addLink( Title::newFromText( "Bar" ) );

		$this->assertLinksUpdate( $t, $po, 'pagelinks', 'pl_namespace, pl_title', 'pl_from = 111', array(
			array( NS_MAIN, 'Bar' ),
		) );
	}

	public function testUpdate_externallinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addExternalLink( "http://testing.com/wiki/Foo" );

		$this->assertLinksUpdate( $t, $po, 'externallinks', 'el_to, el_index', 'el_from = 111', array(
			array( 'http://testing.com/wiki/Foo', 'http://com.testing./wiki/Foo' ),
		) );
	}

	public function testUpdate_categorylinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addCategory( "Foo", "FOO" );

		$this->assertLinksUpdate( $t, $po, 'categorylinks', 'cl_to, cl_sortkey', 'cl_from = 111', array(
			array( 'Foo', "FOO\nTESTING" ),
		) );
	}

	public function testUpdate_iwlinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$target = Title::makeTitleSafe( NS_MAIN, "Foo", '', 'linksupdatetest' );
		$po->addInterwikiLink( $target );

		$this->assertLinksUpdate( $t, $po, 'iwlinks', 'iwl_prefix, iwl_title', 'iwl_from = 111', array(
			array( 'linksupdatetest', 'Foo' ),
		) );
	}

	public function testUpdate_templatelinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addTemplate( Title::newFromText( "Template:Foo" ), 23, 42 );

		$this->assertLinksUpdate( $t, $po, 'templatelinks', 'tl_namespace, tl_title', 'tl_from = 111', array(
			array( NS_TEMPLATE, 'Foo' ),
		) );
	}

	public function testUpdate_imagelinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addImage( "Foo.png" );


		$this->assertLinksUpdate( $t, $po, 'imagelinks', 'il_to', 'il_from = 111', array(
			array( 'Foo.png' ),
		) );
	}

	public function testUpdate_langlinks() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->addLanguageLink( Title::newFromText( "en:Foo" ) );


		$this->assertLinksUpdate( $t, $po, 'langlinks', 'll_lang, ll_title', 'll_from = 111', array(
			array( 'En', 'Foo' ),
		) );
	}

	public function testUpdate_page_props() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", 111 );

		$po->setProperty( "foo", "bar" );

		$this->assertLinksUpdate( $t, $po, 'page_props', 'pp_propname, pp_value', 'pp_page = 111', array(
			array( 'foo', 'bar' ),
		) );
	}

	#@todo: test recursive, too!

	protected function assertLinksUpdate( Title $title, ParserOutput $parserOutput, $table, $fields, $condition, Array $expectedRows ) {
		$update = new LinksUpdate( $title, $parserOutput );

		$update->doUpdate();

		$this->assertSelect( $table, $fields, $condition, $expectedRows );
	}
}

