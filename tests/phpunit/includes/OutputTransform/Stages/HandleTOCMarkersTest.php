<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\Language;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleTOCMarkers;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Skin\Skin;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\HandleTOCMarkers
 */
class HandleTOCMarkersTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HandleTOCMarkers(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getTidy()
		);
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [] ],
			[ new ParserOutput(), null, [ 'allowTOC' => false, 'injectTOC' => false ] ],
			[ new ParserOutput(), null, [ 'allowTOC' => false, 'injectTOC' => true ] ],
			[ new ParserOutput(), null, [ 'allowTOC' => true, 'injectTOC' => true ] ],
			[ new ParserOutput(), null, [ 'injectTOC' => true ] ],
			[ new ParserOutput(), null, [ 'allowTOC' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [ 'allowTOC' => true, 'injectTOC' => false ] ]
		];
	}

	/** @dataProvider provideTransform */
	public function testTransform( $parserOutput, $parserOptions, $options, $expected, $message = '' ) {
		if ( array_key_exists( 'userLang', $options ) ) {
			$lang = $this->createNoOpMock(
				Language::class, [ 'getCode', 'getHtmlCode', 'getDir' ]
			);
			$lang->method( 'getCode' )->willReturn( 'en' );
			$lang->method( 'getHtmlCode' )->willReturn( 'en' );
			$lang->method( 'getDir' )->willReturn( 'ltr' );
			$options['userLang'] = $lang;
		}
		if ( array_key_exists( 'skin', $options ) ) {
			$skin = $this->createNoOpMock(
				Skin::class, [ 'getLanguage' ]
			);
			$skin->method( 'getLanguage' )->willReturn( $options['userLang'] );
			$options['skin'] = $skin;
		}
		parent::testTransform( $parserOutput, $parserOptions, $options, $expected, $message = '' );
	}

	public static function provideTransform(): iterable {
		$withToc = <<<EOF
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

<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1">Section 2.1</h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		$withoutToc = <<<EOF
<p>Test document.
</p>

<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1">Section 2.1</h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;
		$poTest1 = new ParserOutput( TestUtils::TEST_DOC );
		TestUtils::initSections( $poTest1 );
		$expectedWith = new ParserOutput( $withToc );
		TestUtils::initSections( $expectedWith );
		yield [ $poTest1, null, [
			'userLang' => null,
			'skin' => null,
			'allowTOC' => true,
			'injectTOC' => true
		], $expectedWith, 'should insert TOC' ];

		$poTest2 = new ParserOutput( TestUtils::TEST_DOC );
		TestUtils::initSections( $poTest2 );
		$expectedWithout = new ParserOutput( $withoutToc );
		TestUtils::initSections( $expectedWithout );
		yield [ $poTest2, null, [ 'allowTOC' => false ], $expectedWithout, 'should not insert TOC' ];

		$poTest3 = new ParserOutput( TestUtils::TEST_DOC . '<meta property="mw:PageProp/toc" />' );
		TestUtils::initSections( $poTest3 );
		$expectedWith = new ParserOutput( $withToc );
		TestUtils::initSections( $expectedWith );
		yield [ $poTest3, null, [
			'userLang' => null,
			'skin' => null,
			'allowTOC' => true,
			'injectTOC' => true
		], $expectedWith, 'should insert TOC only once' ];
	}
}
