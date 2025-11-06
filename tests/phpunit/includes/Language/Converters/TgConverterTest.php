<?php

namespace MediaWiki\Tests\Language\Converters;

use MediaWiki\Tests\Language\LanguageConverterTestTrait;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @covers \TgConverter
 */
class TgConverterTest extends MediaWikiIntegrationTestCase {
	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'tg'      => 'г',
					'tg-latn' => 'g',
				],
				'г'
			],
			[
				[
					'tg'      => 'g',
					'tg-latn' => 'g',
				],
				'g'
			],
		];
	}
}
