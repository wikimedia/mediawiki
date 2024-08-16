<?php

use PHPUnit\Framework\SkippedTestError;
use PHPUnit\Framework\SkippedTestSuiteError;
use PHPUnit\Framework\SyntheticError;
use PHPUnit\Framework\TestResult;

/**
 * Trait that returns to PHPUnit test suites the support for setUp/tearDown events that
 * was removed in PHPUnit 8.
 *
 * @since 1.35
 */
trait SuiteEventsTrait {
	/**
	 * @inheritDoc
	 */
	public function run( TestResult $result = null ): TestResult {
		// setUp / tearDown handling based on code in TestSuite::run()
		// (except in the parent only beforeClass / afterClass are run)
		$result ??= $this->createResult();

		// Don't run events if there are no tests (T292239)
		if ( count( $this ) === 0 ) {
			return $result;
		}

		$calls = 0;
		if ( is_callable( [ $this, 'setUp' ] ) ) {
			$calls++;
			try {
				$this->setUp();
			} catch ( SkippedTestSuiteError $error ) {
				$result->startTestSuite( $this );
				foreach ( $this->tests() as $test ) {
					$result->startTest( $test );
					$result->addFailure( $test, $error, 0 );
					$result->endTest( $test, 0 );
				}
				$result->endTestSuite( $this );
				return $result;
			} catch ( \Throwable $t ) {
				$errorAdded = false;
				$result->startTestSuite( $this );
				foreach ( $this->tests() as $test ) {
					if ( $result->shouldStop() ) {
						break;
					}
					$result->startTest( $test );
					if ( !$errorAdded ) {
						$result->addError( $test, $t, 0 );
						$errorAdded = true;
					} else {
						$result->addFailure(
							$test,
							new SkippedTestError( 'Test skipped because of an error in setUp method' ),
							0
						);
					}
					$result->endTest( $test, 0 );
				}
				$result->endTestSuite( $this );
				return $result;
			}
		}

		$result = parent::run( $result );

		if ( is_callable( [ $this, 'tearDown' ] ) ) {
			$calls++;
			try {
				$this->tearDown();
			} catch ( \Throwable $t ) {
				$message = "Exception in tearDown" . \PHP_EOL . $t->getMessage();
				$error = new SyntheticError( $message, 0, $t->getFile(), $t->getLine(), $t->getTrace() );
				$placeholderTest = clone $this->testAt( 0 );
				$placeholderTest->setName( 'tearDown' );
				// Unlike in parent implementation, $result->endTestSuite() has
				// already been invoked by parent::run, so we need to reopen
				// the test suite
				$result->startTestSuite( $this );
				$result->startTest( $placeholderTest );
				$result->addFailure( $placeholderTest, $error, 0 );
				$result->endTest( $placeholderTest, 0 );
				$result->endTestSuite( $this );
			}
		}
		if ( !$calls ) {
			throw new LogicException(
				get_class( $this )
				. " uses neither setUp() nor tearDown(), so it doesn't need SuiteEventsTrait"
			);
		}
		return $result;
	}
}
