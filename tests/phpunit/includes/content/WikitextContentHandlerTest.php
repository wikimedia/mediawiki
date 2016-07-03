<?php

/**
 * @group ContentHandler
 */
class WikitextContentHandlerTest extends MediaWikiLangTestCase {
	/**
	 * @var ContentHandler
	 */
	private $handler;

	protected function setUp() {
		parent::setUp();

		$this->handler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @covers WikitextContentHandler::serializeContent
	 */
	public function testSerializeContent() {
		$content = new WikitextContent( 'hello world' );

		$this->assertEquals( 'hello world', $this->handler->serializeContent( $content ) );
		$this->assertEquals(
			'hello world',
			$this->handler->serializeContent( $content, CONTENT_FORMAT_WIKITEXT )
		);

		try {
			$this->handler->serializeContent( $content, 'dummy/foo' );
			$this->fail( "serializeContent() should have failed on unknown format" );
		} catch ( MWException $e ) {
			// ok, as expected
		}
	}

	/**
	 * @covers WikitextContentHandler::unserializeContent
	 */
	public function testUnserializeContent() {
		$content = $this->handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getNativeData() );

		$content = $this->handler->unserializeContent( 'hello world', CONTENT_FORMAT_WIKITEXT );
		$this->assertEquals( 'hello world', $content->getNativeData() );

		try {
			$this->handler->unserializeContent( 'hello world', 'dummy/foo' );
			$this->fail( "unserializeContent() should have failed on unknown format" );
		} catch ( MWException $e ) {
			// ok, as expected
		}
	}

	/**
	 * @covers WikitextContentHandler::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$content = $this->handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertEquals( '', $content->getNativeData() );
	}

	public static function dataIsSupportedFormat() {
		return [
			[ null, true ],
			[ CONTENT_FORMAT_WIKITEXT, true ],
			[ 99887766, false ],
		];
	}

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @param Title|string $title Title object or string for Title::newFromText()
	 * @param string $expected Serialized form of the content object built
	 * @covers WikitextContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		global $wgContLang;
		$wgContLang->resetNamespaces();

		MagicWord::clearCache();

		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}
		$content = $this->handler->makeRedirectContent( $title );
		$this->assertEquals( $expected, $content->serialize() );
	}

	public static function provideMakeRedirectContent() {
		return [
			[ 'Hello', '#REDIRECT [[Hello]]' ],
			[ 'Template:Hello', '#REDIRECT [[Template:Hello]]' ],
			[ 'Hello#section', '#REDIRECT [[Hello#section]]' ],
			[ 'user:john_doe#section', '#REDIRECT [[User:John doe#section]]' ],
			[ 'MEDIAWIKI:FOOBAR', '#REDIRECT [[MediaWiki:FOOBAR]]' ],
			[ 'Category:Foo', '#REDIRECT [[:Category:Foo]]' ],
			[ Title::makeTitle( NS_MAIN, 'en:Foo' ), '#REDIRECT [[en:Foo]]' ],
			[ Title::makeTitle( NS_MAIN, 'Foo', '', 'en' ), '#REDIRECT [[:en:Foo]]' ],
			[
				Title::makeTitle( NS_MAIN, 'Bar', 'fragment', 'google' ),
				'#REDIRECT [[google:Bar#fragment]]'
			],
		];
	}

	/**
	 * @dataProvider dataIsSupportedFormat
	 * @covers WikitextContentHandler::isSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$this->assertEquals( $supported, $this->handler->isSupportedFormat( $format ) );
	}

	public function testSupportsDirectEditing() {
		$handler = new WikiTextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

	public static function dataMerge3() {
		return [
			[
				"first paragraph

					second paragraph\n",

				"FIRST paragraph

					second paragraph\n",

				"first paragraph

					SECOND paragraph\n",

				"FIRST paragraph

					SECOND paragraph\n",
			],

			[ "first paragraph
					second paragraph\n",

				"Bla bla\n",

				"Blubberdibla\n",

				false,
			],
		];
	}

	/**
	 * @dataProvider dataMerge3
	 * @covers WikitextContentHandler::merge3
	 */
	public function testMerge3( $old, $mine, $yours, $expected ) {
		$this->markTestSkippedIfNoDiff3();

		// test merge
		$oldContent = new WikitextContent( $old );
		$myContent = new WikitextContent( $mine );
		$yourContent = new WikitextContent( $yours );

		$merged = $this->handler->merge3( $oldContent, $myContent, $yourContent );

		$this->assertEquals( $expected, $merged ? $merged->getNativeData() : $merged );
	}

	public static function dataGetAutosummary() {
		return [
			[
				'Hello there, world!',
				'#REDIRECT [[Foo]]',
				0,
				'/^Redirected page .*Foo/'
			],

			[
				null,
				'Hello world!',
				EDIT_NEW,
				'/^Created page .*Hello/'
			],

			[
				'Hello there, world!',
				'',
				0,
				'/^Blanked/'
			],

			[
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
				eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
				voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet
				clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Hello world!',
				0,
				'/^Replaced .*Hello/'
			],

			[
				'foo',
				'bar',
				0,
				'/^$/'
			],
		];
	}

	/**
	 * @dataProvider dataGetAutosummary
	 * @covers WikitextContentHandler::getAutosummary
	 */
	public function testGetAutosummary( $old, $new, $flags, $expected ) {
		$oldContent = is_null( $old ) ? null : new WikitextContent( $old );
		$newContent = is_null( $new ) ? null : new WikitextContent( $new );

		$summary = $this->handler->getAutosummary( $oldContent, $newContent, $flags );

		$this->assertTrue(
			(bool)preg_match( $expected, $summary ),
			"Autosummary didn't match expected pattern $expected: $summary"
		);
	}

	/**
	 * @todo Text case requires database, should be done by a test class in the Database group
	 */
	/*
	public function testGetAutoDeleteReason( Title $title, &$hasHistory ) {}
	*/

	/**
	 * @todo Text case requires database, should be done by a test class in the Database group
	 */
	/*
	public function testGetUndoContent( Revision $current, Revision $undo,
		Revision $undoafter = null
	) {
	}
	*/
}
