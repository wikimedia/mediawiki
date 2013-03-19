<?php
/**
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @license GNU GPL v2
 *
 * @file
 */

/**
 * Represent the result of a parser test.
 *
 * @since 1.21
 */
class ParserTestResult {
	/** Name of the parser test */
	public $description;
	/** Text that was expected */
	public $expected;
	/** Actual text rendered */
	public $actual;

	/**
	 * @param $description string The test has described in the .txt file
	 */
	public function __construct( $description ) {
		$this->description = $description;
	}

	/** Whether the test passed */
	public function isSuccess() {
		return ($this->expected === $this->actual);
	}
}
