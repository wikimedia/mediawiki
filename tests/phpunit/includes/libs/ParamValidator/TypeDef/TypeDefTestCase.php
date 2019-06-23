<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Test case infrastructure for TypeDef subclasses
 *
 * Generally you'll only need to override static::$testClass and data providers
 * for methods the TypeDef actually implements.
 */
abstract class TypeDefTestCase extends \PHPUnit\Framework\TestCase {

	/** @var string|null TypeDef class name being tested */
	protected static $testClass = null;

	/**
	 * Create a SimpleCallbacks for testing
	 *
	 * The object created here should result in a call to the TypeDef's
	 * `getValue( 'test' )` returning an appropriate result for testing.
	 *
	 * @param mixed $value Value to return for 'test'
	 * @param array $options Options array.
	 * @return SimpleCallbacks
	 */
	protected function getCallbacks( $value, array $options ) {
		return new SimpleCallbacks( [ 'test' => $value ] );
	}

	/**
	 * Create an instance of the TypeDef subclass being tested
	 *
	 * @param SimpleCallbacks $callbacks From $this->getCallbacks()
	 * @param array $options Options array.
	 * @return TypeDef
	 */
	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		if ( static::$testClass === null ) {
			throw new \LogicException( 'Either assign static::$testClass or override ' . __METHOD__ );
		}

		return new static::$testClass( $callbacks );
	}

	/**
	 * @dataProvider provideValidate
	 * @param mixed $value Value for getCallbacks()
	 * @param mixed|ValidationException $expect Expected result from TypeDef::validate().
	 *  If a ValidationException, it is expected that a ValidationException
	 *  with matching failure code and data will be thrown. Otherwise, the return value must be equal.
	 * @param array $settings Settings array.
	 * @param array $options Options array
	 * @param array[] $expectConds Expected conditions reported. Each array is
	 *  `[ $ex->getFailureCode() ] + $ex->getFailureData()`.
	 */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		$callbacks = $this->getCallbacks( $value, $options );
		$typeDef = $this->getInstance( $callbacks, $options );

		if ( $expect instanceof ValidationException ) {
			try {
				$v = $typeDef->getValue( 'test', $settings, $options );
				$typeDef->validate( 'test', $v, $settings, $options );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ValidationException $ex ) {
				$this->assertEquals( $expect->getFailureCode(), $ex->getFailureCode() );
				$this->assertEquals( $expect->getFailureData(), $ex->getFailureData() );
			}
		} else {
			$v = $typeDef->getValue( 'test', $settings, $options );
			$this->assertEquals( $expect, $typeDef->validate( 'test', $v, $settings, $options ) );
		}

		$conditions = [];
		foreach ( $callbacks->getRecordedConditions() as $ex ) {
			$conditions[] = array_merge( [ $ex->getFailureCode() ], $ex->getFailureData() );
		}
		$this->assertSame( $expectConds, $conditions );
	}

	/**
	 * @return array|Iterable
	 */
	abstract public function provideValidate();

	/**
	 * @dataProvider provideNormalizeSettings
	 * @param array $settings
	 * @param array $expect
	 * @param array $options Options array
	 */
	public function testNormalizeSettings( array $settings, array $expect, array $options = [] ) {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$this->assertSame( $expect, $typeDef->normalizeSettings( $settings ) );
	}

	/**
	 * @return array|Iterable
	 */
	public function provideNormalizeSettings() {
		return [
			'Basic test' => [ [ 'param-foo' => 'bar' ], [ 'param-foo' => 'bar' ] ],
		];
	}

	/**
	 * @dataProvider provideGetEnumValues
	 * @param array $settings
	 * @param array|null $expect
	 * @param array $options Options array
	 */
	public function testGetEnumValues( array $settings, $expect, array $options = [] ) {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$this->assertSame( $expect, $typeDef->getEnumValues( 'test', $settings, $options ) );
	}

	/**
	 * @return array|Iterable
	 */
	public function provideGetEnumValues() {
		return [
			'Basic test' => [ [], null ],
		];
	}

	/**
	 * @dataProvider provideStringifyValue
	 * @param mixed $value
	 * @param string|null $expect
	 * @param array $settings
	 * @param array $options Options array
	 */
	public function testStringifyValue( $value, $expect, array $settings = [], array $options = [] ) {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$this->assertSame( $expect, $typeDef->stringifyValue( 'test', $value, $settings, $options ) );
	}

	/**
	 * @return array|Iterable
	 */
	public function provideStringifyValue() {
		return [
			'Basic test' => [ 123, '123' ],
		];
	}

	/**
	 * @dataProvider provideDescribeSettings
	 * @param array $settings
	 * @param array $expectNormal
	 * @param array $expectCompact
	 * @param array $options Options array
	 */
	public function testDescribeSettings(
		array $settings, array $expectNormal, array $expectCompact, array $options = []
	) {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$this->assertSame(
			$expectNormal,
			$typeDef->describeSettings( 'test', $settings, $options ),
			'Normal mode'
		);
		$this->assertSame(
			$expectCompact,
			$typeDef->describeSettings( 'test', $settings, [ 'compact' => true ] + $options ),
			'Compact mode'
		);
	}

	/**
	 * @return array|Iterable
	 */
	public function provideDescribeSettings() {
		yield 'Basic test' => [ [], [], [] ];

		foreach ( $this->provideStringifyValue() as $i => $v ) {
			yield "Default value (from provideStringifyValue data set \"$i\")" => [
				[ ParamValidator::PARAM_DEFAULT => $v[0] ] + ( $v[2] ?? [] ),
				[ 'default' => $v[1] ],
				[ 'default' => [ 'value' => $v[1] ] ],
				$v[3] ?? [],
			];
		}
	}

}
