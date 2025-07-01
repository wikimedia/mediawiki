<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiLangTestCase;

/**
 * Needs database to do link updates.
 *
 * @group ContentHandler
 * @group Database
 * @covers \MediaWiki\Content\TextContent
 * @covers \MediaWiki\Content\TextContentHandler
 */
class TextContentTest extends MediaWikiLangTestCase {
	/** @var RequestContext */
	protected $context;

	protected function setUp(): void {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->context = new RequestContext();
		$this->context->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		$this->context->setUser( $user );

		RequestContext::getMain()->setTitle( $this->context->getTitle() );

		$this->overrideConfigValues( [
			MainConfigNames::TextModelsToParse => [
				CONTENT_MODEL_WIKITEXT,
				CONTENT_MODEL_CSS,
				CONTENT_MODEL_JAVASCRIPT,
			],
			MainConfigNames::CapitalLinks => true,
		] );
		$this->clearHook( 'ContentGetParserOutput' );
	}

	/**
	 * NOTE: Overridden by subclass!
	 *
	 * @param string $text
	 * @return TextContent
	 */
	public function newContent( $text ) {
		return new TextContent( $text );
	}

	public static function provideGetRedirectTargetTextContent() {
		return [
			[ '#REDIRECT [[Test]]',
				null,
			],
		];
	}

	/**
	 * @dataProvider provideGetRedirectTargetTextContent
	 */
	public function testGetRedirectTarget( $text, $expected ) {
		$content = $this->newContent( $text );
		$t = $content->getRedirectTarget();

		if ( $expected === null ) {
			$this->assertNull( $t, "text should not have generated a redirect target: $text" );
		} else {
			$this->assertEquals( $expected, $t->getPrefixedText() );
		}
	}

	/**
	 * @dataProvider provideGetRedirectTargetTextContent
	 */
	public function testIsRedirect( $text, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected !== null, $content->isRedirect() );
	}

	public static function provideIsCountable() {
		return [
			[ '',
				null,
				'any',
				true
			],
			[ 'Foo',
				null,
				'any',
				true
			],
		];
	}

	/**
	 * @dataProvider provideIsCountable
	 */
	public function testIsCountable( $text, $hasLinks, $mode, $expected ) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, $mode );

		$content = $this->newContent( $text );

		$v = $content->isCountable( $hasLinks );

		$this->assertEquals(
			$expected,
			$v,
			'isCountable() returned unexpected value ' . var_export( $v, true )
				. ' instead of ' . var_export( $expected, true )
				. " in mode `$mode` for text \"$text\""
		);
	}

	public static function provideGetTextForSummary() {
		return [
			[ "hello\nworld.",
				16,
				'hello world.',
			],
			[ 'hello world.',
				8,
				'hello...',
			],
			[ '[[hello world]].',
				8,
				'[[hel...',
			],
		];
	}

	/**
	 * @dataProvider provideGetTextForSummary
	 */
	public function testGetTextForSummary( $text, $maxlength, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected, $content->getTextForSummary( $maxlength ) );
	}

	/**
	 */
	public function testCopy() {
		$content = $this->newContent( 'hello world.' );
		$copy = $content->copy();

		$this->assertTrue( $content->equals( $copy ), 'copy must be equal to original' );
		$this->assertEquals( 'hello world.', $copy->getText() );
	}

	public function testGetTextMethods() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 12, $content->getSize() );
		$this->assertEquals( 'hello world.', $content->getText() );
		$this->assertEquals( 'hello world.', $content->getTextForSearchIndex() );
		$this->assertEquals( 'hello world.', $content->getNativeData() );
		$this->assertEquals( 'hello world.', $content->getWikitextForTransclusion() );
	}

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getContentHandler()->getModelID() );
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
			[ new TextContent( "hallo" ), null, false ],
			[ new TextContent( "hallo" ), new TextContent( "hallo" ), true ],
			[ new TextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new WikitextContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new TextContent( "HALLO" ), false ],
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

	/**
	 * @dataProvider provideConvert
	 */
	public function testConvert( $text, $model, $lossy, $expectedNative ) {
		$content = $this->newContent( $text );

		/** @var TextContent $converted */
		$converted = $content->convert( $model, $lossy );

		if ( $expectedNative === false ) {
			$this->assertFalse( $converted, "conversion to $model was expected to fail!" );
		} else {
			$this->assertInstanceOf( Content::class, $converted );
			$this->assertEquals( $expectedNative, $converted->getText() );
		}
	}

	/**
	 * @dataProvider provideNormalizeLineEndings
	 */
	public function testNormalizeLineEndings( $input, $expected ) {
		$this->assertEquals( $expected, TextContent::normalizeLineEndings( $input ) );
	}

	public static function provideNormalizeLineEndings() {
		return [
			[
				"Foo\r\nbar",
				"Foo\nbar"
			],
			[
				"Foo\rbar",
				"Foo\nbar"
			],
			[
				"Foobar\n  ",
				"Foobar"
			]
		];
	}

	public function testSerialize() {
		$cnt = $this->newContent( 'testing text' );

		$this->assertSame( 'testing text', $cnt->serialize() );
	}

}
class_alias( TextContentTest::class, 'TextContentTest' );
