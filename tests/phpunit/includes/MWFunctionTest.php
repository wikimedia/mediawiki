<?php

class MWFunctionTest extends MediaWikiTestCase {
	
	function testCallUserFuncWorkarounds() {
		
		$this->assertEquals( 
			MWFunction::call( 'MWFunctionTest::someMethod' ), 
			call_user_func( array( 'MWFunctionTest', 'someMethod' ) )
		);
		$this->assertEquals( 
			MWFunction::call( 'MWFunctionTest::someMethod', 'foo', 'bar', 'baz' ), 
			call_user_func( array( 'MWFunctionTest', 'someMethod' ), 'foo', 'bar', 'baz' )
		);
		
		
		
		$this->assertEquals( 
			MWFunction::callArray( 'MWFunctionTest::someMethod', array() ), 
			call_user_func_array( array( 'MWFunctionTest', 'someMethod' ), array() )
		);
		$this->assertEquals( 
			MWFunction::callArray( 'MWFunctionTest::someMethod', array( 'foo', 'bar', 'baz' ) ), 
			call_user_func_array( array( 'MWFunctionTest', 'someMethod' ), array( 'foo', 'bar', 'baz' ) )
		);
		
	}
	
	public static function someMethod() {
		return func_get_args();
	}
	
}

