<?php
namespace JUnit;

class Format {

	static $fields = array(
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
}

class TestSuites {
}

class TestSuite {
	protected $name;
	protected $results;

	function setName( $name ) {
		$this->name = $name;
	}
	function addResult( \JUnit\TestResult $result ) {
		$this->results[] = $result;
	}
}

class XML {
	function convert( \JUnit\TestSuites $testSuites ) {
		$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	}

}
