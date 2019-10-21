<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\BooleanDef
 */
class BooleanDefTest extends TypeDefTestCase {

	protected static $testClass = BooleanDef::class;

	public function provideValidate() {
		$ex = new ValidationException( 'test', '', [], 'badbool', [
			'truevals' => BooleanDef::$TRUEVALS,
			'falsevals' => array_merge( BooleanDef::$FALSEVALS, [ 'the empty string' ] ),
		] );

		foreach ( [
			[ BooleanDef::$TRUEVALS, true ],
			[ BooleanDef::$FALSEVALS, false ],
			[ [ '' ], false ],
			[ [ '2', 'foobar' ], $ex ],
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
	}

	public function provideStringifyValue() {
		return [
			[ true, 'true' ],
			[ false, 'false' ],
		];
	}

}
