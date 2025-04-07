<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestListProcessor;
use MediaWiki\Composer\PhpUnitSplitter\TestDescriptor;
use MediaWiki\Composer\PhpUnitSplitter\TestSuiteBuilder;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\TestSuiteBuilder
 */
class TestSuiteBuilderTest extends TestCase {
	use MediaWikiCoversValidator;

	private const TEST_LIST_FIXTURE_FILE = __DIR__ . "/fixtures/tests-list-large.xml";
	private const CACHE_RESULTS_FIXTURE_FILE = __DIR__ . "/fixtures/cache-results-large.json";

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
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php", 0 ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php", 200 ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php", 0 ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php", 100 ),
			new TestDescriptor( "UTest", [ "MediaWiki" ], "MediaWiki/UTest.php", 0 ),
			new TestDescriptor( "TTest", [ "MediaWiki" ], "MediaWiki/TTest.php", 200 ),
			new TestDescriptor( "STest", [ "MediaWiki" ], "MediaWiki/STest.php", 0 ),
			new TestDescriptor( "RTest", [ "MediaWiki" ], "MediaWiki/RTest.php", 100 ),
			new TestDescriptor( "QTest", [ "MediaWiki" ], "MediaWiki/QTest.php", 0 ),
			new TestDescriptor( "PTest", [ "MediaWiki" ], "MediaWiki/PTest.php", 0 ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 4 );
		$expected = [
			[
				"list" => [
					"MediaWiki/PTest.php",
					"MediaWiki/QTest.php",
					"MediaWiki/RTest.php",
				],
				"time" => 100
			],
			[
				"list" => [
					"MediaWiki/STest.php",
					"MediaWiki/TTest.php",
				],
				"time" => 200
			],
			[
				"list" => [
					"MediaWiki/UTest.php",
					"MediaWiki/VTest.php",
					"MediaWiki/WTest.php",
					"MediaWiki/XTest.php",
				],
				"time" => 300
			],
			[
				"list" => [
					"MediaWiki/YTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 400
			],
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}

	public function testBehavesWellIfAllTestsAreZero() {
		$testList = [
			new TestDescriptor( "ZTest", [ "MediaWiki" ], "MediaWiki/ZTest.php", 0 ),
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php", 0 ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php", 0 ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php", 0 ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php", 0 ),
			new TestDescriptor( "UTest", [ "MediaWiki" ], "MediaWiki/UTest.php", 0 ),
			new TestDescriptor( "TTest", [ "MediaWiki" ], "MediaWiki/TTest.php", 0 ),
			new TestDescriptor( "STest", [ "MediaWiki" ], "MediaWiki/STest.php", 0 ),
			new TestDescriptor( "RTest", [ "MediaWiki" ], "MediaWiki/RTest.php", 0 ),
			new TestDescriptor( "QTest", [ "MediaWiki" ], "MediaWiki/QTest.php", 0 ),
			new TestDescriptor( "PTest", [ "MediaWiki" ], "MediaWiki/PTest.php", 0 ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 4 );
		$expected = [
			[
				"list" => [
					"MediaWiki/PTest.php",
					"MediaWiki/QTest.php",
					"MediaWiki/RTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/STest.php",
					"MediaWiki/TTest.php",
					"MediaWiki/UTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/VTest.php",
					"MediaWiki/WTest.php",
					"MediaWiki/XTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/YTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 0
			],
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}

	public function testBehavesWellIfFewerTestsHaveDurationThanWeHaveBuckets() {
		$testList = [
			new TestDescriptor( "ZTest", [ "MediaWiki" ], "MediaWiki/ZTest.php", 0 ),
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php", 0 ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php", 100 ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php", 0 ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php", 100 ),
			new TestDescriptor( "UTest", [ "MediaWiki" ], "MediaWiki/UTest.php", 0 ),
			new TestDescriptor( "TTest", [ "MediaWiki" ], "MediaWiki/TTest.php", 100 ),
			new TestDescriptor( "STest", [ "MediaWiki" ], "MediaWiki/STest.php", 0 ),
			new TestDescriptor( "RTest", [ "MediaWiki" ], "MediaWiki/RTest.php", 0 ),
			new TestDescriptor( "QTest", [ "MediaWiki" ], "MediaWiki/QTest.php", 0 ),
			new TestDescriptor( "PTest", [ "MediaWiki" ], "MediaWiki/PTest.php", 0 ),
		];
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 4 );
		$expected = [
			[
				"list" => [
					"MediaWiki/PTest.php",
					"MediaWiki/QTest.php",
					"MediaWiki/RTest.php",
					"MediaWiki/STest.php",
					"MediaWiki/TTest.php",
				],
				"time" => 100
			],
			[
				"list" => [
					"MediaWiki/UTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/VTest.php",
				],
				"time" => 100
			],
			[
				"list" => [
					"MediaWiki/WTest.php",
					"MediaWiki/XTest.php",
					"MediaWiki/YTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 100
			],
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}

	public function testWithRealData() {
		$testList = new PhpUnitTestListProcessor(
			self::TEST_LIST_FIXTURE_FILE,
			self::CACHE_RESULTS_FIXTURE_FILE,
			'database'
		);
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList->getTestClasses(), 8 );
		$minDuration = array_reduce( $suites, static fn ( $min, $suite ) => min( $min, $suite["time"] ), PHP_INT_MAX );
		$maxDuration = array_reduce( $suites, static fn ( $max, $suite ) => max( $max, $suite["time"] ), 0 );
		$avgDuration = array_reduce( $suites, static fn ( $acc, $suite ) => $acc + $suite["time"], 0 ) / count( $suites );
		$aboveAverageBuckets = array_reduce( $suites, static fn ( $acc, $suite ) => $acc + ( $suite["time"] > $avgDuration ? 1 : 0 ), 0 );
		$belowAverageBuckets = array_reduce( $suites, static fn ( $acc, $suite ) => $acc + ( $suite["time"] <= $avgDuration ? 1 : 0 ), 0 );
		$zeroClassBuckets = array_reduce( $suites, static fn ( $acc, $suite ) => $acc + ( count( $suite["list"] ) === 0 ? 1 : 0 ), 0 );
		$this->assertGreaterThanOrEqual( 2, $aboveAverageBuckets );
		$this->assertGreaterThanOrEqual( 2, $belowAverageBuckets );
		$this->assertLessThan( $avgDuration * 2, $maxDuration );
		$this->assertSame( 0, $zeroClassBuckets );
	}

	public function testFallbackInTheCaseOfAnError() {
		$testList = [
			new TestDescriptor( "ZTest", [ "MediaWiki" ], "MediaWiki/ZTest.php", 400 ),
			new TestDescriptor( "YTest", [ "MediaWiki" ], "MediaWiki/YTest.php", 0 ),
			new TestDescriptor( "XTest", [ "MediaWiki" ], "MediaWiki/XTest.php", 200 ),
			new TestDescriptor( "WTest", [ "MediaWiki" ], "MediaWiki/WTest.php", 0 ),
			new TestDescriptor( "VTest", [ "MediaWiki" ], "MediaWiki/VTest.php", 100 ),
			new TestDescriptor( "UTest", [ "MediaWiki" ], "MediaWiki/UTest.php", 0 ),
			new TestDescriptor( "TTest", [ "MediaWiki" ], "MediaWiki/TTest.php", 200 ),
			new TestDescriptor( "STest", [ "MediaWiki" ], "MediaWiki/STest.php", 0 ),
			new TestDescriptor( "RTest", [ "MediaWiki" ], "MediaWiki/RTest.php", 100 ),
			new TestDescriptor( "QTest", [ "MediaWiki" ], "MediaWiki/QTest.php", 0 ),
			new TestDescriptor( "PTest", [ "MediaWiki" ], "MediaWiki/PTest.php", 0 ),
		];
		// Set the chunk size larger than the number of tests
		$suites = ( new TestSuiteBuilder() )->buildSuites( $testList, 4, 20 );
		$expected = [
			[
				"list" => [
					"MediaWiki/PTest.php",
					"MediaWiki/QTest.php",
					"MediaWiki/RTest.php",
				],
				"time" => 100
			],
			[
				"list" => [
					"MediaWiki/STest.php",
					"MediaWiki/TTest.php",
					"MediaWiki/UTest.php",
				],
				"time" => 200
			],
			[
				"list" => [
					"MediaWiki/VTest.php",
					"MediaWiki/WTest.php",
					"MediaWiki/XTest.php",
				],
				"time" => 300
			],
			[
				"list" => [
					"MediaWiki/YTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 400
			],
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}
}
