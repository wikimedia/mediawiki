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
 */

require_once dirname( __DIR__ ) . '/bootstrap.php';

/** Tests for MediaWiki languages/LanguageUz.php */
class LanguageUzTest extends MediaWikiTestCase {
	/* Language object. Initialized before each test */
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'uz' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/**
	 * @author Nikola Smolenski
	 */
	function testConversionToCyrillic() {
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

	function testConversionToLatin() {
		// A simple convertion of Latin to Latin
		$this->assertEquals( 'abdef',
			$this->convertToLatin( 'abdef' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( 'gʻabtsdOʻQyo',
			$this->convertToLatin( 'ғабцдЎҚё' )
		);
	}

	##### HELPERS #####################################################
	/**
	 * Wrapper to verify text stay the same after applying conversion
	 * @param $text string Text to convert
	 * @param $variant string Language variant 'uz-cyrl' or 'uz-latn'
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
	 * @param $variant string Language variant 'uz-cyrl' or 'uz-latn'
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
		$this->assertUnConverted( $text, 'uz-cyrl', $msg );
		$this->assertConverted( $text, 'uz-latn', $msg );
	}
	/**
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 */
	function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'uz-latn', $msg );
		$this->assertConverted( $text, 'uz-cyrl', $msg );
	}


	/** Wrapper for converter::convertTo() method*/
	function convertTo( $text, $variant ) {
		return $this->lang->mConverter->convertTo( $text, $variant );
	}
	function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'uz-cyrl' );
	}
	function convertToLatin( $text ) {
		return $this->convertTo( $text, 'uz-latn' );
	}
}
