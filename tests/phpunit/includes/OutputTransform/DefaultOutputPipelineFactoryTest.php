<?php

namespace MediaWiki\Tests\OutputTransform;

use LogicException;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWikiLangTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @covers \MediaWiki\OutputTransform\DefaultOutputPipelineFactory
 * The tests in this file are copied from the tests in ParserOutputTest. They aim at being the sole version
 * once we deprecate ParserOutput::getText. Some of them have been moved to their specific pipeline stage instead.
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class DefaultOutputPipelineFactoryTest extends MediaWikiLangTestCase {

	/**
	 * @covers \MediaWiki\OutputTransform\DefaultOutputPipelineFactory::buildPipeline
	 * @dataProvider provideTransform
	 * @param array $options Options to transform()
	 * @param string $text Parser text
	 * @param string $expect Expected output
	 */
	public function testTransform( $options, $text, $expect ) {
		// Avoid other skins or extensions affecting the section edit links
		$this->overrideConfigValue( MainConfigNames::DefaultSkin, 'fallback' );
		$this->clearHook( 'SkinEditSectionLinks' );
		RequestContext::resetMain();

		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );

		$po = new ParserOutput( $text );
		if ( $options['isParsoidContent'] ?? false ) {
			$po = PageBundleParserOutputConverter::parserOutputFromPageBundle( new PageBundle( $text ) );
			$po->setExtensionData( ParsoidParser::PARSOID_TITLE_KEY, 'Test_page' );
			$po->setLanguage( new Bcp47CodeValue( 'en' ) );
				global $IP;
				$msgDirs = [];
				$msgDirs[] = "$IP/tests/phpunit/data/OutputTransform/i18n";
				$this->overrideConfigValue( MainConfigNames::MessagesDirs, $msgDirs );
		}

		TestUtils::initSections( $po );
		$actual = $this->getServiceContainer()->getDefaultOutputPipeline()
			->run( $po, null, $options )->getContentHolderText();
		$this->assertSame( $expect, $actual );
	}

	public static function provideTransform() {
		return [
			'No options' => [
				[], TestUtils::TEST_DOC, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc" role="navigation" aria-labelledby="mw-toc-heading"><input type="checkbox" role="button" id="toctogglecheckbox" class="toctogglecheckbox" style="display:none" /><div class="toctitle" lang="en" dir="ltr"><h2 id="mw-toc-heading">Contents</h2><span class="toctogglespan"><label class="toctogglelabel" for="toctogglecheckbox"></label></span></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<div class="mw-heading mw-heading2"><h2 id="Section_1">Section 1</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>One
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_2">Section 2</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>Two
</p>
<div class="mw-heading mw-heading3"><h3 id="Section_2.1">Section 2.1</h3></div>
<p>Two point one
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_3">Section 3</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>Three
</p>
EOF
			],
			'Disable section edit links' => [
				[ 'enableSectionEditLinks' => false ], TestUtils::TEST_DOC, <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc" role="navigation" aria-labelledby="mw-toc-heading"><input type="checkbox" role="button" id="toctogglecheckbox" class="toctogglecheckbox" style="display:none" /><div class="toctitle" lang="en" dir="ltr"><h2 id="mw-toc-heading">Contents</h2><span class="toctogglespan"><label class="toctogglelabel" for="toctogglecheckbox"></label></span></div>
<ul>
<li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li>
<li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a>
<ul>
<li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext">Section 2.1</span></a></li>
</ul>
</li>
<li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li>
</ul>
</div>

<div class="mw-heading mw-heading2"><h2 id="Section_1">Section 1</h2></div>
<p>One
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_2">Section 2</h2></div>
<p>Two
</p>
<div class="mw-heading mw-heading3"><h3 id="Section_2.1">Section 2.1</h3></div>
<p>Two point one
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_3">Section 3</h2></div>
<p>Three
</p>
EOF
			],
			'Disable TOC, but wrap' => [
				[ 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ], TestUtils::TEST_DOC, <<<EOF
<div class="mw-content-ltr mw-parser-output" lang="en" dir="ltr"><p>Test document.
</p>

<div class="mw-heading mw-heading2"><h2 id="Section_1">Section 1</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>One
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_2">Section 2</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>Two
</p>
<div class="mw-heading mw-heading3"><h3 id="Section_2.1">Section 2.1</h3></div>
<p>Two point one
</p>
<div class="mw-heading mw-heading2"><h2 id="Section_3">Section 3</h2><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></div>
<p>Three
</p></div>
EOF
			],
			'Style deduplication disabled' => [
				[ 'deduplicateStyles' => false ], TestUtils::TEST_TO_DEDUP, TestUtils::TEST_TO_DEDUP
			],
			'Section edit links + localization' => [
				[ 'isParsoidContent' => true, 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ],
				TestUtils::TEST_MULTI_STAGE, TestUtils::TEST_MULTI_STAGE_POST_PIPELINE
			]
		];
		// phpcs:enable
	}

	/**
	 * @covers \MediaWiki\OutputTransform\DefaultOutputPipelineFactory::buildPipeline
	 */
	public function testTransform_failsIfNoText() {
		$po = new ParserOutput( null );

		$this->expectException( LogicException::class );
		$this->getServiceContainer()->getDefaultOutputPipeline()
			->run( $po, null, [] );
	}
}
