<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \ShiConverter
 */
class ShiConverterTest extends MediaWikiIntegrationTestCase {
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
					'shi'      => 'ABGGʷDḌEFKKʷ HḤƐXQIJLMN URṚƔSṢCTṬW YZẒ OPV',
					'shi-tfng' => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ ⵓⴱⴼ',
					'shi-latn' => 'ABGGʷDḌEFKKʷ HḤƐXQIJLMN URṚƔSṢCTṬW YZẒ OPV',
				],
				'ABGGʷDḌEFKKʷ HḤƐXQIJLMN URṚƔSṢCTṬW YZẒ OPV'
			],
			[
				[
					'shi'      => 'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ opv',
					'shi-tfng' => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ ⵓⴱⴼ',
					'shi-latn' => 'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ opv',
				],
				'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ opv'
			],
			[
				[
					'shi'      => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ',
					'shi-tfng' => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ',
					'shi-latn' => 'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ',
				],
				'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ'
			],
		];
	}
}
