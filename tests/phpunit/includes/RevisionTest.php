<?php

/**
 * @group ContentHandler
 */
class RevisionTest extends MediaWikiTestCase {

	protected function setUp() {
		global $wgContLang;

		parent::setUp();

		$this->setMwGlobals( [
			'wgContLang' => Language::factory( 'en' ),
			'wgLanguageCode' => 'en',
			'wgLegacyEncoding' => false,
			'wgCompressRevisions' => false,

			'wgContentHandlerTextFallback' => 'ignore',
		] );

		$this->mergeMwGlobalArrayValue(
			'wgExtraNamespaces',
			[
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			]
		);

		$this->mergeMwGlobalArrayValue(
			'wgNamespaceContentModels',
			[
				12312 => 'testing',
			]
		);

		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[
				'testing' => 'DummyContentHandlerForTesting',
				'RevisionTestModifyableContent' => 'RevisionTestModifyableContentHandler',
			]
		);

		MWNamespace::clearCaches();
		// Reset namespace cache
		$wgContLang->resetNamespaces();
	}

	protected function tearDown() {
		global $wgContLang;

		MWNamespace::clearCaches();
		// Reset namespace cache
		$wgContLang->resetNamespaces();

		parent::tearDown();
	}

	public function provideConstructFromArray() {
		yield 'with text' => [
			[
				'text' => 'hello world.',
				'content_model' => CONTENT_MODEL_JAVASCRIPT
			],
		];
		yield 'with content' => [
			[
				'content' => new JavaScriptContent( 'hellow world.' )
			],
		];
	}

	/**
	 * @dataProvider provideConstructFromArray
	 */
	public function testConstructFromArray( $rowArray ) {
		$rev = new Revision( $rowArray );
		$this->assertNotNull( $rev->getContent(), 'no content object available' );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContent()->getModel() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $rev->getContentModel() );
	}

	public function provideConstructFromArrayThrowsExceptions() {
		yield 'content and text_id both not empty' => [
			[
				'content' => new WikitextContent( 'GOAT' ),
				'text_id' => 'someid',
				],
			new MWException( "Text already stored in external store (id someid), " .
				"can't serialize content object" )
		];
		yield 'with bad content object (class)' => [
			[ 'content' => new stdClass() ],
			new MWException( '`content` field must contain a Content object.' )
		];
		yield 'with bad content object (string)' => [
			[ 'content' => 'ImAGoat' ],
			new MWException( '`content` field must contain a Content object.' )
		];
		yield 'bad row format' => [
			'imastring, not a row',
			new MWException( 'Revision constructor passed invalid row format.' )
		];
	}

	/**
	 * @dataProvider provideConstructFromArrayThrowsExceptions
	 */
	public function testConstructFromArrayThrowsExceptions( $rowArray, Exception $expectedException ) {
		$this->setExpectedException(
			get_class( $expectedException ),
			$expectedException->getMessage(),
			$expectedException->getCode()
		);
		new Revision( $rowArray );
	}

	public function provideGetRevisionText() {
		yield 'Generic test' => [
			'This is a goat of revision text.',
			[
				'old_flags' => '',
				'old_text' => 'This is a goat of revision text.',
			],
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionText
	 */
	public function testGetRevisionText( $expected, $rowData, $prefix = 'old_', $wiki = false ) {
		$this->assertEquals(
			$expected,
			Revision::getRevisionText( (object)$rowData, $prefix, $wiki ) );
	}

	public function provideGetRevisionTextWithZlibExtension() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			[
				'old_flags' => 'gzip',
				'old_text' => gzdeflate( 'This is a small goat of revision text.' ),
			],
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithZlibExtension
	 */
	public function testGetRevisionWithZlibExtension( $expected, $rowData ) {
		$this->checkPHPExtension( 'zlib' );
		$this->testGetRevisionText( $expected, $rowData );
	}

	public function provideGetRevisionTextWithLegacyEncoding() {
		yield 'Utf8Native' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			[
				'old_flags' => 'utf-8',
				'old_text' => "Wiki est l'\xc3\xa9cole superieur !",
			]
		];
		yield 'Utf8Legacy' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			[
				'old_flags' => '',
				'old_text' => "Wiki est l'\xe9cole superieur !",
			]
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithLegacyEncoding
	 */
	public function testGetRevisionWithLegacyEncoding( $expected, $encoding, $rowData ) {
		$GLOBALS['wgLegacyEncoding'] = $encoding;
		$this->testGetRevisionText( $expected, $rowData );
	}

	public function provideGetRevisionTextWithGzipAndLegacyEncoding() {
		yield 'Utf8NativeGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			[
				'old_flags' => 'gzip,utf-8',
				'old_text' => gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" ),
			]
		];
		yield 'Utf8LegacyGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			[
				'old_flags' => 'gzip',
				'old_text' => gzdeflate( "Wiki est l'\xe9cole superieur !" ),
			]
		];
	}

	/**
	 * @covers Revision::getRevisionText
	 * @dataProvider provideGetRevisionTextWithGzipAndLegacyEncoding
	 */
	public function testGetRevisionWithGzipAndLegacyEncoding( $expected, $encoding, $rowData ) {
		$this->checkPHPExtension( 'zlib' );
		$GLOBALS['wgLegacyEncoding'] = $encoding;
		$this->testGetRevisionText( $expected, $rowData );
	}

	/**
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8() {
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

	/**
	 * @covers Revision::compressRevisionText
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$this->checkPHPExtension( 'zlib' );
		$this->setMwGlobals( 'wgCompressRevisions', true );

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

	/**
	 * @param string $text
	 * @param string $title
	 * @param string $model
	 * @param string $format
	 *
	 * @return Revision
	 */
	private function newTestRevision( $text, $title = "Test",
		$model = CONTENT_MODEL_WIKITEXT, $format = null
	) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$content = ContentHandler::makeContent( $text, $title, $model, $format );

		$rev = new Revision(
			[
				'id' => 42,
				'page' => 23,
				'title' => $title,

				'content' => $content,
				'length' => $content->getSize(),
				'comment' => "testing",
				'minor_edit' => false,

				'content_format' => $format,
			]
		);

		return $rev;
	}

	public function provideGetContentModel() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, CONTENT_MODEL_WIKITEXT ],
			[ 'hello world', 'User:hello/there.css', null, null, CONTENT_MODEL_CSS ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, "testing" ],
		];
	}

	/**
	 * @group Database
	 * @dataProvider provideGetContentModel
	 * @covers Revision::getContentModel
	 */
	public function testGetContentModel( $text, $title, $model, $format, $expectedModel ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedModel, $rev->getContentModel() );
	}

	public function provideGetContentFormat() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, CONTENT_FORMAT_WIKITEXT ],
			[ 'hello world', 'Help:Hello', CONTENT_MODEL_CSS, null, CONTENT_FORMAT_CSS ],
			[ 'hello world', 'User:hello/there.css', null, null, CONTENT_FORMAT_CSS ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, "testing" ],
		];
	}

	/**
	 * @group Database
	 * @dataProvider provideGetContentFormat
	 * @covers Revision::getContentFormat
	 */
	public function testGetContentFormat( $text, $title, $model, $format, $expectedFormat ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedFormat, $rev->getContentFormat() );
	}

	public function provideGetContentHandler() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, 'WikitextContentHandler' ],
			[ 'hello world', 'User:hello/there.css', null, null, 'CssContentHandler' ],
			[ serialize( 'hello world' ), 'Dummy:Hello', null, null, 'DummyContentHandlerForTesting' ],
		];
	}

	/**
	 * @group Database
	 * @dataProvider provideGetContentHandler
	 * @covers Revision::getContentHandler
	 */
	public function testGetContentHandler( $text, $title, $model, $format, $expectedClass ) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );

		$this->assertEquals( $expectedClass, get_class( $rev->getContentHandler() ) );
	}

	public function provideGetContent() {
		// NOTE: we expect the help namespace to always contain wikitext
		return [
			[ 'hello world', 'Help:Hello', null, null, Revision::FOR_PUBLIC, 'hello world' ],
			[
				serialize( 'hello world' ),
				'Hello',
				"testing",
				null,
				Revision::FOR_PUBLIC,
				serialize( 'hello world' )
			],
			[
				serialize( 'hello world' ),
				'Dummy:Hello',
				null,
				null,
				Revision::FOR_PUBLIC,
				serialize( 'hello world' )
			],
		];
	}

	/**
	 * @group Database
	 * @dataProvider provideGetContent
	 * @covers Revision::getContent
	 */
	public function testGetContent( $text, $title, $model, $format,
		$audience, $expectedSerialization
	) {
		$rev = $this->newTestRevision( $text, $title, $model, $format );
		$content = $rev->getContent( $audience );

		$this->assertEquals(
			$expectedSerialization,
			is_null( $content ) ? null : $content->serialize( $format )
		);
	}

	public function provideGetSize() {
		return [
			[ "hello world.", CONTENT_MODEL_WIKITEXT, 12 ],
			[ serialize( "hello world." ), "testing", 12 ],
		];
	}

	/**
	 * @covers Revision::getSize
	 * @group Database
	 * @dataProvider provideGetSize
	 */
	public function testGetSize( $text, $model, $expected_size ) {
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSize', $model );
		$this->assertEquals( $expected_size, $rev->getSize() );
	}

	public function provideGetSha1() {
		return [
			[ "hello world.", CONTENT_MODEL_WIKITEXT, Revision::base36Sha1( "hello world." ) ],
			[
				serialize( "hello world." ),
				"testing",
				Revision::base36Sha1( serialize( "hello world." ) )
			],
		];
	}

	/**
	 * @covers Revision::getSha1
	 * @group Database
	 * @dataProvider provideGetSha1
	 */
	public function testGetSha1( $text, $model, $expected_hash ) {
		$rev = $this->newTestRevision( $text, 'RevisionTest_testGetSha1', $model );
		$this->assertEquals( $expected_hash, $rev->getSha1() );
	}

	/**
	 * Tests whether $rev->getContent() returns a clone when needed.
	 *
	 * @group Database
	 * @covers Revision::getContent
	 */
	public function testGetContentClone() {
		$content = new RevisionTestModifyableContent( "foo" );

		$rev = new Revision(
			[
				'id' => 42,
				'page' => 23,
				'title' => Title::newFromText( "testGetContentClone_dummy" ),

				'content' => $content,
				'length' => $content->getSize(),
				'comment' => "testing",
				'minor_edit' => false,
			]
		);

		/** @var RevisionTestModifyableContent $content */
		$content = $rev->getContent( Revision::RAW );
		$content->setText( "bar" );

		/** @var RevisionTestModifyableContent $content2 */
		$content2 = $rev->getContent( Revision::RAW );
		// content is mutable, expect clone
		$this->assertNotSame( $content, $content2, "expected a clone" );
		// clone should contain the original text
		$this->assertEquals( "foo", $content2->getText() );

		$content2->setText( "bla bla" );
		// clones should be independent
		$this->assertEquals( "bar", $content->getText() );
	}

	/**
	 * Tests whether $rev->getContent() returns the same object repeatedly if appropriate.
	 *
	 * @group Database
	 * @covers Revision::getContent
	 */
	public function testGetContentUncloned() {
		$rev = $this->newTestRevision( "hello", "testGetContentUncloned_dummy", CONTENT_MODEL_WIKITEXT );
		$content = $rev->getContent( Revision::RAW );
		$content2 = $rev->getContent( Revision::RAW );

		// for immutable content like wikitext, this should be the same object
		$this->assertSame( $content, $content2 );
	}
}
