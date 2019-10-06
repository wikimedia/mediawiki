<?php

use PHPUnit\TextUI\TestRunner;

class MediaWikiTestRunner extends TestRunner {
	private $cliArgs;

	public function setMwCliArgs( array $cliArgs ) {
		$this->cliArgs = $cliArgs;
	}

	protected function createTestResult() {
		return new MediaWikiTestResult( $this->cliArgs );
	}
}
