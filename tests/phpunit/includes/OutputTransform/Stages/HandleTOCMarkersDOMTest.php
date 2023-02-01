<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleTOCMarkersDOM;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\HandleTOCMarkersDOM
 */
class HandleTOCMarkersDOMTest extends HandleTOCMarkersTestCommon {

	public function createStage(): OutputTransformStage {
		return new HandleTOCMarkersDOM(
			new ServiceOptions( [] ),
			new NullLogger(),
		);
	}

	public static function provideTransform(): iterable {
		$withToc = <<<EOF
<p>Test document.
</p>
<div id="toc" class="toc" role="navigation" aria-labelledby="mw-toc-heading"><input type="checkbox" role="button" id="toctogglecheckbox" class="toctogglecheckbox" style="display:none"/><div class="toctitle" lang="en" dir="ltr"><h2 id="mw-toc-heading">Content &amp; more content</h2><span class="toctogglespan"><label class="toctogglelabel" for="toctogglecheckbox"></label></span></div><ul><li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li><li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a><ul><li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext"><i>Section 2.1</i></span></a></li></ul></li><li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li></ul></div>
<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1"><i>Section 2.1</i></h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;

		// Test altered title/id/class via TOCData extension data
		$withCustomToc = <<<EOF
<p>Test document.
</p>
<div id="my-id-test" class="my-class-test" role="navigation" aria-labelledby="mw-toc-heading"><input type="checkbox" role="button" id="toctogglecheckbox" class="toctogglecheckbox" style="display:none"/><div class="toctitle" lang="en" dir="ltr"><h2 id="mw-toc-heading">(my-title-msg)</h2><span class="toctogglespan"><label class="toctogglelabel" for="toctogglecheckbox"></label></span></div><ul><li class="toclevel-1 tocsection-1"><a href="#Section_1"><span class="tocnumber">1</span> <span class="toctext">Section 1</span></a></li><li class="toclevel-1 tocsection-2"><a href="#Section_2"><span class="tocnumber">2</span> <span class="toctext">Section 2</span></a><ul><li class="toclevel-2 tocsection-3"><a href="#Section_2.1"><span class="tocnumber">2.1</span> <span class="toctext"><i>Section 2.1</i></span></a></li></ul></li><li class="toclevel-1 tocsection-4"><a href="#Section_3"><span class="tocnumber">3</span> <span class="toctext">Section 3</span></a></li></ul></div>
<h2 data-mw-anchor="Section_1">Section 1<mw:editsection page="Test Page" section="1">Section 1</mw:editsection></h2>
<p>One
</p>
<h2 data-mw-anchor="Section_2">Section 2<mw:editsection page="Test Page" section="2">Section 2</mw:editsection></h2>
<p>Two
</p>
<h3 data-mw-anchor="Section_2.1"><i>Section 2.1</i></h3>
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
<h3 data-mw-anchor="Section_2.1"><i>Section 2.1</i></h3>
<p>Two point one
</p>
<h2 data-mw-anchor="Section_3">Section 3<mw:editsection page="Test Page" section="4">Section 3</mw:editsection></h2>
<p>Three
</p>
EOF;
		foreach ( self::yieldTransformTestCases( $withToc, $withoutToc, $withCustomToc ) as $y ) {
			yield $y;
		}
	}

	/** @dataProvider provideTransform */
	public function testTransform( ParserOutput $parserOutput, ?ParserOptions $parserOptions, array $options,
								   ParserOutput $expected, string $message = '' ): void {
		$this->parentTestTransform( $parserOutput, $parserOptions, $options, $expected, $message );
	}
}
