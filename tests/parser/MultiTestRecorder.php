<?php

use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;

/**
 * This is a TestRecorder representing a collection of other TestRecorders.
 * It proxies calls to all constituent objects.
 */
class MultiTestRecorder extends TestRecorder {
	/** @var TestRecorder[] */
	private $recorders = [];

	public function addRecorder( TestRecorder $recorder ) {
		$this->recorders[] = $recorder;
	}

	private function proxy( $funcName, $args ) {
		foreach ( $this->recorders as $recorder ) {
			$recorder->$funcName( ...$args );
		}
	}

	public function start() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function startTest( ParserTest $test, ParserTestMode $mode ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function startSuite( string $path ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function endSuite( string $path ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function record( ParserTestResult $result ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function warning( string $message ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function skipped( ParserTest $test, ParserTestMode $mode, string $reason ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function report() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function end() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}
}
