<?php

/**
 * @group GlobalFunctions
 * @covers ::wfEscapeWikiText
 */
class WfEscapeWikiTextTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideEscape
	 */
	public function testEscape( $input, $expected ) {
		// save global
		global $wgEnableMagicLinks;
		$old = $wgEnableMagicLinks;
		$wgEnableMagicLinks = [];

		try {
			$actual = wfEscapeWikiText( $input );
			// Sanity check that the output can be decoded back to the input
			// input as well.
			$decoded = html_entity_decode( $actual, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5 );
			$this->assertEquals( $decoded, (string)$input );
			// And that the output was what we expected
			$this->assertEquals( $expected, $actual );
		} finally {
			// restore global
			$wgEnableMagicLinks = $old;
		}
	}

	public static function provideEscape() {
		return [
			'null' => [
				null,
				'',
			],
			'false' => [
				false,
				'',
			],
			'empty string' => [
				'',
				'',
			],
			'no escapes' => [
				'a',
				'a',
			],
			'braces and brackets' => [
				'[[WikiLink]] {{Template}} <html>',
				'&#91;&#91;WikiLink&#93;&#93; &#123;&#123;Template&#125;&#125; &#60;html&#62;',
			],
			'quotes' => [
				'"\'',
				'&#34;&#39;',
			],
			'tokens' => [
				'{| |- |+ !! ~~~~~ __FOO__',
				'&#123;&#124; &#124;- &#124;+ &#33;! ~~&#126;~~ _&#95;FOO_&#95;',
			],
			'start of line' => [
				"* foo\n! bar\n# bat\n:baz\n pre\n----",
				"&#42; foo\n&#33; bar\n&#35; bat\n&#58;baz\n&#32;pre\n&#45;---",
			],
			'paragraph separators' => [
				"a\n\n\n\nb",
				"a\n&#10;\n&#10;b",
			],
			'language converter' => [
				'-{ foo ; bar }-',
				'&#45;&#123; foo &#59; bar &#125;-',
			],
			'left-side context: |+' => [
				'+ foo + bar',
				'&#43; foo + bar',
			],
			'left-side context: |-' => [
				'- foo - bar',
				'&#45; foo - bar',
			],
			'left-side context: __FOO__' => [
				'_FOO__',
				'&#95;FOO_&#95;',
			],
			'left-side context: ~~~' => [
				'~~ long string here',
				'&#126;~ long string here',
			],
			'left-side context: newlines' => [
				"\n\n\nFoo",
				"&#10;\n&#10;Foo",
			],
			'right-side context: ~~~' => [
				'long string here ~~',
				'long string here ~&#126;',
			],
			'right-side context: __FOO__' => [
				'__FOO_',
				'&#95;&#95;FOO&#95;',
			],
			'right-side context: newlines' => [
				"foo\n\n\n",
				"foo\n&#10;&#10;",
			],
			// A single character input needs to be protected against both
			// left-side context and right-side context.
			'both-side context: +' => [ // | + + (left side)
				'+',
				'&#43;',
			],
			'both-side context: -' => [ // | + - (left side)
				'-',
				'&#45;',
			],
			'both-side context: _' => [ // _ + _FOO as well as __FOO_ + _
				'_',
				'&#95;',
			],
			'both-side context: ~' => [ // ~ + ~~ as well as ~~ + ~
				'~',
				'&#126;',
			],
			'both-side context: \\n' => [ // \n + \n
				"\n",
				'&#10;',
			],
			'both-side context: \\t' => [ // \n + \t + \n becomes paragraph break
				"\t",
				'&#9;',
			],
		];
	}
}
