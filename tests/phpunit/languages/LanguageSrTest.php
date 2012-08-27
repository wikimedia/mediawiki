<?php
/**
 * PHPUnit tests for the Serbian language.
 * The language can be represented using two scripts:
 *  - Latin (SR_el)
 *  - Cyrillic (SR_ec)
 * Both representations seems to be bijective, hence MediaWiki can convert
 * from one script to the other.
 *
 * @author Antoine Musso <hashar at free dot fr>
 * @copyright Copyright © 2011, Antoine Musso <hashar at free dot fr>
 * @file
 */

require_once dirname( __DIR__ ) . '/bootstrap.php';

/** Tests for MediaWiki languages/LanguageSr.php */
class LanguageSrTest extends MediaWikiTestCase {
	/* Language object. Initialized before each test */
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'sr' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	##### TESTS #######################################################

	function testEasyConversions( ) {
		$this->assertCyrillic(
			'шђчћжШЂЧЋЖ',
			'Cyrillic guessing characters'
		);
		$this->assertLatin(
			'šđčćžŠĐČĆŽ',
			'Latin guessing characters'
		);
	}

	function testMixedConversions() {
		$this->assertCyrillic(
			'шђчћжШЂЧЋЖ - šđčćž',
			'Mostly cyrillic characters'
		);
		$this->assertLatin(
			'šđčćžŠĐČĆŽ - шђчћж',
			'Mostly latin characters'
		);
	}

	function testSameAmountOfLatinAndCyrillicGetConverted() {
		$this->assertConverted(
			'4 latin: šđčć | 4 cyrillic: шђчћ',
			'sr-ec'
		);
		$this->assertConverted(
			'4 latin: šđčć | 4 cyrillic: шђчћ',
			'sr-el'
		);
	}

	/**
	 * @author Nikola Smolenski
	 */
	function testConversionToCyrillic() {
		//A simple convertion of Latin to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'abvg' )
		);
		//Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{dž}-' )
		);
		//A simple convertion of Cyrillic to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'абвг' )
		);
		//Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{dž}-' )
		);
		//This text has some Latin, but is recognized as Cyrillic, so it should not be converted
		$this->assertEquals( 'abvgшђжчћ',
			$this->convertToCyrillic( 'abvgшђжчћ' )
		);
		//Same as above, but assert that -{}-s must be removed
		$this->assertEquals( 'љabvgњшђжчћџ',
			$this->convertToCyrillic( '-{љ}-abvg-{њ}-шђжчћ-{џ}-' )
		);
		//This text has some Cyrillic, but is recognized as Latin, so it should be converted
		$this->assertEquals( 'абвгшђжчћ',
			$this->convertToCyrillic( 'абвгšđžčć' )
		);
		//Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабвгnjшђжчћdž',
			$this->convertToCyrillic( '-{lj}-абвг-{nj}-šđžčć-{dž}-' )
		);
		// Roman numerals are not converted
		$this->assertEquals( 'а I б II в III г IV шђжчћ',
			$this->convertToCyrillic( 'a I b II v III g IV šđžčć' )
		);
	}

	function testConversionToLatin() {
		//A simple convertion of Latin to Latin
		$this->assertEquals( 'abcd',
			$this->convertToLatin( 'abcd' )
		);
		//A simple convertion of Cyrillic to Latin
		$this->assertEquals( 'abcd',
			$this->convertToLatin( 'абцд' )
		);
		//This text has some Latin, but is recognized as Cyrillic, so it should be converted
		$this->assertEquals( 'abcdšđžčć',
			$this->convertToLatin( 'abcdшђжчћ' )
		);
		//This text has some Cyrillic, but is recognized as Latin, so it should not be converted
		$this->assertEquals( 'абцдšđžčć',
			$this->convertToLatin( 'абцдšđžčć' )
		);
	}

	/** @dataProvider providePluralFourForms */
	function testPluralFourForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providePluralFourForms() {
		return array (
			array( 'one', 1 ),
			array( 'many', 11 ),
			array( 'one', 91 ),
			array( 'one', 121 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'few', 334 ),
			array( 'many', 5 ),
			array( 'many', 15 ),
			array( 'many', 120 ),
		);
	}
	/** @dataProvider providePluralTwoForms */
	function testPluralTwoForms( $result, $value ) {
		$forms = array( 'one', 'several' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}
	function providePluralTwoForms() {
		return array (
			array( 'one', 1 ),
			array( 'several', 11 ),
			array( 'several', 91 ),
			array( 'several', 121 ),
		);
	}

	##### HELPERS #####################################################
	/**
	 *Wrapper to verify text stay the same after applying conversion
	 * @param $text string Text to convert
	 * @param $variant string Language variant 'sr-ec' or 'sr-el'
	 * @param $msg string Optional message
	 */
	function assertUnConverted( $text, $variant, $msg = '' ) {
		$this->assertEquals(
			$text,
			$this->convertTo( $text, $variant ),
			$msg
		);
	}
	/**
	 * Wrapper to verify a text is different once converted to a variant.
	 * @param $text string Text to convert
	 * @param $variant string Language variant 'sr-ec' or 'sr-el'
	 * @param $msg string Optional message
	 */
	function assertConverted( $text, $variant, $msg = '' ) {
		$this->assertNotEquals(
			$text,
			$this->convertTo( $text, $variant ),
			$msg
		);
	}

	/**
	 * Verifiy the given Cyrillic text is not converted when using
	 * using the cyrillic variant and converted to Latin when using
	 * the Latin variant.
	 */
	function assertCyrillic( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'sr-ec', $msg );
		$this->assertConverted( $text, 'sr-el', $msg );
	}
	/**
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 */
	function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'sr-el', $msg );
		$this->assertConverted( $text, 'sr-ec', $msg );
	}


	/** Wrapper for converter::convertTo() method*/
	function convertTo( $text, $variant ) {
		return $this
			->lang
			->mConverter
			->convertTo(
				$text, $variant
			);
	}
	function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'sr-ec' );
	}
	function convertToLatin( $text ) {
		return $this->convertTo( $text, 'sr-el' );
	}
}
