<?php

/**
 * @group ContentHandler
 */
class WikitextContentHandlerTest extends MediaWikiTestCase {

	/**
	 * @var ContentHandler
	 */
	var $handler;

	public function setup() {
		$this->handler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
	}

	public function teardown() {
	}

	public function testSerializeContent( ) {
		$content = new WikitextContent( 'hello world' );

		$this->assertEquals( 'hello world', $this->handler->serializeContent( $content ) );
		$this->assertEquals( 'hello world', $this->handler->serializeContent( $content, CONTENT_FORMAT_WIKITEXT ) );

		try {
			$this->handler->serializeContent( $content, 'dummy/foo' );
			$this->fail( "serializeContent() should have failed on unknown format" );
		} catch ( MWException $e ) {
			// ok, as expected
		}
	}

	public function testUnserializeContent( ) {
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

	public function testMakeEmptyContent() {
		$content = $this->handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertEquals( '', $content->getNativeData() );
	}

	public function dataIsSupportedFormat( ) {
		return array(
			array( null, true ),
			array( CONTENT_FORMAT_WIKITEXT, true ),
			array( 99887766, false ),
		);
	}

	/**
	 * @dataProvider dataIsSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$this->assertEquals( $supported, $this->handler->isSupportedFormat( $format ) );
	}

	public function dataMerge3( ) {
		return array(
			array( "first paragraph

					second paragraph\n",

					"FIRST paragraph

					second paragraph\n",

					"first paragraph

					SECOND paragraph\n",

					"FIRST paragraph

					SECOND paragraph\n",
			),

			array( "first paragraph
					second paragraph\n",

				   "Bla bla\n",

				   "Blubberdibla\n",

				   false,
			),

		);
	}

	/**
	 * @dataProvider dataMerge3
	 */
	public function testMerge3( $old, $mine, $yours, $expected ) {
		global $wgDiff3;

		if ( !$wgDiff3 ) {
			$this->markTestSkipped( "Can't test merge3(), since \$wgDiff3 is not configured" );
		}

		if ( !file_exists( $wgDiff3 ) ) {
			#XXX: this sucks, since it uses arcane internal knowledge about TextContentHandler::merge3 and wfMerge.
			$this->markTestSkipped( "Can't test merge3(), since \$wgDiff3 is misconfigured: can't find $wgDiff3" );
		}

		// test merge
		$oldContent = new WikitextContent( $old );
		$myContent = new WikitextContent( $mine );
		$yourContent = new WikitextContent( $yours );

		$merged = $this->handler->merge3( $oldContent, $myContent, $yourContent );

		$this->assertEquals( $expected, $merged ? $merged->getNativeData() : $merged );
	}

	public function dataGetAutosummary( ) {
		return array(
			array(
				'Hello there, world!',
				'#REDIRECT [[Foo]]',
				0,
				'/^Redirected page .*Foo/'
			),

			array(
				null,
				'Hello world!',
				EDIT_NEW,
				'/^Created page .*Hello/'
			),

			array(
				'Hello there, world!',
				'',
				0,
				'/^Blanked/'
			),

			array(
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
				labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et
				ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
				'Hello world!',
				0,
				'/^Replaced .*Hello/'
			),

			array(
				'foo',
				'bar',
				0,
				'/^$/'
			),
		);
	}

	/**
	 * @dataProvider dataGetAutoSummary
	 */
	public function testGetAutosummary( $old, $new, $flags, $expected ) {
		global $wgLanguageCode, $wgContLang;

		$oldContent = is_null( $old ) ? null : new WikitextContent( $old );
		$newContent = is_null( $new ) ? null : new WikitextContent( $new );

		$summary = $this->handler->getAutosummary( $oldContent, $newContent, $flags );

		$this->assertTrue( (bool)preg_match( $expected, $summary ), "Autosummary didn't match expected pattern $expected: $summary" );
	}

	/**
	 * @todo Text case requires database, should be done by a test class in the Database group
	 */
	/*
	public function testGetAutoDeleteReason( Title $title, &$hasHistory ) {
	}
	*/

	/**
	 * @todo Text case requires database, should be done by a test class in the Database group
	 */
	/*
	public function testGetUndoContent( Revision $current, Revision $undo, Revision $undoafter = null ) {
	}
	*/

}
