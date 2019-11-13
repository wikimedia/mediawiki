<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\EnumDef
 */
class EnumDefTest extends TypeDefTestCase {

	protected static $testClass = EnumDef::class;

	public function provideValidate() {
		$settings = [
			ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
			EnumDef::PARAM_DEPRECATED_VALUES => [
				'b' => [ 'not-to-be' ],
				'c' => true,
			],
		];

		return [
			'Basic' => [ 'a', 'a', $settings ],
			'Deprecated' => [ 'c', 'c', $settings, [], [
				[ 'code' => 'deprecated-value', 'data' => [ 'data' => true ] ]
			] ],
			'Deprecated with message' => [
				'b', 'b', $settings, [], [
				[ 'code' => 'deprecated-value', 'data' => [ 'data' => [ 'not-to-be' ] ] ]
			] ],
			'Bad value, non-multi' => [
				'x',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue-enumnotmulti', [], 'badvalue', [] ),
					'test', 'x', $settings
				),
				$settings,
			],
			'Bad value, non-multi but looks like it' => [
				'x|y',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue-enumnotmulti', [], 'badvalue', [] ),
					'test', 'x|y', $settings
				),
				$settings,
			],
			'Bad value, multi' => [
				'x|y',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue-enummulti', [], 'badvalue', [] ),
					'test', 'x|y', $settings + [ ParamValidator::PARAM_ISMULTI => true ]
				),
				$settings + [ ParamValidator::PARAM_ISMULTI => true ],
				[ 'values-list' => [ 'x|y' ] ],
			],
		];
	}

	public function provideGetEnumValues() {
		return [
			'Basic test' => [
				[ ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ] ],
				[ 'a', 'b', 'c', 'd' ],
			],
		];
	}

	public function provideStringifyValue() {
		return [
			'Basic test' => [ 123, '123' ],
			'Array' => [ [ 1, 2, 3 ], '1|2|3' ],
			'Array with pipes' => [ [ 1, 2, '3|4', 5 ], "\x1f1\x1f2\x1f3|4\x1f5" ],
		];
	}

	public function provideGetInfo() {
		return [
			'Non-multi' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
				],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><list listType="comma"><text>a</text><text>b</text><text>c</text><text>d</text></list><num>4</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
			'Multi' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					ParamValidator::PARAM_ISMULTI => true,
				],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>2</text><list listType="comma"><text>a</text><text>b</text><text>c</text><text>d</text></list><num>4</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
			'Deprecated values' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					EnumDef::PARAM_DEPRECATED_VALUES => [ 'b' => 'B', 'c' => false, 'x' => true ],
				],
				[
					'deprecatedvalues' => [ 'b', 'c' ],
				],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><list listType="comma"><text>a</text><text>d</text><text>b</text><text>c</text></list><num>4</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
			'Deprecated values are all not allowed values' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					EnumDef::PARAM_DEPRECATED_VALUES => [ 'x' => true ],
				],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><list listType="comma"><text>a</text><text>b</text><text>c</text><text>d</text></list><num>4</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
			'Empty-string is a value' => [
				[
					ParamValidator::PARAM_TYPE => [ '', 'a', 'b', 'c', 'd' ],
				],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><text><message key="paramvalidator-help-type-enum-can-be-empty"><list listType="comma"><text>a</text><text>b</text><text>c</text><text>d</text></list><num>4</num></message></text><num>5</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
		];
	}

}
