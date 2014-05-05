<?php

class MediaWikiPHPUnitTestListener extends PHPUnit_TextUI_ResultPrinter implements PHPUnit_Framework_TestListener {

	/**
	 * @var string
	 */
	protected $logChannel = 'PHPUnitCommand';

	protected function getTestName( PHPUnit_Framework_Test $test ) {
		$name = get_class( $test );

		if ( $test instanceof PHPUnit_Framework_TestCase ) {
			$name .= '::' . $test->getName( true );
		}

		return $name;
	}

	protected function getErrorName( Exception $exception ) {
		$name = get_class( $exception );
		$name = "[$name] " . $exception->getMessage();

		return $name;
	}

	/**
	 * An error occurred.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param Exception $e
	 * @param float $time
	 */
	public function addError( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		parent::addError( $test, $e, $time );
		wfDebugLog(
			$this->logChannel,
			'ERROR in ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e )
		);
	}

	/**
	 * A failure occurred.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param PHPUnit_Framework_AssertionFailedError $e
	 * @param float $time
	 */
	public function addFailure( PHPUnit_Framework_Test $test,
		PHPUnit_Framework_AssertionFailedError $e, $time
	) {
		parent::addFailure( $test, $e, $time );
		wfDebugLog(
			$this->logChannel,
			'FAILURE in ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e )
		);
	}

	/**
	 * Incomplete test.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param Exception $e
	 * @param float $time
	 */
	public function addIncompleteTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		parent::addIncompleteTest( $test, $e, $time );
		wfDebugLog(
			$this->logChannel,
			'Incomplete test ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e )
		);
	}

	/**
	 * Skipped test.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param Exception $e
	 * @param float $time
	 */
	public function addSkippedTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		parent::addSkippedTest( $test, $e, $time );
		wfDebugLog(
			$this->logChannel,
			'Skipped test ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e )
		);
	}

	/**
	 * A test suite started.
	 *
	 * @param PHPUnit_Framework_TestSuite $suite
	 */
	public function startTestSuite( PHPUnit_Framework_TestSuite $suite ) {
		parent::startTestSuite( $suite );
		wfDebugLog( $this->logChannel, 'START suite ' . $suite->getName() );
	}

	/**
	 * A test suite ended.
	 *
	 * @param PHPUnit_Framework_TestSuite $suite
	 */
	public function endTestSuite( PHPUnit_Framework_TestSuite $suite ) {
		parent::endTestSuite( $suite );
		wfDebugLog( $this->logChannel, 'END suite ' . $suite->getName() );
	}

	/**
	 * A test started.
	 *
	 * @param PHPUnit_Framework_Test $test
	 */
	public function startTest( PHPUnit_Framework_Test $test ) {
		parent::startTest( $test );
		wfDebugLog( $this->logChannel, 'Start test ' . $this->getTestName( $test ) );
	}

	/**
	 * A test ended.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param float $time
	 */
	public function endTest( PHPUnit_Framework_Test $test, $time ) {
		parent::endTest( $test, $time );
		wfDebugLog( $this->logChannel, 'End test ' . $this->getTestName( $test ) );
	}
}
