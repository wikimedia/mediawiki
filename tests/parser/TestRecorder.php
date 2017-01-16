<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Testing
 */

/**
 * Interface to record parser test results.
 *
 * The TestRecorder is an class hierarchy to record the result of
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
	 */
	public function startTest( $test ) {
	}

	/**
	 * Called before starting an input file
	 */
	public function startSuite( $path ) {
	}

	/**
	 * Called after ending an input file
	 */
	public function endSuite( $path ) {
	}

	/**
	 * Called after each test
	 * @param array $test
	 * @param ParserTestResult $result
	 */
	public function record( $test, ParserTestResult $result ) {
	}

	/**
	 * Show a warning to the user
	 */
	public function warning( $message ) {
	}

	/**
	 * Mark a test skipped
	 */
	public function skipped( $test, $subtest ) {
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
