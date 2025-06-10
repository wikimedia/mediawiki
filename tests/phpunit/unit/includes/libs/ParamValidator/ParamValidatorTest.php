<?php

namespace Wikimedia\Tests\ParamValidator;

use DomainException;
use InvalidArgumentException;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;
use UnexpectedValueException;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\ParamValidator
 */
class ParamValidatorTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testTypeRegistration() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) )
		);
		$this->assertSame( array_keys( ParamValidator::STANDARD_TYPES ), $validator->knownTypes() );

		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => [], 'bar' => [] ] ]
		);
		$validator->addTypeDef( 'baz', [] );
		try {
			$validator->addTypeDef( 'baz', [] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException ) {
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
			->onlyMethods( [ 'createObject' ] )
			->getMock();
		$factory->method( 'createObject' )
			->willReturnCallback( function ( $spec, $options ) use ( $callbacks ) {
				$this->assertIsArray( $spec );
				$this->assertSame(
					[ 'extraArgs' => [ $callbacks ], 'assertClass' => TypeDef::class ], $options
				);
				$ret = $this->getMockBuilder( TypeDef::class )
					->setConstructorArgs( [ $callbacks ] )
					->getMockForAbstractClass();
				return $ret;
			} );
		$validator = new ParamValidator( $callbacks, $factory );

		$def = $validator->getTypeDef( 'boolean' );
		$this->assertInstanceOf( TypeDef::class, $def );

		$def = $validator->getTypeDef( [] );
		$this->assertInstanceOf( TypeDef::class, $def );

		$def = $validator->getTypeDef( 'missing' );
		$this->assertNull( $def );
	}

	public function testGetTypeDef_caching() {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] );
		$def1 = $mb->getMockForAbstractClass();
		$def2 = $mb->getMockForAbstractClass();
		$this->assertNotSame( $def1, $def2, 'consistency check' );

		$factory = $this->getMockBuilder( ObjectFactory::class )
			->setConstructorArgs( [ $this->getMockForAbstractClass( ContainerInterface::class ) ] )
			->onlyMethods( [ 'createObject' ] )
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
	 */
	public function testGetTypeDef_error() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => [ 'class' => stdClass::class ] ] ]
		);
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage(
			"Expected instance of Wikimedia\ParamValidator\TypeDef, got stdClass" );
		$validator->getTypeDef( 'foo' );
	}

	/** @dataProvider provideNormalizeSettings */
	public function testNormalizeSettings( $input, $expect ) {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->onlyMethods( [ 'normalizeSettings' ] );
		$mock1 = $mb->getMockForAbstractClass();
		$mock1->method( 'normalizeSettings' )->willReturnCallback( static function ( $s ) {
			$s['foo'] = 'FooBar!';
			return $s;
		} );
		$mock2 = $mb->getMockForAbstractClass();
		$mock2->method( 'normalizeSettings' )->willReturnCallback( static function ( $s ) {
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

	/** @dataProvider provideCheckSettings */
	public function testCheckSettings( $settings, array $expect ): void {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->onlyMethods( [ 'checkSettings' ] );
		$mock1 = $mb->getMockForAbstractClass();
		$mock1->method( 'checkSettings' )->willReturnCallback(
			static function ( string $name, $settings, array $options, array $ret ) {
				$ret['allowedKeys'][] = 'XXX-test';
				if ( isset( $settings['XXX-test'] ) ) {
					$ret['issues']['XXX-test'] = 'XXX-test was ' . $settings['XXX-test'];
				}
				return $ret;
			}
		);
		$mock2 = $mb->getMockForAbstractClass();
		$mock2->method( 'checkSettings' )->willReturnCallback(
			static function ( string $name, $settings, array $options, array $ret ) {
				return $ret;
			}
		);

		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => $mock1, 'NULL' => $mock2 ] + ParamValidator::STANDARD_TYPES ]
		);

		$this->assertEquals( $expect, $validator->checkSettings( 'dummy', $settings, [] ) );
	}

	public static function provideCheckSettings(): array {
		$normalKeys = [
			ParamValidator::PARAM_TYPE, ParamValidator::PARAM_DEFAULT, ParamValidator::PARAM_REQUIRED,
			ParamValidator::PARAM_ISMULTI, ParamValidator::PARAM_SENSITIVE, ParamValidator::PARAM_DEPRECATED,
			ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES,
		];
		$multiKeys = [ ...$normalKeys,
			ParamValidator::PARAM_ISMULTI_LIMIT1, ParamValidator::PARAM_ISMULTI_LIMIT2,
			ParamValidator::PARAM_ALL, ParamValidator::PARAM_ALLOW_DUPLICATES
		];
		$multiEnumKeys = [ ...$multiKeys, TypeDef\EnumDef::PARAM_DEPRECATED_VALUES ];

		return [
			'Basic test' => [
				null,
				[
					'issues' => [],
					'allowedKeys' => $normalKeys,
					'messages' => [],
				],
			],
			'Basic multi-value' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				[
					'issues' => [],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'Test with everything' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					ParamValidator::PARAM_DEFAULT => 'a|b',
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_SENSITIVE => false,
					ParamValidator::PARAM_DEPRECATED => false,
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 20,
					ParamValidator::PARAM_ALL => 'all',
					ParamValidator::PARAM_ALLOW_DUPLICATES => true,
				],
				[
					'issues' => [],
					'allowedKeys' => $multiEnumKeys,
					'messages' => [],
				],
			],
			'Lots of bad types' => [
				[
					ParamValidator::PARAM_TYPE => false,
					ParamValidator::PARAM_REQUIRED => 1,
					ParamValidator::PARAM_ISMULTI => 1,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '10',
					ParamValidator::PARAM_ISMULTI_LIMIT2 => '100',
					ParamValidator::PARAM_ALL => [],
					ParamValidator::PARAM_ALLOW_DUPLICATES => 1,
					ParamValidator::PARAM_SENSITIVE => 1,
					ParamValidator::PARAM_DEPRECATED => 1,
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => 1,
				],
				[
					'issues' => [
						ParamValidator::PARAM_TYPE => 'PARAM_TYPE must be a string or array, got boolean',
						ParamValidator::PARAM_REQUIRED => 'PARAM_REQUIRED must be boolean, got integer',
						ParamValidator::PARAM_ISMULTI => 'PARAM_ISMULTI must be boolean, got integer',
						ParamValidator::PARAM_ISMULTI_LIMIT1 => 'PARAM_ISMULTI_LIMIT1 must be an integer, got string',
						ParamValidator::PARAM_ISMULTI_LIMIT2 => 'PARAM_ISMULTI_LIMIT2 must be an integer, got string',
						ParamValidator::PARAM_ALL => 'PARAM_ALL must be a string or boolean, got array',
						ParamValidator::PARAM_ALLOW_DUPLICATES
							=> 'PARAM_ALLOW_DUPLICATES must be boolean, got integer',
						ParamValidator::PARAM_SENSITIVE => 'PARAM_SENSITIVE must be boolean, got integer',
						ParamValidator::PARAM_DEPRECATED => 'PARAM_DEPRECATED must be boolean, got integer',
						ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES
							=> 'PARAM_IGNORE_UNRECOGNIZED_VALUES must be boolean, got integer',
					],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'Multi-value stuff is ignored without ISMULTI' => [
				[
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '10',
					ParamValidator::PARAM_ISMULTI_LIMIT2 => '100',
					ParamValidator::PARAM_ALL => [],
					ParamValidator::PARAM_ALLOW_DUPLICATES => 1,
				],
				[
					'issues' => [],
					'allowedKeys' => $normalKeys,
					'messages' => [],
				],
			],
			'PARAM_TYPE is not registered' => [
				[ ParamValidator::PARAM_TYPE => 'xyz' ],
				[
					'issues' => [
						ParamValidator::PARAM_TYPE => 'Unknown/unregistered PARAM_TYPE "xyz"',
					],
					'allowedKeys' => $normalKeys,
					'messages' => [],
				],
			],
			'PARAM_DEFAULT value doesn\'t validate' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					ParamValidator::PARAM_DEFAULT => 'a|b',
				],
				[
					'issues' => [
						ParamValidator::PARAM_DEFAULT => 'Value for PARAM_DEFAULT does not validate (code badvalue)',
					],
					'allowedKeys' => [ ...$normalKeys, TypeDef\EnumDef::PARAM_DEPRECATED_VALUES ],
					'messages' => [],
				],
			],
			'PARAM_ISMULTI_LIMIT1 out of range' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 0,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI_LIMIT1 => 'PARAM_ISMULTI_LIMIT1 must be greater than 0, got 0',
					],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ISMULTI_LIMIT2 out of range' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 100,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI_LIMIT2 => 'PARAM_ISMULTI_LIMIT2 must be greater than or equal to PARAM_ISMULTI_LIMIT1, but 10 < 100',
					],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ISMULTI_LIMIT1 = LIMIT2 is ok' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
				],
				[
					'issues' => [],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ALL false is ok with non-enumerated type' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => false,
				],
				[
					'issues' => [],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ALL true is not ok with non-enumerated type' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => true,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ALL => 'PARAM_ALL cannot be used with non-enumerated types',
					],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ALL string is not ok with non-enumerated type' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => 'all',
				],
				[
					'issues' => [
						ParamValidator::PARAM_ALL => 'PARAM_ALL cannot be used with non-enumerated types',
					],
					'allowedKeys' => $multiKeys,
					'messages' => [],
				],
			],
			'PARAM_ALL true value collision' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', '*' ],
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => true,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ALL => 'Value for PARAM_ALL conflicts with an enumerated value',
					],
					'allowedKeys' => $multiEnumKeys,
					'messages' => [],
				],
			],
			'PARAM_ALL string value collision' => [
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'all' ],
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => 'all',
				],
				[
					'issues' => [
						ParamValidator::PARAM_ALL => 'Value for PARAM_ALL conflicts with an enumerated value',
					],
					'allowedKeys' => $multiEnumKeys,
					'messages' => [],
				],
			],
			'TypeDef is called' => [
				[
					ParamValidator::PARAM_TYPE => 'foo',
					'XXX-test' => '!!!',
				],
				[
					'issues' => [
						'XXX-test' => 'XXX-test was !!!',
					],
					'allowedKeys' => array_merge( $normalKeys, [ 'XXX-test' ] ),
					'messages' => [],
				],
			],
		];
	}

	public function testCheckSettings_noEnum(): void {
		$callbacks = new SimpleCallbacks( [] );
		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [] ]
		);

		$this->assertEquals(
			[ ParamValidator::PARAM_TYPE => 'Unknown/unregistered PARAM_TYPE "enum"' ],
			$validator->checkSettings( 'dummy', [ ParamValidator::PARAM_TYPE => [ 'xyz' ] ], [] )['issues']
		);
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

	/** @dataProvider provideImplodeMultiValue */
	public function testImplodeMultiValue( $value, $expect ) {
		$this->assertSame( $expect, ParamValidator::implodeMultiValue( $value ) );
	}

	public static function provideImplodeMultiValue() {
		return [
			[ [], '' ],
			[ [ '' ], '|' ],
			[ [ '', '' ], '|' ],
			[ [ 'foo', '' ], 'foo|' ],
			[ [ '', 'foo' ], '|foo' ],
			[ [ '', 'foo', '' ], '|foo|' ],
			[ [ 'foobar' ], 'foobar' ],
			[ [ 'foo', 'bar' ], 'foo|bar' ],
			[ [ 'foo|bar' ], "\x1ffoo|bar" ],
			[ [ 'foo', '|', 'bar' ], "\x1ffoo\x1f|\x1fbar" ],
		];
	}

	public function testGetValue_badType() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [] ]
		);
		$this->expectException( DomainException::class );
		$this->expectExceptionMessage( "Param foo's type is unknown - string" );
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

		$expectOptions = $options;
		if ( $get === [] && isset( $settings[ParamValidator::PARAM_DEFAULT] ) ) {
			$expectOptions['is-default'] = true;
		}

		// Mock the validateValue method so we can test only getValue
		$validator = $this->getMockBuilder( ParamValidator::class )
			->setConstructorArgs( [
				$callbacks,
				new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
				[ 'typeDefs' => [ 'xyz' => $mockDef ] ]
			] )
			->onlyMethods( [ 'validateValue' ] )
			->getMock();
		$validator->expects( $this->once() )->method( 'validateValue' )
			->with(
				$this->identicalTo( 'foobar' ),
				$this->identicalTo( $value ),
				$this->identicalTo( $settings ),
				$this->identicalTo( $expectOptions )
			)
			->willReturn( $dummy );

		$this->assertSame( $dummy, $validator->getValue( 'foobar', $settings, $options ) );

		$expectConditions = [];
		if ( $isSensitive ) {
			$expectConditions[] = [
				'message' => DataMessageValue::new( 'paramvalidator-param-sensitive', [], 'param-sensitive' )
					->plaintextParams( 'foobar', $value ),
				'name' => 'foobar',
				'value' => $value,
				'settings' => $settings,
			];
		}
		if ( $isDeprecated ) {
			$expectConditions[] = [
				'message' => DataMessageValue::new( 'paramvalidator-param-deprecated', [], 'param-deprecated' )
					->plaintextParams( 'foobar', $value ),
				'name' => 'foobar',
				'value' => $value,
				'settings' => $settings,
			];
		}
		$this->assertEquals( $expectConditions, $callbacks->getRecordedConditions() );
	}

	public static function provideGetValue() {
		$sen = [ ParamValidator::PARAM_SENSITIVE => true ];
		$dep = [ ParamValidator::PARAM_DEPRECATED => true ];
		$dflt = [ ParamValidator::PARAM_DEFAULT => 'DeFaUlT' ];
		$arr = [ ParamValidator::PARAM_ISMULTI => true, ParamValidator::PARAM_TYPE => 'integer' ];

		// [ $settings, $parseLimit, $get, $value, $isSensitive, $isDeprecated ]
		return [
			'Simple case' => [ [], false, [ 'foobar' => '!!!' ], '!!!', false, false ],
			'Not provided' => [ $sen + $dep, false, [], null, false, false ],
			'Not provided, default' => [ $sen + $dep + $dflt, true, [], 'DeFaUlT', false, false ],
			'Provided' => [ $dflt, false, [ 'foobar' => 'XYZ' ], 'XYZ', false, false ],
			'Provided, sensitive' => [ $sen, false, [ 'foobar' => 'XYZ' ], 'XYZ', true, false ],
			'Provided, deprecated' => [ $dep, false, [ 'foobar' => 'XYZ' ], 'XYZ', false, true ],
			'Provided array' => [ $dflt, false, [ 'foobar' => [ 'XYZ' ] ], [ 'XYZ' ], false, false ],
			'Multivalue as array' => [ $dflt, false, [ 'foobar' => [ 1, 2, 3 ] ], [ 1, 2, 3 ], false, false ],
		];
	}

	public function testValidateValue_badType() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [] ]
		);
		$this->expectException( DomainException::class );
		$this->expectExceptionMessage( "Param foo's type is unknown - string" );
		$validator->validateValue( 'foo', null, 'default', [] );
	}

	public function testValidateValue_multiValueMustBeArray() {
		$validator = new ParamValidator(
			new SimpleCallbacks( [
				'foo' => 'x|y|z'
			] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => ParamValidator::STANDARD_TYPES ]
		);

		$options = [
			ParamValidator::OPT_ENFORCE_JSON_TYPES => true
		];

		$settings = [
			ParamValidator::PARAM_TYPE => 'integer',
			ParamValidator::PARAM_ISMULTI => true,
		];

		$this->expectException( ValidationException::class );
		$this->expectExceptionMessage( 'multivalue-must-be-array' );
		$validator->validateValue( 'foo', 'x|y|z', $settings, $options );
	}

	public static function provideMismatchValueToType() {
		yield 'array provided but not multi-value' => [
			'foo',
			[ 'foobar' ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			ValidationException::class,
			"Validation of `foo` failed: badvalue",
		];

		yield 'string provided but multi-value set' => [
			'foo',
			[ 'foobar' ],
			[ ParamValidator::PARAM_TYPE => 'integer' ],
			ValidationException::class,
			"Validation of `foo` failed: badvalue",
		];
	}

	/** @dataProvider provideMismatchValueToType */
	public function testValidateValue_valueToTypeMismatch(
		$name,
		$value,
		$settings,
		$expectedException,
		$expectedExceptionMsg
	) {
		$validator = new ParamValidator(
			new SimpleCallbacks( [] ),
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => ParamValidator::STANDARD_TYPES ]
		);

		$this->expectException( $expectedException );
		$this->expectExceptionMessage( $expectedExceptionMsg );
		$validator->validateValue( $name, $value, $settings, [] );
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
			->onlyMethods( [ 'validate', 'getEnumValues' ] )
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
					throw new ValidationException(
						DataMessageValue::new( 'XXX-from-test', [], 'badvalue' ),
						$n, $v, $s
					);
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
				$this->assertSame(
					$expect->getFailureMessage()->getCode(),
					$ex->getFailureMessage()->getCode()
				);
				$this->assertSame(
					$expect->getFailureMessage()->getData(),
					$ex->getFailureMessage()->getData()
				);
			}
		} else {
			$this->assertSame(
				$expect, $validator->validateValue( 'foobar', $value, $settings, $options )
			);
			foreach ( $expectConditions as $k => $c ) {
				$expectConditions[$k]['settings'] = $settings;
			}
			$this->assertEquals( $expectConditions, $callbacks->getRecordedConditions() );
		}
	}

	public static function provideValidateValue() {
		// [ $value, $settings, $highLimits, $valuesList, $calls, $expect,
		//   $expectConditions = [], $constructorOptions = [] ]
		return [
			'No value' => [ null, [], false, null, [], null ],
			'No value, required' => [
				null,
				[ ParamValidator::PARAM_REQUIRED => true ],
				false,
				null,
				[],
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-missingparam', [], 'missingparam' )
						->plaintextParams( 'foobar' ),
					'foobar', null, []
				),
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
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
						'parameter' => 'foobar',
						'limit' => 2,
						'lowlimit' => 2,
						'highlimit' => 4,
					] )->plaintextParams( 'foobar', 'a|b|c|d' )->numParams( 2 ),
					'foobar', 'a|b|c|d', []
				),
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
					DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
						'parameter' => 'foobar',
						'limit' => 2,
						'lowlimit' => 2,
						'highlimit' => 4,
					] )->plaintextParams( 'foobar', 'a|b|c|d' )->numParams( 2 ),
					'foobar', [ 'a', 'b', 'c', 'd' ], []
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
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
						'parameter' => 'foobar',
						'limit' => 4,
						'lowlimit' => 2,
						'highlimit' => 4,
					] )->plaintextParams( 'foobar', 'a|b|c|d|e' )->numParams( 4 ),
					'foobar', 'a|b|c|d|e', []
				),
			],

			'Too many values via default' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				false,
				null,
				[],
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
						'parameter' => 'foobar',
						'limit' => 2,
						'lowlimit' => 2,
						'highlimit' => 4,
					] )->plaintextParams( 'foobar', 'a|b|c|d' )->numParams( 2 ),
					'foobar', 'a|b|c|d|e', []
				),
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
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-toomanyvalues', [], 'toomanyvalues', [
						'parameter' => 'foobar',
						'limit' => 4,
						'lowlimit' => 2,
						'highlimit' => 4,
					] )->plaintextParams( 'foobar', 'a|b|c|d|e' )->numParams( 4 ),
					'foobar', 'a|b|c|d|e', []
				),
				[],
				[ 'ismultiLimits' => [ 2, 4 ] ],
			],
			'U+001F-style multi value when not multi' => [
				"\x1fa",
				[ ParamValidator::PARAM_ISMULTI => false ],
				false,
				null,
				[],
				new ValidationException(
					DataMessageValue::new( 'paramvalicator-notmulti', [], 'badvalue' )
						->plaintextParams( 'foobar', "\x1fa" ),
					'foobar', "\x1fa", []
				),
			],

			'Invalid values' => [
				'a|b|c|d',
				[ ParamValidator::PARAM_ISMULTI => true ],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => null, ],
				new ValidationException(
					DataMessageValue::new( 'XXX-from-test', [], 'badvalue' ),
					'foobar', 'b', []
				),
			],
			'Ignored unrecognized values' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
				],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => null, 'c' => null, 'd' => 'D' ],
				[ 'A', 'D' ],
				[
					[
						'message' => DataMessageValue::new(
							'paramvalidator-unrecognizedvalues', [], 'unrecognizedvalues', [ 'values' => [ 'b', 'c' ] ]
						)
							->plaintextParams( 'foobar', 'a|b|c|d' )
							->commaListParams( [
								new ScalarParam( ParamType::PLAINTEXT, 'b' ),
								new ScalarParam( ParamType::PLAINTEXT, 'c' ),
							] )
							->numParams( 2 ),
						'name' => 'foobar',
						'value' => 'a|b|c|d',
					],
				],
			],
			'Ignored unrecognized values, passed as array' => [
				[ 'a', 'b', 'c', 'd' ],
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
				],
				false,
				[ 'a', 'b', 'c', 'd' ],
				[ 'a' => 'A', 'b' => null, 'c' => null, 'd' => 'D' ],
				[ 'A', 'D' ],
				[
					[
						'message' => DataMessageValue::new(
							'paramvalidator-unrecognizedvalues', [], 'unrecognizedvalues', [ 'values' => [ 'b', 'c' ] ]
						)
							->plaintextParams( 'foobar', 'a|b|c|d' )
							->commaListParams( [
								new ScalarParam( ParamType::PLAINTEXT, 'b' ),
								new ScalarParam( ParamType::PLAINTEXT, 'c' ),
							] )
							->numParams( 2 ),
						'name' => 'foobar',
						'value' => 'a|b|c|d',
					],
				],
			],
		];
	}

	/** @dataProvider provideGetParamInfo */
	public function testGetParamInfo(
		$settings, array $expect, array $typeDefReturns = [], array $options = []
	) {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->onlyMethods( [ 'getParamInfo' ] );
		$mock = $mb->getMockForAbstractClass();
		$mock->method( 'getParamInfo' )->willReturn( $typeDefReturns );

		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => $mock ] ]
		);
		$settings += [ ParamValidator::PARAM_TYPE => 'foo' ];
		$this->assertSame( $expect, $validator->getParamInfo( 'test', $settings, $options ) );
	}

	public static function provideGetParamInfo() {
		return [
			'Basic test' => [
				[],
				[
					'type' => 'foo',
					'required' => false,
					'multi' => false,
				],
			],
			'Various settings' => [
				[
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_DEPRECATED => true,
					ParamValidator::PARAM_SENSITIVE => true,
					ParamValidator::PARAM_DEFAULT => 1234,
				],
				[
					'type' => 'foo',
					'required' => true,
					'deprecated' => true,
					'sensitive' => true,
					'default' => 1234,
					'multi' => false,
				],
			],
			'Multi-value' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALLOW_DUPLICATES => true,
					ParamValidator::PARAM_ALL => true,
				],
				[
					'type' => 'foo',
					'required' => false,
					'multi' => true,
					'lowlimit' => 50,
					'highlimit' => 500,
					'limit' => 50,
					'allowsduplicates' => true,
					'allspecifier' => ParamValidator::ALL_DEFAULT_STRING,
				],
			],
			'Multi-value, high limit' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				[
					'type' => 'foo',
					'required' => false,
					'multi' => true,
					'lowlimit' => 50,
					'highlimit' => 500,
					'limit' => 500,
				],
				[],
				[ 'useHighLimits' => true ],
			],
			'Multi-value, custom limits and all string' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 15,
					ParamValidator::PARAM_ALL => 'all',
				],
				[
					'type' => 'foo',
					'required' => false,
					'multi' => true,
					'lowlimit' => 10,
					'highlimit' => 15,
					'limit' => 10,
					'allspecifier' => 'all',
				],
			],
			'Multi-value, screwy custom limits' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
				],
				[
					'type' => 'foo',
					'required' => false,
					'multi' => true,
					'lowlimit' => 50,
					'highlimit' => 50,
					'limit' => 50,
				],
			],
			'Overrides from the TypeDef' => [
				[
					ParamValidator::PARAM_SENSITIVE => true,
					ParamValidator::PARAM_DEFAULT => 1234,
				],
				[
					'type' => 'foo',
					'required' => false,
					'sensitive' => true,
					'multi' => false,
					'foobar' => [ 'baz' ],
				],
				[ 'default' => null, 'foobar' => [ 'baz' ] ],
			]
		];
	}

	/** @dataProvider provideGetHelpInfo */
	public function testGetHelpInfo(
		$settings, array $expect, array $typeDefReturns = [], array $options = []
	) {
		$callbacks = new SimpleCallbacks( [] );

		$mb = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->onlyMethods( [ 'getHelpInfo', 'getEnumValues' ] );
		$mock = $mb->getMockForAbstractClass();
		$mock->method( 'getHelpInfo' )->willReturn( $typeDefReturns );
		$mock->method( 'getEnumValues' )->willReturn( $settings['values'] ?? null );

		$validator = new ParamValidator(
			$callbacks,
			new ObjectFactory( $this->getMockForAbstractClass( ContainerInterface::class ) ),
			[ 'typeDefs' => [ 'foo' => $mock ] ]
		);
		$settings += [ ParamValidator::PARAM_TYPE => 'foo' ];

		$info = [];
		foreach ( $validator->getHelpInfo( 'test', $settings, $options ) as $k => $m ) {
			$this->assertInstanceOf( MessageValue::class, $m );
			$info[$k] = $m->dump();
		}
		$this->assertSame( $expect, $info );
	}

	public static function provideGetHelpInfo() {
		return [
			'Basic test' => [
				[],
				[],
			],
			'Various settings' => [
				[
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_DEPRECATED => true,
					ParamValidator::PARAM_SENSITIVE => true,
					ParamValidator::PARAM_DEFAULT => 1234,
				],
				[
					ParamValidator::PARAM_DEPRECATED => '<message key="paramvalidator-help-deprecated"></message>',
					ParamValidator::PARAM_REQUIRED => '<message key="paramvalidator-help-required"></message>',
					ParamValidator::PARAM_DEFAULT => '<message key="paramvalidator-help-default"><plaintext>1234</plaintext></message>',
				],
			],
			'Multi-value' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALLOW_DUPLICATES => true,
					ParamValidator::PARAM_ALL => true,
					ParamValidator::PARAM_DEFAULT => '',
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max"><num>50</num><num>500</num></message>',
					ParamValidator::PARAM_ALL => '<message key="paramvalidator-help-multi-all"><plaintext>*</plaintext></message>',
					ParamValidator::PARAM_DEFAULT => '<message key="paramvalidator-help-default-empty"></message>',
				],
			],
			'Multi-value, high limit' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max"><num>50</num><num>500</num></message>',
				],
				[],
				[ 'useHighLimits' => true ],
			],
			'Multi-value, custom limits and all string' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 15,
					ParamValidator::PARAM_ALL => 'all',
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max"><num>10</num><num>15</num></message>',
					ParamValidator::PARAM_ALL => '<message key="paramvalidator-help-multi-all"><plaintext>all</plaintext></message>',
				],
			],
			'Multi-value, screwy custom limits' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max-simple"><num>50</num></message>',
				],
			],
			'Multi-value, not enough values to meet the limit' => [
				[
					'values' => [ 'a', 'b', 'c', 'd' ],
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 4,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 2,
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
				],
			],
			'Multi-value, enough values to meet the limit' => [
				[
					'values' => [ 'a', 'b', 'c', 'd', 'e' ],
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 4,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max"><num>4</num><num>10</num></message>',
				],
			],
			'Multi-value, not enough values to meet the limit but dups allowed' => [
				[
					'values' => [ 'a', 'b', 'c', 'd' ],
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 4,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 2,
					ParamValidator::PARAM_ALLOW_DUPLICATES => true,
				],
				[
					ParamValidator::PARAM_ISMULTI => '<message key="paramvalidator-help-multi-separate"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max-simple"><num>4</num></message>',
				],
			],
			'Overrides from the TypeDef' => [
				[
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_DEFAULT => 1234,
				],
				[
					ParamValidator::PARAM_REQUIRED => '<message key="paramvalidator-help-required"></message>',
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-help"></message>',
					ParamValidator::PARAM_ISMULTI_LIMIT1 => '<message key="paramvalidator-help-multi-max"><num>50</num><num>500</num></message>',
					'foobar' => '<message key="foobaz"></message>',
					ParamValidator::PARAM_DEFAULT => '<message key="paramvalidator-help-default"><text>XX</text></message>',
				],
				[
					ParamValidator::PARAM_DEFAULT => MessageValue::new( 'paramvalidator-help-default', [ 'XX' ] ),
					'foobar' => MessageValue::new( 'foobaz' ),
					ParamValidator::PARAM_ISMULTI => null,
					ParamValidator::PARAM_TYPE => MessageValue::new( 'paramvalidator-help-type-help' ),
				],
			]
		];
	}

}
