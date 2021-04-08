<?php

namespace Tests\Languages\Converters;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 *
 */
class LanguageConverterIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideConversionData
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversion( $variant, $text, $expected ) {
		$language = MediaWikiServices::getInstance()->getLanguageFactory()->getParentLanguage(
			str_starts_with( $variant, 'ike' ) ? 'iu' : $variant
		);

		$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()->getLanguageConverter( $language );

		$this->assertEquals(
			$expected,
			$converter->convertTo( $text, $variant )
		);
	}

	public function provideConversionData() {
		$jsonFile = file_get_contents( dirname( __DIR__ ) . '/data/languageConverterIntegrationData.json' );
		$converterDataArr = json_decode( $jsonFile, true );

		return $converterDataArr;
	}
}
