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
		$this->assertEquals( $expected, !(bool)$checker->checkArray( [
				'FakeExtension' => [
					'MediaWiki' => $constraint,
				],
			] )
		);
	}

	public static function provideCheck() {
		return [
			// array( $wgVersion, constraint, expected )
			[ '1.25alpha', '>= 1.26', false ],
			[ '1.25.0', '>= 1.26', false ],
			[ '1.26alpha', '>= 1.26', true ],
			[ '1.26alpha', '>= 1.26.0', true ],
			[ '1.26alpha', '>= 1.26.0-stable', false ],
			[ '1.26.0', '>= 1.26.0-stable', true ],
			[ '1.26.1', '>= 1.26.0-stable', true ],
			[ '1.27.1', '>= 1.26.0-stable', true ],
			[ '1.26alpha', '>= 1.26.1', false ],
			[ '1.26alpha', '>= 1.26alpha', true ],
			[ '1.26alpha', '>= 1.25', true ],
			[ '1.26.0-alpha.14', '>= 1.26.0-alpha.15', false ],
			[ '1.26.0-alpha.14', '>= 1.26.0-alpha.10', true ],
			[ '1.26.1', '>= 1.26.2, <=1.26.0', false ],
			[ '1.26.1', '^1.26.2', false ],
			// Accept anything for un-parsable version strings
			[ '1.26mwf14', '== 1.25alpha', true ],
			[ 'totallyinvalid', '== 1.0', true ],
		];
	}

	/**
	 * @dataProvider provideType
	 */
	public function testInvalidType( $type, $expected ) {
		$checker = new VersionChecker();
		$checker
			->setCoreVersion( '1.0.0' )
			->setLoaded( [
				'FakeDependency' => [
					'version' => '1.0.0',
				],
			] );
		$this->assertEquals( $expected, $checker->checkArray( [
				'FakeExtension' => [
					$type => '1.0.0',
				]
			] )
		);
	}

	public static function provideType() {
		return [
			// valid type
			[ 'FakeDependency', [] ],
			[ 'MediaWiki', [] ],
		];
	}

	/**
	 * Check, if a non-parsable version constraint does not throw an exception or
	 * returns any error message.
	 */
	public function testInvalidConstraint() {
		$checker = new VersionChecker();
		$checker
			->setCoreVersion( '1.0.0' )
			->setLoaded( [
				'FakeDependency' => [
					'version' => 'not really valid',
				],
			] );
		$this->assertEquals( [], $checker->checkArray( [
				'FakeExtension' => [
					'FakeDependency' => '1.24.3',
				]
			] )
		);
	}
}
