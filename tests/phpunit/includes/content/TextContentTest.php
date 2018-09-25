<?php

use MediaWiki\MediaWikiServices;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class TextContentTest extends MediaWikiLangTestCase {
	protected $context;

	protected function setUp() {
		parent::setUp();

		// trigger purging of all page related tables
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->context = new RequestContext( new FauxRequest() );
		$this->context->setTitle( Title::newFromText( 'Test' ) );
		$this->context->setUser( $user );

		$this->setMwGlobals( [
			'wgUser' => $user,
			'wgTextModelsToParse' => [
				CONTENT_MODEL_WIKITEXT,
				CONTENT_MODEL_CSS,
				CONTENT_MODEL_JAVASCRIPT,
			],
			'wgTidyConfig' => [ 'driver' => 'RemexHtml' ],
			'wgCapitalLinks' => true,
			'wgHooks' => [], // bypass hook ContentGetParserOutput that force custom rendering
		] );

		MWTidy::destroySingleton();
	}

	protected function tearDown() {
		MWTidy::destroySingleton();
		parent::tearDown();
	}

	public function newContent( $text ) {
		return new TextContent( $text );
	}

	public static function dataGetParserOutput() {
		return [
			[
				'TextContentTest_testGetParserOutput',
				CONTENT_MODEL_TEXT,
				"hello ''world'' & [[stuff]]\n", "hello ''world'' &amp; [[stuff]]",
				[
					'Links' => []
				]
			],
			// TODO: more...?
		];
	}

	/**
	 * @dataProvider dataGetParserOutput
	 * @covers TextContent::getParserOutput
	 */
	public function testGetParserOutput( $title, $model, $text, $expectedHtml,
		$expectedFields = null
	) {
		$title = Title::newFromText( $title );
		$content = ContentHandler::makeContent( $text, $title, $model );

		$po = $content->getParserOutput( $title );

		$html = $po->getText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments

		$this->assertEquals( $expectedHtml, trim( $html ) );

		if ( $expectedFields ) {
			foreach ( $expectedFields as $field => $exp ) {
				$f = 'get' . ucfirst( $field );
				$v = call_user_func( [ $po, $f ] );

				if ( is_array( $exp ) ) {
					$this->assertArrayEquals( $exp, $v );
				} else {
					$this->assertEquals( $exp, $v );
				}
			}
		}

		// TODO: assert more properties
	}

	public static function dataPreSaveTransform() {
		return [
			[
				# 0: no signature resolution
				'hello this is ~~~',
				'hello this is ~~~',
			],
			[
				# 1: rtrim
				" Foo \n ",
				' Foo',
			],
			[
				# 2: newline normalization
				"LF\n\nCRLF\r\n\r\nCR\r\rEND",
				"LF\n\nCRLF\n\nCR\n\nEND",
			],
		];
	}

	/**
	 * @dataProvider dataPreSaveTransform
	 * @covers TextContent::preSaveTransform
	 */
	public function testPreSaveTransform( $text, $expected ) {
		$options = ParserOptions::newFromUserAndLang( $this->context->getUser(),
			MediaWikiServices::getInstance()->getContentLanguage() );

		$content = $this->newContent( $text );
		$content = $content->preSaveTransform(
			$this->context->getTitle(),
			$this->context->getUser(),
			$options
		);

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public static function dataPreloadTransform() {
		return [
			[
				'hello this is ~~~',
				'hello this is ~~~',
			],
		];
	}

	/**
	 * @dataProvider dataPreloadTransform
	 * @covers TextContent::preloadTransform
	 */
	public function testPreloadTransform( $text, $expected ) {
		$options = ParserOptions::newFromUserAndLang( $this->context->getUser(),
			MediaWikiServices::getInstance()->getContentLanguage() );

		$content = $this->newContent( $text );
		$content = $content->preloadTransform( $this->context->getTitle(), $options );

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public static function dataGetRedirectTarget() {
		return [
			[ '#REDIRECT [[Test]]',
				null,
			],
		];
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 * @covers TextContent::getRedirectTarget
	 */
	public function testGetRedirectTarget( $text, $expected ) {
		$content = $this->newContent( $text );
		$t = $content->getRedirectTarget();

		if ( is_null( $expected ) ) {
			$this->assertNull( $t, "text should not have generated a redirect target: $text" );
		} else {
			$this->assertEquals( $expected, $t->getPrefixedText() );
		}
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 * @covers TextContent::isRedirect
	 */
	public function testIsRedirect( $text, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( !is_null( $expected ), $content->isRedirect() );
	}

	public static function dataIsCountable() {
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
	 * @dataProvider dataIsCountable
	 * @covers TextContent::isCountable
	 */
	public function testIsCountable( $text, $hasLinks, $mode, $expected ) {
		$this->setMwGlobals( 'wgArticleCountMethod', $mode );

		$content = $this->newContent( $text );

		$v = $content->isCountable( $hasLinks, $this->context->getTitle() );

		$this->assertEquals(
			$expected,
			$v,
			'isCountable() returned unexpected value ' . var_export( $v, true )
				. ' instead of ' . var_export( $expected, true )
				. " in mode `$mode` for text \"$text\""
		);
	}

	public static function dataGetTextForSummary() {
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
	 * @dataProvider dataGetTextForSummary
	 * @covers TextContent::getTextForSummary
	 */
	public function testGetTextForSummary( $text, $maxlength, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected, $content->getTextForSummary( $maxlength ) );
	}

	/**
	 * @covers TextContent::getTextForSearchIndex
	 */
	public function testGetTextForSearchIndex() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getTextForSearchIndex() );
	}

	/**
	 * @covers TextContent::copy
	 */
	public function testCopy() {
		$content = $this->newContent( 'hello world.' );
		$copy = $content->copy();

		$this->assertTrue( $content->equals( $copy ), 'copy must be equal to original' );
		$this->assertEquals( 'hello world.', $copy->getNativeData() );
	}

	/**
	 * @covers TextContent::getSize
	 */
	public function testGetSize() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 12, $content->getSize() );
	}

	/**
	 * @covers TextContent::getNativeData
	 */
	public function testGetNativeData() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getNativeData() );
	}

	/**
	 * @covers TextContent::getWikitextForTransclusion
	 */
	public function testGetWikitextForTransclusion() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 'hello world.', $content->getWikitextForTransclusion() );
	}

	/**
	 * @covers TextContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getModel() );
	}

	/**
	 * @covers TextContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getContentHandler()->getModelID() );
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
	 * @covers TextContent::isEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public static function dataEquals() {
		return [
			[ new TextContent( "hallo" ), null, false ],
			[ new TextContent( "hallo" ), new TextContent( "hallo" ), true ],
			[ new TextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new WikitextContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new TextContent( "HALLO" ), false ],
		];
	}

	/**
	 * @dataProvider dataEquals
	 * @covers TextContent::equals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public static function dataGetDeletionUpdates() {
		return [
			[
				CONTENT_MODEL_TEXT, "hello ''world''\n",
				[]
			],
			[
				CONTENT_MODEL_TEXT, "hello [[world test 21344]]\n",
				[]
			],
			// TODO: more...?
		];
	}

	/**
	 * @dataProvider dataGetDeletionUpdates
	 * @covers TextContent::getDeletionUpdates
	 */
	public function testDeletionUpdates( $model, $text, $expectedStuff ) {
		$page = $this->getNonexistingTestPage( get_class( $this ) . '-' . $this->getName() );
		$title = $page->getTitle();

		$content = ContentHandler::makeContent( $text, $title, $model );
		$page->doEditContent( $content, '' );

		$updates = $content->getDeletionUpdates( $page );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[$class] = $update;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[$class];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; # if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}

		// make phpunit happy even if $expectedStuff was empty
		$this->assertTrue( true );
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
	 * @covers TextContent::convert
	 */
	public function testConvert( $text, $model, $lossy, $expectedNative ) {
		$content = $this->newContent( $text );

		$converted = $content->convert( $model, $lossy );

		if ( $expectedNative === false ) {
			$this->assertFalse( $converted, "conversion to $model was expected to fail!" );
		} else {
			$this->assertInstanceOf( Content::class, $converted );
			$this->assertEquals( $expectedNative, $converted->getNativeData() );
		}
	}

	/**
	 * @covers TextContent::normalizeLineEndings
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

}
