<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\BooleanDef
 */
class BooleanDefTest extends TypeDefTestCase {

	protected static $testClass = BooleanDef::class;

	public function provideValidate() {
		foreach ( [
			[ BooleanDef::$TRUEVALS, true ],
			[ BooleanDef::$FALSEVALS, false ],
			[ [ '' ], false ],
		] as list( $vals, $expect ) ) {
			foreach ( $vals as $v ) {
				yield "Value '$v'" => [ $v, $expect ];
				$v2 = ucfirst( $v );
				if ( $v2 !== $v ) {
					yield "Value '$v2'" => [ $v2, $expect ];
				}
				$v3 = strtoupper( $v );
				if ( $v3 !== $v2 ) {
					yield "Value '$v3'" => [ $v3, $expect ];
				}
			}
		}

		yield "Value '2'" => [ 2, new ValidationException(
			DataMessageValue::new( 'paramvalidator-badbool', [], 'badbool' ),
			'test', '2', []
		) ];

		yield "Value 'foobar'" => [ 'foobar', new ValidationException(
			DataMessageValue::new( 'paramvalidator-badbool', [], 'badbool' ),
			'test', 'foobar', []
		) ];
	}

	public function provideStringifyValue() {
		return [
			[ true, 'true' ],
			[ false, 'false' ],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-boolean"><text>1</text></message>',
				],
			],
			'Multi-valued' => [
				[ ParamValidator::PARAM_ISMULTI => true ],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-boolean"><text>2</text></message>',
				],
			],
		];
	}

}
