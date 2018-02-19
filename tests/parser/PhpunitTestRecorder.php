<?php

class PhpunitTestRecorder extends TestRecorder {
	private $testCase;

	public function setTestCase( PHPUnit\Framework\TestCase $testCase ) {
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
