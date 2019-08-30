<?php

// phpcs:ignoreFile

define( 'EXCIMER_REAL', 0 );
define( 'EXCIMER_CPU', 1 );

class ExcimerProfiler {
	public function __construct() {
	}
	public function setPeriod( $period ) {
	}
	public function setEventType( $event_type ) {
	}
	public function setMaxDepth( $maxDepth ) {
	}
	public function setFlushCallback( $callback, $max_samples ) {
	}
	public function clearFlushCallback() {
	}
	public function start() {
	}
	public function stop() {
	}
	public function getLog() : ExcimerLog {
	}
	public function flush() {
	}
}

class ExcimerLog {
	private final function __construct() {
	}
	function formatCollapsed() {
	}
	/**
	 * @return array[]
	 */
	function aggregateByFunction() {
	}
	/**
	 * @return int
	 */
	function getEventCount() {
	}
	function current() {
	}
	function key() {
	}
	function next() {
	}
	function rewind() {
	}
	function valid() {
	}
	function count() {
	}
	function offsetExists( $offset ) {
	}
	function offsetGet( $offset ) {
	}
	function offsetSet( $offset, $value ) {
	}
	function offsetUnset( $offset ) {
	}

}

class ExcimerLogEntry {
	private final function __construct() {
	}
	function getTimestamp() {
	}
	function getEventCount() {
	}
	function getTrace() {
	}
}

class ExcimerTimer {
	function setEventType( $event_type ) {
	}
	function setInterval( $interval ) {
	}
	function setPeriod( $period ) {
	}
	function setCallback( $callback ) {
	}
	function start() {
	}
	function stop() {
	}
	function getTime() {
	}
}
