<?php

use PHPUnit\Framework\TestCase;

class PhpunitTestRecorder extends TestRecorder {
	/** @var TestCase */
	private $testCase;

	public function setTestCase( TestCase $testCase ) {
		$this->testCase = $testCase;
	}

	/**
	 * Mark a test skipped
	 * @param string $test
	 * @param string $reason
	 */
	public function skipped( $test, $reason ) {
		$this->testCase->markTestSkipped( "SKIPPED: $reason" );
	}
}
