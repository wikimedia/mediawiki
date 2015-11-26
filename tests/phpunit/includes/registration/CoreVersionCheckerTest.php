<?php

/**
 * @covers CoreVersionChecker
 */
class CoreVersionCheckerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider provideCheck
	 */
	public function testCheck( $coreVersion, $constraint, $expected ) {
		$checker = new CoreVersionChecker( $coreVersion );
		$this->assertEquals( $expected, $checker->check( $constraint ) );
	}

	public static function provideCheck() {
		return array(
			// array( $wgVersion, constraint, expected )
			array( '1.25alpha', '>= 1.26', false ),
			array( '1.25.0', '>= 1.26', false ),
			array( '1.26alpha', '>= 1.26', true ),
			array( '1.26alpha', '>= 1.26.0', true ),
			array( '1.26alpha', '>= 1.26.0-stable', false ),
			array( '1.26.0', '>= 1.26.0-stable', true ),
			array( '1.26.1', '>= 1.26.0-stable', true ),
			array( '1.27.1', '>= 1.26.0-stable', true ),
			array( '1.26alpha', '>= 1.26.1', false ),
			array( '1.26alpha', '>= 1.26alpha', true ),
			array( '1.26alpha', '>= 1.25', true ),
			array( '1.26.0-alpha.14', '>= 1.26.0-alpha.15', false ),
			array( '1.26.0-alpha.14', '>= 1.26.0-alpha.10', true ),
			array( '1.26.1', '>= 1.26.2, <=1.26.0', false ),
			array( '1.26.1', '^1.26.2', false ),
			// Accept anything for un-parsable version strings
			array( '1.26mwf14', '== 1.25alpha', true ),
			array( 'totallyinvalid', '== 1.0', true ),
		);
	}
}
