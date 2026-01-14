<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \MediaWiki\Language\Converters\ZghConverter
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
					'zgh'      => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏⵒ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ',
					'zgh-latn' => 'abggʷdḍefkkʷ hḥɛxqijlmnp urṛɣsṣctṭw yzẓ',
				],
				'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏⵒ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ'
			],
		];
	}
}
