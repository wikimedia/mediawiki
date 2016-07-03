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
 *
 * @todo methods in test class should be tidied:
 *  - Should be split into separate test methods and data providers
 *  - Tests for LanguageConverter and Language should probably be separate..
 */

/** Tests for MediaWiki languages/LanguageSr.php */
class LanguageSrTest extends LanguageClassesTestCase {
	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testEasyConversions() {
		$this->assertCyrillic(
			'шђчћжШЂЧЋЖ',
			'Cyrillic guessing characters'
		);
		$this->assertLatin(
			'šđčćžŠĐČĆŽ',
			'Latin guessing characters'
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testMixedConversions() {
		$this->assertCyrillic(
			'шђчћжШЂЧЋЖ - šđčćž',
			'Mostly cyrillic characters'
		);
		$this->assertLatin(
			'šđčćžŠĐČĆŽ - шђчћж',
			'Mostly latin characters'
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testSameAmountOfLatinAndCyrillicGetConverted() {
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
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToCyrillic() {
		// A simple convertion of Latin to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'abvg' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{dž}-' )
		);
		// A simple convertion of Cyrillic to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'абвг' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{dž}-' )
		);
		// This text has some Latin, but is recognized as Cyrillic, so it should not be converted
		$this->assertEquals( 'abvgшђжчћ',
			$this->convertToCyrillic( 'abvgшђжчћ' )
		);
		// Same as above, but assert that -{}-s must be removed
		$this->assertEquals( 'љabvgњшђжчћџ',
			$this->convertToCyrillic( '-{љ}-abvg-{њ}-шђжчћ-{џ}-' )
		);
		// This text has some Cyrillic, but is recognized as Latin, so it should be converted
		$this->assertEquals( 'абвгшђжчћ',
			$this->convertToCyrillic( 'абвгšđžčć' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабвгnjшђжчћdž',
			$this->convertToCyrillic( '-{lj}-абвг-{nj}-šđžčć-{dž}-' )
		);
		// Roman numerals are not converted
		$this->assertEquals( 'а I б II в III г IV шђжчћ',
			$this->convertToCyrillic( 'a I b II v III g IV šđžčć' )
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToLatin() {
		// A simple convertion of Latin to Latin
		$this->assertEquals( 'abcd',
			$this->convertToLatin( 'abcd' )
		);
		// A simple convertion of Cyrillic to Latin
		$this->assertEquals( 'abcd',
			$this->convertToLatin( 'абцд' )
		);
		// This text has some Latin, but is recognized as Cyrillic, so it should be converted
		$this->assertEquals( 'abcdšđžčć',
			$this->convertToLatin( 'abcdшђжчћ' )
		);
		// This text has some Cyrillic, but is recognized as Latin, so it should not be converted
		$this->assertEquals( 'абцдšđžčć',
			$this->convertToLatin( 'абцдšđžčć' )
		);
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return [
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'few', 334 ],
			[ 'other', 5 ],
			[ 'other', 15 ],
			[ 'other', 120 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'other', 4 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
		];
	}

	# #### HELPERS #####################################################
	/**
	 *Wrapper to verify text stay the same after applying conversion
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'sr-ec' or 'sr-el'
	 * @param string $msg Optional message
	 */
	protected function assertUnConverted( $text, $variant, $msg = '' ) {
		$this->assertEquals(
			$text,
			$this->convertTo( $text, $variant ),
			$msg
		);
	}

	/**
	 * Wrapper to verify a text is different once converted to a variant.
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'sr-ec' or 'sr-el'
	 * @param string $msg Optional message
	 */
	protected function assertConverted( $text, $variant, $msg = '' ) {
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
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertCyrillic( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'sr-ec', $msg );
		$this->assertConverted( $text, 'sr-el', $msg );
	}

	/**
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'sr-el', $msg );
		$this->assertConverted( $text, 'sr-ec', $msg );
	}

	/** Wrapper for converter::convertTo() method*/
	protected function convertTo( $text, $variant ) {
		return $this->getLang()
			->mConverter
			->convertTo(
				$text, $variant
			);
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'sr-ec' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'sr-el' );
	}
}
