<?php
/**
 * HIGHLY INCOMPLETE!
 *
 * @copyright Copyright © 2012-2013, Antoine Musso
 * @copyright Copyright © 2012-2013, Wikimedia Foundation Inc.
 * @file
 */

namespace MediaWiki\JUnit;

class Format {

	public static $fields = array(
		'test_total',
		'test_pass',
		'test_fail',
		'test_error',
		'test_skip',
		'started_at',
		'finished_at',
		'execution_time',
		'result_xml',
	);
}

class Recorder {

	protected $startTS;
	protected $endTS;
	protected $running = false;
	protected $results = array();

	function start() {
		$this->running = true;
		$this->startTS = microtime(true);
		$this->endTS   = null;
		return $this;
	}

	function stop() {
		$this->running = false;
		$this->endTS = microtime(true);
		return $this;
	}

	function getStartedAt() {
		return number_format( $this->startTS, 2 );
	}

	function getFinishedAt() {
		if( $this->running ) {
			throw new Exception( __METHOD__ . " called on an unfinished run\n" );
		}
		return number_format( $this->endTS, 2 );
	}

	function getExecutionTime() {
		if( $this->running ) {
			throw new Exception( __METHOD__ . " called on an unfinished run\n" );
		}
		return number_format( $this->endTS - $this->startTs, 2);
	}

	function addResult( $testName, $status = 'pass' ) {
		$this->results[$testName] = $status;
	}

	function dumpXML() {
		$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<testsuites>\n";
		$body = '';
		$footer = "</testsuites>\n";

		$stats = array_count_values( $this->results );
		$num_tests = array_sum( $stats );

		$body .= "\t<testsuite name=\"\" tests=\"$num_tests\" errors=\"{$stats['fail']}\">\n";

		foreach( $this->results as $testname => $status ) {
			$testname = str_replace( '"', '\"', $testname );

			if( $status === 'pass' ) {
				$body .= "\t\t<testcase name=\"$testname\" />\n";
				continue;
			}

			$body .= "\t\t<testcase name=\"$testname\">\n";
			$body .= "\t\t\t<failure />\n";
			$body .= "\t\t</testcase>\n";
		}

		$body .= "\t</testsuite>\n";

		print $header . $body . $footer;
	}
}
