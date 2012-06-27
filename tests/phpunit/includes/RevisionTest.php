<?php

/**
 * @group ContentHandler
 */
class RevisionTest extends MediaWikiTestCase {
	var $saveGlobals = array();

	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );

		$globalSet = array(
			'wgLegacyEncoding' => false,
			'wgCompressRevisions' => false,

			'wgContentHandlerTextFallback' => $GLOBALS['wgContentHandlerTextFallback'],
			'wgExtraNamespaces' => $GLOBALS['wgExtraNamespaces'],
			'wgNamespaceContentModels' => $GLOBALS['wgNamespaceContentModels'],
			'wgContentHandlers' => $GLOBALS['wgContentHandlers'],
		);

		foreach ( $globalSet as $var => $data ) {
			$this->saveGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $data;
		}

		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;
		$wgExtraNamespaces[ 12312 ] = 'Dummy';
		$wgExtraNamespaces[ 12313 ] = 'Dummy_talk';

		$wgNamespaceContentModels[ 12312 ] = "testing";
		$wgContentHandlers[ "testing" ] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		global $wgContentHandlerTextFallback;
		$wgContentHandlerTextFallback = 'ignore';
	}

	function tearDown() {
		global $wgContLang;

		foreach ( $this->saveGlobals as $var => $data ) {
			$GLOBALS[$var] = $data;
		}

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	function testGetRevisionText() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = 'This is a bunch of revision text.';
		$this->assertEquals(
			'This is a bunch of revision text.',
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextGzip() {
		if ( !function_exists( 'gzdeflate' ) ) {
			$this->markTestSkipped( 'Gzip compression is not enabled (requires zlib).' );
		} else {
			$row = new stdClass;
			$row->old_flags = 'gzip';
			$row->old_text = gzdeflate( 'This is a bunch of revision text.' );
			$this->assertEquals(
				'This is a bunch of revision text.',
				Revision::getRevisionText( $row ) );
		}
	}

	function testGetRevisionTextUtf8Native() {
		$row = new stdClass;
		$row->old_flags = 'utf-8';
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8Legacy() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = "Wiki est l'\xe9cole superieur !";
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8NativeGzip() {
		if ( !function_exists( 'gzdeflate' ) ) {
			$this->markTestSkipped( 'Gzip compression is not enabled (requires zlib).' );
		} else {
			$row = new stdClass;
			$row->old_flags = 'gzip,utf-8';
			$row->old_text = gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" );
			$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
			$this->assertEquals(
				"Wiki est l'\xc3\xa9cole superieur !",
				Revision::getRevisionText( $row ) );
		}
	}

	function testGetRevisionTextUtf8LegacyGzip() {
		if ( !function_exists( 'gzdeflate' ) ) {
			$this->markTestSkipped( 'Gzip compression is not enabled (requires zlib).' );
		} else {
			$row = new stdClass;
			$row->old_flags = 'gzip';
			$row->old_text = gzdeflate( "Wiki est l'\xe9cole superieur !" );
			$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
			$this->assertEquals(
				"Wiki est l'\xc3\xa9cole superieur !",
				Revision::getRevisionText( $row ) );
		}
	}

	function testCompressRevisionTextUtf8() {
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	function testCompressRevisionTextUtf8Gzip() {
		$GLOBALS['wgCompressRevisions'] = true;
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertTrue( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	# =================================================================================================================

	/**
	 * @param string $text
	 * @param string $title
	 * @param string $model
	 * @return Revision
	 */
	function newTestRevision( $text, $title = "Test", $model = CONTENT_MODEL_WIKITEXT, $format = null ) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$content = ContentHandler::makeContent( $text, $title, $model, $format );

		$rev = new Revision(
			array(
				'id'         => 42,
				'page'       => 23,
				'title'      => $title,

				'content'    => $content,
				'length'     => $content->getSize(),
				'comment'    => "testing",
				'minor_edit' => false,

				'content_format' => $format,
			)
		);

		return $rev;
	}

	function dataGetContentModel() {
		return array(
			array( 'hello world', 'Hello', null, null, CONTENT_MODEL_WIKITEXT ),
			array( 'hello world', 'User:hello/there.css', null, null, CONTENT_MODEL_CSS ),
			array( serialize('hello world'), 'Dummy:Hello', null, null, "testing" ),
		);
	}

	/**
	 * @dataProvider dataGetContentModel
	 */
	function testGetContentModel( $text, $title, $model, $format, $expectedModel ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedModel, $rev->getContentModel() );
	}

	function dataGetContentFormat() {
		return array(
			array( 'hello world', 'Hello', null, null, CONTENT_FORMAT_WIKITEXT ),
			array( 'hello world', 'Hello', CONTENT_MODEL_CSS, null, CONTENT_FORMAT_CSS ),
			array( 'hello world', 'User:hello/there.css', null, null, CONTENT_FORMAT_CSS ),
			array( serialize('hello world'), 'Dummy:Hello', null, null, "testing" ),
		);
	}

	/**
	 * @dataProvider dataGetContentFormat
	 */
	function testGetContentFormat( $text, $title, $model, $format, $expectedFormat ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedFormat, $rev->getContentFormat() );
	}

	function dataGetContentHandler() {
		return array(
			array( 'hello world', 'Hello', null, null, 'WikitextContentHandler' ),
			array( 'hello world', 'User:hello/there.css', null, null, 'CssContentHandler' ),
			array( serialize('hello world'), 'Dummy:Hello', null, null, 'DummyContentHandlerForTesting' ),
		);
	}

	/**
	 * @dataProvider dataGetContentHandler
	 */
	function testGetContentHandler( $text, $title, $model, $format, $expectedClass ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedClass, get_class( $rev->getContentHandler() ) );
	}

	function dataGetContent() {
		return array(
			array( 'hello world', 'Hello', null, null, Revision::FOR_PUBLIC, 'hello world' ),
			array( serialize('hello world'), 'Hello', "testing", null, Revision::FOR_PUBLIC, serialize('hello world') ),
			array( serialize('hello world'), 'Dummy:Hello', null, null, Revision::FOR_PUBLIC, serialize('hello world') ),
		);
	}

	/**
	 * @dataProvider dataGetContent
	 */
	function testGetContent( $text, $title, $model, $format, $audience, $expectedSerialization ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );
		$content = $rev->getContent( $audience );

		$this->assertEquals( $expectedSerialization, is_null( $content ) ? null : $content->serialize( $format ) );
	}

	function dataGetText() {
		return array(
			array( 'hello world', 'Hello', null, null, Revision::FOR_PUBLIC, 'hello world' ),
			array( serialize('hello world'), 'Hello', "testing", null, Revision::FOR_PUBLIC, null ),
			array( serialize('hello world'), 'Dummy:Hello', null, null, Revision::FOR_PUBLIC, null ),
		);
	}

	/**
	 * @dataProvider dataGetText
	 */
	function testGetText( $text, $title, $model, $format, $audience, $expectedText ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedText, $rev->getText( $audience ) );
	}

	/**
	 * @dataProvider dataGetText
	 */
	function testGetRawText( $text, $title, $model, $format, $audience, $expectedText ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedText, $rev->getRawText( $audience ) );
	}


	public function dataGetSize( ) {
		return array(
			array( "hello world.", null, 12 ),
			array( serialize( "hello world." ), "testing", 12 ),
		);
	}

	/**
	 * @covers Revision::getSize
	 * @dataProvider dataGetSize
	 */
	public function testGetSize( $text, $model, $expected_size )
	{
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSize', $model );
		$this->assertEquals( $expected_size, $rev->getSize() );
	}

	public function dataGetSha1( ) {
		return array(
			array( "hello world.", null, Revision::base36Sha1( "hello world." ) ),
			array( serialize( "hello world." ), "testing", Revision::base36Sha1( serialize( "hello world." ) ) ),
		);
	}

	/**
	 * @covers Revision::getSha1
	 * @dataProvider dataGetSha1
	 */
	public function testGetSha1( $text, $model, $expected_hash )
	{
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSha1', $model );
		$this->assertEquals( $expected_hash, $rev->getSha1() );
	}

	public function testConstructWithText() {
		$rev = new Revision( array(
		                          'text' => 'hello world.',
		                          'content_model' => CONTENT_MODEL_JAVASCRIPT
		                     ));

		$this->assertNotNull( $rev->getText(), 'no content text' );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

	public function testConstructWithContent() {
		$title = Title::newFromText( 'RevisionTest_testConstructWithContent' );

		$rev = new Revision( array(
		                          'content' => ContentHandler::makeContent( 'hello world.', $title, CONTENT_MODEL_JAVASCRIPT ),
		                     ));

		$this->assertNotNull( $rev->getText(), 'no content text' );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

}


