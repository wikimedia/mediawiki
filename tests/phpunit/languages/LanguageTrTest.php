<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2011, Antoine Musso
 * @file
 */

/** Tests for MediaWiki languages/LanguageTr.php */
class LanguageTrTest extends LanguageClassesTestCase {

	/**
	 * See @bug 28040
	 * Credits to irc://irc.freenode.net/wikipedia-tr users:
	 *  - berm
	 *  - []LuCkY[]
	 *  - Emperyan
	 * @see http://en.wikipedia.org/wiki/Dotted_and_dotless_I
	 * @dataProvider provideDottedAndDotlessI
	 * @covers Language::ucfirst
	 * @covers Language::lcfirst
	 */
	public function testDottedAndDotlessI( $func, $input, $inputCase, $expected ) {
		if ( $func == 'ucfirst' ) {
			$res = $this->getLang()->ucfirst( $input );
		} elseif ( $func == 'lcfirst' ) {
			$res = $this->getLang()->lcfirst( $input );
		} else {
			throw new MWException( __METHOD__ . " given an invalid function name '$func'" );
		}

		$msg = "Converting $inputCase case '$input' with $func should give '$expected'";

		$this->assertEquals( $expected, $res, $msg );
	}

	public static function provideDottedAndDotlessI() {
		return array(
			# function, input, input case, expected
			# Case changed:
			array( 'ucfirst', 'ı', 'lower', 'I' ),
			array( 'ucfirst', 'i', 'lower', 'İ' ),
			array( 'lcfirst', 'I', 'upper', 'ı' ),
			array( 'lcfirst', 'İ', 'upper', 'i' ),

			# Already using the correct case
			array( 'ucfirst', 'I', 'upper', 'I' ),
			array( 'ucfirst', 'İ', 'upper', 'İ' ),
			array( 'lcfirst', 'ı', 'lower', 'ı' ),
			array( 'lcfirst', 'i', 'lower', 'i' ),

			# A real example taken from bug 28040 using
			# http://tr.wikipedia.org/wiki/%C4%B0Phone
			array( 'lcfirst', 'iPhone', 'lower', 'iPhone' ),

			# next case is valid in Turkish but are different words if we
			# consider IPhone is English!
			array( 'lcfirst', 'IPhone', 'upper', 'ıPhone' ),

		);
	}
}
