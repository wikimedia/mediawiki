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
	/**
	 * Description of the parser test.
	 *
	 * This is usually the text used to describe a parser test in the .txt
	 * files.  It is initialized on a construction and you most probably
	 * never want to change it.
	 */
	public $description;
	/** Text that was expected */
	public $expected;
	/** Actual text rendered */
	public $actual;

	/**
	 * @param string $description A short text describing the parser test
	 *   usually the text in the parser test .txt file.  The description
	 *   is later available using the property $description.
	 */
	public function __construct( $description ) {
		$this->description = $description;
	}

	/**
	 * Whether the test passed
	 * @return bool
	 */
	public function isSuccess() {
		return $this->expected === $this->actual;
	}
}
