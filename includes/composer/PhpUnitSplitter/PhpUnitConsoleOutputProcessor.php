<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use Composer\IO\IOInterface;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitConsoleOutputProcessor {

	private const STATE_EXPECT_PHP_VERSION = 0;
	private const STATE_EXPECT_PHPUNIT_VERSION = 1;
	private const STATE_EXPECT_DOT_CHART = 2;
	private const STATE_EXPECT_TEST_SUMMARY = 3;
	private const STATE_EXPECT_ERROR_SUMMARY = 4;
	private const STATE_EXPECT_FAILURE_SUMMARY = 5;
	private const STATE_EXPECT_ERROR_TOTALS = 6;
	private const STATE_EXPECT_SLOW_TESTS = 7;
	private const STATE_EOF = 8;
	private const STATE_CLOSED = 9;

	private int $state = self::STATE_EXPECT_PHP_VERSION;
	private array $failures = [];
	private array $errors = [];
	private array $slowTests = [];
	private ?string $phpVersion = null;
	private ?string $phpUnitVersion = null;
	private ?string $dotChart = null;
	private bool $noTestsExecuted = false;
	private ?PhpUnitFailure $currentFailure = null;
	private int $testCount = 0;
	private int $assertionCount = 0;
	private int $errorCount = 0;
	private int $failureCount = 0;
	private int $skippedCount = 0;

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public function processInput( string $data ): void {
		$array = preg_split( "/\r\n|\n|\r/", $data );
		foreach ( $array as $inputLine ) {
			$matches = [];
			switch ( $this->state ) {
				case self::STATE_EXPECT_PHP_VERSION:
					if ( preg_match( "/^Using PHP (.*)$/", $inputLine, $matches ) ) {
						$this->phpVersion = $matches[1];
						$this->state = self::STATE_EXPECT_PHPUNIT_VERSION;
					}
					break;

				case self::STATE_EXPECT_PHPUNIT_VERSION:
					if ( preg_match( "/^PHPUnit (.*) by .*$/", $inputLine, $matches ) ) {
						$this->phpUnitVersion = $matches[1];
						$this->state = self::STATE_EXPECT_DOT_CHART;
					}
					break;

				case self::STATE_EXPECT_DOT_CHART:
					$this->handleDotChartLine( $inputLine );
					break;

				case self::STATE_EXPECT_TEST_SUMMARY:
					$this->handleTestSummary( $inputLine );
					break;

				case self::STATE_EXPECT_ERROR_SUMMARY:
					$this->handleSummaryTotals( $inputLine, false );
					break;

				case self::STATE_EXPECT_FAILURE_SUMMARY:
					$this->handleSummaryTotals( $inputLine, true );
					break;

				case self::STATE_EXPECT_ERROR_TOTALS:
					$this->processPossibleErrorTotalsLine( $inputLine );
					break;

				case self::STATE_EXPECT_SLOW_TESTS:
					if ( preg_match( "/^ \d+\. (\d+)ms to run (.*)$/", $inputLine, $matches ) ) {
						$this->slowTests[] = new PhpUnitSlowTest( intval( $matches[1] ), $matches[2] );
					}
					break;

				case self::STATE_EOF:
					if ( $inputLine ) {
						throw new PhpUnitConsoleOutputProcessingException(
							"Unexpected input in `EOF` state: '" . $inputLine . "'"
						);
					}
					break;

				default:
					throw new PhpUnitConsoleOutputProcessingException(
						"Unexpected processing state " . $this->state
					);
			}
		}
		if ( $this->currentFailure && !$this->currentFailure->empty() ) {
			$this->failures[] = $this->currentFailure;
		}
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	private function handleSummaryTotals( string $inputLine, bool $errorSectionComplete ) {
		if ( preg_match( "/^.*ERRORS!.*$/", $inputLine ) ||
			preg_match( "/^.*FAILURES!.*$/", $inputLine ) ) {
			$this->state = self::STATE_EXPECT_ERROR_TOTALS;
			return;
		}
		if ( !$errorSectionComplete && (
			preg_match( "/^There were (\d+) failures:$/", $inputLine, $matches ) ||
			$inputLine === "There was 1 failure:" )
		) {
			if ( $this->currentFailure && !$this->currentFailure->empty() ) {
				$this->errors[] = $this->currentFailure;
			}
			$this->state = self::STATE_EXPECT_FAILURE_SUMMARY;
			$this->currentFailure = new PhpUnitFailure();
			return;
		}
		if ( $this->processPossibleErrorTotalsLine( $inputLine ) ) {
			return;
		}
		if ( !$this->currentFailure->processLine( $inputLine ) ) {
			if ( !$errorSectionComplete ) {
				$this->errors[] = $this->currentFailure;
			} else {
				$this->failures[] = $this->currentFailure;
			}
			$this->currentFailure = new PhpUnitFailure();
			$this->currentFailure->processLine( $inputLine );
		}
	}

	private function handleDotChartLine( string $inputLine ) {
		if ( $this->dotChart === null ) {
			$this->dotChart = "";
		}
		$this->dotChart .= $inputLine . PHP_EOL;
		if ( preg_match( "/^.* \(100%\)$/", $inputLine ) ) {
			$this->state = self::STATE_EXPECT_TEST_SUMMARY;
			return;
		}
		if ( preg_match( "/^.*No tests executed!.*$/", $inputLine ) ) {
			$this->noTestsExecuted = true;
			$this->state = self::STATE_EOF;
		}
	}

	private function handleTestSummarySection( string $inputLine, string $keyword, int $nextState ): bool {
		if ( preg_match( "/^There were (\d+) " . $keyword . "s:$/", $inputLine ) ||
			$inputLine === "There was 1 " . $keyword . ":" ) {
			$this->state = $nextState;
			$this->currentFailure = new PhpUnitFailure();
			return true;
		}
		return false;
	}

	private function handleTestSummary( string $inputLine ) {
		if ( $this->handleTestSummarySection(
			$inputLine,
			"error",
			self::STATE_EXPECT_ERROR_SUMMARY
		) ) {
			return;
		}
		if ( $this->handleTestSummarySection(
			$inputLine,
			"failure",
			self::STATE_EXPECT_FAILURE_SUMMARY
		) ) {
			return;
		}
		$this->processPossibleErrorTotalsLine( $inputLine );
	}

	private function processPossibleErrorTotalsLine( string $inputLine ): bool {
		$matches = [];
		if ( preg_match( "/^.*Tests: (\d+).*$/", $inputLine, $matches ) ) {
			$this->testCount = intval( $matches[1] );
			if ( preg_match( "/^.*Assertions: (\d+).*$/", $inputLine, $matches ) ) {
				$this->assertionCount = intval( $matches[1] );
			}
			if ( preg_match( "/^.*Failures: (\d+).*$/", $inputLine, $matches ) ) {
				$this->failureCount = intval( $matches[1] );
			}
			if ( preg_match( "/^.*Errors: (\d+).*$/", $inputLine, $matches ) ) {
				$this->errorCount = intval( $matches[1] );
			}
			if ( preg_match( "/^.*Skipped: (\d+).*$/", $inputLine, $matches ) ) {
				$this->skippedCount = intval( $matches[1] );
			}
			$this->state = self::STATE_EXPECT_SLOW_TESTS;
			return true;
		}
		if ( preg_match( "/^.*OK \((\d+) tests?, (\d+) assertions?\).*$/", $inputLine, $matches ) ) {
			$this->testCount = intval( $matches[1] );
			$this->assertionCount = intval( $matches[2] );
			$this->state = self::STATE_EXPECT_SLOW_TESTS;
		}
		return false;
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public static function collectAndDumpFailureSummary( string $filePattern, int $groupCount, IOInterface $io ): bool {
		$failuresFound = false;
		$slowTests = [];
		for ( $i = 0; $i < $groupCount; $i++ ) {
			$filename = sprintf( $filePattern, $i );
			if ( file_exists( $filename ) ) {
				$summary = new PhpUnitConsoleOutputProcessor();
				$summary->processInput( file_get_contents( $filename ) );
				$summary->close();
				$slowTests = array_values( array_merge( $slowTests, $summary->getSlowTests() ) );
				$failureDetails = $summary->getFailureDetails();
				if ( $failureDetails ) {
					$io->write( "Report from `split_group" . $i . "`:" . PHP_EOL );
					$io->write( $failureDetails );
					$failuresFound = true;
				}
			}
		}
		if ( count( $slowTests ) > 0 ) {
			$io->write( PHP_EOL . "You should really speed up these slow tests (>100ms)..." . PHP_EOL );
			usort( $slowTests, static fn ( $t1, $t2 ) => $t2->getDuration() - $t1->getDuration() );
			for ( $i = 0; $i < min( 10, count( $slowTests ) ); $i++ ) {
				$test = $slowTests[$i];
				$io->write( " " . ( $i + 1 ) . ". " . $test->getDuration() . "ms to run " . $test->getTest() );
			}
		}
		return $failuresFound;
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public function getPhpVersion(): string {
		if ( $this->state !== self::STATE_CLOSED ) {
			throw new PhpUnitConsoleOutputProcessingException( "Still processing. Call `close()` first" );
		}
		if ( !$this->phpVersion ) {
			throw new PhpUnitConsoleOutputProcessingException( "No php version string detceted" );
		}
		return $this->phpVersion;
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public function getPhpUnitVersion(): string {
		if ( $this->state !== self::STATE_CLOSED ) {
			throw new PhpUnitConsoleOutputProcessingException( "Still processing. Call `close()` first" );
		}
		if ( !$this->phpUnitVersion ) {
			throw new PhpUnitConsoleOutputProcessingException( "No phpunit version string detceted" );
		}
		return $this->phpUnitVersion;
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public function wereTestsExecuted(): bool {
		if ( $this->state !== self::STATE_CLOSED ) {
			throw new PhpUnitConsoleOutputProcessingException( "Still processing. Call `close()` first" );
		}
		return !$this->noTestsExecuted;
	}

	public function close(): void {
		$this->state = self::STATE_CLOSED;
	}

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public function hasFailures(): bool {
		if ( $this->state !== self::STATE_CLOSED ) {
			throw new PhpUnitConsoleOutputProcessingException( "Still processing. Call `close()` first" );
		}
		return count( $this->failures ) + count( $this->errors ) > 0;
	}

	public function getFailureDetails(): string {
		$errorDetails = $this->prettyPrintErrors();
		$failureDetails = $this->prettyPrintFailures();
		$joiner = "";
		if ( $errorDetails && $failureDetails ) {
			$joiner = PHP_EOL . "--" . PHP_EOL . PHP_EOL;
		}
		return $errorDetails . $joiner . $failureDetails;
	}

	public function getSlowTests(): array {
		return $this->slowTests;
	}

	private function prettyPrintErrors(): string {
		$errorCount = count( $this->errors );
		if ( $errorCount === 0 ) {
			return "";
		}
		$result = "There " . ( $errorCount > 1 ? "were " : "was " ) . $errorCount
			. " error" . ( $errorCount > 1 ? "s" : "" ) . ":" . PHP_EOL . PHP_EOL;
		return $result . implode(
			PHP_EOL,
			array_map( static fn ( $err ) => $err->getFailureDetails(), array_merge( $this->errors ) )
		);
	}

	private function prettyPrintFailures(): string {
		$failureCount = count( $this->failures );
		if ( $failureCount === 0 ) {
			return "";
		}
		$result = "There " . ( $failureCount > 1 ? "were " : "was " ) . $failureCount
			. " failure" . ( $failureCount > 1 ? "s" : "" ) . ":" . PHP_EOL . PHP_EOL;
		return $result . implode(
			PHP_EOL,
			array_map( static fn ( $err ) => $err->getFailureDetails(), array_merge( $this->failures ) )
		);
	}

	public function getAssertionCount(): int {
		return $this->assertionCount;
	}

	public function getErrorCount(): int {
		return $this->errorCount;
	}

	public function getFailureCount(): int {
		return $this->failureCount;
	}

	public function getTestCount(): int {
		return $this->testCount;
	}

	public function getSkippedCount(): int {
		return $this->skippedCount;
	}
}
