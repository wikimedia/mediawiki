<?php

/**
 * @covers MWFunction
 */
class MWFunctionTest extends MediaWikiTestCase {
	public function testNewObjFunction() {
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
