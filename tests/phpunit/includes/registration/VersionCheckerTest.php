<?php

/**
 * @covers CoreVersionChecker
 */
class VersionCheckerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider provideCheck
	 */
	public function testCheck( $coreVersion, $constraint, $expected ) {
		$checker = new VersionChecker();
		$checker->setCoreVersion( $coreVersion );
		$this->assertEquals( $expected, !(bool)$checker->checkArray( array(
				'FakeExtension' => array(
					'MediaWiki' => $constraint,
				),
			) )
		);
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

	/**
	 * @dataProvider provideType
	 */
	public function testInvalidType( $type, $expected ) {
		$checker = new VersionChecker();
		$checker
			->setCoreVersion( '1.0.0' )
			->setLoaded( array(
				'FakeDependency' => array(
					'version' => '1.0.0',
				),
			) );
		$this->assertEquals( $expected, !(bool)$checker->checkArray( array(
				'FakeExtension' => array(
					$type => '1.0.0',
				)
			) )
		);
	}

	public static function provideType() {
		return array(
			// Invalid type
			array( 'FakeType:FakeDependency', false ),
			// valid type
			array( 'Extension:FakeDependency', true ),
			// check the protection of the MediaWiki core type
			array( 'MediaWiki:FakeDependency', false ),
			array( 'MediaWiki', true ),
		);
	}
}
