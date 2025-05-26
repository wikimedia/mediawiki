<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Context\RequestContext;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * Parser-related tests that don't suit for parserTests.txt
 *
 * @group Database
 */
class ExtraParserTest extends MediaWikiIntegrationTestCase {

	/** @var ParserOptions */
	protected $options;
	/** @var Parser */
	protected $parser;

	protected function setUp(): void {
		parent::setUp();

		$this->setUserLang( 'en' );
		$this->overrideConfigValues( [
			MainConfigNames::ShowExceptionDetails => true,
			MainConfigNames::CleanSignatures => true,
			MainConfigNames::LanguageCode => 'en',
		] );

		$services = $this->getServiceContainer();
		$contLang = $services->getContentLanguage();

		// FIXME: This test should pass without setting global content language
		$this->options = ParserOptions::newFromUserAndLang( new User, $contLang );
		$this->options->setTemplateCallback( [ __CLASS__, 'statelessFetchTemplate' ] );

		$this->parser = $services->getParserFactory()->create();
	}

	/**
	 * @see T10689
	 * @covers \MediaWiki\Parser\Parser::parse
	 */
	public function testLongNumericLinesDontKillTheParser() {
		$longLine = '1.' . str_repeat( '1234567890', 100000 ) . "\n";

		$title = Title::makeTitle( NS_MAIN, 'Unit test' );
		$options = ParserOptions::newFromUser( new User() );
		$this->assertEquals( "<p>$longLine</p>",
			$this->parser->parse( $longLine, $title, $options )->getRawText() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::braceSubstitution
	 * @covers \MediaWiki\SpecialPage\SpecialPageFactory::capturePath
	 */
	public function testSpecialPageTransclusionRestoresGlobalState() {
		$text = "{{Special:ApiHelp/help}}";
		$title = Title::makeTitle( NS_MAIN, 'TestSpecialPageTransclusionRestoresGlobalState' );
		$options = ParserOptions::newFromUser( new User() );

		RequestContext::getMain()->setTitle( $title );

		$parsed = $this->parser->parse( $text, $title, $options )->getRawText();
		$this->assertStringContainsString( 'apihelp-header', $parsed );
	}

	/**
	 * Test the parser entry points
	 * @covers \MediaWiki\Parser\Parser::parse
	 */
	public function testParse() {
		$title = Title::newFromText( __FUNCTION__ );
		$parserOutput = $this->parser->parse( "Test\n{{Foo}}\n{{Bar}}", $title, $this->options );
		$this->assertEquals(
			"<p>Test\nContent of <i>Template:Foo</i>\nContent of <i>Template:Bar</i>\n</p>",
			$parserOutput->getRawText()
		);
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::preSaveTransform
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

		$outputText = $this->parser->preSaveTransform(
			"hello\n\n{{subst:ns:0}}",
			$title,
			new User(),
			$this->options
		);
		$this->assertEquals( "hello", $outputText,
			"Pre-save transform removes trailing whitespace after substituting templates" );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::preprocess
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
	 * @covers \MediaWiki\Parser\Parser::cleanSig
	 */
	public function testCleanSig() {
		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$this->assertEquals( "{{SUBST:Foo}} ", $outputText );
	}

	/**
	 * cleanSig() should do nothing if disabled
	 * @covers \MediaWiki\Parser\Parser::cleanSig
	 */
	public function testCleanSigDisabled() {
		$this->overrideConfigValue( MainConfigNames::CleanSignatures, false );

		$outputText = $this->parser->cleanSig( "{{Foo}} ~~~~" );

		$this->assertEquals( "{{Foo}} ~~~~", $outputText );
	}

	/**
	 * cleanSigInSig() just removes tildes
	 * @dataProvider provideStringsForCleanSigInSig
	 * @covers \MediaWiki\Parser\Parser::cleanSigInSig
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
	 * @covers \MediaWiki\Parser\Parser::getSection
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
	 * @covers \MediaWiki\Parser\Parser::replaceSection
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
	 * @covers \MediaWiki\Parser\Parser::getPreloadText
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
	public static function statelessFetchTemplate( $title, $parser = false ) {
		$text = "Content of ''" . $title->getFullText() . "''";
		$deps = [];

		return [
			'text' => $text,
			'finalTitle' => $title,
			'deps' => $deps ];
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::parse
	 */
	public function testTrackingCategory() {
		$title = Title::newFromText( __FUNCTION__ );
		$catName = wfMessage( 'broken-file-category' )->inContentLanguage()->text();
		$cat = Title::makeTitleSafe( NS_CATEGORY, $catName );
		$expected = [ $cat->getDBkey() ];
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]", $title, $this->options );
		$result = $parserOutput->getCategoryNames();
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::parse
	 */
	public function testTrackingCategorySpecial() {
		// Special pages shouldn't have tracking cats.
		$title = SpecialPage::getTitleFor( 'Contributions' );
		$parserOutput = $this->parser->parse( "[[file:nonexistent]]", $title, $this->options );
		$result = $parserOutput->getCategoryNames();
		$this->assertSame( [], $result );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::parseLinkParameter
	 * @dataProvider provideParseLinkParameter
	 */
	public function testParseLinkParameter( $input, $expected, $expectedLinks, $desc ) {
		static $testInterwikis = [
			[
				'iw_prefix' => 'local',
				'iw_url' => 'http://doesnt.matter.invalid/$1',
				'iw_api' => '',
				'iw_wikiid' => '',
				'iw_local' => 0
			],
			[
				'iw_prefix' => 'mw',
				'iw_url' => 'https://www.mediawiki.org/wiki/$1',
				'iw_api' => 'https://www.mediawiki.org/w/api.php',
				'iw_wikiid' => '',
				'iw_local' => 0
			]
		];
		$this->overrideConfigValue(
			MainConfigNames::InterwikiCache,
			ClassicInterwikiLookup::buildCdbHash( $testInterwikis )
		);

		$this->parser->startExternalParse(
			Title::newFromText( __FUNCTION__ ),
			$this->options,
			Parser::OT_HTML
		);
		$output = TestingAccessWrapper::newFromObject( $this->parser )
			->parseLinkParameter( $input );

		$this->assertEquals( $expected[0], $output[0], "$desc (type)" );

		if ( $expected[0] === 'link-title' ) {
			$this->assertTrue(
				$output[1]->equals( Title::newFromText( $expected[1] ) ),
				"$desc (target); link list title instance matches new title instance"
			);
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
				[ 'link-title', 'Test' ],
				[ 'getLinks' => [ 0 => [ 'Test' => 0 ] ] ],
				'Internal link',
			],
			[
				'mw:Test',
				[ 'link-title', 'mw:Test' ],
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
