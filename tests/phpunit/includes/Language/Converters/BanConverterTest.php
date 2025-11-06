<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \BanConverter
 */
class BanConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	public function testHasVariants() {
		$this->assertTrue( $this->getLanguageConverter()->hasVariants(), 'ban has variants' );
	}

	public function testHasVariantBogus() {
		$variants = [
			'ban-bali',
			'ban-x-dharma',
			'ban-x-palmleaf',
			'ban-x-pku',
			'ban',
		];

		foreach ( $variants as $variant ) {
			$this->assertTrue( $this->getLanguageConverter()->hasVariant( $variant ),
				"no variant for $variant language" );
		}
	}

	public function testBalineseDetection() {
		$this->assertBalinese(
			'ᬫᬦ᭄ᬢ᭄ᬭ - abc',
			'Balinese guessing characters'
		);
	}

	public function testConversionToLatin() {
		// A simple conversion of Latin to Latin
		$this->assertEquals( 'mantra',
			$this->convertToLatin( 'mantra' )
		);
		// A simple conversion of Balinese to Latin
		$this->assertEquals( 'mantra',
			$this->convertToLatin( 'ᬫᬦ᭄ᬢ᭄ᬭ' )
		);
		// This text has some Latin, but is recognized as Balinese, so it should be converted
		$this->assertEquals( 'abcdmantra',
			$this->convertToLatin( 'abcdᬫᬦ᭄ᬢ᭄ᬭ' )
		);
	}

	# #### HELPERS #####################################################

	/**
	 * Wrapper to verify text stay the same after applying conversion
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'ban-bali' or 'ban'
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
	 * @param string $variant Language variant 'ban-bali' or 'ban'
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
	 * Verifiy the given Balinese text is not converted when using
	 * using the Balinese variant and converted to Latin when using
	 * the Latin variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertBalinese( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'ban-bali', $msg );
		$this->assertConverted( $text, 'ban', $msg );
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

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'ban' );
	}
}
