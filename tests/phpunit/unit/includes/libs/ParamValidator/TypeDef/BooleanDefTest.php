<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\TypeDef\BooleanDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\BooleanDef
 */
class BooleanDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new BooleanDef( $callbacks, $options );
	}

	public static function provideValidate() {
		$enforceType = [ TypeDef::OPT_ENFORCE_JSON_TYPES => true ];

		foreach ( [
			[ BooleanDef::TRUEVALS, true ],
			[ BooleanDef::FALSEVALS, false ],
			[ [ '' ], false ],
		] as [ $vals, $expect ] ) {
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

				// Fail with OPT_ENFORCE_JSON_TYPES
				yield "Value '$v' with OPT_ENFORCE_JSON_TYPES" => [
					$v,
					new ValidationException(
						DataMessageValue::new( 'paramvalidator-badbool-type', [], 'badbool-type' ),
						'test', $v, []
					),
					[],
					$enforceType
				];
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

		yield "Value true" => [ true, true ];
		yield "Value false" => [ false, false ];

		yield "Value true, OPT_ENFORCE_JSON_TYPES" => [ true, true, [], $enforceType ];
		yield "Value false, OPT_ENFORCE_JSON_TYPES" => [ false, false, [], $enforceType ];
	}

	public static function provideStringifyValue() {
		return [
			[ true, 'true' ],
			[ false, 'false' ],
		];
	}

	public static function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-boolean"><text>1</text></message>',
				],
			],
			'Multi-valued' => [
				[ ParamValidator::PARAM_ISMULTI => true ],
				[],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-boolean"><text>2</text></message>',
				],
			],
		];
	}

}
