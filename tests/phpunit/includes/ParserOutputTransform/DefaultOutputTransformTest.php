<?php

namespace Mediawiki\ParserOutputTransform;

use LogicException;
use MediaWiki\MainConfigNames;
use MediaWikiLangTestCase;
use ParserOutput;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * @covers \Mediawiki\ParserOutputTransform\DefaultOutputTransform
 * The tests in this file are copied from the tests in ParserOutputTest. They aim at being the sole version
 * once we deprecate ParserOutput::getText.
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class DefaultOutputTransformTest extends MediaWikiLangTestCase {

	/**
	 * @covers \Mediawiki\ParserOutputTransform\DefaultOutputTransform::transform
	 * @dataProvider provideTransform
	 * @param array $options Options to transform()
	 * @param string $text Parser text
	 * @param string $expect Expected output
	 */
	public function testTransform( $options, $text, $expect ) {
		$this->overrideConfigValues( [
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );

		$po = new ParserOutput( $text );
		self::initSections( $po );
		$actual = $this->getServiceContainer()->getDefaultOutputTransform()->transform( $po, $options )
			->getTransformedText();
		$this->assertSame( $expect, $actual );
	}

	private static function initSections( ParserOutput $po ): void {
		$po->setTOCData( new TOCData(
			SectionMetadata::fromLegacy( [
				'index' => "1",
				'level' => 1,
				'toclevel' => 1,
				'number' => "1",
				'line' => "Section 1",
				'anchor' => "Section_1"
			] ),
			SectionMetadata::fromLegacy( [
				'index' => "2",
				'level' => 1,
				'toclevel' => 1,
				'number' => "2",
				'line' => "Section 2",
				'anchor' => "Section_2"
			] ),
			SectionMetadata::fromLegacy( [
				'index' => "3",
				'level' => 2,
				'toclevel' => 2,
				'number' => "2.1",
				'line' => "Section 2.1",
				'anchor' => "Section_2.1"
			] ),
			SectionMetadata::fromLegacy( [
				'index' => "4",
				'level' => 1,
				'toclevel' => 1,
				'number' => "3",
				'line' => "Section 3",
				'anchor' => "Section_3"
			] ),
		) );
	}

	public static function provideTransform() {
		$text = <<<EOF
<p>Test document.
</p>
<meta property="mw:PageProp/toc" />
<h2><span class="mw-headline" id="Section_1">Section 1</span><mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span><mw:editsection page="Talk:User:Bug_T261347" section="3">Section 2.1</mw:editsection></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		$dedupText = <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<style data-mw-deduplicate="duplicate1">.Same-attribute-different-content {}</style>
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF;

		return [
			'No options' => [
				[], $text, <<<EOF
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

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p>
EOF
			],
			'Disable section edit links' => [
				[ 'enableSectionEditLinks' => false ], $text, <<<EOF
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

<h2><span class="mw-headline" id="Section_1">Section 1</span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span></h2>
<p>Three
</p>
EOF
			],
			'Disable TOC, but wrap' => [
				[ 'allowTOC' => false, 'wrapperDivClass' => 'mw-parser-output' ], $text, <<<EOF
<div class="mw-parser-output"><p>Test document.
</p>

<h2><span class="mw-headline" id="Section_1">Section 1</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=1" title="Edit section: Section 1">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>One
</p>
<h2><span class="mw-headline" id="Section_2">Section 2</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=2" title="Edit section: Section 2">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Two
</p>
<h3><span class="mw-headline" id="Section_2.1">Section 2.1</span></h3>
<p>Two point one
</p>
<h2><span class="mw-headline" id="Section_3">Section 3</span><span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="/w/index.php?title=Test_Page&amp;action=edit&amp;section=4" title="Edit section: Section 3">edit</a><span class="mw-editsection-bracket">]</span></span></h2>
<p>Three
</p></div>
EOF
			],
			'Style deduplication' => [
				[], $dedupText, <<<EOF
<p>This is a test document.</p>
<style data-mw-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate2">.Duplicate2 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate2">
<style data-mw-not-deduplicate="duplicate1">.Duplicate1 {}</style>
<link rel="mw-deduplicated-inline-style" href="mw-data:duplicate1">
<style data-mw-deduplicate="duplicate3">.Duplicate1 {}</style>
<style>.Duplicate1 {}</style>
EOF
			],
			'Style deduplication disabled' => [
				[ 'deduplicateStyles' => false ], $dedupText, $dedupText
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers \Mediawiki\ParserOutputTransform\DefaultOutputTransform::transform
	 */
	public function testTransform_failsIfNoText() {
		$po = new ParserOutput( null );

		$this->expectException( LogicException::class );
		$this->getServiceContainer()->getDefaultOutputTransform()->transform( $po, [] );
	}

	public static function provideTransform_absoluteURLs() {
		yield 'empty' => [
			'text' => '',
			'expectedText' => '',
		];
		yield 'no-links' => [
			'text' => '<p>test</p>',
			'expectedText' => '<p>test</p>',
		];
		yield 'simple link' => [
			'text' => '<a href="/wiki/Test">test</a>',
			'expectedText' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'already absolute, relative' => [
			'text' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
			'expectedText' => '<a href="//TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'already absolute, https' => [
			'text' => '<a href="https://TEST_SERVER/wiki/Test">test</a>',
			'expectedText' => '<a href="https://TEST_SERVER/wiki/Test">test</a>',
		];
		yield 'external' => [
			'text' => '<a href="https://en.wikipedia.org/wiki/Test">test</a>',
			'expectedText' => '<a href="https://en.wikipedia.org/wiki/Test">test</a>',
		];
	}

	/**
	 * @covers \Mediawiki\ParserOutputTransform\DefaultOutputTransform::transform
	 * @dataProvider provideTransform_absoluteURLs
	 */
	public function testTransform_absoluteURLs( string $text, string $expectedText ) {
		$this->overrideConfigValue( MainConfigNames::Server, '//TEST_SERVER' );
		$po = new ParserOutput( $text );
		$actual = $this->getServiceContainer()->getDefaultOutputTransform()
			->transform( $po, [ 'absoluteURLs' => true ] )->getTransformedText();
		$this->assertSame( $expectedText, $actual );
	}
}
