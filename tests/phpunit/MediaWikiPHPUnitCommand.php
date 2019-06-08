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

		// Add our own listeners
		$this->arguments['listeners'][] = new MediaWikiPHPUnitTestListener;
		$this->arguments['listeners'][] = new MediaWikiLoggerPHPUnitTestListener;

		// Output only to stderr to avoid "Headers already sent" problems
		$this->arguments['stderr'] = true;

		// Use a custom result printer that includes per-test logging output
		// when nothing is provided.
		if ( !isset( $this->arguments['printer'] ) ) {
			$this->arguments['printer'] = MediaWikiPHPUnitResultPrinter::class;
		}
	}

	protected function createRunner() {
		$runner = new MediaWikiTestRunner;
		$runner->setMwCliArgs( $this->cliArgs );
		return $runner;
	}
}
