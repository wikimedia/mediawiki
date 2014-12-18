<?php

/**
 * @group HtmlFormatter
 */
class HtmlFormatterTest extends MediaWikiTestCase {

	/**
	 * Use TidySupport to check whether we should use $wgTidyInternal.
	 *
	 * The Tidy extension in HHVM does not support error text return, so it is
	 * nominally usable, but does not pass tests which require error text from
	 * Tidy.
	 */
	protected function setUp() {
		parent::setUp();
		$tidySupport = new TidySupport();
		$this->setMwGlobals( 'wgTidyInternal', $tidySupport->isInternal() );
	}

	/**
	 * @dataProvider getHtmlData
	 *
	 * @param string $input
	 * @param string $expectedText
	 * @param array $expectedRemoved
	 * @param callable|bool $callback
	 */
	public function testTransform( $input, $expectedText,
		$expectedRemoved = array(), $callback = false
	) {
		$input = self::normalize( $input );
		$formatter = new HtmlFormatter( HtmlFormatter::wrapHTML( $input ) );
		if ( $callback ) {
			$callback( $formatter );
		}
		$removedElements = $formatter->filterContent();
		$html = $formatter->getText();
		$removed = array();
		foreach ( $removedElements as $removedElement ) {
			$removed[] = self::normalize( $formatter->getText( $removedElement ) );
		}
		$expectedRemoved = array_map( 'self::normalize', $expectedRemoved );

		$this->assertValidHtmlSnippet( $html );
		$this->assertEquals( self::normalize( $expectedText ), self::normalize( $html ) );
		$this->assertEquals( asort( $expectedRemoved ), asort( $removed ) );
	}

	private static function normalize( $s ) {
		return str_replace( "\n", '',
			str_replace( "\r", '', $s ) // "yay" to Windows!
		);
	}

	public function getHtmlData() {
		$removeImages = function ( HtmlFormatter $f ) {
			$f->setRemoveMedia();
		};
		$removeTags = function ( HtmlFormatter $f ) {
			$f->remove( array( 'table', '.foo', '#bar', 'div.baz' ) );
		};
		$flattenSomeStuff = function ( HtmlFormatter $f ) {
			$f->flatten( array( 's', 'div' ) );
		};
		$flattenEverything = function ( HtmlFormatter $f ) {
			$f->flattenAllTags();
		};
		return array(
			// remove images if asked
			array(
				'<img src="/foo/bar.jpg" alt="Blah"/>',
				'',
				array( '<img src="/foo/bar.jpg" alt="Blah">' ),
				$removeImages,
			),
			// basic tag removal
			array(
				// @codingStandardsIgnoreStart Ignore long line warnings.
				'<table><tr><td>foo</td></tr></table><div class="foo">foo</div><div class="foo quux">foo</div><span id="bar">bar</span>
<strong class="foo" id="bar">foobar</strong><div class="notfoo">test</div><div class="baz"/>
<span class="baz">baz</span>',
				// @codingStandardsIgnoreEnd
				'<div class="notfoo">test</div>
<span class="baz">baz</span>',
				array(
					'<table><tr><td>foo</td></tr></table>',
					'<div class="foo">foo</div>',
					'<div class="foo quux">foo</div>',
					'<span id="bar">bar</span>',
					'<strong class="foo" id="bar">foobar</strong>',
					'<div class="baz"/>',
				),
				$removeTags,
			),
			// don't flatten tags that start like chosen ones
			array(
				'<div><s>foo</s> <span>bar</span></div>',
				'foo <span>bar</span>',
				array(),
				$flattenSomeStuff,
			),
			// total flattening
			array(
				'<div style="foo">bar<sup>2</sup></div>',
				'bar2',
				array(),
				$flattenEverything,
			),
			// UTF-8 preservation and security
			array(
				'<span title="&quot; \' &amp;">&lt;Тест!&gt;</span> &amp;&lt;&#38;&#0038;&#x26;&#x026;',
				'<span title="&quot; \' &amp;">&lt;Тест!&gt;</span> &amp;&lt;&amp;&amp;&amp;&amp;',
				array(),
				$removeTags, // Have some rules to trigger a DOM parse
			),
			// https://bugzilla.wikimedia.org/show_bug.cgi?id=53086
			array(
				'Foo<sup id="cite_ref-1" class="reference"><a href="#cite_note-1">[1]</a></sup>'
					. ' <a href="/wiki/Bar" title="Bar" class="mw-redirect">Bar</a>',
				'Foo<sup id="cite_ref-1" class="reference"><a href="#cite_note-1">[1]</a></sup>'
					. ' <a href="/wiki/Bar" title="Bar" class="mw-redirect">Bar</a>',
			),
		);
	}

	public function testQuickProcessing() {
		$f = new MockHtmlFormatter( 'foo' );
		$f->filterContent();
		$this->assertFalse( $f->hasDoc, 'HtmlFormatter should not needlessly parse HTML' );
	}
}

class MockHtmlFormatter extends HtmlFormatter {
	public $hasDoc = false;

	public function getDoc() {
		$this->hasDoc = true;
		return parent::getDoc();
	}
}
