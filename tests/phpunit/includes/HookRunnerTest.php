<?php
namespace MediaWiki\Tests\Hooks\Runner;

use InvalidArgumentException;
use MediaWiki\Hooks\HookRunner;

/**
 * HookRunnerTest
 *
 * @covers MediaWiki\Hooks\HookRunner
 *
 * @license GPL 2+
 */
class HookRunnerTest extends \Phpunit_Framework_TestCase {

	public function testConstruct_badParams() {
		$this->setExpectedException( InvalidArgumentException::class );

		new HookRunner( 'Test', [ 'Xyzzy' ] );
	}

	public function testRun_empty() {
		$runner = new HookRunner( 'Test', [] );
		$retval = $runner->run( [ 'one' ] );

		$this->assertTrue( $retval );
	}

	public function testRun_closure() {
		$result1 = null;
		$result2 = null;

		$func1 = function( $value, &$count ) use ( &$result1 ) {
			$count += 1;
			$result1 = $value;
			return true;
		};

		$func2 = function( $value, &$count ) use ( &$result2 ) {
			$count += 1;
			$result2 = $value;
		};

		$count = 0;

		$runner = new HookRunner( 'Test', [ $func1, $func2 ] );
		$retval = $runner->run( [ 'one', &$count ] );

		$this->assertTrue( $retval );
		$this->assertSame( 2, $count );
		$this->assertSame( 'one', $result2 );
		$this->assertSame( 'one', $result1 );

		$retval = $runner->run( [ 'two', &$count ] );

		$this->assertTrue( $retval );
		$this->assertSame( 4, $count );
		$this->assertSame( 'two', $result1 );
		$this->assertSame( 'two', $result2 );
	}

	public function testRun_abort() {
		$result1 = null;
		$result2 = null;

		$func1 = function( $value ) use ( &$result1 ) {
			$result1 = $value;
			return false;
		};

		$func2 = function( $value ) use ( &$result2 ) {
			$result2 = $value;
		};

		$runner = new HookRunner( 'Test', [ $func1, $func2 ] );
		$retval = $runner->run( [ 'one' ] );

		$this->assertFalse( $retval );
		$this->assertSame( 'one', $result1 );
		$this->assertNull( $result2 );
	}

	public function testRun_badReturnValue() {
		$this->setExpectedException( \Fatalerror::class );

		$func1 = function( $value ) use ( &$result1 ) {
			return "boohoo!";
		};

		$runner = new HookRunner( 'Test', [ $func1 ] );
		$runner->run( [ 'one' ] );
	}

	public function testRun_parameters() {
		$result1 = null;

		$func1 = function( $offset, $value, &$count ) use ( &$result1 ) {
			$count += $offset;
			$result1 = $value;
			return true;
		};

		$count = 0;

		$runner = new HookRunner(
			'Test',
			[
				[ $func1, 5 ],
			]
		);
		$retval = $runner->run( [ 'one', &$count ] );

		$this->assertTrue( $retval );
		$this->assertSame( 'one', $result1 );
		$this->assertSame( 5, $count );
	}

	public static function dummyHandler( &$value ) {
		$value = $value * 2;
	}

	public function testRun_string() {
		$value = 3;

		$runner = new HookRunner( 'Test', [ HookRunnerTest::class . '::dummyHandler' ] );
		$retval = $runner->run( [ &$value ] );

		$this->assertTrue( $retval );
		$this->assertSame( 6, $value );
	}

	public function testRun_object() {
		$value = 3;

		$runner = new HookRunner(
			'Test',
			[
				new HookRunnerTestHandlerClass()
			]
		);

		$retval = $runner->run( [ &$value ] );

		$this->assertTrue( $retval );
		$this->assertSame( 9, $value );
	}

	public function testRun_method() {
		$value = 3;

		$runner = new HookRunner(
			'Test',
			[
				[ new HookRunnerTestHandlerClass(), 'handleTest' ]
			]
		);

		$retval = $runner->run( [ &$value ] );

		$this->assertTrue( $retval );
		$this->assertSame( 15, $value );
	}

}

class HookRunnerTestHandlerClass {
	public function onTest( &$value ) {
		$value = 3 * $value;
	}

	public function handleTest( &$value ) {
		$value = 5 * $value;
	}
}
