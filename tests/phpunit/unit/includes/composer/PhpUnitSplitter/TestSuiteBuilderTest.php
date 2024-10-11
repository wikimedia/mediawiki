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
					"MediaWiki/YTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/WTest.php",
					"MediaWiki/ZTest.php",
				],
				"time" => 0
			],
			[
				"list" => [
					"MediaWiki/XTest.php",
				],
				"time" => 0
			]
		];
		$this->assertEquals( $expected, $suites, "Expected suites to be built correctly" );
	}
}
