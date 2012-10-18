<?php

/**
 * @group ContentHandler
 *
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class TextContentTest extends MediaWikiTestCase {

	public function setup() {
		global $wgUser;

		// anon user
		$wgUser = new User();
		$wgUser->setName( '127.0.0.1' );

		$this->context = new RequestContext( new FauxRequest() );
		$this->context->setTitle( Title::newFromText( "Test" ) );
		$this->context->setUser( $wgUser );
	}

	public function newContent( $text ) {
		return new TextContent( $text );
	}


	public function dataGetParserOutput() {
		return array(
			array(
				"TextContentTest_testGetParserOutput",
				CONTENT_MODEL_TEXT,
				"hello ''world'' & [[stuff]]\n", "hello ''world'' &amp; [[stuff]]",
				array( 'Links' => array() ) ),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetParserOutput
	 */
	public function testGetParserOutput( $title, $model, $text, $expectedHtml, $expectedFields = null ) {
		$title = Title::newFromText( $title );
		$content = ContentHandler::makeContent( $text, $title, $model );

		$po = $content->getParserOutput( $title );

		$html = $po->getText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments

		$this->assertEquals( $expectedHtml, trim( $html ) );

		if ( $expectedFields ) {
			foreach ( $expectedFields as $field => $exp ) {
				$f = 'get' . ucfirst( $field );
				$v = call_user_func( array( $po, $f ) );

				if ( is_array( $exp ) ) {
					$this->assertArrayEquals( $exp, $v );
				} else {
					$this->assertEquals( $exp, $v );
				}
			}
		}

		// @todo: assert more properties
	}

	public function dataPreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
		);
	}

	/**
	 * @dataProvider dataPreSaveTransform
	 */
	public function testPreSaveTransform( $text, $expected ) {
		global $wgContLang;

		$options = ParserOptions::newFromUserAndLang( $this->context->getUser(), $wgContLang );

		$content = $this->newContent( $text );
		$content = $content->preSaveTransform( $this->context->getTitle(), $this->context->getUser(), $options );

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
		);
	}

	/**
	 * @dataProvider dataPreloadTransform
	 */
	public function testPreloadTransform( $text, $expected ) {
		global $wgContLang;
		$options = ParserOptions::newFromUserAndLang( $this->context->getUser(), $wgContLang );

		$content = $this->newContent( $text );
		$content = $content->preloadTransform( $this->context->getTitle(), $options );

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public function dataGetRedirectTarget() {
		return array(
			array( '#REDIRECT [[Test]]',
				null,
			),
		);
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 */
	public function testGetRedirectTarget( $text, $expected ) {
		$content = $this->newContent( $text );
		$t = $content->getRedirectTarget( );

		if ( is_null( $expected ) ) {
			$this->assertNull( $t, "text should not have generated a redirect target: $text" );
		} else {
			$this->assertEquals( $expected, $t->getPrefixedText() );
		}
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 */
	public function isRedirect( $text, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( !is_null($expected), $content->isRedirect() );
	}


	/**
	 * @todo: test needs database! Should be done by a test class in the Database group.
	 */
	/*
	public function getRedirectChain() {
		$text = $this->getNativeData();
		return Title::newFromRedirectArray( $text );
	}
	*/

	/**
	 * @todo: test needs database! Should be done by a test class in the Database group.
	 */
	/*
	public function getUltimateRedirectTarget() {
		$text = $this->getNativeData();
		return Title::newFromRedirectRecurse( $text );
	}
	*/


	public function dataIsCountable() {
		return array(
			array( '',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'comma',
			       false
			),
			array( 'Foo, bar',
			       null,
			       'comma',
			       false
			),
		);
	}


	/**
	 * @dataProvider dataIsCountable
	 * @group Database
	 */
	public function testIsCountable( $text, $hasLinks, $mode, $expected ) {
		global $wgArticleCountMethod;

		$old = $wgArticleCountMethod;
		$wgArticleCountMethod = $mode;

		$content = $this->newContent( $text );

		$v = $content->isCountable( $hasLinks, $this->context->getTitle() );
		$wgArticleCountMethod = $old;

		$this->assertEquals( $expected, $v, "isCountable() returned unexpected value " . var_export( $v, true )
		                                    . " instead of " . var_export( $expected, true ) . " in mode `$mode` for text \"$text\"" );
	}

	public function dataGetTextForSummary() {
		return array(
			array( "hello\nworld.",
			       16,
			       'hello world.',
			),
			array( 'hello world.',
			       8,
			       'hello...',
			),
			array( '[[hello world]].',
			       8,
			       '[[hel...',
			),
		);
	}

	/**
	 * @dataProvider dataGetTextForSummary
	 */
	public function testGetTextForSummary( $text, $maxlength, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected, $content->getTextForSummary( $maxlength ) );
	}


	public function testGetTextForSearchIndex( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getTextForSearchIndex() );
	}

	public function testCopy() {
		$content = $this->newContent( "hello world." );
		$copy = $content->copy();

		$this->assertTrue( $content->equals( $copy ), "copy must be equal to original" );
		$this->assertEquals( "hello world.", $copy->getNativeData() );
	}

	public function testGetSize( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( 12, $content->getSize() );
	}

	public function testGetNativeData( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getNativeData() );
	}

	public function testGetWikitextForTransclusion( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getWikitextForTransclusion() );
	}

	# =================================================================================================================

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getContentHandler()->getModelID() );
	}

	public function dataIsEmpty( ) {
		return array(
			array( '', true ),
			array( '  ', false ),
			array( '0', false ),
			array( 'hallo welt.', false ),
		);
	}

	/**
	 * @dataProvider dataIsEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public function dataEquals( ) {
		return array(
			array( new TextContent( "hallo" ), null, false ),
			array( new TextContent( "hallo" ), new TextContent( "hallo" ), true ),
			array( new TextContent( "hallo" ), new JavascriptContent( "hallo" ), false ),
			array( new TextContent( "hallo" ), new WikitextContent( "hallo" ), false ),
			array( new TextContent( "hallo" ), new TextContent( "HALLO" ), false ),
		);
	}

	/**
	 * @dataProvider dataEquals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public function dataGetDeletionUpdates() {
		return array(
			array("TextContentTest_testGetSecondaryDataUpdates_1",
				CONTENT_MODEL_TEXT, "hello ''world''\n",
				array( )
			),
			array("TextContentTest_testGetSecondaryDataUpdates_2",
				CONTENT_MODEL_TEXT, "hello [[world test 21344]]\n",
				array( )
			),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetDeletionUpdates
	 */
	public function testDeletionUpdates( $title, $model, $text, $expectedStuff ) {
		$title = Title::newFromText( $title );
		$title->resetArticleID( 2342 ); //dummy id. fine as long as we don't try to execute the updates!

		$content = ContentHandler::makeContent( $text, $title, $model );

		$updates = $content->getDeletionUpdates( WikiPage::factory( $title ) );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[ $class ] = $update;
		}

		if ( !$expectedStuff ) {
			$this->assertTrue( true ); // make phpunit happy
			return;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[ $class ];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; #if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}
	}

}
