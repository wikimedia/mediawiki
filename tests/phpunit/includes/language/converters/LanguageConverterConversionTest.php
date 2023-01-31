<?php

namespace Tests\Languages\Converters;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 *
 */
class LanguageConverterConversionTest extends MediaWikiIntegrationTestCase {

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
		$jsonFile = file_get_contents( __DIR__ . '/../../../data/languageConverter/conversionData.json' );
		$converterDataArr = json_decode( $jsonFile, true );

		return $converterDataArr;
	}
}
