<?php

/**
 * This is a TestRecorder representing a collection of other TestRecorders.
 * It proxies calls to all constituent objects.
 */
class MultiTestRecorder extends TestRecorder {
	private $recorders = [];

	public function addRecorder( TestRecorder $recorder ) {
		$this->recorders[] = $recorder;
	}

	private function proxy( $funcName, $args ) {
		foreach ( $this->recorders as $recorder ) {
			call_user_func_array( [ $recorder, $funcName ], $args );
		}
	}

	public function start() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function startTest( $test ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function startSuite( $path ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function endSuite( $path ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function record( $test, ParserTestResult $result ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function warning( $message ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function skipped( $test, $subtest ) {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function report() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}

	public function end() {
		$this->proxy( __FUNCTION__, func_get_args() );
	}
}
