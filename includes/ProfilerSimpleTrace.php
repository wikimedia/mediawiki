<?php
/**
 * @file
 * @ingroup Profiler
 */

if ( !class_exists( 'ProfilerSimple' ) ) {
	require_once(dirname(__FILE__).'/ProfilerSimple.php');
}

/**
 * Execution trace
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimpleTrace extends ProfilerSimple {
	var $mMinimumTime = 0;
	var $mProfileID = false;
	var $trace = "";
	var $memory = 0;

	function __construct() {
		global $wgRequestTime, $wgRUstart;
		if (!empty($wgRequestTime) && !empty($wgRUstart)) {
			$this->mWorkStack[] = array( '-total', 0, $wgRequestTime,$this->getCpuTime($wgRUstart));
			$elapsedcpu = $this->getCpuTime() - $this->getCpuTime($wgRUstart);
			$elapsedreal = microtime(true) - $wgRequestTime;
		}
		$this->trace .= "Beginning trace: \n";
	}

	function profileIn($functionname) {
		global $wgDebugFunctionEntry;
		$this->mWorkStack[] = array($functionname, count( $this->mWorkStack ), microtime(true), $this->getCpuTime());
		$this->trace .= "         " . sprintf("%6.1f",$this->memoryDiff()) . str_repeat( " ", count($this->mWorkStack)) . " > " . $functionname . "\n";
	}

	function profileOut($functionname) {
		global $wgDebugFunctionEntry;

		if ($wgDebugFunctionEntry) {
			$this->debug(str_repeat(' ', count($this->mWorkStack) - 1).'Exiting '.$functionname."\n");
		}

		list($ofname, /* $ocount */ ,$ortime,$octime) = array_pop($this->mWorkStack);

		if (!$ofname) {
			$this->trace .= "Profiling error: $functionname\n";
		} else {
			if ($functionname == 'close') {
				$message = "Profile section ended by close(): {$ofname}";
				$functionname = $ofname;
				$this->trace .= $message . "\n";
			}
			elseif ($ofname != $functionname) {
				$self->trace .= "Profiling error: in({$ofname}), out($functionname)";
			}
			$elapsedcpu = $this->getCpuTime() - $octime;
			$elapsedreal = microtime(true) - $ortime;
			$this->trace .= sprintf("%03.6f %6.1f",$elapsedreal,$this->memoryDiff()) .  str_repeat(" ",count($this->mWorkStack)+1) . " < " . $functionname . "\n";
		}
	}
	
	function memoryDiff() {
		$diff = memory_get_usage() - $this->memory;
		$this->memory = memory_get_usage();
		return $diff/1024;
	}

	function getOutput() {
		print "<!-- \n {$this->trace} \n -->";
	}
}
