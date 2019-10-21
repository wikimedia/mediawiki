<?php

namespace Wikimedia\ParamValidator\TypeDef;

require_once __DIR__ . '/IntegerDefTest.php';

/**
 * @covers Wikimedia\ParamValidator\TypeDef\LimitDef
 */
class LimitDefTest extends IntegerDefTest {

	protected static $testClass = LimitDef::class;

	public function provideValidate() {
		yield from parent::provideValidate();

		$useHigh = [ 'useHighLimits' => true ];
		$max = [ IntegerDef::PARAM_MAX => 2 ];
		$max2 = [ IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ];

		yield 'Max' => [ 'max', 2, $max ];
		yield 'Max, use high' => [ 'max', 2, $max, $useHigh ];
		yield 'Max2' => [ 'max', 2, $max2 ];
		yield 'Max2, use high' => [ 'max', 4, $max2, $useHigh ];
	}

	public function provideNormalizeSettings() {
		return [
			[ [], [ IntegerDef::PARAM_MIN => 0 ] ],
			[
				[ IntegerDef::PARAM_MAX => 2 ],
				[ IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MIN => 0 ],
			],
			[
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
			],
			[
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 4, IntegerDef::PARAM_MAX2 => 2 ],
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 4, IntegerDef::PARAM_MAX2 => 4 ],
			],
			[
				[ IntegerDef::PARAM_MAX2 => 2 ],
				[ IntegerDef::PARAM_MIN => 0 ],
			],
		];
	}

}
