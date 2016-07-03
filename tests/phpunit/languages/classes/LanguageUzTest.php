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
		// A convertion of Latin to Cyrillic
		$this->assertEquals( 'абвгғ',
			$this->convertToCyrillic( 'abvggʻ' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгўоdb',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vgoʻo-{db}-' )
		);
		// A simple convertion of Cyrillic to Cyrillic
		$this->assertEquals( 'абвг',
			$this->convertToCyrillic( 'абвг' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdaž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{da}-ž' )
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToLatin() {
		// A simple convertion of Latin to Latin
		$this->assertEquals( 'abdef',
			$this->convertToLatin( 'abdef' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( 'gʻabtsdOʻQyo',
			$this->convertToLatin( 'ғабцдЎҚё' )
		);
	}

	# #### HELPERS #####################################################
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
	 * Verifiy the given Cyrillic text is not converted when using
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
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'uz-latn', $msg );
		$this->assertConverted( $text, 'uz-cyrl', $msg );
	}

	/** Wrapper for converter::convertTo() method*/
	protected function convertTo( $text, $variant ) {
		return $this->getLang()->mConverter->convertTo( $text, $variant );
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'uz-cyrl' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'uz-latn' );
	}
}
