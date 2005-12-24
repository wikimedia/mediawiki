<?php 
/**
 * Simple profiler base class
 * @package MediaWiki
 */

/**
 * @todo document
 * @package MediaWiki
 */
class ProfilerSimple extends Profiler {
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

	function getCpuTime() {
		$ru=getrusage();
		return ($ru['ru_utime.tv_sec']+$ru['ru_stime.tv_sec']+($ru['ru_utime.tv_usec']+$ru['ru_stime.tv_usec'])*1e-6);
	}

	function getTime() {
		list($a,$b)=explode(" ",microtime());
		return (float)($a+$b);
	}
}
?>
