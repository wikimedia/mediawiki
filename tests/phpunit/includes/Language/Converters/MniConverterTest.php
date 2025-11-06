<?php
/**
 * PHPUnit tests for the Meitei converter.
 * The language can be represented using two scripts:
 *  - Meitei (mni)
 *  - Bangla (mni-beng)
 *
 * @author Nokib Sarkar
 */

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MniConverter
 */
class MniConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @covers \MediaWiki\Language\LanguageConverter::convertTo
	 */
	public function testConversionToBengali() {
		// A conversion of Meitei to Bengali
		$this->assertEquals(
			'মীতৈ অসী কংলৈপাক্কী অচৌবা ফুরুপকী মনুংদা ১নী ।',
			$this->convertToBengali(
				'ꯃꯤꯇꯩ ꯑꯁꯤ ꯀꯪꯂꯩꯄꯥꯛꯀꯤ ꯑꯆꯧꯕ ꯐꯨꯔꯨꯞꯀꯤ ꯃꯅꯨꯡꯗ ꯱ꯅꯤ ꯫'
			)
		);

		// A simple conversion of Bengali to Bengali
		$this->assertEquals( 'লৌমী লৌবুক তা কুম্দুবা ফৌ',
			$this->convertToBengali( 'ꯂꯧꯃꯤ ꯂꯧꯕꯨꯛ ꯇꯥ ꯀꯨꯝꯗꯨꯕꯥ ꯐꯧ' )
		);
	}

	public function testRemoveHalanta() {
		// Remove Halanta from the end of the word
		$this->assertEquals( 'ন ক ম ং ল ৎ প ',
			$this->convertTo( 'ꯟ ꯛ ꯝ ꯡ ꯜ ꯠ ꯞ ', 'mni-beng' )
		);
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

	protected function convertToBengali( $text ) {
		return $this->convertTo( $text, 'mni-beng' );
	}
}
