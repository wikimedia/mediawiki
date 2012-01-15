<?php
/**
 * @file
 * @ingroup Profiler
 */

/**
 * Simple profiler base class.
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimple extends Profiler {
	var $mMinimumTime = 0;

	var $zeroEntry = array('cpu'=> 0.0, 'cpu_sq' => 0.0, 'real' => 0.0, 'real_sq' => 0.0, 'count' => 0);
	var $errorEntry;

	function __construct( $params ) {
		global $wgRequestTime, $wgRUstart;
		parent::__construct( $params );

		$this->errorEntry = $this->zeroEntry;
		$this->errorEntry['count'] = 1;

		if (!empty($wgRequestTime) && !empty($wgRUstart)) {
			# Remove the -total entry from parent::__construct
			$this->mWorkStack = array();

			$this->mWorkStack[] = array( '-total', 0, $wgRequestTime,$this->getCpuTime($wgRUstart));

			$elapsedcpu = $this->getCpuTime() - $this->getCpuTime($wgRUstart);
			$elapsedreal = microtime(true) - $wgRequestTime;

			$entry =& $this->mCollated["-setup"];
			if (!is_array($entry)) {
				$entry = $this->zeroEntry;
				$this->mCollated["-setup"] =& $entry;
			}
			$entry['cpu'] += $elapsedcpu;
			$entry['cpu_sq'] += $elapsedcpu*$elapsedcpu;
			$entry['real'] += $elapsedreal;
			$entry['real_sq'] += $elapsedreal*$elapsedreal;
			$entry['count']++;
		}
	}

	function setMinimum( $min ) {
		$this->mMinimumTime = $min;
	}

	function profileIn($functionname) {
		global $wgDebugFunctionEntry;
		if ($wgDebugFunctionEntry) {
			$this->debug(str_repeat(' ', count($this->mWorkStack)).'Entering '.$functionname."\n");
		}
		$this->mWorkStack[] = array($functionname, count( $this->mWorkStack ), microtime(true), $this->getCpuTime());
	}

	function profileOut($functionname) {
		global $wgDebugFunctionEntry;

		if ($wgDebugFunctionEntry) {
			$this->debug(str_repeat(' ', count($this->mWorkStack) - 1).'Exiting '.$functionname."\n");
		}

		list($ofname, /* $ocount */ ,$ortime,$octime) = array_pop($this->mWorkStack);

		if (!$ofname) {
			$this->debug("Profiling error: $functionname\n");
		} else {
			if ($functionname == 'close') {
				$message = "Profile section ended by close(): {$ofname}";
				$functionname = $ofname;
				$this->debug( "$message\n" );
				$this->mCollated[$message] = $this->errorEntry;
			}
			elseif ($ofname != $functionname) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				$this->debug( "$message\n" );
				$this->mCollated[$message] = $this->errorEntry;
			}
			$entry =& $this->mCollated[$functionname];
			$elapsedcpu = $this->getCpuTime() - $octime;
			$elapsedreal = microtime(true) - $ortime;
			if (!is_array($entry)) {
				$entry = $this->zeroEntry;
				$this->mCollated[$functionname] =& $entry;
			}
			$entry['cpu'] += $elapsedcpu;
			$entry['cpu_sq'] += $elapsedcpu*$elapsedcpu;
			$entry['real'] += $elapsedreal;
			$entry['real_sq'] += $elapsedreal*$elapsedreal;
			$entry['count']++;

		}
	}

	public function getFunctionReport() {
		/* Implement in output subclasses */
		return '';
	}

	public function logData() {
		/* Implement in subclasses */
	}

	function getCpuTime($ru=null) {
		if ( function_exists( 'getrusage' ) ) {
			if ( $ru == null ) {
				$ru = getrusage();
			}
			return ($ru['ru_utime.tv_sec'] + $ru['ru_stime.tv_sec'] + ($ru['ru_utime.tv_usec'] +
				$ru['ru_stime.tv_usec']) * 1e-6);
		} else {
			return 0;
		}
	}
}
