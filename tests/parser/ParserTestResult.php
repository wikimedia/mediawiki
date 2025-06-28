<?php
/**
 * @file
 *
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 */
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;

/**
 * Represent the result of a parser test.
 *
 * @since 1.22
 */
class ParserTestResult {
	/** @var ParserTest The test info */
	public $test;
	/** @var ParserTestMode The test mode */
	public $mode;
	/** @var string Text that was expected */
	public $expected;
	/** @var string Actual text rendered */
	public $actual;

	/**
	 * @param ParserTest $test The test info class
	 * @param ParserTestMode $mode The test mode
	 * @param string $expected The normalized expected output
	 * @param string $actual The actual output
	 */
	public function __construct( $test, $mode, $expected, $actual ) {
		$this->test = $test;
		$this->mode = $mode;
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

	public function getDescription(): string {
		return "{$this->test->testName} [{$this->mode}]";
	}
}
