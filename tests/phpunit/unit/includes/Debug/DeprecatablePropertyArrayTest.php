<?php

use MediaWiki\Debug\DeprecatablePropertyArray;
use PHPUnit\Framework\Assert;

/**
 * @covers \MediaWiki\Debug\DeprecatablePropertyArray
 */
class DeprecatablePropertyArrayTest extends MediaWikiUnitTestCase {

	private const PROP_NAME = 'test_property';

	/**
	 * @dataProvider provideDeprecationWarning
	 */
	public function testDeprecationWarning( callable $callback, string $message ) {
		$this->expectDeprecationAndContinue( '/' . preg_quote( $message, '/' ) . '/' );
		$callback();
	}

	public static function provideDeprecationWarning() {
		$propName = self::PROP_NAME;
		$array = new DeprecatablePropertyArray(
			[
				self::PROP_NAME => 'test_value',
				'callback' => static function () {
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
			static function () use ( $array ) {
				Assert::assertSame( 'test_value', $array[ self::PROP_NAME ] );
			},
			"TEST get '{$propName}'"
		];
		yield 'get, callback' => [
			static function () use ( $array ) {
				Assert::assertSame( 'callback_test_value', $array[ 'callback' ] );
			},
			"TEST get 'callback'"
		];
		yield 'exists' => [
			static function () use ( $array ) {
				Assert::assertTrue( isset( $array[ self::PROP_NAME ] ) );
			},
			"TEST exists '{$propName}'"
		];
		yield 'unset' => [
			static function () use ( $array ) {
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
}
