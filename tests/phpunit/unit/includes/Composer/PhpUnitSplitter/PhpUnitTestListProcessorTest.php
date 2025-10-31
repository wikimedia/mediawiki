<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestListProcessor;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestListProcessor
 */
class PhpUnitTestListProcessorTest extends TestCase {
	use MediaWikiCoversValidator;

	private const FIXTURE_FILE = __DIR__ . "/fixtures/tests-list.xml";
	private const RESULTS_CACHE_FIXTURE = __DIR__ . "/fixtures/results-cache.json";

	public function testGetTestClasses() {
		$testList = new PhpUnitTestListProcessor( self::FIXTURE_FILE );
		$this->assertCount(
			5, $testList->getTestClasses(), "Expected classes to be loaded"
		);
		$test5 = $testList->getTestClasses()[4];
		$this->assertEquals(
			[ [ 'MediaWiki', 'Tests', 'Unit', 'composer', 'PhpUnitSplitter' ], 'TestSuiteBuilderTest' ],
			[ $test5->getNamespace(), $test5->getClassName() ]
		);
	}

	public function testGetTestClassesWithTimings() {
		$testList = new PhpUnitTestListProcessor(
			self::FIXTURE_FILE,
			self::RESULTS_CACHE_FIXTURE,
			'database'
		);
		$this->assertCount(
			5, $testList->getTestClasses(), "Expected classes to be loaded"
		);
		$totalTime = array_reduce( $testList->getTestClasses(), static fn ( $acc, $test ) => $acc + $test->getDuration(), 0 );
		$this->assertEqualsWithDelta( 6.51, $totalTime, 0.0001 );
	}
}
