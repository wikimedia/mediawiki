<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \ZghConverter
 */
class ZghConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result,
			$this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'zgh'      => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ',
					'zgh-latn' => 'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ',
				],
				'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ'
			],
		];
	}
}
