<?php
/**
 * @author Ashar Voultoiz
 * @copyright Copyright © 2011, Ashar Voultoiz
 * @file
 */

require_once dirname(dirname(__FILE__)). '/bootstrap.php';

/** Tests for MediaWiki languages/LanguageTr.php */
class LanguageTrTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Tr' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/**
	 * See @bug 28040
	 * Credits to #wikipedia-tr users berm, []LuCkY[] and Emperyan
	 * @see http://en.wikipedia.org/wiki/Dotted_and_dotless_I
	 * @dataProvider provideDottedAndDotlessI
	 */
	function testChangeCaseOfFirstCharBeingDottedAndDotlessI( $func, $input, $inputCase, $expected ) {
		if( $func == 'ucfirst' ) {
			$res = $this->lang->ucfirst( $input );
		} elseif( $func == 'lcfirst' ) {
			$res = $this->lang->lcfirst( $input );
		} else {
			throw new MWException( __METHOD__ . " given an invalid function name '$func'" );
		}

		$msg = "Converting $inputCase case '$input' with $func should give '$expected'";

		$this->assertEquals( $expected, $res, $msg );
	}

	function provideDottedAndDotlessI() {
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

##### LanguageTr specificities  #############################################
	/**
	 * @cover LanguageTr:lc
	 * See @bug 28040
	 */
	function testLanguageTrLowerCasingOverride() {
		$this->assertEquals( 'ııııı', $this->lang->lc( 'IIIII') );
	}
	/**
	 * @cover LanguageTr:uc
	 * See @bug 28040
	 */
	function testLanguageTrUpperCasingOverride() {
		$this->assertEquals( 'İİİİİ', $this->lang->uc( 'iiiii') );
	}

##### Upper casing a string #################################################
	/**
	 * Generic test for the Turkish dotted and dotless I strings
	 * See @bug 28040
	 * @dataProvider provideUppercaseStringsWithDottedAndDotlessI
	 */
	function testUpperCasingOfAStringWithDottedAndDotLessI( $expected, $input ) {
		$this->assertEquals( $expected, $this->lang->uc( $input ) );
	}
	function provideUppercaseStringsWithDottedAndDotlessI() {
		return array(
			# expected, input string to uc()
			array( 'IIIII', 'ııııı' ),
			array( 'IIIII', 'IIIII' ), #identity
			array( 'İİİİİ', 'iiiii' ), # Specifically handled by LanguageTr:uc
			array( 'İİİİİ', 'İİİİİ' ), #identity
		);
	}

##### Lower casing a string #################################################
	/**
	 * Generic test for the Turkish dotted and dotless I strings
	 * See @bug 28040
	 * @dataProvider provideLowercaseStringsWithDottedAndDotlessI
	 */
	function testLowerCasingOfAStringWithDottedAndDotLessI( $expected, $input ) {
		$this->assertEquals( $expected, $this->lang->lc( $input ) );
	}
	function provideLowercaseStringsWithDottedAndDotlessI() {
		return array(
			# expected, input string to lc()
			array( 'ııııı', 'IIIII' ), # Specifically handled by LanguageTr:lc
			array( 'ııııı', 'ııııı' ), #identity
			array( 'iiiii', 'İİİİİ' ),
			array( 'iiiii', 'iiiii' ), #identity
		);
	}

}
