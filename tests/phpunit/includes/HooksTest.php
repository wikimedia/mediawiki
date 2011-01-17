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
		$foo = 'Foo';
		
		
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
		
	}
	
}

class NothingClass {
	
	static public function someStatic( &$foo, &$bar ) {
		
		$foo = 'bah';
		
		return true;
		
	}
	
	public function someNonStatic( &$foo, &$bar ) {
		
		$foo = 'fOO';
		$bar = 'bAR';
		
		return true;
		
	}
	
	public function onMediaWikiHooksTest001( &$foo, &$bar ) {
		
		$foo = 'foo';
		
		return true;
		
	}
	
	public function someNonStaticWithData( $foo, &$bar ) {
		
		$bar = $foo;
		
		return true;
		
	}
	
}
