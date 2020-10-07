<?php

use MediaWiki\Logger\LogCapturingSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\Spi;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\Warning;

/**
 * Replaces the logging SPI on each test run. This allows
 * another component (the printer) to fetch the logs when
 * reporting why a test failed.
 */
class MediaWikiLoggerPHPUnitTestListener implements TestListener {
	use TestListenerDefaultImplementation;

	/** @var Spi|null */
	private $originalSpi;
	/** @var Spi|null */
	private $spi;

	/**
	 * A test started.
	 *
	 * @param Test $test
	 */
	public function startTest( Test $test ) : void {
		$this->lastTestLogs = null;
		$this->originalSpi = LoggerFactory::getProvider();
		$this->spi = new LogCapturingSpi( $this->originalSpi );
		LoggerFactory::registerProvider( $this->spi );
	}

	public function addRiskyTest( Test $test, Throwable $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	public function addIncompleteTest( Test $test, Throwable $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	public function addSkippedTest( Test $test, Throwable $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	public function addError( Test $test, Throwable $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	public function addWarning( Test $test, Warning $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	public function addFailure( Test $test, AssertionFailedError $e, $time ) : void {
		$this->augmentTestWithLogs( $test );
	}

	private function augmentTestWithLogs( Test $test ) {
		if ( $this->spi ) {
			$logs = $this->spi->getLogs();
			$formatted = $this->formatLogs( $logs );
			$test->_formattedMediaWikiLogs = $formatted;
		}
	}

	/**
	 * A test ended.
	 *
	 * @param Test $test
	 * @param float $time
	 */
	public function endTest( Test $test, $time ) : void {
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
