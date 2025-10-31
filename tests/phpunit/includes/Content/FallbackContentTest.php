<?php

use MediaWiki\Content\Content;
use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\FallbackContentHandler;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\WikitextContent;

/**
 * @group ContentHandler
 * @covers \MediaWiki\Content\FallbackContent
 * @covers \MediaWiki\Content\FallbackContentHandler
 */
class FallbackContentTest extends MediaWikiLangTestCase {

	private const CONTENT_MODEL = 'xyzzy';

	protected function setUp(): void {
		parent::setUp();
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ self::CONTENT_MODEL => FallbackContentHandler::class ]
		);
	}

	/**
	 * @param string $data
	 * @param string $type
	 *
	 * @return FallbackContent
	 */
	public function newContent( $data, $type = self::CONTENT_MODEL ) {
		return new FallbackContent( $data, $type );
	}

	public function testGetRedirectTarget() {
		$content = $this->newContent( '#REDIRECT [[Horkyporky]]' );
		$this->assertNull( $content->getRedirectTarget() );
	}

	public function testIsRedirect() {
		$content = $this->newContent( '#REDIRECT [[Horkyporky]]' );
		$this->assertFalse( $content->isRedirect() );
	}

	public function testIsCountable() {
		$content = $this->newContent( '[[Horkyporky]]' );
		$this->assertFalse( $content->isCountable( true ) );
	}

	public function testGetTextForSummary() {
		$content = $this->newContent( 'Horkyporky' );
		$this->assertSame( '', $content->getTextForSummary() );
	}

	public function testGetTextForSearchIndex() {
		$content = $this->newContent( 'Horkyporky' );
		$this->assertSame( '', $content->getTextForSearchIndex() );
	}

	public function testCopy() {
		$content = $this->newContent( 'hello world.' );
		$copy = $content->copy();

		$this->assertSame( $content, $copy );
	}

	public function testGetSize() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 12, $content->getSize() );
	}

	public function testGetData() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getData() );
	}

	public function testGetNativeData() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getNativeData() );
	}

	public function testGetWikitextForTransclusion() {
		$content = $this->newContent( 'hello world.' );

		$this->assertFalse( $content->getWikitextForTransclusion() );
	}

	public function testGetModel() {
		$content = $this->newContent( "hello world.", 'horkyporky' );

		$this->assertEquals( 'horkyporky', $content->getModel() );
	}

	public function testGetContentHandler() {
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'horkyporky' => FallbackContentHandler::class ]
		);

		$content = $this->newContent( "hello world.", 'horkyporky' );

		$this->assertInstanceOf( FallbackContentHandler::class, $content->getContentHandler() );
		$this->assertEquals( 'horkyporky', $content->getContentHandler()->getModelID() );
	}

	public static function provideIsEmpty() {
		return [
			[ '', true ],
			[ '  ', false ],
			[ '0', false ],
			[ 'hallo welt.', false ],
		];
	}

	/**
	 * @dataProvider provideIsEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public static function provideEquals() {
		return [
			[ new FallbackContent( "hallo", 'horky' ), null, false ],
			[ new FallbackContent( "hallo", 'horky' ), new FallbackContent( "hallo", 'horky' ), true ],
			[ new FallbackContent( "hallo", 'horky' ), new FallbackContent( "hallo", 'xyzzy' ), false ],
			[ new FallbackContent( "hallo", 'horky' ), new JavaScriptContent( "hallo" ), false ],
			[ new FallbackContent( "hallo", 'horky' ), new WikitextContent( "hallo" ), false ],
		];
	}

	/**
	 * @dataProvider provideEquals
	 */
	public function testEquals( Content $a, ?Content $b = null, $equal = false ) {
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

	public function testConvert() {
		$content = $this->newContent( 'More horkyporky?' );

		$this->assertFalse( $content->convert( CONTENT_MODEL_TEXT ) );
	}

	public function testSerialize() {
		$content = $this->newContent( 'Hörkypörky', 'horkyporky' );

		$this->assertSame( 'Hörkypörky', $content->serialize() );
	}

}
