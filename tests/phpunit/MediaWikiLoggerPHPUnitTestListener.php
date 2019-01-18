<?php

use Mediawiki\Logger\LoggerFactory;
use Mediawiki\Logger\Spi;
use Mediawiki\Logger\LogCapturingSpi;

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
	/** @var array|null */
	private $lastTestLogs;

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

	/**
	 * A test ended.
	 *
	 * @param PHPUnit_Framework_Test $test
	 * @param float $time
	 */
	public function endTest( PHPUnit_Framework_Test $test, $time ) {
		$this->lastTestLogs = $this->spi->getLogs();
		LoggerFactory::registerProvider( $this->originalSpi );
		$this->originalSpi = null;
		$this->spi = null;
	}

	/**
	 * Get string formatted logs generated during the last
	 * test to execute.
	 *
	 * @return string
	 */
	public function getLog() {
		$logs = $this->lastTestLogs;
		if ( !$logs ) {
			return '';
		}
		$message = [];
		foreach ( $logs as $log ) {
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
