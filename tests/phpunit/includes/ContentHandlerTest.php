<?php

class ContentHandlerTest extends MediaWikiTestCase {

	public function setup() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		$wgExtraNamespaces[ 12312 ] = 'Dummy';
		$wgExtraNamespaces[ 12313 ] = 'Dummy_talk';

		$wgNamespaceContentModels[ 12312 ] = 'DUMMY';
		$wgContentHandlers[ 'DUMMY' ] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	public function teardown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		unset( $wgExtraNamespaces[ 12312 ] );
		unset( $wgExtraNamespaces[ 12313 ] );

		unset( $wgNamespaceContentModels[ 12312 ] );
		unset( $wgContentHandlers[ 'DUMMY' ] );

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
	public function testGetDefaultModelFor( $title, $expectedModelName ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedModelName, ContentHandler::getDefaultModelFor( $title ) );
	}
	/**
	 * @dataProvider dataGetDefaultModelFor
	 */
	public function testGetForTitle( $title, $expectedContentModel ) {
		$title = Title::newFromText( $title );
		$handler = ContentHandler::getForTitle( $title );
		$this->assertEquals( $expectedContentModel, $handler->getModelName() );
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

	#public static function makeContent( $text, Title $title, $modelName = null, $format = null )

	public function dataMakeContent() {
		return array(
			array( 'hallo', 'Test', null, null, CONTENT_MODEL_WIKITEXT, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', null, null, CONTENT_MODEL_JAVASCRIPT, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', null, null, 'DUMMY', 'hallo', false ),

			array( 'hallo', 'Test', null, 'text/x-wiki', CONTENT_MODEL_WIKITEXT, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', null, 'text/javascript', CONTENT_MODEL_JAVASCRIPT, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', null, 'dummy', 'DUMMY', 'hallo', false ),

			array( 'hallo', 'Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, 'hallo', false ),
			array( serialize('hallo'), 'Dummy:Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, serialize('hallo'), false ),

			array( 'hallo', 'Test', CONTENT_MODEL_WIKITEXT, 'dummy', null, null, true ),
			array( 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, 'dummy', null, null, true ),
			array( 'hallo', 'Dummy:Test', CONTENT_MODEL_JAVASCRIPT, 'dummy', null, null, true ),
		);
	}

	/**
	 * @dataProvider dataMakeContent
	 */
	public function testMakeContent( $data, $title, $modelName, $format, $expectedModelName, $expectedNativeData, $shouldFail ) {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers;

		$title = Title::newFromText( $title );

		try {
			$content = ContentHandler::makeContent( $data, $title, $modelName, $format );

			if ( $shouldFail ) $this->fail( "ContentHandler::makeContent should have failed!" );

			$this->assertEquals( $expectedModelName, $content->getModelName(), 'bad model name' );
			$this->assertEquals( $expectedNativeData, $content->getNativeData(), 'bads native data' );
		} catch ( MWException $ex ) {
			if ( !$shouldFail ) $this->fail( "ContentHandler::makeContent failed unexpectedly!" );
			else $this->assertTrue( true ); // dummy, so we don't get the "test did not perform any assertions" message.
		}

	}


}

class DummyContentHandlerForTesting extends ContentHandler {

	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, array('dummy') );
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
}

class DummyContentForTesting extends Content {

	public function __construct( $data ) {
		parent::__construct( "DUMMY" );

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
		return 23;
	}

	/**
	 * Return a copy of this Content object. The following must be true for the object returned
	 * if $copy = $original->copy()
	 *
	 * * get_class($original) === get_class($copy)
	 * * $original->getModelName() === $copy->getModelName()
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

	/**
	 * @param IContextSource $context
	 * @param null $revId
	 * @param null|ParserOptions $options
	 * @param Boolean $generateHtml whether to generate Html (default: true). If false,
	 *        the result of calling getText() on the ParserOutput object returned by
	 *        this method is undefined.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( IContextSource $context, $revId = null, ParserOptions $options = NULL, $generateHtml = true )
	{
		return new ParserOutput( $this->data );
	}
}

