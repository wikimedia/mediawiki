<?php
class MediaWikiPHPUnitTestListener implements PHPUnit_Framework_TestListener {

	/**
	 * @var string
	 */
	protected $logChannel;

	public function __construct( $logChannel ) {
		$this->logChannel = $logChannel;
	}

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
	 * @param  PHPUnit_Framework_Test $test
	 * @param  Exception              $e
	 * @param  float                  $time
	 */
	public function addError( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		wfDebugLog( $this->logChannel, 'ERROR in ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e ) );
	}

	/**
	 * A failure occurred.
	 *
	 * @param  PHPUnit_Framework_Test                 $test
	 * @param  PHPUnit_Framework_AssertionFailedError $e
	 * @param  float                                  $time
	 */
	public function addFailure( PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time ) {
		wfDebugLog( $this->logChannel, 'FAILURE in ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e ) );
	}

	/**
	 * Incomplete test.
	 *
	 * @param  PHPUnit_Framework_Test $test
	 * @param  Exception              $e
	 * @param  float                  $time
	 */
	public function addIncompleteTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		wfDebugLog( $this->logChannel, 'Incomplete test ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e ) );
	}

	/**
	 * Skipped test.
	 *
	 * @param  PHPUnit_Framework_Test $test
	 * @param  Exception              $e
	 * @param  float                  $time
	 *
	 * @since  Method available since Release 3.0.0
	 */
	public function addSkippedTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		wfDebugLog( $this->logChannel, 'Skipped test ' . $this->getTestName( $test ) . ': ' . $this->getErrorName( $e ) );
	}

	/**
	 * A test suite started.
	 *
	 * @param  PHPUnit_Framework_TestSuite $suite
	 * @since  Method available since Release 2.2.0
	 */
	public function startTestSuite( PHPUnit_Framework_TestSuite $suite ) {
		wfDebugLog( $this->logChannel, 'START suite ' . $suite->getName() );
	}

	/**
	 * A test suite ended.
	 *
	 * @param  PHPUnit_Framework_TestSuite $suite
	 * @since  Method available since Release 2.2.0
	 */
	public function endTestSuite( PHPUnit_Framework_TestSuite $suite ) {
		wfDebugLog( $this->logChannel, 'END suite ' . $suite->getName() );
	}

	/**
	 * A test started.
	 *
	 * @param  PHPUnit_Framework_Test $test
	 */
	public function startTest( PHPUnit_Framework_Test $test ) {
		wfDebugLog( $this->logChannel, 'Start test ' . $this->getTestName( $test ) );
	}

	/**
	 * A test ended.
	 *
	 * @param  PHPUnit_Framework_Test $test
	 * @param  float                  $time
	 */
	public function endTest( PHPUnit_Framework_Test $test, $time ) {
		wfDebugLog( $this->logChannel, 'End test ' . $this->getTestName( $test ) );
	}
}
