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

/**
 * @covers LanguageSr
 * @covers SrConverter
 */
class LanguageSrTest extends LanguageClassesTestCase {
	/**
	 * @covers Language::hasVariants
	 */
	public function testHasVariants() {
		$this->assertTrue( $this->getLang()->hasVariants(), 'sr has variants' );
	}

	/**
	 * @covers Language::hasVariant
	 */
	public function testHasVariant() {
		$langs = [
			'sr' => $this->getLang(),
			'sr-ec' => Language::factory( 'sr-ec' ),
			'sr-cyrl' => Language::factory( 'sr-cyrl' ),
		];
		foreach ( $langs as $code => $l ) {
			$p = $l->getParentLanguage();
			$this->assertTrue( $p !== null, 'parent language exists' );
			$this->assertEquals( 'sr', $p->getCode(), 'sr is parent language' );
			$this->assertTrue( $p instanceof LanguageSr, 'parent is LanguageSr' );
			// This is a valid variant of the base
			$this->assertTrue( $p->hasVariant( $l->getCode() ) );
			// This test should be tweaked if/when sr-ec is renamed (T117845)
			// to swap the roles of sr-ec and sr-Cyrl
			$this->assertTrue( $l->hasVariant( 'sr-ec' ), 'sr-ec exists' );
			// note that sr-cyrl is an alias, not a (strict) variant name
			foreach ( [ 'sr-EC', 'sr-Cyrl', 'sr-cyrl', 'sr-bogus' ] as $v ) {
				$this->assertFalse( $l->hasVariant( $v ), "$v is not a variant of $code" );
			}
		}
	}

	/**
	 * @covers Language::hasVariant
	 */
	public function testHasVariantBogus() {
		$langs = [
			// Note that case matters when calling Language::factory();
			// these are all bogus language codes
			'sr-EC' => Language::factory( 'sr-EC' ),
			'sr-Cyrl' => Language::factory( 'sr-Cyrl' ),
			'sr-bogus' => Language::factory( 'sr-bogus' ),
		];
		foreach ( $langs as $code => $l ) {
			$p = $l->getParentLanguage();
			$this->assertTrue( $p === null, 'no parent for bogus language' );
			$this->assertFalse( $l instanceof LanguageSr, "$code is not sr" );
			$this->assertFalse( $this->getLang()->hasVariant( $code ), "$code is not a sr variant" );
			foreach ( [ 'sr', 'sr-ec', 'sr-EC', 'sr-Cyrl', 'sr-cyrl', 'sr-bogus' ] as $v ) {
				if ( $v !== $code ) {
					$this->assertFalse( $l->hasVariant( $v ), "no variant $v" );
				}
			}
		}
	}

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
			'Mostly Cyrillic characters'
		);
		$this->assertLatin(
			'šđčćžŠĐČĆŽ - шђчћж',
			'Mostly Latin characters'
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testSameAmountOfLatinAndCyrillicGetConverted() {
		$this->assertConverted(
			'4 Latin: šđčć | 4 Cyrillic: шђчћ',
			'sr-ec'
		);
		$this->assertConverted(
			'4 Latin: šđčć | 4 Cyrillic: шђчћ',
			'sr-el'
		);
	}

	/**
	 * @author Nikola Smolenski
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToCyrillic() {
		// A simple conversion of Latin to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'abvg' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdž',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{dž}-' )
		);
		// A simple conversion of Cyrillic to Cyrillic
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
		// A simple conversion of Latin to Latin
		$this->assertEquals( 'abcd',
			$this->convertToLatin( 'abcd' )
		);
		// A simple conversion of Cyrillic to Latin
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
	 * Wrapper to verify text stay the same after applying conversion
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
	 * using the Cyrillic variant and converted to Latin when using
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

	/** Wrapper for converter::convertTo() method */
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
