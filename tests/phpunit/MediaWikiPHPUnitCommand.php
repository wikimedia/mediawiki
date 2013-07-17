<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {

	public static $additionalOptions = array(
		'regex=' => false,
		'file=' => false,
		'use-filebackend=' => false,
		'use-bagostuff=' => false,
		'use-jobqueue=' => false,
		'keep-uploads' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
		'wiki=' => false,
	);

	public function __construct() {
		foreach ( self::$additionalOptions as $option => $default ) {
			$this->longOptions[$option] = $option . 'Handler';
		}

		if ( !isset( $this->arguments['listeners'] ) ) {
			$this->arguments['listeners'] = array();
		}

		$this->arguments['listeners'][] = new MediaWikiPHPUnitTestListener();
	}

	public static function main( $exit = true ) {
		$command = new self;

		if ( wfIsWindows() ) {
			# Windows does not come anymore with ANSI.SYS loaded by default
			# PHPUnit uses the suite.xml parameters to enable/disable colors
			# which can be then forced to be enabled with --colors.
			# The below code inject a parameter just like if the user called
			# phpunit with a --no-color option (which does not exist). It
			# overrides the suite.xml setting.
			# Probably fix bug 29226
			$command->arguments['colors'] = false;
		}

		# Makes MediaWiki PHPUnit directory includable so the PHPUnit will
		# be able to resolve relative files inclusion such as suites/*
		# PHPUnit uses stream_resolve_include_path() internally
		# See bug 32022
		set_include_path(
			__DIR__
				. PATH_SEPARATOR
				. get_include_path()
		);

		$command->run( $_SERVER['argv'], $exit );
	}

	public function __call( $func, $args ) {

		if ( substr( $func, -7 ) == 'Handler' ) {
			if ( is_null( $args[0] ) ) {
				$args[0] = true;
			} //Booleans
			self::$additionalOptions[substr( $func, 0, -7 )] = $args[0];
		}
	}

	public function run( array $argv, $exit = true ) {
		wfProfileIn( __METHOD__ );

		$ret = parent::run( $argv, false );

		wfProfileOut( __METHOD__ );

		// Return to real wiki db, so profiling data is preserved
		MediaWikiTestCase::teardownTestDB();

		// Log profiling data, e.g. in the database or UDP
		wfLogProfilingData();

		if ( $exit ) {
			exit( $ret );
		} else {
			return $ret;
		}
	}

	public function showHelp() {
		parent::showHelp();

		print <<<EOT

ParserTest-specific options:

  --regex="<regex>"        Only run parser tests that match the given regex
  --file="<filename>"      File describing parser tests
  --keep-uploads           Re-use the same upload directory for each test, don't delete it


Database options:
  --use-normal-tables      Use normal DB tables.
  --reuse-db               Init DB only if tables are missing and keep after finish.


EOT;
	}
}

class MediaWikiPHPUnitTestListener implements PHPUnit_Framework_TestListener {

	/**
	 * @var string
	 */
	protected $logChannel;

	public function __construct( $logChannel = 'PHPUnitCommand' ) {
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
	}}