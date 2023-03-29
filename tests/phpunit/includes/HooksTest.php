<?php

class HooksTest extends MediaWikiIntegrationTestCase {

	private const MOCK_HOOK_NAME = 'MediaWikiHooksTest001';

	protected function setUp(): void {
		parent::setUp();
	}

	public static function provideHooks() {
		$i = new HookTestDummyHookHandlerClass();

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
				[ 'HookTestDummyHookHandlerClass::someStatic' ],
				'changed-static',
				'original'
			],
			[
				'Class::method static call as array',
				[ [ 'HookTestDummyHookHandlerClass::someStatic' ] ],
				'changed-static',
				'original'
			],
			[ 'Global function', [ 'wfNothingFunction' ], 'changed-func', 'original' ],
			[ 'Global function with data', [ 'wfNothingFunctionData', 'data' ], 'data', 'original' ],
			[ 'Closure', [ static function ( &$foo, $bar ) {
				$foo = 'changed-closure';

				return true;
			} ], 'changed-closure', 'original' ],
			[ 'Closure with data', [ static function ( $data, &$foo, $bar ) {
				$foo = $data;

				return true;
			}, 'data' ], 'data', 'original' ]
		];
	}

	/**
	 * @dataProvider provideHooks
	 * @covers Hooks::register
	 * @covers Hooks::run
	 */
	public function testRunningNewStyleHooks( $msg, $hook, $expectedFoo, $expectedBar ) {
		$foo = $bar = 'original';
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, $hook );
		Hooks::run( self::MOCK_HOOK_NAME, [ &$foo, &$bar ] );

		$this->assertSame( $expectedFoo, $foo, $msg );
		$this->assertSame( $expectedBar, $bar, $msg );
	}

	/**
	 * @covers Hooks::isRegistered
	 * @covers Hooks::getHandlers
	 * @covers Hooks::register
	 * @covers Hooks::run
	 */
	public function testRegistration() {
		global $wgHooks;
		$hookContainer = $this->getServiceContainer()->getHookContainer();

		$this->expectDeprecationAndContinue( '/\$wgHooks .* deprecated/' );
		$this->expectDeprecationAndContinue( '/Use of Hooks::register was deprecated/' );

		$a = new HookTestDummyHookHandlerClass();
		$b = new HookTestDummyHookHandlerClass();
		$c = new HookTestDummyHookHandlerClass();

		$wgHooks[ self::MOCK_HOOK_NAME ][] = $a;
		Hooks::register( self::MOCK_HOOK_NAME, $b );
		$hookContainer->register( self::MOCK_HOOK_NAME, $c );

		$this->assertTrue( Hooks::isRegistered( self::MOCK_HOOK_NAME ) );
		$this->assertCount( 3, Hooks::getHandlers( self::MOCK_HOOK_NAME ) );

		$foo = 'quux';
		$bar = 'qaax';

		Hooks::run( self::MOCK_HOOK_NAME, [ &$foo, &$bar ] );
		$this->assertSame(
			1,
			$a->calls,
			'Hooks::run() should run hooks registered via $wgHooks'
		);
		$this->assertSame(
			1,
			$b->calls,
			'Hooks::run() should run hooks registered via Hooks::register'
		);
		$this->assertSame(
			1,
			$c->calls,
			'Hooks::run() should run hooks registered via HookContainer::register'
		);
	}

	/**
	 * @covers Hooks::run
	 */
	public function testUncallableFunction() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, 'ThisFunctionDoesntExist' );
		$this->expectExceptionMessage( 'Call to undefined function ThisFunctionDoesntExist' );
		Hooks::run( self::MOCK_HOOK_NAME, [] );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testFalseReturn() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			return false;
		} );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			$foo = 'test';
			return true;
		} );
		$foo = 'original';
		Hooks::run( self::MOCK_HOOK_NAME, [ &$foo ] );
		$this->assertSame( 'original', $foo, 'Hooks abort after a false return.' );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testNullReturn() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			return;
		} );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			$foo = 'test';

			return true;
		} );
		$foo = 'original';
		Hooks::run( self::MOCK_HOOK_NAME, [ &$foo ] );
		$this->assertSame( 'test', $foo, 'Hooks continue after a null return.' );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testCallHook_FalseHook() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, false );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			$foo = 'test';

			return true;
		} );
		$foo = 'original';
		Hooks::run( self::MOCK_HOOK_NAME, [ &$foo ] );
		$this->assertSame( 'test', $foo, 'Hooks that are falsey are skipped.' );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testCallHook_UnknownDatatype() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, 12345 );
		$this->expectException( UnexpectedValueException::class );
		Hooks::run( self::MOCK_HOOK_NAME );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testCallHook_Deprecated() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, 'HookTestDummyHookHandlerClass::someStatic' );
		$this->expectDeprecation();

		$a = $b = 0;
		Hooks::run( self::MOCK_HOOK_NAME, [ $a, $b ], '1.31' );
	}

	/**
	 * @covers Hooks::runWithoutAbort
	 */
	public function testRunWithoutAbort() {
		$list = [];
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$list ) {
			$list[] = 1;
			return true; // Explicit true
		} );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$list ) {
			$list[] = 2;
			return; // Implicit null
		} );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$list ) {
			$list[] = 3;
			// No return
		} );

		Hooks::runWithoutAbort( self::MOCK_HOOK_NAME, [ &$list ] );
		$this->assertSame( [ 1, 2, 3 ], $list, 'All hooks ran.' );
	}

	/**
	 * @covers Hooks::runWithoutAbort
	 */
	public function testRunWithoutAbortWarning() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			return false;
		} );
		$hookContainer->register( self::MOCK_HOOK_NAME, static function ( &$foo ) {
			$foo = 'test';
			return true;
		} );
		$foo = 'original';

		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Invalid return from hook-MediaWikiHooksTest001-closure for ' .
			'unabortable MediaWikiHooksTest001'
		);
		Hooks::runWithoutAbort( self::MOCK_HOOK_NAME, [ &$foo ] );
	}

	/**
	 * @covers Hooks::run
	 */
	public function testBadHookFunction() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookContainer->register( self::MOCK_HOOK_NAME, static function () {
			return 'test';
		} );
		$this->expectDeprecation();
		Hooks::run( self::MOCK_HOOK_NAME, [] );
	}
}

function wfNothingFunction( &$foo, &$bar ) {
	$foo = 'changed-func';

	return true;
}

function wfNothingFunctionData( $data, &$foo, &$bar ) {
	$foo = $data;

	return true;
}

class HookTestDummyHookHandlerClass {
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
