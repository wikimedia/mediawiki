<?php

use MediaWiki\Debug\DeprecatablePropertyArray;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Debug\DeprecatablePropertyArray
 */
class DeprecatablePropertyArrayTest extends MediaWikiUnitTestCase {

	private const PROP_NAME = 'test_property';

	/**
	 * @dataProvider provideDeprecationWarning
	 * @param callable $callback
	 */
	public function testDeprecationWarning( callable $callback, string $message ) {
		$GLOBALS['wgDevelopmentWarnings'] = false;
		$this->assertDeprecationWarningIssued( $callback, $message );
	}

	public function provideDeprecationWarning() {
		$propName = self::PROP_NAME;
		$array = new DeprecatablePropertyArray(
			[
				self::PROP_NAME => 'test_value',
				'callback' => function () {
					return 'callback_test_value';
				},
			],
			[
				self::PROP_NAME => 'DEPRECATED_VERSION',
				'callback' => 'DEPRECATED_VERSION',
			],
			'TEST'
		);

		yield 'get' => [
			function () use ( $array ) {
				$this->assertSame( 'test_value', $array[ self::PROP_NAME ] );
			},
			"TEST get '{$propName}'"
		];
		yield 'get, callback' => [
			function () use ( $array ) {
				$this->assertSame( 'callback_test_value', $array[ 'callback' ] );
			},
			"TEST get 'callback'"
		];
		yield 'exists' => [
			function () use ( $array ) {
				$this->assertTrue( isset( $array[ self::PROP_NAME ] ) );
			},
			"TEST exists '{$propName}'"
		];
		yield 'unset' => [
			function () use ( $array ) {
				unset( $array[ self::PROP_NAME ] );
			},
			"TEST unset '{$propName}'"
		];
	}

	public function testNonDeprecated() {
		$array = new DeprecatablePropertyArray( [], [], __METHOD__ );
		$this->assertFalse( isset( $array[self::PROP_NAME] ) );
		$array[self::PROP_NAME] = 'test_value';
		$this->assertTrue( isset( $array[self::PROP_NAME] ) );
		$this->assertSame( 'test_value', $array[self::PROP_NAME] );
		unset( $array[self::PROP_NAME] );
		$this->assertFalse( isset( $array[self::PROP_NAME] ) );
	}

	public function testNonDeprecatedNumerical() {
		$array = new DeprecatablePropertyArray( [], [], __METHOD__ );
		$this->assertFalse( isset( $array[0] ) );
		$array[] = 'test_value';
		$this->assertTrue( isset( $array[0] ) );
		$this->assertSame( 'test_value', $array[0] );
		unset( $array[0] );
		$this->assertFalse( isset( $array[0] ) );
	}

	/**
	 * Assert that $expectedMessage deprecation warning was emitted while
	 * executing the $callback.
	 * @param callable $callback
	 * @param string $expectedMessage
	 */
	protected function assertDeprecationWarningIssued(
		callable $callback,
		string $expectedMessage
	) {
		MWDebug::clearLog();
		$callback();
		$wrapper = TestingAccessWrapper::newFromClass( MWDebug::class );
		$this->assertCount( 1, $wrapper->deprecationWarnings );
		$this->assertArrayHasKey( $expectedMessage, $wrapper->deprecationWarnings );
	}
}
