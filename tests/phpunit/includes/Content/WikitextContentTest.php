<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;

/**
 * @group ContentHandler
 *
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\WikitextContent
 */
class WikitextContentTest extends TextContentTest {
	use ContentSerializationTestTrait;

	public const SECTIONS = "Intro

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

	public static function provideGetSection() {
		return [
			[ self::SECTIONS,
				"0",
				"Intro"
			],
			[ self::SECTIONS,
				"2",
				"== test ==
just a test"
			],
			[ self::SECTIONS,
				"8",
				false
			],
		];
	}

	/**
	 * @dataProvider provideGetSection
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

	public static function provideReplaceSection() {
		return [
			[ self::SECTIONS,
				"0",
				"No more",
				null,
				trim( preg_replace( '/^Intro/m', 'No more', self::SECTIONS ) )
			],
			[ self::SECTIONS,
				"",
				"No more",
				null,
				"No more"
			],
			[ self::SECTIONS,
				"2",
				"== TEST ==\nmore fun",
				null,
				trim( preg_replace(
					'/^== test ==.*== foo ==/sm', "== TEST ==\nmore fun\n\n== foo ==",
					self::SECTIONS
				) )
			],
			[ self::SECTIONS,
				"8",
				"No more",
				null,
				self::SECTIONS
			],
			[ self::SECTIONS,
				"new",
				"No more",
				"New",
				trim( self::SECTIONS ) . "\n\n\n== New ==\n\nNo more"
			],
		];
	}

	/**
	 * @dataProvider provideReplaceSection
	 */
	public function testReplaceSection( $text, $section, $with, $sectionTitle, $expected ) {
		$content = $this->newContent( $text );
		/** @var WikitextContent $c */
		$c = $content->replaceSection( $section, $this->newContent( $with ), $sectionTitle );

		$this->assertEquals( $expected, $c ? $c->getText() : null );
	}

	public function testAddSectionHeader() {
		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( 'test' );
		$this->assertEquals( "== test ==\n\nhello world", $content->getText() );

		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( '' );
		$this->assertEquals( "hello world", $content->getText() );
	}

	public static function providePreSaveTransform() {
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

	public static function provideGetRedirectTargetTextContent() {
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
				'hel...',
			],
		];
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

	public function testUpdateRedirect() {
		$target = Title::makeTitle( NS_MAIN, 'TestUpdateRedirect_target' );

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

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getContentHandler()->getModelID() );
	}

	/**
	 * @covers \MediaWiki\Parser\ParserOptions
	 */
	public function testRedirectParserOption() {
		$title = Title::makeTitle( NS_MAIN, 'TestRedirectParserOption' );
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

	public static function provideEquals() {
		return [
			[ new WikitextContent( "hallo" ), null, false ],
			[ new WikitextContent( "hallo" ), new WikitextContent( "hallo" ), true ],
			[ new WikitextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ],
			[ new WikitextContent( "hallo" ), new TextContent( "hallo" ), false ],
			[ new WikitextContent( "hallo" ), new WikitextContent( "HALLO" ), false ],
		];
	}

	public static function provideGetDeletionUpdates() {
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

	public static function getClassToTest(): string {
		return WikitextContent::class;
	}

	public static function getTestInstancesAndAssertions(): array {
		// Note that WikitextContent::{get,set}PreSaveTransformFlags()
		// is preserved over JSON serialization.
		$pstFlags = [
			ParserOutputFlags::SHOW_TOC->value,
			ParserOutputFlags::VARY_REVISION->value,
		];
		$withPstFlags = new WikitextContent( 'with PST flags' );
		$withPstFlags->setPreSaveTransformFlags( $pstFlags );

		return [
			'basic' => [
				'instance' => new WikitextContent( 'hello' ),
				'assertions' => static function ( $testCase, $obj ) {
					$testCase->assertInstanceof( WikitextContent::class, $obj );
					$testCase->assertSame( 'hello', $obj->getText() );
					$testCase->assertArrayEquals( [], $obj->getPreSaveTransformFlags() );
				},
			],
			'withPstFlags' => [
				'instance' => $withPstFlags,
				'assertions' => static function ( $testCase, $obj ) use ( $pstFlags ) {
					$testCase->assertInstanceof( WikitextContent::class, $obj );
					$testCase->assertSame( 'with PST flags', $obj->getText() );
					$testCase->assertArrayEquals( $pstFlags, $obj->getPreSaveTransformFlags() );
				},
			],
		];
	}
}
