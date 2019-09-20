<?php

/**
 * @group ContentHandler
 */
class UnknownContentTest extends MediaWikiLangTestCase {

	/**
	 * @param string $data
	 * @return UnknownContent
	 */
	public function newContent( $data, $type = 'xyzzy' ) {
		return new UnknownContent( $data, $type );
	}

	/**
	 * @covers UnknownContent::getParserOutput
	 */
	public function testGetParserOutput() {
		$this->setUserLang( 'en' );
		$this->setContentLang( 'qqx' );

		$title = Title::newFromText( 'Test' );
		$content = $this->newContent( 'Horkyporky' );

		$po = $content->getParserOutput( $title );
		$html = $po->getText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments

		$this->assertNotContains( 'Horkyporky', $html );
		$this->assertNotContains( '(unsupported-content-model)', $html );
	}

	/**
	 * @covers UnknownContent::preSaveTransform
	 */
	public function testPreSaveTransform() {
		$title = Title::newFromText( 'Test' );
		$user = $this->getTestUser()->getUser();
		$content = $this->newContent( 'Horkyporky ~~~' );

		$options = new ParserOptions();

		$this->assertSame( $content, $content->preSaveTransform( $title, $user, $options ) );
	}

	/**
	 * @covers UnknownContent::preloadTransform
	 */
	public function testPreloadTransform() {
		$title = Title::newFromText( 'Test' );
		$content = $this->newContent( 'Horkyporky ~~~' );

		$options = new ParserOptions();

		$this->assertSame( $content, $content->preloadTransform( $title, $options ) );
	}

	/**
	 * @covers UnknownContent::getRedirectTarget
	 */
	public function testGetRedirectTarget() {
		$content = $this->newContent( '#REDIRECT [[Horkyporky]]' );
		$this->assertNull( $content->getRedirectTarget() );
	}

	/**
	 * @covers UnknownContent::isRedirect
	 */
	public function testIsRedirect() {
		$content = $this->newContent( '#REDIRECT [[Horkyporky]]' );
		$this->assertFalse( $content->isRedirect() );
	}

	/**
	 * @covers UnknownContent::isCountable
	 */
	public function testIsCountable() {
		$content = $this->newContent( '[[Horkyporky]]' );
		$this->assertFalse( $content->isCountable( true ) );
	}

	/**
	 * @covers UnknownContent::getTextForSummary
	 */
	public function testGetTextForSummary() {
		$content = $this->newContent( 'Horkyporky' );
		$this->assertSame( '', $content->getTextForSummary() );
	}

	/**
	 * @covers UnknownContent::getTextForSearchIndex
	 */
	public function testGetTextForSearchIndex() {
		$content = $this->newContent( 'Horkyporky' );
		$this->assertSame( '', $content->getTextForSearchIndex() );
	}

	/**
	 * @covers UnknownContent::copy
	 */
	public function testCopy() {
		$content = $this->newContent( 'hello world.' );
		$copy = $content->copy();

		$this->assertSame( $content, $copy );
	}

	/**
	 * @covers UnknownContent::getSize
	 */
	public function testGetSize() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 12, $content->getSize() );
	}

	/**
	 * @covers UnknownContent::getData
	 */
	public function testGetData() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getData() );
	}

	/**
	 * @covers UnknownContent::getNativeData
	 */
	public function testGetNativeData() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getNativeData() );
	}

	/**
	 * @covers UnknownContent::getWikitextForTransclusion
	 */
	public function testGetWikitextForTransclusion() {
		$content = $this->newContent( 'hello world.' );

		$this->assertFalse( $content->getWikitextForTransclusion() );
	}

	/**
	 * @covers UnknownContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( "hello world.", 'horkyporky' );

		$this->assertEquals( 'horkyporky', $content->getModel() );
	}

	/**
	 * @covers UnknownContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'horkyporky' => 'UnknownContentHandler' ]
		);

		$content = $this->newContent( "hello world.", 'horkyporky' );

		$this->assertInstanceOf( UnknownContentHandler::class, $content->getContentHandler() );
		$this->assertEquals( 'horkyporky', $content->getContentHandler()->getModelID() );
	}

	public static function dataIsEmpty() {
		return [
			[ '', true ],
			[ '  ', false ],
			[ '0', false ],
			[ 'hallo welt.', false ],
		];
	}

	/**
	 * @dataProvider dataIsEmpty
	 * @covers UnknownContent::isEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public function provideEquals() {
		return [
			[ new UnknownContent( "hallo", 'horky' ), null, false ],
			[ new UnknownContent( "hallo", 'horky' ), new UnknownContent( "hallo", 'horky' ), true ],
			[ new UnknownContent( "hallo", 'horky' ), new UnknownContent( "hallo", 'xyzzy' ), false ],
			[ new UnknownContent( "hallo", 'horky' ), new JavaScriptContent( "hallo" ), false ],
			[ new UnknownContent( "hallo", 'horky' ), new WikitextContent( "hallo" ), false ],
		];
	}

	/**
	 * @dataProvider provideEquals
	 * @covers UnknownContent::equals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public static function provideConvert() {
		return [
			[ // #0
				'Hallo Welt',
				CONTENT_MODEL_WIKITEXT,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_WIKITEXT,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_CSS,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_JAVASCRIPT,
				'lossless',
				'Hallo Welt'
			],
		];
	}

	/**
	 * @covers UnknownContent::convert
	 */
	public function testConvert() {
		$content = $this->newContent( 'More horkyporky?' );

		$this->assertFalse( $content->convert( CONTENT_MODEL_TEXT ) );
	}

	/**
	 * @covers UnknownContent::__construct
	 * @covers UnknownContentHandler::serializeContent
	 */
	public function testSerialize() {
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'horkyporky' => 'UnknownContentHandler' ]
		);

		$content = $this->newContent( 'Hörkypörky', 'horkyporky' );

		$this->assertSame( 'Hörkypörky', $content->serialize() );
	}

}
