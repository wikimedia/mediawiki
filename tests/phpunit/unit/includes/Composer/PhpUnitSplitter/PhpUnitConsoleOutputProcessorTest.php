<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitConsoleOutputProcessor;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestFileScanner
 */
class PhpUnitConsoleOutputProcessorTest extends TestCase {
	use MediaWikiCoversValidator;

	private function loadFixture( string $filename ): string {
		$fixtureData = file_get_contents(
			implode( DIRECTORY_SEPARATOR, [ __DIR__, "fixtures", $filename ] ) );
		$this->assertNotNull( $fixtureData, "Unable to load fixture data for fixture " . $filename );
		return $fixtureData;
	}

	public function testSplitGoodOutputBySections(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-good.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();
		$this->assertFalse( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertTrue( $outputProcessor->wereTestsExecuted() );
		$this->assertSame( 103, $outputProcessor->getTestCount() );
		$this->assertSame( 0, $outputProcessor->getErrorCount() );
		$this->assertSame( 6, $outputProcessor->getSkippedCount() );
		$this->assertSame( 210, $outputProcessor->getAssertionCount() );
		$this->assertCount( 9, $outputProcessor->getSlowTests() );
	}

	public function testSplitBadOutputBySections(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-with-errors.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertTrue( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertTrue( $outputProcessor->wereTestsExecuted() );
		$this->assertStringContainsString( "Undefined constant ", $outputProcessor->getFailureDetails() );

		$expectedFailureOutput = $this->loadFixture( 'phpunit-expected-error-output.txt' );
		$this->assertEquals(
			preg_split( "/\r\n|\n|\r/", $expectedFailureOutput ),
			preg_split( "/\r\n|\n|\r/", $outputProcessor->getFailureDetails() )
		);
	}

	public function testSplitNoTestsOutputBySections(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-no-tests-run.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertFalse( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertFalse( $outputProcessor->wereTestsExecuted() );
	}

	public function testSplitSingleFailure(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-single-failure.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertTrue( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertTrue( $outputProcessor->wereTestsExecuted() );
		$this->assertSame( 4, $outputProcessor->getTestCount() );
		$this->assertSame( 0, $outputProcessor->getErrorCount() );
		$this->assertSame( 0, $outputProcessor->getSkippedCount() );
		$this->assertSame( 1, $outputProcessor->getFailureCount() );
		$this->assertSame( 4, $outputProcessor->getAssertionCount() );
	}

	public function testErrorsAndFailures(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-failures-and-errors.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertTrue( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertTrue( $outputProcessor->wereTestsExecuted() );
		$this->assertSame( 4, $outputProcessor->getTestCount() );
		$this->assertSame( 2, $outputProcessor->getErrorCount() );
		$this->assertSame( 0, $outputProcessor->getSkippedCount() );
		$this->assertSame( 1, $outputProcessor->getFailureCount() );
		$this->assertSame( 2, $outputProcessor->getAssertionCount() );

		$expectedFailureOutput = $this->loadFixture( 'phpunit-expected-failure-and-errors-output.txt' );
		$this->assertEquals(
			preg_split( "/\r\n|\n|\r/", $expectedFailureOutput ),
			preg_split( "/\r\n|\n|\r/", $outputProcessor->getFailureDetails() )
		);
	}

	public function testGoodOutputWithSlowTests(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-good-with-slow-tests.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertFalse( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertSame( 51, $outputProcessor->getTestCount() );
		$this->assertSame( 0, $outputProcessor->getErrorCount() );
		$this->assertSame( 0, $outputProcessor->getSkippedCount() );
		$this->assertSame( 0, $outputProcessor->getFailureCount() );
		$this->assertSame( 89, $outputProcessor->getAssertionCount() );
		$this->assertCount( 7, $outputProcessor->getSlowTests() );
	}

	public function testGoodOutputWithSkippedTests(): void {
		$exampleOutput = $this->loadFixture( 'phpunit-test-output-good-with-slow-and-skipped-tests.txt' );
		$outputProcessor = new PhpUnitConsoleOutputProcessor();
		$outputProcessor->processInput( $exampleOutput );
		$outputProcessor->close();

		$this->assertFalse( $outputProcessor->hasFailures() );
		$this->assertEquals( "8.1.18", $outputProcessor->getPhpVersion() );
		$this->assertEquals( "9.6.19", $outputProcessor->getPhpUnitVersion() );
		$this->assertSame( 51, $outputProcessor->getTestCount() );
		$this->assertSame( 0, $outputProcessor->getErrorCount() );
		$this->assertSame( 1, $outputProcessor->getSkippedCount() );
		$this->assertSame( 0, $outputProcessor->getFailureCount() );
		$this->assertSame( 50, $outputProcessor->getAssertionCount() );
		$this->assertCount( 8, $outputProcessor->getSlowTests() );
	}
}
