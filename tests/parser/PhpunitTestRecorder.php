<?php

class PhpunitTestRecorder extends TestRecorder {
	private $testCase;

	public function setTestCase( PHPUnit_Framework_TestCase $testCase ) {
		$this->testCase = $testCase;
	}

	/**
	 * Mark a test skipped
	 */
	public function skipped( $test, $reason ) {
		$this->testCase->markTestSkipped( "SKIPPED: $reason" );
	}
}
