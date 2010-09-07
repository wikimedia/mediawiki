<?php

class ParserUnitTest extends PHPUnit_Framework_TestCase {
	private $test = "";
	private $suite;

	public function __construct( $suite, $test = null ) {
		$this->suite = $suite;
		$this->test = $test;
	}

	function count() { return 1; }

	public function run( PHPUnit_Framework_TestResult $result = null ) {
		PHPUnit_Framework_Assert::resetCount();
		if ( $result === NULL ) {
			$result = new PHPUnit_Framework_TestResult;
		}

		$backend = $this->suite->getBackend();
		$result->startTest( $this );
		PHPUnit_Util_Timer::start();

		$r = false;
		try {
			# Run the test.
			# On failure, the subclassed backend will throw an exception with
			# the details.
			$r = $backend->runTest(
				$this->test['test'],
				$this->test['input'],
				$this->test['result'],
				$this->test['options'],
				$this->test['config']
			);
		}
		catch ( PHPUnit_Framework_AssertionFailedError $e ) {
			$result->addFailure( $this, $e, PHPUnit_Util_Timer::stop() );
		}
		catch ( Exception $e ) {
			$result->addError( $this, $e, PHPUnit_Util_Timer::stop() );
		}

		$result->endTest( $this, PHPUnit_Util_Timer::stop() );

		$backend->recorder->record( $this->test['test'], $r );
		$this->addToAssertionCount( PHPUnit_Framework_Assert::getCount() );

		return $result;
	}

	public function toString() {
		return $this->test['test'];
	}

}

class ParserTestSuiteBackend extends ParserTest {
	function showTesting( $desc ) {
	}

	function showRunFile( $path ) {
	}

	function showSuccess( $desc ) {
		PHPUnit_Framework_Assert::assertTrue( true, $desc );
		return true;
	}

	function showFailure( $desc, $expected, $got ) {
		PHPUnit_Framework_Assert::assertEquals( $expected, $got, $desc );
	}

	public function setupRecorder( $options ) {
		$this->recorder = new PHPUnitTestRecorder( $this );
	}
}

class PHPUnitTestRecorder extends TestRecorder {
	function record( $test, $result ) {
		$this->total++;
		$this->success += $result;

	}

	function reportPercentage( $success, $total ) { }
}

