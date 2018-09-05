<?php

class MediaWikiTestRunner extends PHPUnit_TextUI_TestRunner {
	private $cliArgs;

	public function setMwCliArgs( array $cliArgs ) {
		$this->cliArgs = $cliArgs;
	}

	protected function createTestResult() {
		return new MediaWikiTestResult( $this->cliArgs );
	}
}
