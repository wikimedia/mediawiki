<?php

/**
 * @group Mobile
 */
class HtmlFormatterTest extends MediaWikiTestCase {
	/**
	 * @dataProvider getHtmlData
	 */
	public function testTransform( $input, $expected, $callback = false ) {
		$input = self::normalize( $input );
		$formatter = new HtmlFormatter( HtmlFormatter::wrapHTML( $input ) );
		if ( $callback ) {
			$callback( $formatter );
		}
		$formatter->filterContent();
		$html = $formatter->getText();
		$this->assertEquals( self::normalize( $expected ), self::normalize( $html ) );
	}

	private static function normalize( $s ) {
		return str_replace( "\n", '',
			str_replace( "\r", '', $s ) // "yay" to Windows!
		);
	}

	public function getHtmlData() {
		$disableImages = function( HtmlFormatter $f ) {
			$f->removeImages();
		};
		$removeTags = function( HtmlFormatter $f ) {
			$f->remove( array( 'table', '.foo', '#bar', 'div.baz' ) );
			$f->whitelistIds( 'jedi' );
		};
		$flattenSomeStuff = function( HtmlFormatter $f ) {
			$f->flatten( array( 's', 'div' ) );
		};
		$flattenEverything = function( HtmlFormatter $f ) {
			$f->flattenAllTags();
		};
		return array(
			// remove images if asked
			array(
				'<img src="/foo/bar.jpg">Blah</img>',
				'Blah',
				$disableImages,
			),
			// basic tag removal
			array(
				'<table><tr><td>foo</td></tr></table><div class="foo">foo</div><span id="bar">bar</span>
<strong class="foo" id="bar">foobar</strong><div class="notfoo">test</div><div class="baz"/>
<span class="baz">baz</span> <span class="foo" id="jedi">jedi</span>',

				'<div class="notfoo">test</div>
<span class="baz">baz</span> <span class="foo" id="jedi">jedi</span>',
				$removeTags,
			),
			// don't flatten tags that start like chosen ones
			array(
				'<div><s>foo</s> <span>bar</span></div>',
				'foo <span>bar</span>',
				$flattenSomeStuff,
			),
			// total flattening
			array(
				'<div style="foo">bar<sup>2</sup></div>',
				'bar2',
				$flattenEverything,
			),
		);
	}
}
