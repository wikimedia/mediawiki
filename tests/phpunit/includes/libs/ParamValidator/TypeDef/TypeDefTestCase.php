<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
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

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET = [ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];

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
		return new SimpleCallbacks( $value === null ? [] : [ 'test' => $value ] );
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
	 * @param array[] $expectConds Expected condition codes and data reported.
	 */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		$callbacks = $this->getCallbacks( $value, $options );
		$typeDef = $this->getInstance( $callbacks, $options );
		$settings = $typeDef->normalizeSettings( $settings );

		if ( $expect instanceof ValidationException ) {
			try {
				$v = $typeDef->getValue( 'test', $settings, $options );
				$typeDef->validate( 'test', $v, $settings, $options );
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
			$v = $typeDef->getValue( 'test', $settings, $options );
			$this->assertEquals( $expect, $typeDef->validate( 'test', $v, $settings, $options ) );
		}

		$conditions = [];
		foreach ( $callbacks->getRecordedConditions() as $c ) {
			$conditions[] = [ 'code' => $c['message']->getCode(), 'data' => $c['message']->getData() ];
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
	 * @dataProvider provideCheckSettings
	 * @param array $settings
	 * @param array $ret Input $ret array
	 * @param array $expect
	 * @param array $options Options array
	 */
	public function testCheckSettings(
		array $settings,
		array $ret,
		array $expect,
		array $options = []
	) : void {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$this->assertEquals( $expect, $typeDef->checkSettings( 'test', $settings, $options, $ret ) );
	}

	/**
	 * @return array|Iterable
	 */
	public function provideCheckSettings() {
		return [
			'Basic test' => [ [], self::STDRET, self::STDRET ],
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
		$settings = $typeDef->normalizeSettings( $settings );

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
		$settings = $typeDef->normalizeSettings( $settings );

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
	 * @dataProvider provideGetInfo
	 * @param array $settings
	 * @param array $expectParamInfo
	 * @param array $expectHelpInfo
	 * @param array $options Options array
	 */
	public function testGetInfo(
		array $settings, array $expectParamInfo, array $expectHelpInfo, array $options = []
	) {
		$typeDef = $this->getInstance( new SimpleCallbacks( [] ), $options );
		$settings = $typeDef->normalizeSettings( $settings );

		$this->assertSame(
			$expectParamInfo,
			$typeDef->getParamInfo( 'test', $settings, $options )
		);

		$actual = [];
		$constraint = \PHPUnit\Framework\Assert::logicalOr(
			$this->isNull(),
			$this->isInstanceOf( MessageValue::class )
		);
		foreach ( $typeDef->getHelpInfo( 'test', $settings, $options ) as $k => $v ) {
			$this->assertThat( $v, $constraint );
			$actual[$k] = $v ? $v->dump() : null;
		}
		$this->assertSame( $expectHelpInfo, $actual );
	}

	/**
	 * @return array|Iterable
	 */
	public function provideGetInfo() {
		return [
			'Basic test' => [ [], [], [] ],
		];
	}

}
