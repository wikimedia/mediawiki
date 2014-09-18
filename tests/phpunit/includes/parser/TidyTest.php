<?php

/**
 * @group Parser
 */
class TidyTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$check = MWTidy::tidy( '' );
		if ( strpos( $check, '<!--' ) !== false ) {
			$this->markTestSkipped( 'Tidy not found' );
		}
	}

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
		return array(
			array(
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection> should survive tidy'
			),
			array(
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection> should survive tidy'
			),
			array( '<mw:toc>foo</mw:toc>', '<mw:toc>foo</mw:toc>', '<mw:toc> should survive tidy' ),
			array( "<link foo=\"bar\" />\nfoo", '<link foo="bar"/>foo', '<link> should survive tidy' ),
			array( "<meta foo=\"bar\" />\nfoo", '<meta foo="bar"/>foo', '<meta> should survive tidy' ),
			array( $testMathML, $testMathML, '<math> should survive tidy' ),
		);
	}
}
