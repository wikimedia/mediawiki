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

	protected function addInitialStack() {
		$this->errorEntry = $this->zeroEntry;
		$this->errorEntry['count'] = 1;

		$initialTime = $this->getInitialTime();
		$initialCpu = $this->getInitialTime( 'cpu' );
		if ( $initialTime !== null && $initialCpu !== null ) {
			$this->mWorkStack[] = array( '-total', 0, $initialTime, $initialCpu );
			$this->mWorkStack[] = array( '-setup', 1, $initialTime, $initialCpu );

			$this->profileOut( '-setup' );
		} else {
			$this->profileIn( '-total' );
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
		$this->mWorkStack[] = array( $functionname, count( $this->mWorkStack ), $this->getTime(), $this->getTime( 'cpu' ) );
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
			$elapsedcpu = $this->getTime( 'cpu' ) - $octime;
			$elapsedreal = $this->getTime() - $ortime;
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

	/**
	 * Get the actual CPU time or the initial one if $ru is set.
	 *
	 * @deprecated in 1.20
	 * @return float|null
	 */
	function getCpuTime( $ru = null ) {
		wfDeprecated( __METHOD__, '1.20' );

		if ( $ru === null ) {
			return $this->getTime( 'cpu' );
		} else {
			# It theory we should use $ru here, but it always $wgRUstart that is passed here
			return $this->getInitialTime( 'cpu' );
		}
	}
}
