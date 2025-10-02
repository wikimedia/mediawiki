<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph;

use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Handler\Bidi;
use Wikimedia\Leximorph\Handler\Formal;
use Wikimedia\Leximorph\Handler\Gender;
use Wikimedia\Leximorph\Handler\Grammar;
use Wikimedia\Leximorph\Handler\Plural;
use Wikimedia\Leximorph\Manager;

/**
 * This test class verifies the behavior of the {@see Manager} factory.
 * It ensures that handler getters return instances of the expected classes
 * and that repeated calls return memoized instances where applicable.
 *
 * Covered tests include:
 *   - Instantiation of Bidi, Plural, Gender, Formal, and Grammar handlers.
 *   - Memoization behavior for handler getters.
 *
 * @covers \Wikimedia\Leximorph\Manager
 * @author Doğu Abaris (abaris@null.net)
 */
class ManagerTest extends TestCase {

	/**
	 * Single parametrized test for all handlers.
	 *
	 * Each case provides:
	 *  - The getter method name on {@see Manager}.
	 *  - The fully qualified expected handler class name.
	 *
	 * Using get_class() avoids static analysis warnings that can occur when
	 * the getter has a concrete return type, which would make
	 * `assertInstanceOf` trivially true at analysis time.
	 *
	 * @dataProvider provideHandlers
	 *
	 * @param string $method Getter method name, such as "getPlural".
	 * @param class-string $expectedClass Expected concrete handler class.
	 */
	public function testInstantiatesHandlers( string $method, string $expectedClass ): void {
		$manager = new Manager( 'en' );
		$handler = $manager->{$method}();

		$this->assertSame( $expectedClass, get_class( $handler ) );
	}

	/**
	 * Data provider for handler instantiation tests.
	 *
	 * @return array<int, array{0:string,1:class-string}>
	 */
	public static function provideHandlers(): array {
		return [
			[
				'getBidi',
				Bidi::class,
			],
			[
				'getPlural',
				Plural::class,
			],
			[
				'getGender',
				Gender::class,
			],
			[
				'getFormal',
				Formal::class,
			],
			[
				'getGrammar',
				Grammar::class,
			],
		];
	}

	/**
	 * Verifies that handler getters return memoized instances.
	 *
	 * If memoization is part of the contract for a specific getter,
	 * the two calls below must return the same object instance.
	 *
	 * @dataProvider provideMemoizedHandlers
	 *
	 * @param string $method Getter method name expected to be memoized.
	 */
	public function testHandlersAreMemoized( string $method ): void {
		$manager = new Manager( 'en' );
		$this->assertSame( $manager->{$method}(), $manager->{$method}() );
	}

	/**
	 * Data provider for memoization checks.
	 *
	 * List all getters that are expected to return a cached instance.
	 *
	 * @return array<int, array{0:string}>
	 */
	public static function provideMemoizedHandlers(): array {
		return [
			[ 'getBidi' ],
			[ 'getPlural' ],
			[ 'getGender' ],
			[ 'getFormal' ],
			[ 'getGrammar' ],
		];
	}
}
