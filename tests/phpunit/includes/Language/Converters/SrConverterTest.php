<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \SrConverter
 */
class SrConverterTest extends MediaWikiIntegrationTestCase {
	use LanguageConverterTestTrait;

	public function testHasVariants() {
		$this->assertTrue( $this->getLanguageConverter()->hasVariants(), 'sr has variants' );
	}

	public function testHasVariantBogus() {
		$variants = [
			'sr-ec',
			'sr-el',
		];

		foreach ( $variants as $variant ) {
			$this->assertTrue( $this->getLanguageConverter()->hasVariant( $variant ),
				"no variant for $variant language" );
		}
	}

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
		// Same, but put the roman numerals at the start/end of the string
		$this->assertEquals( 'XX а I б II в III г IV шђжчћ XX',
			$this->convertToCyrillic( 'XX a I b II v III g IV šđžčć XX' )
		);
	}

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
		// Roman numerals are not converted (inverse of ToCyrillic test)
		$this->assertEquals( 'a I b II v III g IV šđžčć',
			$this->convertToLatin( 'а I б II в III г IV шђжчћ' )
		);
		// Same, but put the roman numerals at the start/end of the string
		$this->assertEquals( 'XX a I b II v III g IV šđžčć XX',
			$this->convertToLatin( 'XX а I б II в III г IV шђжчћ XX' )
		);
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

	/**
	 * Wrapper for converter::convertTo() method
	 * @param string $text
	 * @param string $variant
	 * @return string
	 */
	protected function convertTo( $text, $variant ) {
		return $this->getLanguageConverter()->convertTo( $text, $variant );
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'sr-ec' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'sr-el' );
	}
}
