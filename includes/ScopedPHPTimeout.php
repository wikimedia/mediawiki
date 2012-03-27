<?php

/**
 * Class to expand PHP execution time for a function call.
 * On construction, set_time_limit() is called and set to $seconds.
 * When the object goes out of scope, the timer is restarted, with
 * the original time limit minus the time the object existed.
 */
class ScopedPHPTimeout {
	protected $startTime; // float; seconds
	protected $oldTimeout; // integer; seconds

	protected static $stackDepth = 0; // integer
	protected static $totalCalls = 0; // integer
	protected static $totalElapsed = 0; // float; seconds

	/* Prevent callers in infinite loops from running forever */
	const MAX_TOTAL_CALLS = 1000000;
	const MAX_TOTAL_TIME = 300; // seconds

	/**
	 * @param $seconds integer
	 */
	public function __construct( $seconds ) {
		if ( ini_get( 'max_execution_time' ) > 0 ) { // CLI uses 0
			if ( self::$totalCalls >= self::MAX_TOTAL_CALLS ) {
				trigger_error( "Maximum invocations of " . __CLASS__ . " exceeded." );
			} elseif ( self::$totalElapsed >= self::MAX_TOTAL_TIME ) {
				trigger_error( "Time limit within invocations of " . __CLASS__ . " exceeded." );
			} elseif ( self::$stackDepth > 0 ) { // recursion guard
				trigger_error( "Resursive invocation of " . __CLASS__ . " attempted." );
			} else {
				$this->oldTimeout = ini_set( 'max_execution_time', $seconds );
				$this->startTime = microtime( true );
				++self::$stackDepth;
				++self::$totalCalls; // proof against < 1us scopes
			}
		}
	}

	/**
	 * Restore the original timeout.
	 * This does not account for the timer value on __construct().
	 */
	public function __destruct() {
		if ( $this->oldTimeout ) {
			$elapsed = microtime( true ) - $this->startTime;
			// Note: a limit of 0 is treated as "forever"
			set_time_limit( max( 1, $this->oldTimeout - (int)$elapsed ) );
			// If each scoped timeout is for less than one second, we end up
			// restoring the original timeout without any decrease in value.
			// Thus web scripts in an infinite loop can run forever unless we
			// take some measures to prevent this. Track total time and calls.
			self::$totalElapsed += $elapsed;
			--self::$stackDepth;
		}
	}
}
