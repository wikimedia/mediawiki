<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\Spi;
use MediaWiki\Logger\LogCapturingSpi;

/**
 * Replaces the logging SPI on each test run. This allows
 * another component (the printer) to fetch the logs when
 * reporting why a test failed.
 */
class MediaWikiLoggerPHPUnitTestListener extends PHPUnit_Framework_BaseTestListener {
	/** @var Spi|null */
	private $originalSpi;
	/** @var Spi|null */
	private $spi;

	/**
	 * A test started.
	 *
	 * @param PHPUnit_Framework_Test $test
	 */
	public function startTest( PHPUnit_Framework_Test $test ) {
		$this->lastTestLogs = null;
		$this->originalSpi = LoggerFactory::getProvider();
		$this->spi = new LogCapturingSpi( $this->originalSpi );
		LoggerFactory::registerProvider( $this->spi );
	}

	public function addRiskyTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		$this->augmentTestWithLogs( $test );
	}

	public function addIncompleteTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		$this->augmentTestWithLogs( $test );
	}

	public function addSkippedTest( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		$this->augmentTestWithLogs( $test );
	}

	public function addError( PHPUnit_Framework_Test $test, Exception $e, $time ) {
		$this->augmentTestWithLogs( $test );
	}

	public function addWarning( PHPUnit_Framework_Test $test, PHPUnit\Framework\Warning $e, $time ) {
		$this->augmentTestWithLogs( $test );
	}

	public function addFailure( PHPUnit_Framework_Test $test,
		PHPUnit_Framework_AssertionFailedError $e, $time
	) {
		$this->augmentTestWithLogs( $test );
	}

	private function augmentTestWithLogs( PHPUnit_Framework_Test $test ) {
		if ( $this->spi ) {
			$logs = $this->spi->getLogs();
			$formatted = $this->formatLogs( $logs );
			$test->_formattedMediaWikiLogs = $formatted;
		}
	}

	/**
	 * A test ended.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param float $time
	 */
	public function endTest( PHPUnit_Framework_Test $test, $time ) {
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
