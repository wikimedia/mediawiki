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

	function __construct( $params ) {
		global $wgRequestTime, $wgRUstart;
		parent::__construct( $params );
		if ( !empty( $wgRequestTime ) && !empty( $wgRUstart ) ) {
			$this->mWorkStack[] = array( '-total', 0, $wgRequestTime, $this->getCpuTime( $wgRUstart ) );
		}
		$this->trace .= "Beginning trace: \n";
	}

	function profileIn($functionname) {
		$this->mWorkStack[] = array($functionname, count( $this->mWorkStack ), microtime(true), $this->getCpuTime());
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
			$elapsedreal = microtime( true ) - $ortime;
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
