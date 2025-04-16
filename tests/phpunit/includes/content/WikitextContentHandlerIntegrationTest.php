<?php

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\Parsoid\Parsoid;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\WikitextContentHandler
 */
class WikitextContentHandlerIntegrationTest extends TextContentHandlerIntegrationTest {
	protected function setUp(): void {
		parent::setUp();

		// Set up temporary interwiki links for 'en' and 'google'
		$defaults = [
			'iw_local' => 0,
			'iw_api' => '/w/api.php',
			'iw_url' => ''
		];
		$this->overrideConfigValue(
			MainConfigNames::InterwikiCache,
			ClassicInterwikiLookup::buildCdbHash( [
				[
					'iw_prefix' => 'en',
					'iw_url' => 'https://en.wikipedia.org/wiki/$1',
					'iw_wikiid' => 'enwiki',
				] + $defaults,
				[
					'iw_prefix' => 'google',
					'iw_url' => 'https://google.com/?q=$1',
					'iw_wikiid' => 'google',
				] + $defaults,
			] )
		);
		// Limit reporting affects the options used
		$this->overrideConfigValue(
			MainConfigNames::EnableParserLimitReporting,
			true
		);
	}

	public static function provideGetParserOutput() {
		$commonOptions = [
			'collapsibleSections',
			'disableContentConversion',
			'interfaceMessage',
			'isPreview',
			'maxIncludeSize',
			'suppressSectionEditLinks',
			'useParsoid',
			'isMessage',
			'wrapclass',
			'expensiveParserFunctionLimit',
			'maxPPExpandDepth',
			'maxPPNodeCount',
		];
		$commonParsoidOptions = array_merge( $commonOptions, [
			'currentRevisionRecordCallback',
		] );
		$commonLegacyOptions = array_merge( $commonOptions, [
			'disableTitleConversion',
			'targetLanguage',
		] );
		$parsoidVersion =
			'data-mw-parsoid-version="' . Parsoid::version() . '" ' .
			'data-mw-html-version="' . Parsoid::defaultHTMLVersion() . '"';

		yield 'Basic render' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "hello ''world''\n",
			'expectedHtml' => '<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr">' . "<p>hello <i>world</i>\n</p></div>",
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
				],
				'UsedOptions' => $commonLegacyOptions,
			],
		];
		yield 'Basic Parsoid render' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "hello ''world''\n",
			'expectedHtml' => "<div class=\"mw-content-ltr mw-parser-output\" lang=\"en\" dir=\"ltr\" $parsoidVersion><section data-mw-section-id=\"0\" id=\"mwAQ\"><p id=\"mwAg\">hello <i id=\"mwAw\">world</i></p>\n</section></div>",
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
				],
				'UsedOptions' => $commonParsoidOptions,
			],
			'options' => [ 'useParsoid' => true, 'suppressSectionEditLinks' => true ],
		];
		yield 'Parsoid render (redirect page)' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "#REDIRECT [[Main Page]]",
			'expectedHtml' => "<div class=\"mw-content-ltr mw-parser-output\" lang=\"en\" dir=\"ltr\" $parsoidVersion><div class=\"redirectMsg\"><p>Redirect to:</p><ul class=\"redirectText\"><li><a href=\"/w/index.php?title=Main_Page&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Main Page (page does not exist)\">Main Page</a></li></ul></div><section data-mw-section-id=\"0\" id=\"mwAQ\"><link rel=\"mw:PageProp/redirect\" href=\"./Main_Page\" id=\"mwAg\"></section></div>",
			'expectedFields' => [
				'Links' => [
					[ 'Main_Page' => 0 ],
				],
				'Sections' => [
				],
				'UsedOptions' => $commonParsoidOptions,
			],
			'options' => [ 'useParsoid' => true, 'suppressSectionEditLinks' => true ],
		];
		yield 'Parsoid render (section edit links)' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "== Hello ==",
			'expectedHtml' => "<div class=\"mw-content-ltr mw-parser-output\" lang=\"en\" dir=\"ltr\" $parsoidVersion id=\"mwAw\">" . '<section data-mw-section-id="0" id="mwAQ"></section><section data-mw-section-id="1" id="mwAg"><div class="mw-heading mw-heading2" id="mwBA"><h2 id="Hello">Hello</h2><span class="mw-editsection" id="mwBQ"><span class="mw-editsection-bracket" id="mwBg">[</span><a href="/w/index.php?title=WikitextContentTest_testGetParserOutput&amp;action=edit&amp;section=1" title="Edit section: Hello" id="mwBw"><span id="mwCA">edit</span></a><span class="mw-editsection-bracket" id="mwCQ">]</span></span></div></section></div>',
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Hello',
						'number' => '1',
						'index' => '1',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 0,
						'anchor' => 'Hello',
						'linkAnchor' => 'Hello',
					],
				],
				'UsedOptions' => $commonParsoidOptions,
			],
			'options' => [ 'useParsoid' => true ],
		];
		yield 'Links' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "[[title that does not really exist]]",
			'expectedHtml' => null,
			'expectedFields' => [
				'Links' => [
					[ 'Title_that_does_not_really_exist' => 0, ],
				],
				'Sections' => [
				],
			],
		];
		yield 'TOC' => [
			'title' => 'WikitextContentTest_testGetParserOutput',
			'model' => CONTENT_MODEL_WIKITEXT,
			'text' => "==One==\n==Two==\n==Three==\n==Four==\n<h2>Five</h2>\n===Six+Seven %2525===",
			'expectedHtml' => null,
			'expectedFields' => [
				'Links' => [
				],
				'Sections' => [
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'One',
						'number' => '1',
						'index' => '1',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 0,
						'anchor' => 'One',
						'linkAnchor' => 'One',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Two',
						'number' => '2',
						'index' => '2',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 8,
						'anchor' => 'Two',
						'linkAnchor' => 'Two',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Three',
						'number' => '3',
						'index' => '3',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 16,
						'anchor' => 'Three',
						'linkAnchor' => 'Three',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Four',
						'number' => '4',
						'index' => '4',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 26,
						'anchor' => 'Four',
						'linkAnchor' => 'Four',
					],
					[
						'toclevel' => 1,
						'level' => '2',
						'line' => 'Five',
						'number' => '5',
						'index' => '',
						'fromtitle' => false,
						'byteoffset' => null,
						'anchor' => 'Five',
						'linkAnchor' => 'Five',
					],
					[
						'toclevel' => 2,
						'level' => '3',
						'line' => 'Six+Seven %2525',
						'number' => '5.1',
						'index' => '5',
						'fromtitle' => 'WikitextContentTest_testGetParserOutput',
						'byteoffset' => 49,
						'anchor' => 'Six+Seven_%2525',
						'linkAnchor' => 'Six+Seven_%252525',
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetParserOutput
	 */
	public function testGetParserOutput( $title, $model, $text, $expectedHtml,
		$expectedFields = null, $options = null
	) {
		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::FragmentMode => [ 'html5' ],
		] );

		$parserOptions = null;
		if ( $options ) {
			$parserOptions = ParserOptions::newFromAnon();
			foreach ( $options as $key => $val ) {
				$parserOptions->setOption( $key, $val );
			}
		}
		parent::testGetParserOutput(
			$title, $model, $text, $expectedHtml, $expectedFields, $parserOptions
		);
	}

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @param LinkTarget $target
	 * @param string $expectedWT Serialized wikitext form of the content object built
	 * @param string $expectedTarget Expected target string in the HTML redirect
	 */
	public function testMakeRedirectContent( LinkTarget $target, string $expectedWT, string $expectedTarget ) {
		$handler = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_WIKITEXT );
		$content = $handler->makeRedirectContent( Title::newFromLinkTarget( $target ) );
		$this->assertEquals( $expectedWT, $content->serialize() );

		// Check that an appropriate redirect header was added to the
		// ParserOutput
		$parserOutput = $handler->getParserOutput(
			$content,
			new ContentParseParams( Title::newMainPage() )
		);
		$redirectHeader = $parserOutput->getRedirectHeader();
		$this->assertStringContainsString( '<div class="redirectMsg"', $redirectHeader );
		$this->assertStringContainsString( '<link rel="mw:PageProp/redirect"', $redirectHeader );
		$this->assertMatchesRegularExpression( '!<a[^<>]+>' . $expectedTarget . '</a>!', $redirectHeader );
	}

	public static function provideMakeRedirectContent() {
		return [
			[ new TitleValue( NS_MAIN, 'Hello' ), '#REDIRECT [[Hello]]', 'Hello' ],
			[ new TitleValue( NS_TEMPLATE, 'Hello' ), '#REDIRECT [[Template:Hello]]', 'Template:Hello' ],
			[ new TitleValue( NS_MAIN, 'Hello', 'section' ), '#REDIRECT [[Hello#section]]', 'Hello#section' ],
			[ new TitleValue( NS_USER, 'John doe', 'section' ), '#REDIRECT [[User:John doe#section]]', 'User:John doe#section' ],
			[ new TitleValue( NS_MEDIAWIKI, 'FOOBAR' ), '#REDIRECT [[MediaWiki:FOOBAR]]', 'MediaWiki:FOOBAR' ],
			[ new TitleValue( NS_CATEGORY, 'Foo' ), '#REDIRECT [[:Category:Foo]]', 'Category:Foo' ],
			[ new TitleValue( NS_MAIN, 'en:Foo' ), '#REDIRECT [[en:Foo]]', 'en:Foo' ],
			[ new TitleValue( NS_MAIN, 'Foo', '', 'en' ), '#REDIRECT [[:en:Foo]]', 'en:Foo' ],
			[
				new TitleValue( NS_MAIN, 'Bar', 'fragment', 'google' ),
				'#REDIRECT [[google:Bar#fragment]]',
				'google:Bar#fragment'
			],
		];
	}
}
