<?php

/**
 * @group ContentHandler
 *
 * @note: Declare that we are using the database, because otherwise we'll fail in the "databaseless" test run.
 * This is because the LinkHolderArray used by the parser needs database access.
 *
 * @group Database
 */
class ContentHandlerTest extends MediaWikiTestCase {

	public function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		$wgExtraNamespaces[ 12312 ] = 'Dummy';
		$wgExtraNamespaces[ 12313 ] = 'Dummy_talk';

		$wgNamespaceContentModels[ 12312 ] = 999999;
		$wgContentHandlers[ 999999 ] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	public function tearDown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		unset( $wgExtraNamespaces[ 12312 ] );
		unset( $wgExtraNamespaces[ 12313 ] );

		unset( $wgNamespaceContentModels[ 12312 ] );
		unset( $wgContentHandlers[ 999999 ] );

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	public function dataGetDefaultModelFor() {
		return array(
			array( 'Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'Foo/bar.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'User:Foo/bar.css', CONTENT_MODEL_CSS ),
			array( 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ),
			array( 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ),
		);
	}

	/**
	 * @dataProvider dataGetDefaultModelFor
	 */
	public function testGetDefaultModelFor( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedModelId, ContentHandler::getDefaultModelFor( $title ) );
	}
	/**
	 * @dataProvider dataGetDefaultModelFor
	 */
	public function testGetForTitle( $title, $expectedContentModel ) {
		$title = Title::newFromText( $title );
		$handler = ContentHandler::getForTitle( $title );
		$this->assertEquals( $expectedContentModel, $handler->getModelID() );
	}

	public function dataGetContentFormatMimeType( ) {
		return array(
			array( 0, null ),
			array( null, null ),
			array( 99887766, null ),

			array( CONTENT_FORMAT_WIKITEXT, 'text/x-wiki' ),
			array( CONTENT_FORMAT_JAVASCRIPT, 'text/javascript' ),
			array( CONTENT_FORMAT_CSS, 'text/css' ),
			array( CONTENT_FORMAT_JSON, 'application/json' ),
			array( CONTENT_FORMAT_XML, 'application/xml' ),
			array( CONTENT_FORMAT_SERIALIZED, 'application/vnd.php.serialized' ),
		);
	}

	/**
	 * @dataProvider dataGetContentFormatMimeType
	 */
	public function testGetContentFormatMimeType( $id, $expectedMime ) {
		$mime = ContentHandler::getContentFormatMimeType( $id );

		$this->assertEquals( $expectedMime, $mime );
	}

	public function dataGetContentFormatID( ) {
		return array(
			array( '', null ),
			array( 'foo', null ),
			array( null, null ),

			array( 'text/x-wiki', CONTENT_FORMAT_WIKITEXT ),
			array( 'text/javascript', CONTENT_FORMAT_JAVASCRIPT ),
			array( 'text/css', CONTENT_FORMAT_CSS ),
			array( 'application/json', CONTENT_FORMAT_JSON ),
			array( 'application/xml', CONTENT_FORMAT_XML ),
			array( 'application/vnd.php.serialized', CONTENT_FORMAT_SERIALIZED ),
		);
	}

	/**
	 * @dataProvider dataGetContentFormatID
	 */
	public function testGetContentFormatID( $mime, $expectedId ) {
		$id = ContentHandler::getContentFormatID( $mime );

		$this->assertEquals( $expectedId, $id );
	}

	public function dataGetLocalizedNameName() {
		return array(
			array( 0, null ),
			array( null, null ),
			array( 99887766, null ),

			array( CONTENT_MODEL_JAVASCRIPT, '/javascript/i' ), //XXX: depends on content language
		);
	}

	/**
	 * @dataProvider dataGetLocalizedNameName
	 */
	public function testGetLocalizedName( $id, $expected ) {
		try{
			$name = ContentHandler::getLocalizedName( $id );

			if ( !$expected ) $this->fail("should not have a name for content id #$id");

			$this->assertNotNull( $name, "no name found for content model #$id" );
			$this->assertTrue( preg_match( $expected, $name ) > 0 , "content model name for #$id did not match pattern $expected" );
		} catch (MWException $e) {
			if ( $expected ) $this->fail("failed to get name for content id #$id");
		}
	}

	public function dataGetContentModelName() {
		return array(
			array( 0, null ),
			array( null, null ),
			array( 99887766, null ),

			array( CONTENT_MODEL_JAVASCRIPT, 'javascript' ),
		);
	}

	/**
	 * @dataProvider dataGetContentModelName
	 */
	public function testGetContentModelName( $id, $expected ) {
		try {
			$name = ContentHandler::getContentModelName( $id );

			if ( !$expected ) $this->fail("should not have a name for content id #$id");

			$this->assertNotNull( $name, "no name found for content model #$id" );
			$this->assertEquals( $expected, $name);
		} catch (MWException $e) {
			if ( $expected ) $this->fail("failed to get name for content id #$id");
		}
	}

	/**
	 * @dataProvider dataGetContentModelName
	 */
	public function testGetModelName( $id, $expected ) {
		try {
			$handler = ContentHandler::getForModelID( $id );
			$name = $handler->getModelName();

			if ( !$expected ) $this->fail("should not have a name for content id #$id");

			$this->assertNotNull( $name, "no name found for content model #$id" );
			$this->assertEquals( $expected, $name);
		} catch (MWException $e) {
			if ( $expected ) $this->fail("failed to get name for content id #$id");
		}
	}

	public function testGetContentText_Null( ) {
		global $wgContentHandlerTextFallback;

		$content = null;

		$wgContentHandlerTextFallback = 'fail';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( '', $text );

		$wgContentHandlerTextFallback = 'serialize';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( '', $text );

		$wgContentHandlerTextFallback = 'ignore';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( '', $text );
	}

	public function testGetContentText_TextContent( ) {
		global $wgContentHandlerTextFallback;

		$content = new WikitextContent( "hello world" );

		$wgContentHandlerTextFallback = 'fail';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->getNativeData(), $text );

		$wgContentHandlerTextFallback = 'serialize';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->serialize(), $text );

		$wgContentHandlerTextFallback = 'ignore';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->getNativeData(), $text );
	}

	public function testGetContentText_NonTextContent( ) {
		global $wgContentHandlerTextFallback;

		$content = new DummyContentForTesting( "hello world" );

		$wgContentHandlerTextFallback = 'fail';

		try {
			$text = ContentHandler::getContentText( $content );

			$this->fail( "ContentHandler::getContentText should have thrown an exception for non-text Content object" );
		} catch (MWException $ex) {
			// as expected
		}

		$wgContentHandlerTextFallback = 'serialize';
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->serialize(), $text );

		$wgContentHandlerTextFallback = 'ignore';
		$text = ContentHandler::getContentText( $content );
		$this->assertNull( $text );
	}

	#public static function makeContent( $text, Title $title, $modelId = null, $format = null )

	public function dataMakeContent() {
		return array(
			array( 'hallo', 'Test', null, null, CONTENT_MODEL_WIKITEXT, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', null, null, CONTENT_MODEL_JAVASCRIPT, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', null, null, 999999, 'hallo', false ),

			array( 'hallo', 'Test', null, CONTENT_FORMAT_WIKITEXT, CONTENT_MODEL_WIKITEXT, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', null, CONTENT_FORMAT_JAVASCRIPT, CONTENT_MODEL_JAVASCRIPT, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', null, 999999, 999999, 'hallo', false ),

			array( 'hallo', 'Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, serialize('hallo'), false ),

			array( 'hallo', 'Test', CONTENT_MODEL_WIKITEXT, 999999, null, null, true ),
			array( 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, 999999, null, null, true ),
			array( 'hallo', 'Dummy:Test', CONTENT_MODEL_JAVASCRIPT, 999999, null, null, true ),
		);
	}

	/**
	 * @dataProvider dataMakeContent
	 */
	public function testMakeContent( $data, $title, $modelId, $format, $expectedModelId, $expectedNativeData, $shouldFail ) {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers;

		$title = Title::newFromText( $title );

		try {
			$content = ContentHandler::makeContent( $data, $title, $modelId, $format );

			if ( $shouldFail ) $this->fail( "ContentHandler::makeContent should have failed!" );

			$this->assertEquals( $expectedModelId, $content->getModel(), 'bad model id' );
			$this->assertEquals( $expectedNativeData, $content->getNativeData(), 'bads native data' );
		} catch ( MWException $ex ) {
			if ( !$shouldFail ) $this->fail( "ContentHandler::makeContent failed unexpectedly: " . $ex->getMessage() );
			else $this->assertTrue( true ); // dummy, so we don't get the "test did not perform any assertions" message.
		}

	}

	public function dataGetParserOutput() {
		return array(
			array("ContentHandlerTest_testGetParserOutput", "hello ''world''\n", "<p>hello <i>world</i>\n</p>"),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetParserOutput
	 */
	public function testGetParserOutput( $title, $text, $expectedHtml ) {
		$title = Title::newFromText( $title );
		$handler = ContentHandler::getForModelID( $title->getContentModel() );
		$content = ContentHandler::makeContent( $text, $title );

		$po = $handler->getParserOutput( $content, $title );

		$this->assertEquals( $expectedHtml, $po->getText() );
		// @todo: assert more properties
	}

	public function dataGetSecondaryDataUpdates() {
		return array(
			array("ContentHandlerTest_testGetSecondaryDataUpdates_1", "hello ''world''\n",
				array( 'LinksUpdate' => array(  'mRecursive' => true,
				                                'mLinks' => array() ) )
			),
			array("ContentHandlerTest_testGetSecondaryDataUpdates_2", "hello [[world test 21344]]\n",
				array( 'LinksUpdate' => array(  'mRecursive' => true,
				                                'mLinks' => array( array( 'World_test_21344' => 0 ) ) ) )
			),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates( $title, $text, $expectedStuff ) {
		$title = Title::newFromText( $title );
		$title->resetArticleID( 2342 ); //dummy id. fine as long as we don't try to execute the updates!

		$handler = ContentHandler::getForModelID( $title->getContentModel() );
		$content = ContentHandler::makeContent( $text, $title );

		$updates = $handler->getSecondaryDataUpdates( $content, $title );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[ $class ] = $update;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[ $class ];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; #if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}
	}

	public function dataGetDeletionUpdates() {
		return array(
			array("ContentHandlerTest_testGetSecondaryDataUpdates_1", "hello ''world''\n",
				array( 'LinksDeletionUpdate' => array( ) )
			),
			array("ContentHandlerTest_testGetSecondaryDataUpdates_2", "hello [[world test 21344]]\n",
				array( 'LinksDeletionUpdate' => array( ) )
			),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetDeletionUpdates
	 */
	public function testDeletionUpdates( $title, $text, $expectedStuff ) {
		$title = Title::newFromText( $title );
		$title->resetArticleID( 2342 ); //dummy id. fine as long as we don't try to execute the updates!

		$handler = ContentHandler::getForModelID( $title->getContentModel() );
		$content = ContentHandler::makeContent( $text, $title );

		$updates = $handler->getDeletionUpdates( $content, $title );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[ $class ] = $update;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[ $class ];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; #if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}
	}

	public function testSupportsSections() {
		$this->markTestIncomplete( "not yet implemented" );
	}
}

class DummyContentHandlerForTesting extends ContentHandler {

	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, array( 999999 ) );
	}

	/**
	 * Serializes Content object of the type supported by this ContentHandler.
	 *
	 * @param Content $content the Content object to serialize
	 * @param null $format the desired serialization format
	 * @return String serialized form of the content
	 */
	public function serializeContent( Content $content, $format = null )
	{
	   return $content->serialize();
	}

	/**
	 * Unserializes a Content object of the type supported by this ContentHandler.
	 *
	 * @param $blob String serialized form of the content
	 * @param null $format the format used for serialization
	 * @return Content the Content object created by deserializing $blob
	 */
	public function unserializeContent( $blob, $format = null )
	{
		$d = unserialize( $blob );
		return new DummyContentForTesting( $d );
	}

	/**
	 * Creates an empty Content object of the type supported by this ContentHandler.
	 *
	 */
	public function makeEmptyContent()
	{
		return new DummyContentForTesting( '' );
	}

	/**
	 * @param Content $content
	 * @param Title $title
	 * @param null $revId
	 * @param null|ParserOptions $options
	 * @param Boolean $generateHtml whether to generate Html (default: true). If false,
	 *        the result of calling getText() on the ParserOutput object returned by
	 *        this method is undefined.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( Content $content, Title $title, $revId = null, ParserOptions $options = NULL, $generateHtml = true )
	{
		return new ParserOutput( $content->getNativeData() );
	}
}

class DummyContentForTesting extends AbstractContent {

	public function __construct( $data ) {
		parent::__construct( 999999 );

		$this->data = $data;
	}

	public function serialize( $format = null ) {
		return serialize( $this->data );
	}

	/**
	 * @return String a string representing the content in a way useful for building a full text search index.
	 *         If no useful representation exists, this method returns an empty string.
	 */
	public function getTextForSearchIndex()
	{
		return '';
	}

	/**
	 * @return String the wikitext to include when another page includes this  content, or false if the content is not
	 *         includable in a wikitext page.
	 */
	public function getWikitextForTransclusion()
	{
		return false;
	}

	/**
	 * Returns a textual representation of the content suitable for use in edit summaries and log messages.
	 *
	 * @param int $maxlength maximum length of the summary text
	 * @return String the summary text
	 */
	public function getTextForSummary( $maxlength = 250 )
	{
		return '';
	}

	/**
	 * Returns native represenation of the data. Interpretation depends on the data model used,
	 * as given by getDataModel().
	 *
	 * @return mixed the native representation of the content. Could be a string, a nested array
	 *         structure, an object, a binary blob... anything, really.
	 */
	public function getNativeData()
	{
		return $this->data;
	}

	/**
	 * returns the content's nominal size in bogo-bytes.
	 *
	 * @return int
	 */
	public function getSize()
	{
		return strlen( $this->data );
	}

	/**
	 * Return a copy of this Content object. The following must be true for the object returned
	 * if $copy = $original->copy()
	 *
	 * * get_class($original) === get_class($copy)
	 * * $original->getModel() === $copy->getModel()
	 * * $original->equals( $copy )
	 *
	 * If and only if the Content object is imutable, the copy() method can and should
	 * return $this. That is,  $copy === $original may be true, but only for imutable content
	 * objects.
	 *
	 * @return Content. A copy of this object
	 */
	public function copy()
	{
		return $this;
	}

	/**
	 * Returns true if this content is countable as a "real" wiki page, provided
	 * that it's also in a countable location (e.g. a current revision in the main namespace).
	 *
	 * @param $hasLinks Bool: if it is known whether this content contains links, provide this information here,
	 *                        to avoid redundant parsing to find out.
	 * @return boolean
	 */
	public function isCountable( $hasLinks = null )
	{
		return false;
	}
}

