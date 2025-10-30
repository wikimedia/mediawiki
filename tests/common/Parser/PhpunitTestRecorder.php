<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;

class PhpunitTestRecorder extends TestRecorder {
	/** @var TestCase */
	private $testCase;

	public function setTestCase( TestCase $testCase ) {
		$this->testCase = $testCase;
	}

	/**
	 * Mark a test skipped
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @param string $reason
	 */
	public function skipped( ParserTest $test, ParserTestMode $mode, string $reason ) {
		$this->testCase->markTestSkipped( "SKIPPED: $reason" );
	}
}
