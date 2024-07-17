<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestListProcessor;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestListProcessor
 */
class PhpUnitTestListProcessorTest extends TestCase {

	private const FIXTURE_FILE = __DIR__ . "/fixtures/tests-list.xml";

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
}
