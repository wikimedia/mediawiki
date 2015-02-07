<?php
/**
 * PHPUnit tests for the Uzbek language.
 * The language can be represented using two scripts:
 *  - Latin (uz-latn)
 *  - Cyrillic (uz-cyrl)
 *
 * @author Robin Pepermans
 * @author Antoine Musso <hashar at free dot fr>
 * @copyright Copyright © 2012, Robin Pepermans
 * @copyright Copyright © 2011, Antoine Musso <hashar at free dot fr>
 * @file
 *
 * @todo methods in test class should be tidied:
 *  - Should be split into separate test methods and data providers
 *  - Tests for LanguageConverter and Language should probably be separate..
 */

/** Tests for MediaWiki languages/LanguageUz.php */
class LanguageUzTest extends LanguageClassesTestCase {

	/**
	 * @author Nikola Smolenski
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToCyrillic() {
		// A conversion of Latin to Cyrillic
		$this->assertEquals( 'абвгғ',
			$this->convertToCyrillic( 'abvggʻ' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгўоdb',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vgoʻo-{db}-' )
		);
		// A simple conversion of Cyrillic to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'абвг' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdaž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{da}-ž' )
		);
		// e = э at the beginning of a word or after a vowel
		// otherwise e = е
		$this->assertEquals( 'эшик Эшак ЭЧКИ поэма АЭРОПОРТ кенг',
			$this->convertToCyrillic( 'eshik Eshak ECHKI poema AEROPORT keng' )
		);
		// s = ц right after a consonant, otherwise s = с
		// In some words that start with an 's', this letter should be transliterated to 'ц',
		// in others to 'с'.
		$this->assertEquals( 'Цирк цент ЦЕНТНЕР пропорция ПОТЕНЦИАЛ Опцион судхўр кесмоқ',
			$this->convertToCyrillic( 'Sirk sent SENTNER proporsiya POTENSIAL Opsion sudxo‘r kesmoq' )
		);
		// compound letters
		$this->assertEquals( 'етмиш юз ўн ҒАЙРАТ Шаҳар ЙЎЛ ПИНЦЕТ Ёлғиз юрак оят',
			$this->convertToCyrillic( 'yetmish yuz o‘n G‘AYRAT Shahar YO‘L PINSET Yolg‘iz yurak oyat' )
		);
		// тс, ц
		$this->assertEquals( 'нажотсиз Ниҳоятсиз БУТСИ вақтсиз регистрация боцман Корреляция',
			$this->convertToCyrillic( 'najotsiz Nihoyatsiz BUTSI vaqtsiz registrasiya bosman Korrelyasiya' )
		);


	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToLatin() {
		// A simple conversion of Latin to Latin
		$this->assertEquals( 'abdef',
			$this->convertToLatin( 'abdef' )
		);
		// A conversion of Cyrillic to Latin
		$this->assertEquals( 'gʻabtsdOʻQyo',
			$this->convertToLatin( 'ғабцдЎҚё' )
		);
	}

	##### HELPERS #####################################################
	/**
	 * Wrapper to verify text stay the same after applying conversion
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'uz-cyrl' or 'uz-latn'
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
	 * @param string $variant Language variant 'uz-cyrl' or 'uz-latn'
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
	 * Verify the given Cyrillic text is not converted when using
	 * using the cyrillic variant and converted to Latin when using
	 * the Latin variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertCyrillic( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'uz-cyrl', $msg );
		$this->assertConverted( $text, 'uz-latn', $msg );
	}

	/**
	 * Verify the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'uz-latn', $msg );
		$this->assertConverted( $text, 'uz-cyrl', $msg );
	}

	/**
	 * Wrapper for converter::convertTo()
	 * @param string $text Text to convert
	 * @param string $variant Language variant
	 * @return string Converted string
	 */
	protected function convertTo( $text, $variant ) {
		return $this->getLang()->mConverter->convertTo( $text, $variant );
	}

	/**
	 * Convert text to cyrillic
	 * @param $text
	 * @return string Converted string
	 */
	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'uz-cyrl' );
	}

	/**
	 * Convert text to latin
	 * @param $text
	 * @return string Converted string
	 */
	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'uz-latn' );
	}
}
