<?php
/**
 * Based on the test suite of the original Python
 * CSSJanus libary:
 * http://code.google.com/p/cssjanus/source/browse/trunk/cssjanus_test.py
 * Ported to PHP for ResourceLoader and has been extended since.
 *
 * @covers CSSJanus
 */
class CSSJanusTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideTransformCases
	 */
	public function testTransform( $cssA, $cssB = null ) {

		if ( $cssB ) {
			$transformedA = CSSJanus::transform( $cssA );
			$this->assertEquals( $transformedA, $cssB, 'Test A-B transformation' );

			$transformedB = CSSJanus::transform( $cssB );
			$this->assertEquals( $transformedB, $cssA, 'Test B-A transformation' );
		} else {
			// If no B version is provided, it means
			// the output should equal the input.
			$transformedA = CSSJanus::transform( $cssA );
			$this->assertEquals( $transformedA, $cssA, 'Nothing was flipped' );
		}
	}

	/**
	 * @dataProvider provideTransformAdvancedCases
	 */
	public function testTransformAdvanced( $code, $expectedOutput, $options = array() ) {
		$swapLtrRtlInURL = isset( $options['swapLtrRtlInURL'] ) ?
			$options['swapLtrRtlInURL'] : false;
		$swapLeftRightInURL = isset( $options['swapLeftRightInURL'] ) ?
			$options['swapLeftRightInURL'] : false;

		$flipped = CSSJanus::transform( $code, $swapLtrRtlInURL, $swapLeftRightInURL );

		$this->assertEquals( $expectedOutput, $flipped,
			'Test flipping, options: url-ltr-rtl=' . ( $swapLtrRtlInURL ? 'true' : 'false' )
				. ' url-left-right=' . ( $swapLeftRightInURL ? 'true' : 'false' )
		);
	}

	/**
	 * @dataProvider provideTransformBrokenCases
	 * @group Broken
	 */
	public function testTransformBroken( $code, $expectedOutput ) {
		$flipped = CSSJanus::transform( $code );

		$this->assertEquals( $expectedOutput, $flipped, 'Test flipping' );
	}

	/**
	 * These transform cases are tested *in both directions*
	 * No need to declare a principle twice in both directions here.
	 */
	public static function provideTransformCases() {
		return array(
			// Property keys
			array(
				'.foo { left: 0; }',
				'.foo { right: 0; }'
			),
			// Guard against partial keys
			// (CSS currently doesn't have flippable properties
			// that contain the direction as part of the key without
			// dash separation)
			array(
				'.foo { alright: 0; }'
			),
			array(
				'.foo { balleft: 0; }'
			),

			// Dashed property keys
			array(
				'.foo { padding-left: 0; }',
				'.foo { padding-right: 0; }'
			),
			array(
				'.foo { margin-left: 0; }',
				'.foo { margin-right: 0; }'
			),
			array(
				'.foo { border-left: 0; }',
				'.foo { border-right: 0; }'
			),

			// Double-dashed property keys
			array(
				'.foo { border-left-color: red; }',
				'.foo { border-right-color: red; }'
			),
			array(
				// Includes unknown properties?
				'.foo { x-left-y: 0; }',
				'.foo { x-right-y: 0; }'
			),

			// Multi-value properties
			array(
				'.foo { padding: 0; }'
			),
			array(
				'.foo { padding: 0 1px; }'
			),
			array(
				'.foo { padding: 0 1px 2px; }'
			),
			array(
				'.foo { padding: 0 1px 2px 3px; }',
				'.foo { padding: 0 3px 2px 1px; }'
			),

			// Shorthand / Four notation
			array(
				'.foo { padding: .25em 15px 0pt 0ex; }',
				'.foo { padding: .25em 0ex 0pt 15px; }'
			),
			array(
				'.foo { margin: 1px -4px 3px 2px; }',
				'.foo { margin: 1px 2px 3px -4px; }'
			),
			array(
				'.foo { padding: 0 15px .25em 0; }',
				'.foo { padding: 0 0 .25em 15px; }'
			),
			array(
				'.foo { padding: 1px 4.1grad 3px 2%; }',
				'.foo { padding: 1px 2% 3px 4.1grad; }'
			),
			array(
				'.foo { padding: 1px 2px 3px auto; }',
				'.foo { padding: 1px auto 3px 2px; }'
			),
			array(
				'.foo { padding: 1px inherit 3px auto; }',
				'.foo { padding: 1px auto 3px inherit; }'
			),
			// border-radius assigns different meanings to the values
			array(
				'.foo { border-radius: .25em 15px 0pt 0ex; }',
				'.foo { border-radius: 15px .25em 0ex 0pt; }'
			),
			array(
				'.foo { border-radius: 0px 0px 5px 5px; }',
			),
			// Ensure the rule doesn't break other stuff
			array(
				'.foo { x-unknown: a b c d; }'
			),
			array(
				'.foo barpx 0 2% { opacity: 0; }'
			),
			array(
				'#settings td p strong'
			),
			array(
				// Color names
				'.foo { border-color: red green blue white }',
				'.foo { border-color: red white blue green }',
			),
			array(
				// Color name, hexdecimal, RGB & RGBA
				'.foo { border-color: red #f00 rgb(255, 0, 0) rgba(255, 0, 0, 0.5) }',
				'.foo { border-color: red rgba(255, 0, 0, 0.5) rgb(255, 0, 0) #f00 }',
			),
			array(
				// Color name, hexdecimal, HSL & HSLA
				'.foo { border-color: red #f00 hsl(0, 100%, 50%) hsla(0, 100%, 50%, 0.5) }',
				'.foo { border-color: red hsla(0, 100%, 50%, 0.5) hsl(0, 100%, 50%) #f00 }',
			),
			array(
				// Do not mangle 5 or more values
				'.foo { -x-unknown: 1 2 3 4 5; }'
			),
			array(
				'.foo { -x-unknown: 1 2 3 4 5 6; }'
			),

			// Shorthand / Three notation
			array(
				'.foo { margin: 1em 0 .25em; }'
			),
			array(
				'.foo { margin:-1.5em 0 -.75em; }'
			),

			// Shorthand / Two notation
			array(
				'.foo { padding: 1px 2px; }'
			),

			// Shorthand / One notation
			array(
				'.foo { padding: 1px; }'
			),

			// text-shadow and box-shadow
			array(
				'.foo { box-shadow: -6px 3px 8px 5px rgba(0, 0, 0, 0.25); }',
				'.foo { box-shadow: 6px 3px 8px 5px rgba(0, 0, 0, 0.25); }',
			),
			array(
				'.foo { box-shadow: inset -6px 3px 8px 5px rgba(0, 0, 0, 0.25); }',
				'.foo { box-shadow: inset 6px 3px 8px 5px rgba(0, 0, 0, 0.25); }',
			),
			array(
				'.foo { text-shadow: orange 2px 0; }',
				'.foo { text-shadow: orange -2px 0; }',
			),
			array(
				'.foo { text-shadow: 2px 0 orange; }',
				'.foo { text-shadow: -2px 0 orange; }',
			),
			array(
				// Don't mangle zeroes
				'.foo { text-shadow: orange 0 2px; }'
			),

			// Direction
			// Note: This differs from the Python implementation,
			// see also CSSJanus::fixDirection for more info.
			array(
				'.foo { direction: ltr; }',
				'.foo { direction: rtl; }'
			),
			array(
				'.foo { direction: rtl; }',
				'.foo { direction: ltr; }'
			),
			array(
				'input { direction: ltr; }',
				'input { direction: rtl; }'
			),
			array(
				'input { direction: rtl; }',
				'input { direction: ltr; }'
			),
			array(
				'body { direction: ltr; }',
				'body { direction: rtl; }'
			),
			array(
				'.foo, body, input { direction: ltr; }',
				'.foo, body, input { direction: rtl; }'
			),
			array(
				'body { padding: 10px; direction: ltr; }',
				'body { padding: 10px; direction: rtl; }'
			),
			array(
				'body { direction: ltr } .myClass { direction: ltr }',
				'body { direction: rtl } .myClass { direction: rtl }'
			),

			// Left/right values
			array(
				'.foo { float: left; }',
				'.foo { float: right; }'
			),
			array(
				'.foo { text-align: left; }',
				'.foo { text-align: right; }'
			),
			array(
				'.foo { -x-unknown: left; }',
				'.foo { -x-unknown: right; }'
			),
			// Guard against selectors that look flippable
			array(
				'.column-left { width: 0; }'
			),
			array(
				'a.left { width: 0; }'
			),
			array(
				'a.leftification { width: 0; }'
			),
			array(
				'a.ltr { width: 0; }'
			),
			array(
				# <div class="a-ltr png">
				'.a-ltr.png { width: 0; }'
			),
			array(
				# <foo-ltr attr="x">
				'foo-ltr[attr="x"] { width: 0; }'
			),
			array(
				'div.left > span.right+span.left { width: 0; }'
			),
			array(
				'.thisclass .left .myclass { width: 0; }'
			),
			array(
				'.thisclass .left .myclass #myid { width: 0; }'
			),

			// Cursor values (east/west)
			array(
				'.foo { cursor: e-resize; }',
				'.foo { cursor: w-resize; }'
			),
			array(
				'.foo { cursor: se-resize; }',
				'.foo { cursor: sw-resize; }'
			),
			array(
				'.foo { cursor: ne-resize; }',
				'.foo { cursor: nw-resize; }'
			),

			// Background
			array(
				'.foo { background-position: top left; }',
				'.foo { background-position: top right; }'
			),
			array(
				'.foo { background: url(/foo/bar.png) top left; }',
				'.foo { background: url(/foo/bar.png) top right; }'
			),
			array(
				'.foo { background: url(/foo/bar.png) top left no-repeat; }',
				'.foo { background: url(/foo/bar.png) top right no-repeat; }'
			),
			array(
				'.foo { background: url(/foo/bar.png) no-repeat top left; }',
				'.foo { background: url(/foo/bar.png) no-repeat top right; }'
			),
			array(
				'.foo { background: #fff url(/foo/bar.png) no-repeat top left; }',
				'.foo { background: #fff url(/foo/bar.png) no-repeat top right; }'
			),
			array(
				'.foo { background-position: 100% 40%; }',
				'.foo { background-position: 0% 40%; }'
			),
			array(
				'.foo { background-position: 23% 0; }',
				'.foo { background-position: 77% 0; }'
			),
			array(
				'.foo { background-position: 23% auto; }',
				'.foo { background-position: 77% auto; }'
			),
			array(
				'.foo { background-position-x: 23%; }',
				'.foo { background-position-x: 77%; }'
			),
			array(
				'.foo { background-position-y: 23%; }',
				'.foo { background-position-y: 23%; }'
			),
			array(
				'.foo { background:url(../foo.png) no-repeat 75% 50%; }',
				'.foo { background:url(../foo.png) no-repeat 25% 50%; }'
			),
			array(
				'.foo { background: 10% 20% } .bar { background: 40% 30% }',
				'.foo { background: 90% 20% } .bar { background: 60% 30% }'
			),

			// Multiple rules
			array(
				'body { direction: rtl; float: right; } .foo { direction: ltr; float: right; }',
				'body { direction: ltr; float: left; } .foo { direction: rtl; float: left; }',
			),

			// Duplicate properties
			array(
				'.foo { float: left; float: right; float: left; }',
				'.foo { float: right; float: left; float: right; }',
			),

			// Preserve comments
			array(
				'/* left /* right */left: 10px',
				'/* left /* right */right: 10px'
			),
			array(
				'/*left*//*left*/left: 10px',
				'/*left*//*left*/right: 10px'
			),
			array(
				'/* Going right is cool */ .foo { width: 0 }',
			),
			array(
				"/* padding-right 1 2 3 4 */\n#test { width: 0}\n/*right*/"
			),
			array(
				"/** Two line comment\n * left\n \*/\n#test {width: 0}"
			),

			// @noflip annotation
			array(
				// before selector (single)
				'/* @noflip */ div { float: left; }'
			),
			array(
				// before selector (multiple)
				'/* @noflip */ div, .notme { float: left; }'
			),
			array(
				// inside selector
				'div, /* @noflip */ .foo { float: left; }'
			),
			array(
				// after selector
				'div, .notme /* @noflip */ { float: left; }'
			),
			array(
				// before multiple rules
				'/* @noflip */ div { float: left; } .foo { float: left; }',
				'/* @noflip */ div { float: left; } .foo { float: right; }'
			),
			array(
				// support parentheses in selector
				'/* @noflip */ .test:not(:first) { margin-right: -0.25em; margin-left: 0.25em; }',
				'/* @noflip */ .test:not(:first) { margin-right: -0.25em; margin-left: 0.25em; }'
			),
			array(
				// after multiple rules
				'.foo { float: left; } /* @noflip */ div { float: left; }',
				'.foo { float: right; } /* @noflip */ div { float: left; }'
			),
			array(
				// before multiple properties
				'div { /* @noflip */ float: left; text-align: left; }',
				'div { /* @noflip */ float: left; text-align: right; }'
			),
			array(
				// after multiple properties
				'div { float: left; /* @noflip */ text-align: left; }',
				'div { float: right; /* @noflip */ text-align: left; }'
			),
			array(
				// before a *= attribute selector with multiple properties
				'/* @noflip */ div.foo[bar*=baz] { float:left; clear: left; }'
			),
			array(
				// before a ^= attribute selector with multiple properties
				'/* @noflip */ div.foo[bar^=baz] { float:left; clear: left; }'
			),
			array(
				// before a ~= attribute selector with multiple properties
				'/* @noflip */ div.foo[bar~=baz] { float:left; clear: left; }'
			),
			array(
				// before a = attribute selector with multiple properties
				'/* @noflip */ div.foo[bar=baz] { float:left; clear: left; }'
			),
			array(
				// before a quoted attribute selector with multiple properties
				'/* @noflip */ div.foo[bar=\'baz{quux\'] { float:left; clear: left; }'
			),

			// Guard against css3 stuff
			array(
				'background-image: -moz-linear-gradient(#326cc1, #234e8c);'
			),
			array(
				'background-image: -webkit-gradient(linear, 100% 0%, 0% 0%, from(#666666), to(#ffffff));'
			),

			// CSS syntax / white-space variations
			// spaces, no spaces, tabs, new lines, omitting semi-colons
			array(
				".foo { left: 0; }",
				".foo { right: 0; }"
			),
			array(
				".foo{ left: 0; }",
				".foo{ right: 0; }"
			),
			array(
				".foo{ left: 0 }",
				".foo{ right: 0 }"
			),
			array(
				".foo{left:0 }",
				".foo{right:0 }"
			),
			array(
				".foo{left:0}",
				".foo{right:0}"
			),
			array(
				".foo  {  left : 0 ; }",
				".foo  {  right : 0 ; }"
			),
			array(
				".foo\n  {  left : 0 ; }",
				".foo\n  {  right : 0 ; }"
			),
			array(
				".foo\n  {  \nleft : 0 ; }",
				".foo\n  {  \nright : 0 ; }"
			),
			array(
				".foo\n  { \n left : 0 ; }",
				".foo\n  { \n right : 0 ; }"
			),
			array(
				".foo\n  { \n left\n  : 0; }",
				".foo\n  { \n right\n  : 0; }"
			),
			array(
				".foo \n  { \n left\n  : 0; }",
				".foo \n  { \n right\n  : 0; }"
			),
			array(
				".foo\n{\nleft\n:\n0;}",
				".foo\n{\nright\n:\n0;}"
			),
			array(
				".foo\n.bar {\n\tleft: 0;\n}",
				".foo\n.bar {\n\tright: 0;\n}"
			),
			array(
				".foo\t{\tleft\t:\t0;}",
				".foo\t{\tright\t:\t0;}"
			),

			// Guard against partial keys
			array(
				'.foo { leftxx: 0; }',
				'.foo { leftxx: 0; }'
			),
			array(
				'.foo { rightxx: 0; }',
				'.foo { rightxx: 0; }'
			),
		);
	}

	/**
	 * These cases are tested in one way only (format: actual, expected, msg).
	 * If both ways can be tested, either put both versions in here or move
	 * it to provideTransformCases().
	 */
	public static function provideTransformAdvancedCases() {
		$bgPairs = array(
			# [ - _ . ] <-> [ left right ltr rtl ]
			'foo.jpg' => 'foo.jpg',
			'left.jpg' => 'right.jpg',
			'ltr.jpg' => 'rtl.jpg',

			'foo-left.png' => 'foo-right.png',
			'foo_left.png' => 'foo_right.png',
			'foo.left.png' => 'foo.right.png',

			'foo-ltr.png' => 'foo-rtl.png',
			'foo_ltr.png' => 'foo_rtl.png',
			'foo.ltr.png' => 'foo.rtl.png',

			'left-foo.png' => 'right-foo.png',
			'left_foo.png' => 'right_foo.png',
			'left.foo.png' => 'right.foo.png',

			'ltr-foo.png' => 'rtl-foo.png',
			'ltr_foo.png' => 'rtl_foo.png',
			'ltr.foo.png' => 'rtl.foo.png',

			'foo-ltr-left.gif' => 'foo-rtl-right.gif',
			'foo_ltr_left.gif' => 'foo_rtl_right.gif',
			'foo.ltr.left.gif' => 'foo.rtl.right.gif',
			'foo-ltr_left.gif' => 'foo-rtl_right.gif',
			'foo_ltr.left.gif' => 'foo_rtl.right.gif',
		);
		$provider = array();
		foreach ( $bgPairs as $left => $right ) {
			# By default '-rtl' and '-left' etc. are not touched,
			# Only when the appropiate parameter is set.
			$provider[] = array(
				".foo { background: url(images/$left); }",
				".foo { background: url(images/$left); }"
			);
			$provider[] = array(
				".foo { background: url(images/$right); }",
				".foo { background: url(images/$right); }"
			);
			$provider[] = array(
				".foo { background: url(images/$left); }",
				".foo { background: url(images/$right); }",
				array(
					'swapLtrRtlInURL' => true,
					'swapLeftRightInURL' => true,
				)
			);
			$provider[] = array(
				".foo { background: url(images/$right); }",
				".foo { background: url(images/$left); }",
				array(
					'swapLtrRtlInURL' => true,
					'swapLeftRightInURL' => true,
				)
			);
		}

		return $provider;
	}

	/**
	 * Cases that are currently failing, but
	 * should be looked at in the future as enhancements and/or bug fix
	 */
	public static function provideTransformBrokenCases() {
		return array(
			// Guard against selectors that look flippable
			array(
				# <foo-left-x attr="x">
				'foo-left-x[attr="x"] { width: 0; }',
				'foo-left-x[attr="x"] { width: 0; }'
			),
			array(
				# <div class="foo" data-left="x">
				'.foo[data-left="x"] { width: 0; }',
				'.foo[data-left="x"] { width: 0; }'
			),
		);
	}
}
