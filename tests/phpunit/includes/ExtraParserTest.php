<?php

use MediaWiki\MediaWikiServices;

/**
 * Parser-related tests that don't suit for parserTests.txt
 *
 * @group Database
 */
class ExtraParserTest extends MediaWikiTestCase {

	/** @var ParserOptions */
	protected $options;
	/** @var Parser */
	protected $parser;

	protected function setUp() {
		parent::setUp();

		$contLang = Language::factory( 'en' );
		$this->setMwGlobals( [
			'wgShowExceptionDetails' => true,
			'wgCleanSignatures' => true,
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( $contLang );

		// FIXME: This test should pass without setting global content language
		$this->options = ParserOptions::newFromUserAndLang( new User, $contLang );
		$this->options->setTemplateCallback( [ __CLASS__, 'statelessFetchTemplate' ] );
		$this->parser = new Parser;

		MediaWikiServices::getInstance()->resetServiceForTesting( 'MagicWordFactory' );
	}

	/**
	 * @see T10689
	 * @covers Parser::parse
	 */
	public function testLongNumericLinesDontKillTheParser() {
		$longLine = '1.' . str_repeat( '1234567890', 100000 ) . "\n";

		$title = Title::newFromText( 'Unit test' );
		$options = ParserOptions::newFromUser( new User() );
		$this->assertEquals( "<p>$longLine</p>",
			$this->parser->parse( $longLine, $title, $options )->getText( [ 'unwrap' => true ] ) );
	}

	/**
	 * @covers Parser::braceSubstitution
	 * @covers SpecialPageFactory::capturePath
	 */
	public function testSpecialPageTransclusionRestoresGlobalState() {
		$text = "{{Special:ApiHelp/help}}";
		$title = Title::newFromText( 'testSpecialPageTransclusionRestoresGlobalState' );
		$options = ParserOptions::newFromUser( new User() );

		RequestContext::getMain()->setTitle( $title );
		RequestContext::getMain()->getWikiPage()->CustomTestProp = true;

		$parsed = $this->parser->parse( $text, $title, $options )->getText();
		$this->assertContains( 'apihelp-header', $parsed );

		// Verify that this property wasn't wiped out by the parse
		$this->assertTrue( RequestContext::getMain()->getWikiPage()->CustomTestProp );
	}

	/**
	 * Test the parser entry points
	 * @covers Parser::parse
	 */
	public function testParse() {
		$title = Title::newFromText( __FUNCTION__ );
		$parserOutput = $this->parser->parse( "Test\n{{Foo}}\n{{Bar}}", $title, $this->options );
		$this->assertEquals(
			"<p>Test\nContent of <i>Template:Foo</i>\nContent of <i>Template:Bar</i>\n</p>",
			$parserOutput->getText( [ 'unwrap' => true ] )
		);
	}

	/**
	 * @covers Parser::preSaveTransform
	 */
	public function testPreSaveTransform() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->preSaveTransform(
			"Test\r\n{{subst:Foo}}\n{{Bar}}",
			$title,
			new User(),
			$this->options
		);

		$this->assertEquals( "Test\nContent of ''Template:Foo''\n{{Bar}}", $outputText );
	}

	/**
	 * @covers Parser::preprocess
	 */
	public function testPreprocess() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->preprocess( "Test\n{{Foo}}\n{{Bar}}", $title, $this->options );

		$this->assertEquals(
			"Test\nContent of ''Template:Foo''\nContent of ''Template:Bar''",
			$outputText
		);
	}

	/**
	 * cleanSig() makes all templates substs and removes tildes
	 * @covers Parser::cleanSig
	 */
	public function testCleanSig() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$this->assertEquals( "{{SUBST:Foo}} ", $outputText );
	}

	/**
	 * cleanSig() should do nothing if disabled
	 * @covers Parser::cleanSig
	 */
	public function testCleanSigDisabled() {
		$this->setMwGlobals( 'wgCleanSignatures', false );

		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$this->assertEquals( "{{Foo}} ~~~~", $outputText );
	}

	/**
	 * cleanSigInSig() just removes tildes
	 * @dataProvider provideStringsForCleanSigInSig
	 * @covers Parser::cleanSigInSig
	 */
	public function testCleanSigInSig( $in, $out ) {
		$this->assertEquals( Parser::cleanSigInSig( $in ), $out );
	}

	public static function provideStringsForCleanSigInSig() {
		return [
			[ "{{Foo}} ~~~~", "{{Foo}} " ],
			[ "~~~", "" ],
			[ "~~~~~", "" ],
		];
	}

	/**
	 * @covers Parser::getSection
	 */
	public function testGetSection() {
		$outputText2 = $this->parser->getSection(
			"Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\n"
				. "Section 2\n== Heading 3 ==\nSection 3\n",
			2
		);
		$outputText1 = $this->parser->getSection(
			"Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\n"
				. "Section 2\n== Heading 3 ==\nSection 3\n",
			1
		);

		$this->assertEquals( "=== Heading 2 ===\nSection 2", $outputText2 );
		$this->assertEquals( "== Heading 1 ==\nSection 1\n=== Heading 2 ===\nSection 2", $outputText1 );
	}

	/**
	 * @covers Parser::replaceSection
	 */
	public function testReplaceSection() {
		$outputText = $this->parser->replaceSection(
			"Section 0\n== Heading 1 ==\nSection 1\n=== Heading 2 ===\n"
				. "Section 2\n== Heading 3 ==\nSection 3\n",
			1,
			"New section 1"
		);

		$this->assertEquals( "Section 0\nNew section 1\n\n== Heading 3 ==\nSection 3", $outputText );
	}

	/**
	 * Templates and comments are not affected, but noinclude/onlyinclude is.
	 * @covers Parser::getPreloadText
	 */
	public function testGetPreloadText() {
		$title = Title::newFromText( __FUNCTION__ );
		$outputText = $this->parser->getPreloadText(
			"{{Foo}}<noinclude> censored</noinclude> information <!-- is very secret -->",
			$title,
			$this->options
		);

		$this->assertEquals( "{{Foo}} information <!-- is very secret -->", $outputText );
	}

	/**
	 * @param Title $title
	 * @param bool $parser
	 *
	 * @return array
	 */
	static function statelessFetchTemplate( $title, $parser = false ) {
		$text = "Content of ''" . $title->getFullText() . "''";
		$deps = [];

		return [
			'text' => $text,
			'finalTitle' => $title,
			'deps' => $deps ];
	}

	/**
	 * @covers Parser::parse
	 */
	public function testTrackingCategory() {
		$title = Title::newFromText( __FUNCTION__ );
		$catName = wfMessage( 'broken-file-category' )->inContentLanguage()->text();
		$cat = Title::makeTitleSafe( NS_CATEGORY, $catName );
		$expected = [ $cat->getDBkey() ];
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]", $title, $this->options );
		$result = $parserOutput->getCategoryLinks();
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @covers Parser::parse
	 */
	public function testTrackingCategorySpecial() {
		// Special pages shouldn't have tracking cats.
		$title = SpecialPage::getTitleFor( 'Contributions' );
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]", $title, $this->options );
		$result = $parserOutput->getCategoryLinks();
		$this->assertEmpty( $result );
	}

	/**
	 * @covers Parser::parseLinkParameter
	 * @dataProvider provideParseLinkParameter
	 */
	public function testParseLinkParameter( $input, $expected, $expectedLinks, $desc ) {
		$this->parser->startExternalParse( Title::newFromText( __FUNCTION__ ),
			$this->options, Parser::OT_HTML );
		$output = $this->parser->parseLinkParameter( $input );

		$this->assertEquals( $expected[0], $output[0], "$desc (type)" );

		if ( $expected[0] === 'link-title' ) {
			$this->assertTrue( $expected[1]->equals( $output[1] ), "$desc (target)" );
		} else {
			$this->assertEquals( $expected[1], $output[1], "$desc (target)" );
		}

		foreach ( $expectedLinks as $func => $expected ) {
			$output = $this->parser->getOutput()->$func();
			$this->assertEquals( $expected, $output, "$desc ($func)" );
		}
	}

	public static function provideParseLinkParameter() {
		return [
			[
				'',
				[ 'no-link', false ],
				[],
				'Return no link when requested',
			],
			[
				'https://example.com/',
				[ 'link-url', 'https://example.com/' ],
				[ 'getExternalLinks' => [ 'https://example.com/' => 1 ] ],
				'External link',
			],
			[
				'//example.com/',
				[ 'link-url', '//example.com/' ],
				[ 'getExternalLinks' => [ '//example.com/' => 1 ] ],
				'External link',
			],
			[
				'Test',
				[ 'link-title', Title::newFromText( 'Test' ) ],
				[ 'getLinks' => [ 0 => [ 'Test' => 0 ] ] ],
				'Internal link',
			],
			[
				'mw:Test',
				[ 'link-title', Title::newFromText( 'mw:Test' ) ],
				[ 'getInterwikiLinks' => [ 'mw' => [ 'Test' => 1 ] ] ],
				'Internal link (interwiki)',
			],
			[
				'https://',
				[ null, false ],
				[],
				'Invalid link target',
			],
			[
				'<>',
				[ null, false ],
				[],
				'Invalid link target',
			],
			[
				' ',
				[ null, false ],
				[],
				'Invalid link target',
			],
		];
	}
}
