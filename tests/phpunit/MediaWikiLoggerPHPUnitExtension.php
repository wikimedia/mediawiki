<?php

use MediaWiki\Logger\LogCapturingSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\Spi;
use PHPUnit\Runner\AfterIncompleteTestHook;
use PHPUnit\Runner\AfterRiskyTestHook;
use PHPUnit\Runner\AfterSkippedTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\AfterTestWarningHook;
use PHPUnit\Runner\BeforeTestHook;

/**
 * Replaces the logging SPI on each test run. This allows
 * another component (the printer) to fetch the logs when
 * reporting why a test failed.
 */
class MediaWikiLoggerPHPUnitExtension implements
	BeforeTestHook,
	AfterRiskyTestHook,
	AfterIncompleteTestHook,
	AfterSkippedTestHook,
	AfterTestErrorHook,
	AfterTestWarningHook,
	AfterTestFailureHook,
	AfterTestHook
{
	/**
	 * @var string[]
	 * @todo Can we avoid global state?
	 */
	public static $testsCollection;
	/** @var Spi|null */
	private $originalSpi;
	/** @var Spi|null */
	private $spi;

	/**
	 * @inheritDoc
	 */
	public function executeBeforeTest( string $test ): void {
		$this->lastTestLogs = null;
		$this->originalSpi = LoggerFactory::getProvider();
		$this->spi = new LogCapturingSpi( $this->originalSpi );
		LoggerFactory::registerProvider( $this->spi );
	}

	/** @inheritDoc */
	public function executeAfterRiskyTest( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	/** @inheritDoc */
	public function executeAfterIncompleteTest( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	/** @inheritDoc */
	public function executeAfterSkippedTest( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	/** @inheritDoc */
	public function executeAfterTestError( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	/** @inheritDoc */
	public function executeAfterTestWarning( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	/** @inheritDoc */
	public function executeAfterTestFailure( string $test, string $message, float $time ): void {
		$this->augmentTestWithLogs( $test );
	}

	private function augmentTestWithLogs( string $test ) {
		if ( $this->spi ) {
			$logs = $this->spi->getLogs();
			$formatted = $this->formatLogs( $logs );
			self::$testsCollection[$test] = $formatted;
		}
	}

	/** @inheritDoc */
	public function executeAfterTest( string $test, float $time ): void {
		LoggerFactory::registerProvider( $this->originalSpi );
		$this->originalSpi = null;
		$this->spi = null;
	}

	/**
	 * Get string formatted logs generated during the last
	 * test to execute.
	 *
	 * @param array $logs
	 * @return string
	 */
	private function formatLogs( array $logs ) {
		$message = [];
		foreach ( $logs as $log ) {
			if ( $log['channel'] === 'PHPUnitCommand' ) {
				// Don't print the log of PHPUnit events while running PHPUnit,
				// because PHPUnit is already printing those already.
				continue;
			}
			$message[] = sprintf(
				'[%s] [%s] %s %s',
				$log['channel'],
				$log['level'],
				$log['message'],
				json_encode( $log['context'] )
			);
		}
		return implode( "\n", $message );
	}

}
