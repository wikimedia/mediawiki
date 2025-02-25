<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;

class RemexDriverTest extends MediaWikiUnitTestCase {
	private static $remexTidyTestData = [
		[
			'Empty string',
			"",
			""
		],
		[
			'Simple p-wrap',
			"x",
			"<p>x</p>"
		],
		[
			'No p-wrap of blank node',
			" ",
			" "
		],
		[
			'p-wrap terminated by div',
			"x<div></div>",
			"<p>x</p><div></div>"
		],
		[
			'p-wrap not terminated by span',
			"x<span></span>",
			"<p>x<span></span></p>"
		],
		[
			'An element is non-blank and so gets p-wrapped',
			"<span></span>",
			"<p><span></span></p>"
		],
		[
			'The blank flag is set after a block-level element',
			"<div></div> ",
			"<div></div> "
		],
		[
			'Blank detection between two block-level elements',
			"<div></div> <div></div>",
			"<div></div> <div></div>"
		],
		[
			'But p-wrapping of non-blank content works after an element',
			"<div></div>x",
			"<div></div><p>x</p>"
		],
		[
			'p-wrapping between two block-level elements',
			"<div></div>x<div></div>",
			"<div></div><p>x</p><div></div>"
		],
		[
			'p-wrap inside blockquote',
			"<blockquote>x</blockquote>",
			"<blockquote><p>x</p></blockquote>"
		],
		[
			'A comment is blank for p-wrapping purposes',
			"<!-- x -->",
			"<!-- x -->"
		],
		[
			'A comment is blank even when a p-wrap was opened by a text node',
			" <!-- x -->",
			" <!-- x -->"
		],
		[
			'A comment does not open a p-wrap',
			"<!-- x -->x",
			"<!-- x --><p>x</p>"
		],
		[
			'A comment does not close a p-wrap',
			"x<!-- x -->",
			"<p>x<!-- x --></p>"
		],
		[
			'Empty li',
			"<ul><li></li></ul>",
			"<ul><li class=\"mw-empty-elt\"></li></ul>"
		],
		[
			'li with element',
			"<ul><li><span></span></li></ul>",
			"<ul><li><span></span></li></ul>"
		],
		[
			'li with text',
			"<ul><li>x</li></ul>",
			"<ul><li>x</li></ul>"
		],
		[
			'Empty tr',
			"<table><tbody><tr></tr></tbody></table>",
			"<table><tbody><tr class=\"mw-empty-elt\"></tr></tbody></table>"
		],
		[
			'Empty p',
			"<p>\n</p>",
			"<p class=\"mw-empty-elt\">\n</p>"
		],
		[
			'No p-wrapping of an inline element which contains a block element (T150317)',
			"<small><div>x</div></small>",
			"<small><div>x</div></small>"
		],
		[
			'p-wrapping of an inline element which contains an inline element',
			"<small><b>x</b></small>",
			"<p><small><b>x</b></small></p>"
		],
		[
			'p-wrapping is enabled in a blockquote in an inline element',
			"<small><blockquote>x</blockquote></small>",
			"<small><blockquote><p>x</p></blockquote></small>"
		],
		[
			'All bare text should be p-wrapped even when surrounded by block tags',
			"<small><blockquote>x</blockquote></small>y<div></div>z",
			"<small><blockquote><p>x</p></blockquote></small><p>y</p><div></div><p>z</p>"
		],
		[
			'Split tag stack 1',
			"<small>x<div>y</div>z</small>",
			"<p><small>x</small></p><small><div>y</div></small><p><small>z</small></p>"
		],
		[
			'Split tag stack 2',
			"<small><div>y</div>z</small>",
			"<small><div>y</div></small><p><small>z</small></p>"
		],
		[
			'Split tag stack 3',
			"<small>x<div>y</div></small>",
			"<p><small>x</small></p><small><div>y</div></small>"
		],
		[
			'Split tag stack 4 (modified to use splittable tag)',
			"a<code>b<i>c<div>d</div></i>e</code>",
			"<p>a<code>b<i>c</i></code></p><code><i><div>d</div></i></code><p><code>e</code></p>"
		],
		[
			"Split tag stack regression check 1",
			"x<span><div>y</div></span>",
			"<p>x</p><span><div>y</div></span>"
		],
		[
			"Split tag stack regression check 2 (modified to use splittable tag)",
			"a<code><i><div>d</div></i>e</code>",
			"<p>a</p><code><i><div>d</div></i></code><p><code>e</code></p>"
		],
		// Simple tests from pwrap.js
		[
			'Simple pwrap test 1',
			'a',
			'<p>a</p>'
		],
		[
			'<span> is not a splittable tag',
			'<span>x<div>a</div>y</span> <span>x<div></div>y</span>',
			'<span>x<div>a</div>y</span> <span>x<div></div>y</span>',
		],
		[
			'<span> is not a splittable tag, but gets p-wrapped in simple wrapping scenarios',
			'<span>a</span>',
			'<p><span>a</span></p>'
		],
		[
			'Simple pwrap test 3',
			'x <div>a</div> <div>b</div> y',
			'<p>x </p><div>a</div> <div>b</div><p> y</p>'
		],
		[
			'Simple pwrap test 4',
			'x<!--c--> <div>a</div> <div>b</div> <!--c-->y',
			'<p>x<!--c--> </p><div>a</div> <div>b</div> <!--c--><p>y</p>'
		],
		// Complex tests from pwrap.js
		[
			'Complex pwrap test 1',
			'<i>x<div>a</div>y</i>',
			'<p><i>x</i></p><i><div>a</div></i><p><i>y</i></p>'
		],
		[
			'Complex pwrap test 2',
			'a<small>b</small><i>c<div>d</div>e</i>f',
			'<p>a<small>b</small><i>c</i></p><i><div>d</div></i><p><i>e</i>f</p>'
		],
		[
			'Complex pwrap test 3',
			'a<small>b<i>c<div>d</div></i>e</small>',
			'<p>a<small>b<i>c</i></small></p><small><i><div>d</div></i></small><p><small>e</small></p>'
		],
		[
			'Complex pwrap test 4',
			'x<small><div>y</div></small>',
			'<p>x</p><small><div>y</div></small>'
		],
		[
			'Complex pwrap test 5',
			'a<small><i><div>d</div></i>e</small>',
			'<p>a</p><small><i><div>d</div></i></small><p><small>e</small></p>'
		],
		[
			'Complex pwrap test 6',
			'<i>a<div>b</div>c<b>d<div>e</div>f</b>g</i>',
			'<p><i>a</i></p><i><div>b</div></i><p><i>c<b>d</b></i></p><i><b><div>e</div></b></i><p><i><b>f</b>g</i></p>'
		],
		/* FIXME the second <b> causes a stack split which clones the <i> even
		 * though no <p> is actually generated
		[
			'Complex pwrap test 7',
			'<i><b><font><div>x</div></font></b><div>y</div><b><font><div>z</div></font></b></i>',
			'<i><b><font><div>x</div></font></b><div>y</div><b><font><div>z</div></font></b></i>'
		],
		 */
		// New local tests
		[
			'Blank text node after block end',
			'<small>x<div>y</div> <b>z</b></small>',
			'<p><small>x</small></p><small><div>y</div></small><p><small> <b>z</b></small></p>'
		],
		[
			'Text node fostering (FIXME: wrap missing)',
			'<table>x</table>',
			'x<table></table>'
		],
		[
			'Blockquote fostering',
			'<table><blockquote>x</blockquote></table>',
			'<blockquote><p>x</p></blockquote><table></table>'
		],
		[
			'Block element fostering',
			'<table><div>x',
			'<div>x</div><table></table>'
		],
		[
			'Formatting element fostering (FIXME: wrap missing)',
			'<table><b>x',
			'<b>x</b><table></table>'
		],
		[
			'AAA clone of p-wrapped element (FIXME: empty b)',
			'<b>x<p>y</b>z</p>',
			'<p><b>x</b></p><b></b><p><b>y</b>z</p>',
		],
		[
			'AAA with fostering (FIXME: wrap missing)',
			'<table><b>1<p>2</b>3</p>',
			'<b>1</b><p><b>2</b>3</p><table></table>'
		],
		[
			'AAA causes reparent of p-wrapped text node (T178632)',
			'<i><blockquote>x</i></blockquote>',
			'<i></i><blockquote><p><i>x</i></p></blockquote>',
		],
		[
			'p-wrap ended by reparenting (T200827)',
			'<i><blockquote><p></i>',
			'<i></i><blockquote><p><i></i></p><p><i></i></p></blockquote>',
		],
		[
			'style tag isn\'t p-wrapped (T186965)',
			'<style>/* ... */</style>',
			'<style>/* ... */</style>',
		],
		[
			'link tag isn\'t p-wrapped (T186965)',
			'<link rel="foo" href="bar" />',
			'<link rel="foo" href="bar" />',
		],
		[
			'style tag doesn\'t split p-wrapping (T208901)',
			'foo <style>/* ... */</style> bar',
			'<p>foo <style>/* ... */</style> bar</p>',
		],
		[
			'link tag doesn\'t split p-wrapping (T208901)',
			'foo <link rel="foo" href="bar" /> bar',
			'<p>foo <link rel="foo" href="bar" /> bar</p>',
		],
		// From the old TidyTest class
		[
			'<mw:editsection> should survive tidy',
			'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
			'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
		],
		[
			'TOC_PLACEHOLDER should survive tidy',
			'<meta property="mw:PageProp/toc" />',
			'<meta property="mw:PageProp/toc" />',
		],
		[
			'<link> should survive tidy',
			'<link foo="bar"/>foo',
			"<link foo=\"bar\" /><p>foo</p>",
		],
		[
			'<meta> should survive tidy',
			'<meta foo="bar"/>foo',
			"<meta foo=\"bar\" /><p>foo</p>",
		],
		[
			'Unicode combining characters (T387130)',
			"<p>\u{0338} <!--comment-->\u{0338}</p>",
			'<p>&#x338; <!--comment-->&#x338;</p>',
		],
	];

	public static function provider() {
		$testMathML = <<<'MathML'
<math xmlns="http://www.w3.org/1998/Math/MathML">
    <mrow>
      <mi>a</mi>
      <mo>&InvisibleTimes;</mo>
      <msup>
        <mi>x</mi>
        <mn>2</mn>
      </msup>
      <mo>+</mo>
      <mi>b</mi>
      <mo>&InvisibleTimes; </mo>
      <mi>x</mi>
      <mo>+</mo>
      <mi>c</mi>
    </mrow>
  </math>
MathML;
		$testMathML = Sanitizer::normalizeCharReferences( $testMathML );
		return array_merge( self::$remexTidyTestData, [ [
			'<math> should survive tidy',
			$testMathML,
			$testMathML,
		] ] );
	}

	/**
	 * @dataProvider provider
	 * @covers \MediaWiki\Tidy\RemexCompatFormatter
	 * @covers \MediaWiki\Tidy\RemexCompatMunger
	 * @covers \MediaWiki\Tidy\RemexDriver
	 * @covers \MediaWiki\Tidy\RemexMungerData
	 */
	public function testTidy( $desc, $input, $expected ) {
		$r = new MediaWiki\Tidy\RemexDriver(
			new ServiceOptions(
				MediaWiki\Tidy\RemexDriver::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::TidyConfig => [],
					MainConfigNames::ParserEnableLegacyMediaDOM => false,
				] )
			)
		);
		$result = $r->tidy( $input );
		$this->assertEquals( $expected, $result, $desc );
	}

	public static function html5libProvider() {
		$files = json_decode( file_get_contents( __DIR__ . '/html5lib-tests.json' ), true );
		$tests = [];
		foreach ( $files as $file => $fileTests ) {
			foreach ( $fileTests as $i => $test ) {
				$tests[] = [ "$file:$i", $test['data'] ];
			}
		}
		return $tests;
	}

	/**
	 * This is a quick and dirty test to make sure none of the html5lib tests
	 * generate exceptions. We don't really know what the expected output is.
	 *
	 * @dataProvider html5libProvider
	 * @coversNothing
	 */
	public function testHtml5Lib( $desc, $input ) {
		$r = new MediaWiki\Tidy\RemexDriver(
			new ServiceOptions(
				MediaWiki\Tidy\RemexDriver::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::TidyConfig => [],
					MainConfigNames::ParserEnableLegacyMediaDOM => false,
				] )
			)
		);
		$result = $r->tidy( $input );
		$this->assertTrue( true, $desc );
	}
}
