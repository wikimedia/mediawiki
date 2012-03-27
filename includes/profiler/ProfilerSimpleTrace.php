<?php
/**
 * @file
 * @ingroup Profiler
 */

/**
 * Execution trace
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimpleTrace extends ProfilerSimple {
	var $trace = "";
	var $memory = 0;

	function addInitialStack() {
		$initialTime = $this->getInitialTime();
		$initialCpu = $this->getInitialTime( 'cpu' );
		if ( $initialTime !== null && $initialCpu !== null ) {
			$this->mWorkStack[] = array( '-total', 0, $initialTime, $initialCpu );
		}
		$this->trace .= "Beginning trace: \n";
	}

	function profileIn($functionname) {
		$this->mWorkStack[] = array( $functionname, count( $this->mWorkStack ), $this->getTime(), $this->getTime( 'cpu' ) );
		$this->trace .= "         " . sprintf("%6.1f",$this->memoryDiff()) .
				str_repeat( " ", count($this->mWorkStack)) . " > " . $functionname . "\n";
	}

	function profileOut($functionname) {
		global $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry ) {
			$this->debug(str_repeat(' ', count($this->mWorkStack) - 1).'Exiting '.$functionname."\n");
		}

		list( $ofname, /* $ocount */ , $ortime ) = array_pop( $this->mWorkStack );

		if ( !$ofname ) {
			$this->trace .= "Profiling error: $functionname\n";
		} else {
			if ( $functionname == 'close' ) {
				$message = "Profile section ended by close(): {$ofname}";
				$functionname = $ofname;
				$this->trace .= $message . "\n";
			}
			elseif ( $ofname != $functionname ) {
				$this->trace .= "Profiling error: in({$ofname}), out($functionname)";
			}
			$elapsedreal = $this->getTime() - $ortime;
			$this->trace .= sprintf( "%03.6f %6.1f", $elapsedreal, $this->memoryDiff() ) .
					str_repeat(" ", count( $this->mWorkStack ) + 1 ) . " < " . $functionname . "\n";
		}
	}
	
	function memoryDiff() {
		$diff = memory_get_usage() - $this->memory;
		$this->memory = memory_get_usage();
		return $diff / 1024;
	}

	function logData() {
		print "<!-- \n {$this->trace} \n -->";
	}
}
