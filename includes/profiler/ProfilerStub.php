<?php
/**
 * Stub profiling functions
 * @file
 * @ingroup Profiler
 */
class ProfilerStub extends Profiler {
	public function isStub() {
		return true;
	}
	public function profileIn( $fn ) {}
	public function profileOut( $fn ) {}
	public function getOutput() {}
	public function close() {}
}
