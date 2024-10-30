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

	public function testBuildSuitesInAlphabeticalOrder() {
		$testList = [
			new TestDescriptor( "ZTest", [ "MediaWiki" ], "MediaWiki/ZTest.php" ),
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php" ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php" ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php" ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php" ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 3 );
		$expected = [
			[
				"list" => [
					"MediaWiki/VTest.php",
					"MediaWiki/WTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/XTest.php",
					"MediaWiki/YTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/ZTest.php",
				],
				"time" => 0
			]
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}

	public function testGroupsWithSimilarDurations() {
		$testList = [
			new TestDescriptor( "ZTest", [ "MediaWiki" ], "MediaWiki/ZTest.php", 400 ),
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php", 200 ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php", 200 ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php", 100 ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php", 100 ),
			new TestDescriptor( "UTest", [ "MediaWiki" ], "MediaWiki/UTest.php", 0 ),
			new TestDescriptor( "TTest", [ "MediaWiki" ], "MediaWiki/TTest.php", 0 ),
			new TestDescriptor( "STest", [ "MediaWiki" ], "MediaWiki/STest.php", 0 ),
			new TestDescriptor( "RTest", [ "MediaWiki" ], "MediaWiki/RTest.php", 0 ),
			new TestDescriptor( "QTest", [ "MediaWiki" ], "MediaWiki/QTest.php", 0 ),
			new TestDescriptor( "PTest", [ "MediaWiki" ], "MediaWiki/PTest.php", 0 ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 3 );
		$expected = [
			[
				"list" => [
					"MediaWiki/PTest.php",
					"MediaWiki/QTest.php",
					"MediaWiki/RTest.php",
					"MediaWiki/STest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/TTest.php",
					"MediaWiki/UTest.php",
					"MediaWiki/VTest.php",
					"MediaWiki/WTest.php",
				],
				"time" => 200
			],
			[
				"list" => [
					"MediaWiki/XTest.php",
					"MediaWiki/YTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 800
			],
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}
}
