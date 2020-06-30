<?php

/**
 * @group Language
 * @covers ZhConverter
 */
class ZhConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers ZhConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result,
			$this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			// Plain hant -> hans
			[
				[
					'zh'      => '㑯',
					'zh-hans' => '㑔',
					'zh-hant' => '㑯',
					'zh-cn'   => '㑔',
					'zh-hk'   => '㑯',
					'zh-mo'   => '㑯',
					'zh-my'   => '㑔',
					'zh-sg'   => '㑔',
					'zh-tw'   => '㑯',
				],
				'㑯'
			],
			// Plain hans -> hant
			[
				[
					'zh'      => '㐷',
					'zh-hans' => '㐷',
					'zh-hant' => '傌',
					'zh-cn'   => '㐷',
					'zh-hk'   => '傌',
					'zh-mo'   => '傌',
					'zh-my'   => '㐷',
					'zh-sg'   => '㐷',
					'zh-tw'   => '傌',
				],
				'㐷'
			],
			// zh-cn specific
			[
				[
					'zh'      => '仲介',
					'zh-hans' => '仲介',
					'zh-hant' => '仲介',
					'zh-cn'   => '中介',
					'zh-hk'   => '仲介',
					'zh-mo'   => '仲介',
					'zh-my'   => '中介',
					'zh-sg'   => '中介',
					'zh-tw'   => '仲介',
				],
				'仲介'
			],
			// zh-hk specific
			[
				[
					'zh'      => '中文里',
					'zh-hans' => '中文里',
					'zh-hant' => '中文裡',
					'zh-cn'   => '中文里',
					'zh-hk'   => '中文裏',
					'zh-mo'   => '中文裏',
					'zh-my'   => '中文里',
					'zh-sg'   => '中文里',
					'zh-tw'   => '中文裡',
				],
				'中文里'
			],
			// zh-tw specific
			[
				[
					'zh'      => '甲肝',
					'zh-hans' => '甲肝',
					'zh-hant' => '甲肝',
					'zh-cn'   => '甲肝',
					'zh-hk'   => '甲肝',
					'zh-mo'   => '甲肝',
					'zh-my'   => '甲肝',
					'zh-sg'   => '甲肝',
					'zh-tw'   => 'A肝',
				],
				'甲肝'
			],
			// zh-tw overrides zh-hant
			[
				[
					'zh'      => '账',
					'zh-hans' => '账',
					'zh-hant' => '賬',
					'zh-cn'   => '账',
					'zh-hk'   => '賬',
					'zh-mo'   => '賬',
					'zh-my'   => '账',
					'zh-sg'   => '账',
					'zh-tw'   => '帳',
				],
				'账'
			],
			// zh-hk overrides zh-hant
			[
				[
					'zh'      => '一地里',
					'zh-hans' => '一地里',
					'zh-hant' => '一地裡',
					'zh-cn'   => '一地里',
					'zh-hk'   => '一地裏',
					'zh-mo'   => '一地裏',
					'zh-my'   => '一地里',
					'zh-sg'   => '一地里',
					'zh-tw'   => '一地裡',
				],
				'一地里'
			],
		];
	}
}
