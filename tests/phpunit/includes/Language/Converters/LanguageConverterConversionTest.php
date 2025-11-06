<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \MediaWiki\Language\LanguageConverter
 */
class LanguageConverterConversionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideConversionData
	 */
	public function testConversion( $variant, $text, $expected ) {
		$language = $this->getServiceContainer()->getLanguageFactory()->getParentLanguage(
			str_starts_with( $variant, 'ike' ) ? 'iu' : $variant
		);

		$converter = $this->getServiceContainer()->getLanguageConverterFactory()->getLanguageConverter( $language );

		$this->assertEquals(
			$expected,
			$converter->convertTo( $text, $variant )
		);
	}

	public static function provideConversionData() {
		$jsonFile = file_get_contents( __DIR__ . '/../../../data/languageConverter/conversionData.json' );
		$converterDataArr = json_decode( $jsonFile, true );

		return $converterDataArr;
	}
}
