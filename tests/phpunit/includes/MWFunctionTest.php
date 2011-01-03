<?php

class MWFunctionTest extends MediaWikiTestCase {
	
	function testCallUserFuncWorkarounds() {
		
		$this->assertEquals( 
			call_user_func( array( 'MWFunctionTest', 'someMethod' ) ),
			MWFunction::call( 'MWFunctionTest::someMethod' )
		);
		$this->assertEquals( 
			call_user_func( array( 'MWFunctionTest', 'someMethod' ), 'foo', 'bar', 'baz' ),
			MWFunction::call( 'MWFunctionTest::someMethod', 'foo', 'bar', 'baz' )
		);
		
		
		
		$this->assertEquals( 
			call_user_func_array( array( 'MWFunctionTest', 'someMethod' ), array() ),
			MWFunction::callArray( 'MWFunctionTest::someMethod', array() )
		);
		$this->assertEquals( 
			call_user_func_array( array( 'MWFunctionTest', 'someMethod' ), array( 'foo', 'bar', 'baz' ) ),
			MWFunction::callArray( 'MWFunctionTest::someMethod', array( 'foo', 'bar', 'baz' ) )
		);
		
	}
	
	function testNewObjFunction() {
		
		$arg1 = 'Foo';
		$arg2 = 'Bar';
		$arg3 = array( 'Baz' );
		$arg4 = new ExampleObject;
		
		$args = array( $arg1, $arg2, $arg3, $arg4 );
		
		$newObject = new MWBlankClass( $arg1, $arg2, $arg3, $arg4 );
		
		$this->assertEquals( 
			MWFunction::newObj( 'MWBlankClass', $args )->args, 
			$newObject->args
		);
		
		$this->assertEquals( 
			MWFunction::newObj( 'MWBlankClass', $args, true )->args, 
			$newObject->args,
			'Works even with PHP version < 5.1.3'
		);
		
	}
	
	public static function someMethod() {
		return func_get_args();
	}
	
}

class MWBlankClass {
	
	public $args = array();
	
	function __construct( $arg1, $arg2, $arg3, $arg4 ) {
		$this->args = array( $arg1, $arg2, $arg3, $arg4 );
	}
	
}

class ExampleObject {
}
