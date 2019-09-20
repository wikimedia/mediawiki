<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;

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
		$this->assertEquals( 'hello world', $content->getText() );

		$content = $this->handler->unserializeContent( 'hello world', CONTENT_FORMAT_WIKITEXT );
		$this->assertEquals( 'hello world', $content->getText() );

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
		$this->assertSame( '', $content->getText() );
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
		MediaWikiServices::getInstance()->getContentLanguage()->resetNamespaces();

		MediaWikiServices::getInstance()->resetServiceForTesting( 'MagicWordFactory' );

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

	/**
	 * @covers WikitextContentHandler::supportsDirectEditing
	 */
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

		$this->assertEquals( $expected, $merged ? $merged->getText() : $merged );
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
				null,
				'',
				EDIT_NEW,
				'/^Created blank page$/'
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

	public static function dataGetChangeTag() {
		return [
			[
				null,
				'#REDIRECT [[Foo]]',
				0,
				'mw-new-redirect'
			],

			[
				'Lorem ipsum dolor',
				'#REDIRECT [[Foo]]',
				0,
				'mw-new-redirect'
			],

			[
				'#REDIRECT [[Foo]]',
				'Lorem ipsum dolor',
				0,
				'mw-removed-redirect'
			],

			[
				'#REDIRECT [[Foo]]',
				'#REDIRECT [[Bar]]',
				0,
				'mw-changed-redirect-target'
			],

			[
				null,
				'Lorem ipsum dolor',
				EDIT_NEW,
				null // mw-newpage is not defined as a tag
			],

			[
				null,
				'',
				EDIT_NEW,
				null // mw-newblank is not defined as a tag
			],

			[
				'Lorem ipsum dolor',
				'',
				0,
				'mw-blank'
			],

			[
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
				eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
				voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet
				clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Ipsum',
				0,
				'mw-replace'
			],

			[
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
				eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
				voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet
				clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Duis purus odio, rhoncus et finibus dapibus, facilisis ac urna. Pellentesque
				arcu, tristique nec tempus nec, suscipit vel arcu. Sed non dolor nec ligula
				congue tempor. Quisque pellentesque finibus orci a molestie. Nam maximus, purus
				euismod finibus mollis, dui ante malesuada felis, dignissim rutrum diam sapien.',
				0,
				null
			],
		];
	}

	/**
	 * @dataProvider dataGetChangeTag
	 * @covers WikitextContentHandler::getChangeTag
	 */
	public function testGetChangeTag( $old, $new, $flags, $expected ) {
		$this->setMwGlobals( 'wgSoftwareTags', [
			'mw-new-redirect' => true,
			'mw-removed-redirect' => true,
			'mw-changed-redirect-target' => true,
			'mw-newpage' => true,
			'mw-newblank' => true,
			'mw-blank' => true,
			'mw-replace' => true,
		] );
		$oldContent = is_null( $old ) ? null : new WikitextContent( $old );
		$newContent = is_null( $new ) ? null : new WikitextContent( $new );

		$tag = $this->handler->getChangeTag( $oldContent, $newContent, $flags );

		$this->assertSame( $expected, $tag );
	}

	/**
	 * @covers WikitextContentHandler::getDataForSearchIndex
	 */
	public function testDataIndexFieldsFile() {
		$mockEngine = $this->createMock( SearchEngine::class );
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );
		$page = new WikiPage( $title );

		$fileHandler = $this->getMockBuilder( FileContentHandler::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getDataForSearchIndex' ] )
			->getMock();

		$handler = $this->getMockBuilder( WikitextContentHandler::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getFileHandler' ] )
			->getMock();

		$handler->method( 'getFileHandler' )->will( $this->returnValue( $fileHandler ) );
		$fileHandler->expects( $this->once() )
			->method( 'getDataForSearchIndex' )
			->will( $this->returnValue( [ 'file_text' => 'This is file content' ] ) );

		$data = $handler->getDataForSearchIndex( $page, new ParserOutput(), $mockEngine );
		$this->assertArrayHasKey( 'file_text', $data );
		$this->assertEquals( 'This is file content', $data['file_text'] );
	}

	/**
	 * @covers ContentHandler::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );
		$content = new WikitextContent( '' );

		/** @var SlotRenderingProvider $srp */
		$srp = $this->getMock( SlotRenderingProvider::class );

		$handler = new WikitextContentHandler();
		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ContentHandler::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );
		$content = new WikitextContent( '' );

		$srp = $this->getMock( SlotRenderingProvider::class );

		$handler = new WikitextContentHandler();
		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );

		$this->assertEquals( [], $updates );
	}

}
