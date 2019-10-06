<?php

use PHPUnit\Framework\TestResult;

class MediaWikiTestResult extends TestResult {
	private $cliArgs;

	public function __construct( array $cliArgs ) {
		$this->cliArgs = $cliArgs;
	}

	/**
	 * Get the command-line arguments from phpunit.php
	 * @return array
	 */
	public function getMediaWikiCliArgs() {
		return $this->cliArgs;
	}
}
