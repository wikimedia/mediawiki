<?php

class PHPUnitParserTest extends ParserTest {
	function showTesting( $desc ) {
		if( MediaWikiPHPUnitCommand::$additionalArgs['verbose'] ) parent::showTesting( $desc );
		/* Do nothing since we don't want to show info during PHPUnit testing. */
	}

	public function showSuccess( $desc ) {
		PHPUnit_Framework_Assert::assertTrue( true, $desc );
		if( MediaWikiPHPUnitCommand::$additionalArgs['verbose'] ) parent::showSuccess( $desc );
		return true;
	}

	public function showFailure( $desc, $expected, $got ) {
		PHPUnit_Framework_Assert::assertEquals( $expected, $got, $desc );
		if( MediaWikiPHPUnitCommand::$additionalArgs['verbose'] ) parent::showFailure( $desc, $expected, $got );
		return false;
	}
	
	public function setupRecorder( $options ) {
		$this->recorder = new PHPUnitTestRecorder( $this );
	}
}

class ParserUnitTest extends MediaWikiTestCase {
	private $test = "";

	public function __construct( $suite, $test = null ) {
		parent::__construct();
		$this->test = $test;
		$this->suite = $suite;
	}

	function count() { return 1; }

	public function run( PHPUnit_Framework_TestResult $result = null ) {
		PHPUnit_Framework_Assert::resetCount();
		if ( $result === NULL ) {
			$result = new PHPUnit_Framework_TestResult;
		}

		$this->suite->publishTestArticles(); // Add articles needed by the tests.
		$backend = new ParserTestSuiteBackend;
		$result->startTest( $this );

		// Support the transition to PHPUnit 3.5 where PHPUnit_Util_Timer is replaced with PHP_Timer
		if ( class_exists( 'PHP_Timer' ) ) {
			PHP_Timer::start();
		} else {
			PHPUnit_Util_Timer::start();
		}

		$r = false;
		try {
			# Run the test.
			# On failure, the subclassed backend will throw an exception with
			# the details.
			$pt = new PHPUnitParserTest;
			$r =  $pt->runTest( $this->test['test'], $this->test['input'],
				$this->test['result'], $this->test['options'], $this->test['config']
			);
		}
		catch ( PHPUnit_Framework_AssertionFailedError $e ) {

			// PHPUnit_Util_Timer -> PHP_Timer support (see above)
			if ( class_exists( 'PHP_Timer' ) ) {
				$result->addFailure( $this, $e, PHP_Timer::stop() );
			} else {
				$result->addFailure( $this, $e, PHPUnit_Util_Timer::stop() );
			}
		}
		catch ( Exception $e ) {
			// PHPUnit_Util_Timer -> PHP_Timer support (see above)
			if ( class_exists( 'PHP_Timer' ) ) {
				$result->addFailure( $this, $e, PHP_Timer::stop() );
			} else {
				$result->addFailure( $this, $e, PHPUnit_Util_Timer::stop() );
			}
		}

		// PHPUnit_Util_Timer -> PHP_Timer support (see above)
		if ( class_exists( 'PHP_Timer' ) ) {
			$result->endTest( $this, PHP_Timer::stop() );
		} else {
			$result->endTest( $this, PHPUnit_Util_Timer::stop() );
		}

		$backend->recorder->record( $this->test['test'], $r );
		$this->addToAssertionCount( PHPUnit_Framework_Assert::getCount() );

		return $result;
	}

	public function toString() {
		return $this->test['test'];
	}

}

class ParserTestSuiteBackend extends PHPUnit_FrameWork_TestSuite {
	public $recorder;
	public $term;
	static $usePHPUnit = false;

	function __construct() {
		parent::__construct();
		$this->setupRecorder(null);
		self::$usePHPUnit = method_exists('PHPUnit_Framework_Assert', 'assertEquals');
	}

	function showTesting( $desc ) {
	}

	function showRunFile( $path ) {
	}

	function showTestResult( $desc, $result, $out ) {
		if ( $result === $out ) {
			return self::showSuccess( $desc, $result, $out );
		} else {
			return self::showFailure( $desc, $result, $out );
	}
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
