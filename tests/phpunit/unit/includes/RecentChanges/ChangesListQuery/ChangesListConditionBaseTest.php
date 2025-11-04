<?php

namespace MediaWiki\Tests\Unit\RecentChanges\ChangesListQuery;

use MediaWiki\RecentChanges\ChangesListQuery\ChangesListConditionBase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListConditionBase
 */
class ChangesListConditionBaseTest extends \MediaWikiUnitTestCase {
	/**
	 * @return TestingAccessWrapper|ChangesListConditionBase
	 */
	private function getModuleWrapper() {
		$mock = $this->getMockForAbstractClass( ChangesListConditionBase::class );
		$mock->method( 'validateValue' )
			->willReturnArgument( 0 );
		return TestingAccessWrapper::newFromObject( $mock );
	}

	public function testCapture() {
		$module = $this->getModuleWrapper();
		$this->assertFalse( $module->isCaptured() );
		$module->capture();
		$this->assertTrue( $module->isCaptured() );
	}

	public static function provideGetUniqueValues() {
		$stringableA = self::stringable( 'a' );
		return [
			'simply unconstrained' => [
				'input' => [ [], [] ],
				'expect' => [ null, [] ],
			],
			'require a' => [
				'input' => [ [ 'a' ], [] ],
				'expect' => [ [ 'a' ], [] ],
			],
			'require a or b' => [
				'input' => [ [ 'a', 'b' ], [] ],
				'expect' => [ [ 'a', 'b' ], [] ],
			],
			'require duplicate' => [
				'input' => [ [ 'a', 'a' ], [] ],
				'expect' => [ [ 'a' ], [] ],
			],
			'require stringable' => [
				'input' => [ [ $stringableA ], [] ],
				'expect' => [ [ $stringableA ], [] ],
			],
			'require stringable duplicate' => [
				'input' => [ [ $stringableA, $stringableA ], [] ],
				'expect' => [ [ $stringableA ], [] ],
			],
			'require null' => [
				'input' => [ [ null ], [] ],
				'expect' => [ [ null ], [] ],
			],
			'require null duplicate' => [
				'input' => [ [ null, null ], [] ],
				'expect' => [ [ null ], [] ],
			],
			'exclude a' => [
				'input' => [ [], [ 'a' ] ],
				'expect' => [ null, [ 'a' ] ],
			],
			'exclude a or b' => [
				'input' => [ [], [ 'a', 'b' ] ],
				'expect' => [ null, [ 'a', 'b' ] ],
			],
			'exclude stringable duplicate' => [
				'input' => [ [], [ $stringableA, $stringableA ] ],
				'expect' => [ null, [ $stringableA ] ],
			],
			'require/exclude non-conflict' => [
				'input' => [ [ 'a' ], [ 'b' ] ],
				'expect' => [ [ 'a' ], [] ],
			],
			'require/exclude conflict' => [
				'input' => [ [ 'a' ], [ 'a' ] ],
				'expect' => [ [], [] ],
			],
			'require/exclude with residual requirement' => [
				'input' => [ [ 'a', 'b' ], [ 'a' ] ],
				'expect' => [ [ 'b' ], [] ],
			],
			'require/exclude with unused exclusion' => [
				'input' => [ [ 'a' ], [ 'a', 'b' ] ],
				'expect' => [ [], [] ],
			],
		];
	}

	private static function stringable( $str ) {
		return new class( $str ) implements \Stringable {
			public function __construct( private $str ) {
			}

			public function __toString(): string {
				return (string)$this->str;
			}
		};
	}

	/**
	 * @dataProvider provideGetUniqueValues
	 * @param array $input
	 * @param array $expect
	 */
	public function testGetUniqueValues( $input, $expect ) {
		$module = $this->getModuleWrapper();
		[ $inputRequire, $inputExclude ] = $input;
		[ $expectedRequire, $expectedExclude ] = $expect;
		foreach ( $inputRequire as $value ) {
			$module->require( $value );
		}
		foreach ( $inputExclude as $value ) {
			$module->exclude( $value );
		}
		[ $resultRequire, $resultExclude ] = $module->getUniqueValues();
		$this->assertSame( $expectedRequire, $resultRequire, 'required' );
		$this->assertSame( $expectedExclude, $resultExclude, 'excluded' );
	}

	public static function provideGetEnumValues() {
		return [
			'simply unconstrained' => [
				'input' => [ [], [] ],
				'expect' => null,
			],
			'require a' => [
				'input' => [ [ 'a' ], [] ],
				'expect' => [ 'a' ],
			],
			'require duplicate' => [
				'input' => [ [ 'a', 'a' ], [] ],
				'expect' => [ 'a' ],
			],
			'exclude a' => [
				'input' => [ [], [ 'a' ] ],
				'expect' => [ 'b', 'c' ],
			],
			'exclude duplicate' => [
				'input' => [ [], [ 'a', 'a' ] ],
				'expect' => [ 'b', 'c' ],
			],
			'exclude a and b' => [
				'input' => [ [], [ 'a', 'b' ] ],
				'expect' => [ 'c' ],
			],
			'exclude all' => [
				'input' => [ [], [ 'a', 'b', 'c' ] ],
				'expect' => [],
			],
			'require/exclude conflict' => [
				'input' => [ [ 'a' ], [ 'a' ] ],
				'expect' => [],
			],
			'require/exclude with residual requirement' => [
				'input' => [ [ 'a', 'b' ], [ 'a' ] ],
				'expect' => [ 'b' ],
			],
			'require/exclude with unused exclusion' => [
				'input' => [ [ 'a' ], [ 'a', 'b' ] ],
				'expect' => [],
			],
		];
	}

	/**
	 * @dataProvider provideGetEnumValues
	 * @param array $input
	 * @param array $expect
	 */
	public function testGetEnumValues( $input, $expect ) {
		$module = $this->getModuleWrapper();
		[ $inputRequire, $inputExclude ] = $input;
		foreach ( $inputRequire as $value ) {
			$module->require( $value );
		}
		foreach ( $inputExclude as $value ) {
			$module->exclude( $value );
		}
		$result = $module->getEnumValues( [ 'a', 'b', 'c' ] );
		$this->assertSame( $expect, $result );
	}
}
