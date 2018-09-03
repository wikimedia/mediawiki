<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {
	private $cliArgs;

	public function __construct( $ignorableOptions, $cliArgs ) {
		$ignore = function ( $arg ) {
		};
		foreach ( $ignorableOptions as $option ) {
			$this->longOptions[$option] = $ignore;
		}
		$this->cliArgs = $cliArgs;
	}

	protected function handleCustomTestSuite() {
		// Use our suite.xml
		if ( !isset( $this->arguments['configuration'] ) ) {
			$this->arguments['configuration'] = __DIR__ . '/suite.xml';
		}

		// Add our own listener
		$this->arguments['listeners'][] = new MediaWikiPHPUnitTestListener;
	}

	protected function createRunner() {
		$runner = new MediaWikiTestRunner;
		$runner->setMwCliArgs( $this->cliArgs );
		return $runner;
	}
}
