<?php

class HooksTest extends MediaWikiTestCase {

	function setUp() {
		global $wgHooks;
		parent::setUp();
		Hooks::clear( 'MediaWikiHooksTest001' );
		unset( $wgHooks['MediaWikiHooksTest001'] );
	}

	public static function provideHooks() {
		$i = new NothingClass();

		return [
			[
				'Object and method',
				[ $i, 'someNonStatic' ],
				'changed-nonstatic',
				'changed-nonstatic'
			],
			[ 'Object and no method', [ $i ], 'changed-onevent', 'original' ],
			[
				'Object and method with data',
				[ $i, 'someNonStaticWithData', 'data' ],
				'data',
				'original'
			],
			[ 'Object and static method', [ $i, 'someStatic' ], 'changed-static', 'original' ],
			[
				'Class::method static call',
				[ 'NothingClass::someStatic' ],
				'changed-static',
				'original'
			],
			[ 'Global function', [ 'NothingFunction' ], 'changed-func', 'original' ],
			[ 'Global function with data', [ 'NothingFunctionData', 'data' ], 'data', 'original' ],
			[ 'Closure', [ function ( &$foo, $bar ) {
				$foo = 'changed-closure';

				return true;
			} ], 'changed-closure', 'original' ],
			[ 'Closure with data', [ function ( $data, &$foo, $bar ) {
				$foo = $data;

				return true;
			}, 'data' ], 'data', 'original' ]
		];
	}

	/**
	 * @dataProvider provideHooks
	 * @covers ::wfRunHooks
	 */
	public function testOldStyleHooks( $msg, array $hook, $expectedFoo, $expectedBar ) {
		global $wgHooks;
		$foo = $bar = 'original';

		$wgHooks['MediaWikiHooksTest001'][] = $hook;
		wfRunHooks( 'MediaWikiHooksTest001', [ &$foo, &$bar ] );

		$this->assertSame( $expectedFoo, $foo, $msg );
		$this->assertSame( $expectedBar, $bar, $msg );
	}

	/**
	 * @dataProvider provideHooks
	 * @covers Hooks::register
	 * @covers Hooks::run
	 */
	public function testNewStyleHooks( $msg, $hook, $expectedFoo, $expectedBar ) {
		$foo = $bar = 'original';

		Hooks::register( 'MediaWikiHooksTest001', $hook );
		Hooks::run( 'MediaWikiHooksTest001', [ &$foo, &$bar ] );

		$this->assertSame( $expectedFoo, $foo, $msg );
		$this->assertSame( $expectedBar, $bar, $msg );
	}

	/**
	 * @covers Hooks::isRegistered
	 * @covers Hooks::register
	 * @covers Hooks::getHandlers
	 * @covers Hooks::run
	 */
	public function testNewStyleHookInteraction() {
		global $wgHooks;

		$a = new NothingClass();
		$b = new NothingClass();

		$wgHooks['MediaWikiHooksTest001'][] = $a;
		$this->assertTrue(
			Hooks::isRegistered( 'MediaWikiHooksTest001' ),
			'Hook registered via $wgHooks should be noticed by Hooks::isRegistered'
		);

		Hooks::register( 'MediaWikiHooksTest001', $b );
		$this->assertEquals(
			2,
			count( Hooks::getHandlers( 'MediaWikiHooksTest001' ) ),
			'Hooks::getHandlers() should return hooks registered via wgHooks as well as Hooks::register'
		);

		$foo = 'quux';
		$bar = 'qaax';

		Hooks::run( 'MediaWikiHooksTest001', [ &$foo, &$bar ] );
		$this->assertEquals(
			1,
			$a->calls,
			'Hooks::run() should run hooks registered via wgHooks as well as Hooks::register'
		);
		$this->assertEquals(
			1,
			$b->calls,
			'Hooks::run() should run hooks registered via wgHooks as well as Hooks::register'
		);
	}

	/**
	 * @expectedException MWException
	 * @covers Hooks::run
	 */
	public function testUncallableFunction() {
		Hooks::register( 'MediaWikiHooksTest001', 'ThisFunctionDoesntExist' );
		Hooks::run( 'MediaWikiHooksTest001', [] );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testFalseReturn() {
		Hooks::register( 'MediaWikiHooksTest001', function ( &$foo ) {
			return false;
		} );
		Hooks::register( 'MediaWikiHooksTest001', function ( &$foo ) {
			$foo = 'test';

			return true;
		} );
		$foo = 'original';
		Hooks::run( 'MediaWikiHooksTest001', [ &$foo ] );
		$this->assertSame( 'original', $foo, 'Hooks continued processing after a false return.' );
	}

	/**
	 * @expectedException FatalError
	 * @covers Hooks::run
	 */
	public function testFatalError() {
		Hooks::register( 'MediaWikiHooksTest001', function () {
			return 'test';
		} );
		Hooks::run( 'MediaWikiHooksTest001', [] );
	}
}

function NothingFunction( &$foo, &$bar ) {
	$foo = 'changed-func';

	return true;
}

function NothingFunctionData( $data, &$foo, &$bar ) {
	$foo = $data;

	return true;
}

class NothingClass {
	public $calls = 0;

	public static function someStatic( &$foo, &$bar ) {
		$foo = 'changed-static';

		return true;
	}

	public function someNonStatic( &$foo, &$bar ) {
		$this->calls++;
		$foo = 'changed-nonstatic';
		$bar = 'changed-nonstatic';

		return true;
	}

	public function onMediaWikiHooksTest001( &$foo, &$bar ) {
		$this->calls++;
		$foo = 'changed-onevent';

		return true;
	}

	public function someNonStaticWithData( $data, &$foo, &$bar ) {
		$this->calls++;
		$foo = $data;

		return true;
	}
}
