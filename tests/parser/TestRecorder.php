<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Testing
 */

use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;

/**
 * Interface to record parser test results.
 *
 * The TestRecorder is a class hierarchy to record the result of
 * MediaWiki parser tests. One should call start() before running the
 * full parser tests and end() once all the tests have been finished.
 * After each test, you should use record() to keep track of your tests
 * results. Finally, report() is used to generate a summary of your
 * test run, one could dump it to the console for human consumption or
 * register the result in a database for tracking purposes.
 *
 * @since 1.22
 */
class TestRecorder {

	/**
	 * Called at beginning of the parser test run
	 */
	public function start() {
	}

	/**
	 * Called before starting a test
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 */
	public function startTest( ParserTest $test, ParserTestMode $mode ) {
	}

	/**
	 * Called before starting an input file
	 */
	public function startSuite( string $path ) {
	}

	/**
	 * Called after ending an input file
	 */
	public function endSuite( string $path ) {
	}

	/**
	 * Called after each test
	 */
	public function record( ParserTestResult $result ) {
	}

	/**
	 * Show a warning to the user
	 */
	public function warning( string $message ) {
	}

	/**
	 * Mark a test skipped
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @param string $reason
	 */
	public function skipped( ParserTest $test, ParserTestMode $mode, string $reason ) {
	}

	/**
	 * Called before finishing the test run
	 */
	public function report() {
	}

	/**
	 * Called at the end of the parser test run
	 */
	public function end() {
	}

}
