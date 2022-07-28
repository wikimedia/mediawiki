<?php

use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;

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

	public static function dataGetSection() {
		return [
			[ self::$sections,
				"0",
				"Intro"
			],
			[ self::$sections,
				"2",
				"== test ==
just a test"
			],
			[ self::$sections,
				"8",
				false
			],
		];
	}

	/**
	 * @dataProvider dataGetSection
	 * @covers WikitextContent::getSection
	 */
	public function testGetSection( $text, $sectionId, $expectedText ) {
		$content = $this->newContent( $text );

		$sectionContent = $content->getSection( $sectionId );
		if ( is_object( $sectionContent ) ) {
			$sectionText = $sectionContent->getText();
		} else {
			$sectionText = $sectionContent;
		}

		$this->assertEquals( $expectedText, $sectionText );
	}

	public static function dataReplaceSection() {
		return [
			[ self::$sections,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/sm', 'No more', self::$sections ) )
			],
			[ self::$sections,
				"",
				"No more",
				null,
				"No more"
			],
			[ self::$sections,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace(
					'/^== test ==.*== foo ==/sm', "== TEST ==\nmore fun\n\n== foo ==",
					self::$sections
				) )
			],
			[ self::$sections,
				"8",
				"No more",
				null,
				self::$sections
			],
			[ self::$sections,
				"new",
				"No more",
				"New",
				trim( self::$sections ) . "\n\n\n== New ==\n\nNo more"
			],
		];
	}

	/**
	 * @dataProvider dataReplaceSection
	 * @covers WikitextContent::replaceSection
	 */
	public function testReplaceSection( $text, $section, $with, $sectionTitle, $expected ) {
		$content = $this->newContent( $text );
		/** @var WikitextContent $c */
		$c = $content->replaceSection( $section, $this->newContent( $with ), $sectionTitle );

		$this->assertEquals( $expected, $c ? $c->getText() : null );
	}

	/**
	 * @covers WikitextContent::addSectionHeader
	 */
	public function testAddSectionHeader() {
		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( 'test' );
		$this->assertEquals( "== test ==\n\nhello world", $content->getText() );

		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( '' );
		$this->assertEquals( "hello world", $content->getText() );
	}

	public static function dataPreSaveTransform() {
		return [
			[ 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			],
			[ 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			],
			[ // rtrim
				" Foo \n ",
				" Foo",
			],
		];
	}

	public static function dataGetRedirectTarget() {
		return [
			[ '#REDIRECT [[Test]]',
				'Test',
			],
			[ '#REDIRECT Test',
				null,
			],
			[ '* #REDIRECT [[Test]]',
				null,
			],
		];
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
				'hel...',
			],
		];
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
			[ 'Foo',
				null,
				'link',
				false
			],
			[ 'Foo [[bar]]',
				null,
				'link',
				true
			],
			[ 'Foo',
				true,
				'link',
				true
			],
			[ 'Foo [[bar]]',
				false,
				'link',
				false
			],
			[ '#REDIRECT [[bar]]',
				true,
				'any',
				false
			],
			[ '#REDIRECT [[bar]]',
				true,
				'link',
				false
			],
		];
	}

	/**
	 * @covers WikitextContent::matchMagicWord
	 */
	public function testMatchMagicWord() {
		$mw = $this->getServiceContainer()->getMagicWordFactory()->get( "staticredirect" );

		$content = $this->newContent( "#REDIRECT [[FOO]]\n__STATICREDIRECT__" );
		$this->assertTrue( $content->matchMagicWord( $mw ), "should have matched magic word" );

		$content = $this->newContent( "#REDIRECT [[FOO]]" );
		$this->assertFalse(
			$content->matchMagicWord( $mw ),
			"should not have matched magic word"
		);
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

		$this->assertEquals(
			$target->getFullText(),
			$newContent->getRedirectTarget()->getFullText()
		);
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

	/**
	 * @covers ParserOptions::getRedirectTarget
	 * @covers ParserOptions::setRedirectTarget
	 */
	public function testRedirectParserOption() {
		$title = Title::newFromText( 'testRedirectParserOption' );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();

		// Set up hook and its reporting variables
		$wikitext = null;
		$redirectTarget = null;
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'InternalParseBeforeLinks' => [
				static function ( Parser $parser, $text, $stripState ) use ( &$wikitext, &$redirectTarget ) {
					$wikitext = $text;
					$redirectTarget = $parser->getOptions()->getRedirectTarget();
				}
			]
		] );

		// Test with non-redirect page
		$wikitext = false;
		$redirectTarget = false;
		$content = $this->newContent( 'hello world.' );
		$options = ParserOptions::newFromAnon();
		$options->setRedirectTarget( $title );
		$contentRenderer->getParserOutput( $content, $title, null, $options );
		$this->assertEquals( 'hello world.', $wikitext,
			'Wikitext passed to hook was not as expected'
		);
		$this->assertNull( $redirectTarget, 'Redirect seen in hook was not null' );
		$this->assertEquals( $title, $options->getRedirectTarget(),
			'ParserOptions\' redirectTarget was changed'
		);

		// Test with a redirect page
		$wikitext = false;
		$redirectTarget = false;
		$content = $this->newContent(
			"#REDIRECT [[TestRedirectParserOption/redir]]\nhello redirect."
		);
		$options = ParserOptions::newFromAnon();
		$contentRenderer->getParserOutput( $content, $title, null, $options );
		$this->assertEquals(
			'hello redirect.',
			$wikitext,
			'Wikitext passed to hook was not as expected'
		);
		$this->assertNotEquals(
			null,
			$redirectTarget,
			'Redirect seen in hook was null' );
		$this->assertEquals(
			'TestRedirectParserOption/redir',
			$redirectTarget->getFullText(),
			'Redirect seen in hook was not the expected title'
		);
		$this->assertNull(
			$options->getRedirectTarget(),
			'ParserOptions\' redirectTarget was changed'
		);
	}

	public static function dataEquals() {
		return [
			[ new WikitextContent( "hallo" ), null, false ],
			[ new WikitextContent( "hallo" ), new WikitextContent( "hallo" ), true ],
			[ new WikitextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ],
			[ new WikitextContent( "hallo" ), new TextContent( "hallo" ), false ],
			[ new WikitextContent( "hallo" ), new WikitextContent( "HALLO" ), false ],
		];
	}

	public static function dataGetDeletionUpdates() {
		return [
			[
				CONTENT_MODEL_WIKITEXT, "hello ''world''\n",
				[ LinksDeletionUpdate::class => [] ]
			],
			[
				CONTENT_MODEL_WIKITEXT, "hello [[world test 21344]]\n",
				[ LinksDeletionUpdate::class => [] ]
			],
			// @todo more...?
		];
	}
}
