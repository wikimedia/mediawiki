<?php

/**
 * @group ContentHandler
 *
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class WikitextContentTest extends TextContentTest {
	public static $sections = "Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";

	public function newContent( $text ) {
		return new WikitextContent( $text );
	}

	public static function dataGetParserOutput() {
		return array(
			array(
				"WikitextContentTest_testGetParserOutput",
				CONTENT_MODEL_WIKITEXT,
				"hello ''world''\n",
				"<p>hello <i>world</i>\n</p>"
			),
			// TODO: more...?
		);
	}

	public static function dataGetSecondaryDataUpdates() {
		return array(
			array( "WikitextContentTest_testGetSecondaryDataUpdates_1",
				CONTENT_MODEL_WIKITEXT, "hello ''world''\n",
				array(
					'LinksUpdate' => array(
						'mRecursive' => true,
						'mLinks' => array()
					)
				)
			),
			array( "WikitextContentTest_testGetSecondaryDataUpdates_2",
				CONTENT_MODEL_WIKITEXT, "hello [[world test 21344]]\n",
				array(
					'LinksUpdate' => array(
						'mRecursive' => true,
						'mLinks' => array(
							array( 'World_test_21344' => 0 )
						)
					)
				)
			),
			// TODO: more...?
		);
	}

	/**
	 * @dataProvider dataGetSecondaryDataUpdates
	 * @group Database
	 * @covers WikitextContent::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates( $title, $model, $text, $expectedStuff ) {
		$ns = $this->getDefaultWikitextNS();
		$title = Title::newFromText( $title, $ns );

		$content = ContentHandler::makeContent( $text, $title, $model );

		$page = WikiPage::factory( $title );
		$page->doEditContent( $content, '' );

		$updates = $content->getSecondaryDataUpdates( $title );

		// make updates accessible by class name
		foreach ( $updates as $update ) {
			$class = get_class( $update );
			$updates[$class] = $update;
		}

		foreach ( $expectedStuff as $class => $fieldValues ) {
			$this->assertArrayHasKey( $class, $updates, "missing an update of type $class" );

			$update = $updates[$class];

			foreach ( $fieldValues as $field => $value ) {
				$v = $update->$field; #if the field doesn't exist, just crash and burn
				$this->assertEquals( $value, $v, "unexpected value for field $field in instance of $class" );
			}
		}

		$page->doDeleteArticle( '' );
	}

	public static function dataGetSection() {
		return array(
			array( WikitextContentTest::$sections,
				"0",
				"Intro"
			),
			array( WikitextContentTest::$sections,
				"2",
				"== test ==
just a test"
			),
			array( WikitextContentTest::$sections,
				"8",
				false
			),
		);
	}

	/**
	 * @dataProvider dataGetSection
	 * @covers WikitextContent::getSection
	 */
	public function testGetSection( $text, $sectionId, $expectedText ) {
		$content = $this->newContent( $text );

		$sectionContent = $content->getSection( $sectionId );
		if ( is_object( $sectionContent ) ) {
			$sectionText = $sectionContent->getNativeData();
		} else {
			$sectionText = $sectionContent;
		}

		$this->assertEquals( $expectedText, $sectionText );
	}

	public static function dataReplaceSection() {
		return array(
			array( WikitextContentTest::$sections,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/sm', 'No more', WikitextContentTest::$sections ) )
			),
			array( WikitextContentTest::$sections,
				"",
				"No more",
				null,
				"No more"
			),
			array( WikitextContentTest::$sections,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace(
					'/^== test ==.*== foo ==/sm', "== TEST ==\nmore fun\n\n== foo ==",
					WikitextContentTest::$sections
				) )
			),
			array( WikitextContentTest::$sections,
				"8",
				"No more",
				null,
				WikitextContentTest::$sections
			),
			array( WikitextContentTest::$sections,
				"new",
				"No more",
				"New",
				trim( WikitextContentTest::$sections ) . "\n\n\n== New ==\n\nNo more"
			),
		);
	}

	/**
	 * @dataProvider dataReplaceSection
	 * @covers WikitextContent::replaceSection
	 */
	public function testReplaceSection( $text, $section, $with, $sectionTitle, $expected ) {
		$content = $this->newContent( $text );
		$c = $content->replaceSection( $section, $this->newContent( $with ), $sectionTitle );

		$this->assertEquals( $expected, is_null( $c ) ? null : $c->getNativeData() );
	}

	/**
	 * @covers WikitextContent::addSectionHeader
	 */
	public function testAddSectionHeader() {
		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( 'test' );

		$this->assertEquals( "== test ==\n\nhello world", $content->getNativeData() );
	}

	public static function dataPreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
			array( // rtrim
				" Foo \n ",
				" Foo",
			),
		);
	}

	public static function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
				"hello this is ~~~",
			),
			array( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
				'hello \'\'this\'\' is bar',
			),
		);
	}

	public static function dataGetRedirectTarget() {
		return array(
			array( '#REDIRECT [[Test]]',
				'Test',
			),
			array( '#REDIRECT Test',
				null,
			),
			array( '* #REDIRECT [[Test]]',
				null,
			),
		);
	}

	public static function dataGetTextForSummary() {
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
				'hel...',
			),
		);
	}

	public static function dataIsCountable() {
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
				true
			),
			array( 'Foo',
				null,
				'link',
				false
			),
			array( 'Foo [[bar]]',
				null,
				'link',
				true
			),
			array( 'Foo',
				true,
				'link',
				true
			),
			array( 'Foo [[bar]]',
				false,
				'link',
				false
			),
			array( '#REDIRECT [[bar]]',
				true,
				'any',
				false
			),
			array( '#REDIRECT [[bar]]',
				true,
				'comma',
				false
			),
			array( '#REDIRECT [[bar]]',
				true,
				'link',
				false
			),
		);
	}

	/**
	 * @covers WikitextContent::matchMagicWord
	 */
	public function testMatchMagicWord() {
		$mw = MagicWord::get( "staticredirect" );

		$content = $this->newContent( "#REDIRECT [[FOO]]\n__STATICREDIRECT__" );
		$this->assertTrue( $content->matchMagicWord( $mw ), "should have matched magic word" );

		$content = $this->newContent( "#REDIRECT [[FOO]]" );
		$this->assertFalse( $content->matchMagicWord( $mw ), "should not have matched magic word" );
	}

	/**
	 * @covers WikitextContent::updateRedirect
	 */
	public function testUpdateRedirect() {
		$target = Title::newFromText( "testUpdateRedirect_target" );

		// test with non-redirect page
		$content = $this->newContent( "hello world." );
		$newContent = $content->updateRedirect( $target );

		$this->assertTrue( $content->equals( $newContent ), "content should be unchanged" );

		// test with actual redirect
		$content = $this->newContent( "#REDIRECT [[Someplace]]" );
		$newContent = $content->updateRedirect( $target );

		$this->assertFalse( $content->equals( $newContent ), "content should have changed" );
		$this->assertTrue( $newContent->isRedirect(), "new content should be a redirect" );

		$this->assertEquals( $target->getFullText(), $newContent->getRedirectTarget()->getFullText() );
	}

	/**
	 * @covers WikitextContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getModel() );
	}

	/**
	 * @covers WikitextContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getContentHandler()->getModelID() );
	}

	public function testRedirectParserOption() {
		$title = Title::newFromText( 'testRedirectParserOption' );

		// Set up hook and its reporting variables
		$wikitext = null;
		$redirectTarget = null;
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'InternalParseBeforeLinks' => array(
				function ( &$parser, &$text, &$stripState ) use ( &$wikitext, &$redirectTarget ) {
					$wikitext = $text;
					$redirectTarget = $parser->getOptions()->getRedirectTarget();
				}
			)
		) );

		// Test with non-redirect page
		$wikitext = false;
		$redirectTarget = false;
		$content = $this->newContent( 'hello world.' );
		$options = $content->getContentHandler()->makeParserOptions( 'canonical' );
		$options->setRedirectTarget( $title );
		$content->getParserOutput( $title, null, $options );
		$this->assertEquals( 'hello world.', $wikitext,
			'Wikitext passed to hook was not as expected'
		);
		$this->assertEquals( null, $redirectTarget, 'Redirect seen in hook was not null' );
		$this->assertEquals( $title, $options->getRedirectTarget(),
			'ParserOptions\' redirectTarget was changed'
		);

		// Test with a redirect page
		$wikitext = false;
		$redirectTarget = false;
		$content = $this->newContent( "#REDIRECT [[TestRedirectParserOption/redir]]\nhello redirect." );
		$options = $content->getContentHandler()->makeParserOptions( 'canonical' );
		$content->getParserOutput( $title, null, $options );
		$this->assertEquals( 'hello redirect.', $wikitext, 'Wikitext passed to hook was not as expected' );
		$this->assertNotEquals( null, $redirectTarget, 'Redirect seen in hook was null' );
		$this->assertEquals( 'TestRedirectParserOption/redir', $redirectTarget->getFullText(),
			'Redirect seen in hook was not the expected title'
		);
		$this->assertEquals( null, $options->getRedirectTarget(),
			'ParserOptions\' redirectTarget was changed'
		);
	}

	public static function dataEquals() {
		return array(
			array( new WikitextContent( "hallo" ), null, false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "hallo" ), true ),
			array( new WikitextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ),
			array( new WikitextContent( "hallo" ), new TextContent( "hallo" ), false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "HALLO" ), false ),
		);
	}

	public static function dataGetDeletionUpdates() {
		return array(
			array( "WikitextContentTest_testGetSecondaryDataUpdates_1",
				CONTENT_MODEL_WIKITEXT, "hello ''world''\n",
				array( 'LinksDeletionUpdate' => array() )
			),
			array( "WikitextContentTest_testGetSecondaryDataUpdates_2",
				CONTENT_MODEL_WIKITEXT, "hello [[world test 21344]]\n",
				array( 'LinksDeletionUpdate' => array() )
			),
			// @todo more...?
		);
	}
}
