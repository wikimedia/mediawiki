<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Parser\MWTidy;
use MediaWiki\Parser\Sanitizer;

/**
 * @group Parser
 * @covers \MediaWiki\Parser\MWTidy
 */
class TidyTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideTestWrapping
	 */
	public function testTidyWrapping( $expected, $text, $msg = '' ) {
		$text = MWTidy::tidy( $text );
		// We don't care about where Tidy wants to stick is <p>s
		$text = trim( preg_replace( '#</?p>#', '', $text ) );
		// Windows, we love you!
		$text = str_replace( "\r", '', $text );
		$this->assertEquals( $expected, $text, $msg );
	}

	public static function provideTestWrapping() {
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
		return [
			[
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection> should survive tidy'
			],
			[
				'<meta property="mw:PageProp/toc" />',
				'<meta property="mw:PageProp/toc" />',
				'TOC_PLACEHOLDER should survive tidy',
			],
			[ "<link foo=\"bar\" />foo", '<link foo="bar"/>foo', '<link> should survive tidy' ],
			[ "<meta foo=\"bar\" />foo", '<meta foo="bar"/>foo', '<meta> should survive tidy' ],
			[ $testMathML, $testMathML, '<math> should survive tidy' ],
		];
	}
}
