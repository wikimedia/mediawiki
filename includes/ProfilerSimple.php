<?php 
/**
 * Simple profiler base class
 * @package MediaWiki
 */

/**
 * @todo document
 * @package MediaWiki
 */
require_once('Profiling.php');

class ProfilerSimple extends Profiler {
	function ProfilerSimple() {
		global $wgRequestTime,$wgRUstart;
		if (!empty($wgRequestTime) && !empty($wgRUstart)) {
			$this->mWorkStack[] = array( '-total', 0, $this->getTime($wgRequestTime),$this->getCpuTime($wgRUstart));

			$elapsedcpu = $this->getCpuTime() - $this->getCpuTime($wgRUstart);
			$elapsedreal = $this->getTime() - $this->getTime($wgRequestTime);

			$entry =& $this->mCollated["-setup"];
			$entry['cpu'] += $elapsedcpu;
			$entry['cpu_sq'] += $elapsedcpu*$elapsedcpu;
			$entry['real'] += $elapsedreal;
			$entry['real_sq'] += $elapsedreal*$elapsedreal;
			$entry['count']++;
		}
	}

	function profileIn($functionname) {
		global $wgDebugFunctionEntry;
		if ($wgDebugFunctionEntry && function_exists('wfDebug')) {
			wfDebug(str_repeat(' ', count($this->mWorkStack)).'Entering '.$functionname."\n");
		}
		$this->mWorkStack[] = array($functionname, count( $this->mWorkStack ), $this->getTime(), $this->getCpuTime());
	}

	function profileOut($functionname) {
		$memory = memory_get_usage();

		global $wgDebugFunctionEntry;

		if ($wgDebugFunctionEntry && function_exists('wfDebug')) {
			wfDebug(str_repeat(' ', count($this->mWorkStack) - 1).'Exiting '.$functionname."\n");
		}

		list($ofname,$ocount,$ortime,$octime) = array_pop($this->mWorkStack);

		if (!$ofname) {
			wfDebug("Profiling error: $functionname\n");
		} else {
			if ($functionname == 'close') {
				$message = "Profile section ended by close(): {$ofname}";
				$functionname = $ofname;
				wfDebug( "$message\n" );
			}
			elseif ($ofname != $functionname) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				wfDebug( "$message\n" );
			}
			$entry =& $this->mCollated[$functionname];
			
			$elapsedcpu = $this->getCpuTime() - $octime;
			$elapsedreal = $this->getTime() - $ortime;

			$entry['cpu'] += $elapsedcpu;
			$entry['cpu_sq'] += $elapsedcpu*$elapsedcpu;
			$entry['real'] += $elapsedreal;
			$entry['real_sq'] += $elapsedreal*$elapsedreal;
			$entry['count']++;
				
		}
	}

	function getFunctionReport() {		
		/* Implement in output subclasses */
	}

	function getCpuTime($ru=null) {
		if ($ru==null)
			$ru=getrusage();
		return ($ru['ru_utime.tv_sec']+$ru['ru_stime.tv_sec']+($ru['ru_utime.tv_usec']+$ru['ru_stime.tv_usec'])*1e-6);
	}

	function getTime($time=null) {
		if ($time==null)
			$time=microtime();
		list($a,$b)=explode(" ",$time);
		return (float)($a+$b);
	}
}
?>
