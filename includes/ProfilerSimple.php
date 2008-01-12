<?php

require_once(dirname(__FILE__).'/Profiler.php');

/**
 * Simple profiler base class.
 * @todo document methods (?)
 * @addtogroup Profiler
 */
class ProfilerSimple extends Profiler {
	var $mMinimumTime = 0;
	var $mProfileID = false;

	function __construct() {
		global $wgRequestTime;
		if (!empty($wgRequestTime)) {
			$this->mWorkStack[] = array( '-total', 0, $wgRequestTime);

			$elapsedreal = microtime(true) - $wgRequestTime;

			$entry =& $this->mCollated["-setup"];
			if (!is_array($entry)) {
				$entry = array('real' => 0.0, 'count' => 0);
				$this->mCollated["-setup"] =& $entry;
			}
			$entry['real'] += $elapsedreal;
			$entry['count']++;
		}
	}

	function setMinimum( $min ) {
		$this->mMinimumTime = $min;
	}

	function setProfileID( $id ) {
		$this->mProfileID = $id;
	}

	function getProfileID() {
		if ( $this->mProfileID === false ) {
			return wfWikiID();
		} else {
			return $this->mProfileID;
		}
	}

	function profileIn($functionname) {
		global $wgDebugFunctionEntry;
		if ($wgDebugFunctionEntry) {
			$this->debug(str_repeat(' ', count($this->mWorkStack)).'Entering '.$functionname."\n");
		}
		$this->mWorkStack[] = array($functionname, count( $this->mWorkStack));
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
			}
			elseif ($ofname != $functionname) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				$this->debug( "$message\n" );
			}
			$entry =& $this->mCollated[$functionname];
			$elapsedreal = microtime(true) - $ortime;
			if (!is_array($entry)) {
				$entry = array('real' => 0.0, 'count' => 0);
				$this->mCollated[$functionname] =& $entry;
			}
			$entry['real'] += $elapsedreal;
			$entry['count']++;

		}
	}

	function getFunctionReport() {
		/* Implement in output subclasses */
	}

	/* If argument is passed, it assumes that it is dual-format time string, returns proper float time value */
	function getTime($time=null) {
		if ($time==null)
			return microtime(true);
		list($a,$b)=explode(" ",$time);
		return (float)($a+$b);
	}

	function debug( $s ) {
		if (function_exists( 'wfDebug' ) ) {
			wfDebug( $s );
		}
	}
}

