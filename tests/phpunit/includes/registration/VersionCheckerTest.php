<?php

/**
 * @covers VersionChecker
 */
class VersionCheckerTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/**
	 * @dataProvider provideCheck
	 */
	public function testCheck( $coreVersion, $constraint, $expected ) {
		$checker = new VersionChecker( $coreVersion );
		$this->assertEquals( $expected, !(bool)$checker->checkArray( [
			'FakeExtension' => [
				'MediaWiki' => $constraint,
			],
		] ) );
	}

	public static function provideCheck() {
		return [
			// [ $wgVersion, constraint, expected ]
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
	public function testType( $given, $expected ) {
		$checker = new VersionChecker( '1.0.0' );
		$checker->setLoadedExtensionsAndSkins( [
				'FakeDependency' => [
					'version' => '1.0.0',
				],
				'NoVersionGiven' => [],
			] );
		$this->assertEquals( $expected, $checker->checkArray( [
			'FakeExtension' => $given,
		] ) );
	}

	public static function provideType() {
		return [
			// valid type
			[
				[
					'extensions' => [
						'FakeDependency' => '1.0.0',
					],
				],
				[],
			],
			[
				[
					'MediaWiki' => '1.0.0',
				],
				[],
			],
			[
				[
					'extensions' => [
						'NoVersionGiven' => '*',
					],
				],
				[],
			],
			[
				[
					'extensions' => [
						'NoVersionGiven' => '1.0',
					],
				],
				[
					[
						'incompatible' => 'FakeExtension',
						'type' => 'incompatible-extensions',
						'msg' => 'NoVersionGiven does not expose its version, but FakeExtension requires: 1.0.',
					],
				],
			],
			[
				[
					'extensions' => [
						'Missing' => '*',
					],
				],
				[
					[
						'missing' => 'Missing',
						'type' => 'missing-extensions',
						'msg' => 'FakeExtension requires Missing to be installed.',
					],
				],
			],
			[
				[
					'extensions' => [
						'FakeDependency' => '2.0.0',
					],
				],
				[
					[
						'incompatible' => 'FakeExtension',
						'type' => 'incompatible-extensions',
						// phpcs:ignore Generic.Files.LineLength.TooLong
						'msg' => 'FakeExtension is not compatible with the current installed version of FakeDependency (1.0.0), it requires: 2.0.0.',
					],
				],
			],
			[
				[
					'skins' => [
						'FakeSkin' => '*',
					],
				],
				[
					[
						'missing' => 'FakeSkin',
						'type' => 'missing-skins',
						'msg' => 'FakeExtension requires FakeSkin to be installed.',
					],
				],
			],
		];
	}

	/**
	 * Check, if a non-parsable version constraint does not throw an exception or
	 * returns any error message.
	 */
	public function testInvalidConstraint() {
		$checker = new VersionChecker( '1.0.0' );
		$checker->setLoadedExtensionsAndSkins( [
				'FakeDependency' => [
					'version' => 'not really valid',
				],
			] );
		$this->assertEquals( [
			[
				'type' => 'invalid-version',
				'msg' => "FakeDependency does not have a valid version string.",
			],
		], $checker->checkArray( [
			'FakeExtension' => [
				'extensions' => [
					'FakeDependency' => '1.24.3',
				],
			],
		] ) );

		$checker = new VersionChecker( '1.0.0' );
		$checker->setLoadedExtensionsAndSkins( [
				'FakeDependency' => [
					'version' => '1.24.3',
				],
			] );

		$this->setExpectedException( UnexpectedValueException::class );
		$checker->checkArray( [
			'FakeExtension' => [
				'FakeDependency' => 'not really valid',
			],
		] );
	}

	/**
	 * T197478
	 */
	public function testInvalidDependency() {
		$checker = new VersionChecker( '1.0.0' );
		$this->setExpectedException( UnexpectedValueException::class,
			'Dependency type skin unknown in FakeExtension' );
		$this->assertEquals( [
			[
				'type' => 'invalid-version',
				'msg' => 'FakeDependency does not have a valid version string.',
			],
		], $checker->checkArray( [
			'FakeExtension' => [
				'skin' => [
					'FakeSkin' => '*',
				],
			],
		] ) );
	}
}
