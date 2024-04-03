<?php

namespace MediaWiki\Tests\ParamValidator\TypeDef;

use MediaWiki\ParamValidator\TypeDef\ArrayDef;
use Wikimedia\ParamValidator\SimpleCallbacks;

/**
 * @covers \MediaWiki\ParamValidator\TypeDef\ArrayDef
 */
class ArrayDefTest extends TypeDefUnitTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new ArrayDef( $callbacks );
	}

	public function provideValidate() {
		yield 'assoc array' => [ [ 'x' => 1 ], [ 'x' => 1 ], ];
		yield 'indexed array' => [ [ 'x' ], [ 'x' ], ];
		yield 'array' => [ [], [], ];

		$notComplex = self::getValidationException( 'notarray', null );
		yield 'null' => [ null, $notComplex ];
		yield 'string' => [ 'foo', $notComplex ];
		yield 'zero' => [ 0, $notComplex ];
	}
}
