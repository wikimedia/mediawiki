<?php

namespace Wikimedia\ParamValidator;

use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory;

/**
 * @covers Wikimedia\ParamValidator\ParamValidator
 */
class ParamValidatorTest extends \PHPUnit\Framework\TestCase {

	public function testTypeRegistration() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) )
		);
		$this->assertSame( array_keys( ParamValidator::$STANDARD_TYPES ), $validator->knownTypes() );

		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => [], 'bar' => [] ] ]
		);
		$validator->addTypeDef( 'baz', [] );
		try {
			$validator->addTypeDef( 'baz', [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
		}
		$validator->overrideTypeDef( 'bar', null );
		$validator->overrideTypeDef( 'baz', [] );
		$this->assertSame( [ 'foo', 'baz' ], $validator->knownTypes() );

		$this->assertTrue( $validator->hasTypeDef( 'foo' ) );
		$this->assertFalse( $validator->hasTypeDef( 'bar' ) );
		$this->assertTrue( $validator->hasTypeDef( 'baz' ) );
		$this->assertFalse( $validator->hasTypeDef( 'bazz' ) );
	}

	public function testGetTypeDef() {
		$callbacks = new SimpleCallbacks( [] );
		$factory = $this->getMockBuilder( ObjectFactory::class )
			->setConstructorArgs( [ $this->getMockForAbstractClass( ContainerInterface::class ) ] )
			->setMethods( [ 'createObject' ] )
			->getMock();
		$factory->method( 'createObject' )
			->willReturnCallback( function ( $spec, $options ) use ( $callbacks ) {
				$this->assertInternalType( 'array', $spec );
				$this->assertSame(
					[ 'extraArgs' => [ $callbacks ], 'assertClass' => TypeDef::class ], $options
				);
				$ret = $this->getMockBuilder( TypeDef::class )
					->setConstructorArgs( [ $callbacks ] )
					->getMockForAbstractClass();
				$ret->spec = $spec;
				return $ret;
			} );
		$validator = new ParamValidator( $callbacks, $factory );

		$def = $validator->getTypeDef( 'boolean' );
		$this->assertInstanceOf( TypeDef::class, $def );
		$this->assertSame( ParamValidator::$STANDARD_TYPES['boolean'], $def->spec );

		$def = $validator->getTypeDef( [] );
		$this->assertInstanceOf( TypeDef::class, $def );
		$this->assertSame( ParamValidator::$STANDARD_TYPES['enum'], $def->spec );

		$def = $validator->getTypeDef( 'missing' );
		$this->assertNull( $def );
	}

	public function testGetTypeDef_caching() {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] );
		$def1 = $mb->getMockForAbstractClass();
		$def2 = $mb->getMockForAbstractClass();
		$this->assertNotSame( $def1, $def2, 'sanity check' );

		$factory = $this->getMockBuilder( ObjectFactory::class )
			->setConstructorArgs( [ $this->getMockForAbstractClass( ContainerInterface::class ) ] )
			->setMethods( [ 'createObject' ] )
			->getMock();
		$factory->expects( $this->once() )->method( 'createObject' )->willReturn( $def1 );

		$validator = new ParamValidator( $callbacks, $factory, [ 'typeDefs' => [
			'foo' => [],
			'bar' => $def2,
		] ] );

		$this->assertSame( $def1, $validator->getTypeDef( 'foo' ) );

		// Second call doesn't re-call ObjectFactory
		$this->assertSame( $def1, $validator->getTypeDef( 'foo' ) );

		// When registered a TypeDef directly, doesn't call ObjectFactory
		$this->assertSame( $def2, $validator->getTypeDef( 'bar' ) );
	}

	/**
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionMessage Expected instance of Wikimedia\ParamValidator\TypeDef, got stdClass
	 */
	public function testGetTypeDef_error() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => [ 'class' => \stdClass::class ] ] ]
		);
		$validator->getTypeDef( 'foo' );
	}

	/** @dataProvider provideNormalizeSettings */
	public function testNormalizeSettings( $input, $expect ) {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->setMethods( [ 'normalizeSettings' ] );
		$mock1 = $mb->getMockForAbstractClass();
		$mock1->method( 'normalizeSettings' )->willReturnCallback( function ( $s ) {
			$s['foo'] = 'FooBar!';
			return $s;
		} );
		$mock2 = $mb->getMockForAbstractClass();
		$mock2->method( 'normalizeSettings' )->willReturnCallback( function ( $s ) {
			$s['bar'] = 'FooBar!';
			return $s;
		} );

		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => $mock1, 'bar' => $mock2 ] ]
		);

		$this->assertSame( $expect, $validator->normalizeSettings( $input ) );
	}

	public static function provideNormalizeSettings() {
		return [
			'Plain value' => [
				'ok?',
				[ ParamValidator::PARAM_DEFAULT => 'ok?', ParamValidator::PARAM_TYPE => 'string' ],
			],
			'Simple array' => [
				[ 'test' => 'ok?' ],
				[ 'test' => 'ok?', ParamValidator::PARAM_TYPE => 'NULL' ],
			],
			'A type with overrides' => [
				[ ParamValidator::PARAM_TYPE => 'foo', 'test' => 'ok?' ],
				[ ParamValidator::PARAM_TYPE => 'foo', 'test' => 'ok?', 'foo' => 'FooBar!' ],
			],
		];
	}

	/** @dataProvider provideExplodeMultiValue */
	public function testExplodeMultiValue( $value, $limit, $expect ) {
		$this->assertSame( $expect, ParamValidator::explodeMultiValue( $value, $limit ) );
	}

	public static function provideExplodeMultiValue() {
		return [
			[ 'foobar', 100, [ 'foobar' ] ],
			[ 'foo|bar|baz', 100, [ 'foo', 'bar', 'baz' ] ],
			[ "\x1Ffoo\x1Fbar\x1Fbaz", 100, [ 'foo', 'bar', 'baz' ] ],
			[ 'foo|bar|baz', 2, [ 'foo', 'bar|baz' ] ],
			[ "\x1Ffoo\x1Fbar\x1Fbaz", 2, [ 'foo', "bar\x1Fbaz" ] ],
			[ '|bar|baz', 100, [ '', 'bar', 'baz' ] ],
			[ "\x1F\x1Fbar\x1Fbaz", 100, [ '', 'bar', 'baz' ] ],
			[ '', 100, [] ],
			[ "\x1F", 100, [] ],
		];
	}

	/**
	 * @expectedException DomainException
	 * @expectedExceptionMessage Param foo's type is unknown - string
	 */
	public function testGetValue_badType() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [] ]
		);
		$validator->getValue( 'foo', 'default', [] );
	}

	/** @dataProvider provideGetValue */
	public function testGetValue(
		$settings, $parseLimit, $get, $value, $isSensitive, $isDeprecated
	) {
		$callbacks = new SimpleCallbacks( $get );
		$dummy = (object)[];
		$options = [ $dummy ];

		$settings += [
			ParamValidator::PARAM_TYPE => 'xyz',
			ParamValidator::PARAM_DEFAULT => null,
		];

		$mockDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->getMockForAbstractClass();

		// Mock the validateValue method so we can test only getValue
		$validator = $this->getMockBuilder( ParamValidator::class )
			->setConstructorArgs( [
				$callbacks,
				new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
				[ 'typeDefs' => [ 'xyz' => $mockDef ] ]
			] )
			->setMethods( [ 'validateValue' ] )
			->getMock();
		$validator->expects( $this->once() )->method( 'validateValue' )
			->with(
				$this->identicalTo( 'foobar' ),
				$this->identicalTo( $value ),
				$this->identicalTo( $settings ),
				$this->identicalTo( $options )
			)
			->willReturn( $dummy );

		$this->assertSame( $dummy, $validator->getValue( 'foobar', $settings, $options ) );

		$expectConditions = [];
		if ( $isSensitive ) {
			$expectConditions[] = new ValidationException(
				'foobar', $value, $settings, 'param-sensitive', []
			);
		}
		if ( $isDeprecated ) {
			$expectConditions[] = new ValidationException(
				'foobar', $value, $settings, 'param-deprecated', []
			);
		}
		$this->assertEquals( $expectConditions, $callbacks->getRecordedConditions() );
	}

	public static function provideGetValue() {
		$sen = [ ParamValidator::PARAM_SENSITIVE => true ];
		$dep = [ ParamValidator::PARAM_DEPRECATED => true ];
		$dflt = [ ParamValidator::PARAM_DEFAULT => 'DeFaUlT' ];
		return [
			'Simple case' => [ [], false, [ 'foobar' => '!!!' ], '!!!', false, false ],
			'Not provided' => [ $sen + $dep, false, [], null, false, false ],
			'Not provided, default' => [ $sen + $dep + $dflt, true, [], 'DeFaUlT', false, false ],
			'Provided' => [ $dflt, false, [ 'foobar' => 'XYZ' ], 'XYZ', false, false ],
			'Provided, sensitive' => [ $sen, false, [ 'foobar' => 'XYZ' ], 'XYZ', true, false ],
			'Provided, deprecated' => [ $dep, false, [ 'foobar' => 'XYZ' ], 'XYZ', false, true ],
			'Provided array' => [ $dflt, false, [ 'foobar' => [ 'XYZ' ] ], [ 'XYZ' ], false, false ],
		];
	}

	/**
	 * @expectedException DomainException
	 * @expectedExceptionMessage Param foo's type is unknown - string
	 */
	public function testValidateValue_badType() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [] ]
		);
		$validator->validateValue( 'foo', null, 'default', [] );
	}

	/** @dataProvider provideValidateValue */
	public function testValidateValue(
		$value, $settings, $highLimits, $valuesList, $calls, $expect, $expectConditions = [],
		$constructorOptions = []
	) {
		$callbacks = new SimpleCallbacks( [] );
		$settings += [
			ParamValidator::PARAM_TYPE => 'xyz',
			ParamValidator::PARAM_DEFAULT => null,
		];
		$dummy = (object)[];
		$options = [ $dummy, 'useHighLimits' => $highLimits ];
		$eOptions = $options;
		$eOptions2 = $eOptions;
		if ( $valuesList !== null ) {
			$eOptions2['values-list'] = $valuesList;
		}

		$mockDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->setMethods( [ 'validate', 'getEnumValues' ] )
			->getMockForAbstractClass();
		$mockDef->method( 'getEnumValues' )
			->with(
				$this->identicalTo( 'foobar' ), $this->identicalTo( $settings ), $this->identicalTo( $eOptions )
			)
			->willReturn( [ 'a', 'b', 'c', 'd', 'e', 'f' ] );
		$mockDef->expects( $this->exactly( count( $calls ) ) )->method( 'validate' )->willReturnCallback(
			function ( $n, $v, $s, $o ) use ( $settings, $eOptions2, $calls ) {
				$this->assertSame( 'foobar', $n );
				$this->assertSame( $settings, $s );
				$this->assertSame( $eOptions2, $o );

				if ( !array_key_exists( $v, $calls ) ) {
					$this->fail( "Called with unexpected value '$v'" );
				}
				if ( $calls[$v] === null ) {
					throw new ValidationException( $n, $v, $s, 'badvalue', [] );
				}
				return $calls[$v];
			}
		);

		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			$constructorOptions + [ 'typeDefs' => [ 'xyz' => $mockDef ] ]
		);

		if ( $expect instanceof ValidationException ) {
			try {
				$validator->validateValue( 'foobar', $value, $settings, $options );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ValidationException $ex ) {
				$this->assertSame( $expect->getFailureCode(), $ex->getFailureCode() );
				$this->assertSame( $expect->getFailureData(), $ex->getFailureData() );
			}
		} else {
			$this->assertSame(
				$expect, $validator->validateValue( 'foobar', $value, $settings, $options )
			);

			$conditions = [];
			foreach ( $callbacks->getRecordedConditions() as $c ) {
				$conditions[] = array_merge( [ $c->getFailureCode() ], $c->getFailureData() );
			}
			$this->assertSame( $expectConditions, $conditions );
		}
	}

	public static function provideValidateValue() {
		return [
			'No value' => [ null, [], false, null, [], null ],
			'No value, required' => [
				null,
				[ ParamValidator::PARAM_REQUIRED => true ],
				false,
				null,
				[],
				new ValidationException( 'foobar', null, [], 'missingparam', [] ),
			],
			'Non-multi value' => [ 'abc', [], false, null, [ 'abc' => 'def' ], 'def' ],
			'Simple multi value' => [
				'a|b|c|d',
				[ ParamValidator::PARAM_ISMULTI => true ],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D' ],
				[ 'A', 'B', 'C', 'D' ],
			],
			'Array multi value' => [
				[ 'a', 'b', 'c', 'd' ],
				[ ParamValidator::PARAM_ISMULTI => true ],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D' ],
				[ 'A', 'B', 'C', 'D' ],
			],
			'Multi value with PARAM_ALL' => [
				'*',
				[ ParamValidator::PARAM_ISMULTI => true, ParamValidator::PARAM_ALL => true ],
				false,
				null,
				[],
				[ 'a', 'b', 'c', 'd', 'e', 'f' ],
			],
			'Multi value with PARAM_ALL = "x"' => [
				'x',
				[ ParamValidator::PARAM_ISMULTI => true, ParamValidator::PARAM_ALL => "x" ],
				false,
				null,
				[],
				[ 'a', 'b', 'c', 'd', 'e', 'f' ],
			],
			'Multi value with PARAM_ALL = "x", passing "*"' => [
				'*',
				[ ParamValidator::PARAM_ISMULTI => true, ParamValidator::PARAM_ALL => "x" ],
				false,
				[ '*' ],
				[ '*' => '?' ],
				[ '?' ],
			],

			'Too many values' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 4,
				],
				false,
				null,
				[],
				new ValidationException( 'foobar', 'a|b|c|d', [], 'toomanyvalues', [ 'limit' => 2 ] ),
			],
			'Too many values as array' => [
				[ 'a', 'b', 'c', 'd' ],
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 4,
				],
				false,
				null,
				[],
				new ValidationException(
					'foobar', [ 'a', 'b', 'c', 'd' ], [], 'toomanyvalues', [ 'limit' => 2 ]
				),
			],
			'Not too many values for highlimits' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 4,
				],
				true,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D' ],
				[ 'A', 'B', 'C', 'D' ],
			],
			'Too many values for highlimits' => [
				'a|b|c|d|e',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 4,
				],
				true,
				null,
				[],
				new ValidationException( 'foobar', 'a|b|c|d|e', [], 'toomanyvalues', [ 'limit' => 4 ] ),
			],

			'Too many values via default' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				false,
				null,
				[],
				new ValidationException( 'foobar', 'a|b|c|d', [], 'toomanyvalues', [ 'limit' => 2 ] ),
				[],
				[ 'ismultiLimits' => [ 2, 4 ] ],
			],
			'Not too many values for highlimits via default' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				true,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D' ],
				[ 'A', 'B', 'C', 'D' ],
				[],
				[ 'ismultiLimits' => [ 2, 4 ] ],
			],
			'Too many values for highlimits via default' => [
				'a|b|c|d|e',
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				true,
				null,
				[],
				new ValidationException( 'foobar', 'a|b|c|d|e', [], 'toomanyvalues', [ 'limit' => 4 ] ),
				[],
				[ 'ismultiLimits' => [ 2, 4 ] ],
			],

			'Invalid values' => [
				'a|b|c|d',
				[ ParamValidator::PARAM_ISMULTI => true ],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => null ],
				new ValidationException( 'foobar', 'b', [], 'badvalue', [] ),
			],
			'Ignored invalid values' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_IGNORE_INVALID_VALUES => true,
				],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => null, 'c' => null, 'd' => 'D' ],
				[ 'A', 'D' ],
				[
					[ 'unrecognizedvalues', 'values' => [ 'b', 'c' ] ],
				],
			],
		];
	}

}
