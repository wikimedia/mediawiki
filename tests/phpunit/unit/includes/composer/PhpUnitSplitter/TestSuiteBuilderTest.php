<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\TestDescriptor;
use MediaWiki\Composer\PhpUnitSplitter\TestSuiteBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\TestSuiteBuilder
 */
class TestSuiteBuilderTest extends TestCase {

	public function testBuildSuites() {
		$testList = [
			new TestDescriptor( "ATest", [ "MediaWiki" ], "MediaWiki/ATest.php" ),
			new TestDescriptor( "BTest", [ "MediaWiki" ], "MediaWiki/BTest.php" ),
			new TestDescriptor( "CTest", [ "MediaWiki" ], "MediaWiki/CTest.php" ),
			new TestDescriptor( "DTest", [ "MediaWiki" ], "MediaWiki/DTest.php" ),
			new TestDescriptor( "ETest", [ "MediaWiki" ], "MediaWiki/ETest.php" ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 3 );
		$expected = [
			[
				"list" => [
					"MediaWiki/ATest.php",
					"MediaWiki/DTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/BTest.php",
					"MediaWiki/ETest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/CTest.php",
				],
				"time" => 0
			]
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}
}
