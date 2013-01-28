<?php

class HooksTest extends MediaWikiTestCase {

	public function testOldStyleHooks() {
		$foo = 'Foo';
		$bar = 'Bar';

		$i = new NothingClass();

		global $wgHooks;

		$wgHooks['MediaWikiHooksTest001'][] = array( $i, 'someNonStatic' );

		wfRunHooks( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'fOO', $foo, 'Standard method' );
		$foo = 'Foo';

		$wgHooks['MediaWikiHooksTest001'][] = $i;

		wfRunHooks( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'foo', $foo, 'onEventName style' );
		$foo = 'Foo';

		$wgHooks['MediaWikiHooksTest001'][] = array( $i, 'someNonStaticWithData', 'baz' );

		wfRunHooks( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'baz', $foo, 'Data included' );
		$foo = 'Foo';

		$wgHooks['MediaWikiHooksTest001'][] = array( $i, 'someStatic' );

		wfRunHooks( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'bah', $foo, 'Standard static method' );
		//$foo = 'Foo';

		unset( $wgHooks['MediaWikiHooksTest001'] );

	}

	public function testNewStyleHooks() {
		$foo = 'Foo';
		$bar = 'Bar';

		$i = new NothingClass();

		Hooks::register( 'MediaWikiHooksTest001', array( $i, 'someNonStatic' ) );

		Hooks::run( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'fOO', $foo, 'Standard method' );
		$foo = 'Foo';

		Hooks::register( 'MediaWikiHooksTest001', $i );

		Hooks::run( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'foo', $foo, 'onEventName style' );
		$foo = 'Foo';

		Hooks::register( 'MediaWikiHooksTest001', array( $i, 'someNonStaticWithData', 'baz' ) );

		Hooks::run( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'baz', $foo, 'Data included' );
		$foo = 'Foo';

		Hooks::register( 'MediaWikiHooksTest001', array( $i, 'someStatic' ) );

		Hooks::run( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );

		$this->assertEquals( 'bah', $foo, 'Standard static method' );
		$foo = 'Foo';

		Hooks::clear( 'MediaWikiHooksTest001' );
	}

	public function testNewStyleHookInteraction() {
		global $wgHooks;

		$a = new NothingClass();
		$b = new NothingClass();

		// make sure to start with a clean slate
		Hooks::clear( 'MediaWikiHooksTest001' );
		unset( $wgHooks['MediaWikiHooksTest001'] );

		$wgHooks['MediaWikiHooksTest001'][] = $a;
		$this->assertTrue( Hooks::isRegistered( 'MediaWikiHooksTest001' ), 'Hook registered via $wgHooks should be noticed by Hooks::isRegistered' );

		Hooks::register( 'MediaWikiHooksTest001', $b );
		$this->assertEquals( 2, count( Hooks::getHandlers( 'MediaWikiHooksTest001' ) ), 'Hooks::getHandlers() should return hooks registered via wgHooks as well as Hooks::register' );

		$foo = 'quux';
		$bar = 'qaax';

		Hooks::run( 'MediaWikiHooksTest001', array( &$foo, &$bar ) );
		$this->assertEquals( 1, $a->calls, 'Hooks::run() should run hooks registered via wgHooks as well as Hooks::register' );
		$this->assertEquals( 1, $b->calls, 'Hooks::run() should run hooks registered via wgHooks as well as Hooks::register' );

		// clean up
		Hooks::clear( 'MediaWikiHooksTest001' );
		unset( $wgHooks['MediaWikiHooksTest001'] );
	}
}

class NothingClass {
	public $calls = 0;

	public static function someStatic( &$foo, &$bar ) {
		$foo = 'bah';
		return true;
	}

	public function someNonStatic( &$foo, &$bar ) {
		$this->calls++;
		$foo = 'fOO';
		$bar = 'bAR';
		return true;
	}

	public function onMediaWikiHooksTest001( &$foo, &$bar ) {
		$this->calls++;
		$foo = 'foo';
		return true;
	}

	public function someNonStaticWithData( $foo, &$bar ) {
		$this->calls++;
		$bar = $foo;
		return true;
	}
}
