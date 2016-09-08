<?php
/**
 * @file
 *
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 */

/**
 * Represent the result of a parser test.
 *
 * @since 1.22
 */
class ParserTestResult {
	/** The test info array */
	public $test;
	/** Text that was expected */
	public $expected;
	/** Actual text rendered */
	public $actual;

	/**
	 * @param array $test The test info array from TestIterator
	 * @param string $expected The normalized expected output
	 * @param string $actual The actual output
	 */
	public function __construct( $test, $expected, $actual ) {
		$this->test = $test;
		$this->expected = $expected;
		$this->actual = $actual;
	}

	/**
	 * Whether the test passed
	 * @return bool
	 */
	public function isSuccess() {
		return $this->expected === $this->actual;
	}

	public function getDescription() {
		return $this->test['desc'];
	}
}
