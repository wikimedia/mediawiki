<?php
# This file is only included if profiling is enabled
function wfProfileIn( $functionname )
{
	global $wgProfiler;
	$wgProfiler->profileIn( $functionname );
}

function wfProfileOut( $functionname = "missing" ) 
{
	global $wgProfiler;
	$wgProfiler->profileOut( $functionname );
}

function wfGetProfilingOutput( $start, $elapsed ) {
	global $wgProfiler;
	return $wgProfiler->getOutput( $start, $elapsed );
}

function wfProfileClose()
{
	global $wgProfiler;
	$wgProfiler->close();
}

class Profiler
{
	var $mStack = array(), $mWorkStack = array(), $mCollated = array();
	var $mCalls = array(), $mTotals = array(), $mDone = false;
	/*
	function Profiler()
	{
		$this->mProfileStack = array();
		$this->mWorkStack = array();
		$this->mCollated = array();
	}
	*/
	
	function profileIn( $functionname )
	{
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry && function_exists( "wfDebug" ) ) {
			wfDebug( "Entering $functionname\n" );
		}
		array_push( $this->mWorkStack, array($functionname, count( $this->mWorkStack ), microtime() ) );
	}

	function profileOut( $functionname) 
	{
		global $wgDebugProfiling, $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry && function_exists( "wfDebug" ) ) {
			wfDebug( "Exiting $functionname\n" );
		}
		
		$bit = array_pop( $this->mWorkStack );
		
		if ( !$bit ) {
			wfDebug( "Profiling error, !\$bit: $functionname\n" );
		} else {
			if ( $wgDebugProfiling ) {
				if ( $functionname == "close" ) {
					wfDebug( "Profile section ended by close(): {$bit[0]}\n" );
				} elseif ( $bit[0] != $functionname ) {
					wfDebug( "Profiling error: in({$bit[0]}), out($functionname)\n" );
				}
			}
			array_push( $bit, microtime() );
			array_push( $this->mStack, $bit );
		}
	}
	
	function close() 
	{
		while ( count( $this->mWorkStack ) ) {
			$this->profileOut( "close" );
		}
	}

	function getOutput( $scriptStart, $scriptElapsed )
	{
		if ( $this->mDone ) {
			return '';
		} elseif( !count( $this->mStack ) ) {
			return "No profiling output\n";
		}
		$this->close();
		$width = 125;
		$format = "%-" . ($width - 28) . "s %6d %6.3f %6.3f %6.3f%%\n";
		$titleFormat = "%-" . ($width - 28) . "s %9s %9s %9s %9s\n";
		$prof = "\nProfiling data\n";
		$prof .= sprintf( $titleFormat, "Name", "Calls", "Total", "Each", "%" );
		$this->mCollated = array();
		$this->mCalls = array();
		
		# Estimate profiling overhead
		$profileCount = count( $this->mStack );
		wfProfileIn( "-overhead-total" );
		for ($i=0; $i<$profileCount ; $i++) {
			wfProfileIn( "-overhead-internal" );
			wfProfileOut( "-overhead-internal" );
		}
		wfProfileOut( "-overhead-total" );
		
		# Collate
		foreach ( $this->mStack as $entry ) {
			$fname = $entry[0];
			$thislevel = $entry[1];
			$start = explode( " ", $entry[2]);
			$start = (float)$start[0] + (float)$start[1];
			$end = explode( " ", $entry[3]);
			$end = (float)$end[0] + (float)$end[1];
			$elapsed = $end - $start;
			
			if ( !array_key_exists( $fname, $this->mCollated ) ) {
				$this->mCollated[$fname] = 0;
				$this->mCalls[$fname] = 0;
			}

			$this->mCollated[$fname] += $elapsed;
			$this->mCalls[$fname] ++;
		}

		$total = @$this->mCollated["-total"];
		$overhead = $this->mCollated["-overhead-internal"] / $profileCount;
		$this->mCalls["-overhead-total"] = $profileCount;

		# Output
		foreach ( $this->mCollated as $fname => $elapsed ) {
			$calls = $this->mCalls[$fname];
			# Adjust for overhead
			if ( $fname[0] != "-" ) {
				$elapsed -= $overhead * $calls;
			}

			$percent = $total ? 100. * $elapsed / $total : 0;
			$prof .= sprintf( $format, $fname, $calls, (float)($elapsed * 1000), 
					(float)($elapsed * 1000) / $calls, $percent );

			global $wgProfileToDatabase;
			if( $wgProfileToDatabase ) {
				Profiler::logToDB( $fname, (float)($elapsed * 1000), $calls );
			}
		}
		$prof .= "\nTotal: $total\n\n";
		
		$this->mDone = true;
		return $prof;
	}


	/* static */ function logToDB($name, $timeSum, $eventCount) 
	{
		$name = wfStrencode( $name );
		$sql = "UPDATE profiling ".
			"SET pf_count=pf_count+{$eventCount}, ".
			"pf_time=pf_time + {$timeSum} ".
			"WHERE pf_name='{$name}'";
		wfQuery($sql , DB_WRITE);

		$rc = wfAffectedRows();	
		if( $rc == 0) {
			$sql = "INSERT IGNORE INTO profiling (pf_name,pf_count,pf_time) ".
				"VALUES ('{$name}', {$eventCount}, {$timeSum}) ";
			wfQuery($sql , DB_WRITE);
			$rc = wfAffectedRows();    
		}
		// When we upgrade to mysql 4.1, the insert+update
		// can be merged into just a insert with this construct added:
		//     "ON DUPLICATE KEY UPDATE ".
		//     "pf_count=pf_count + VALUES(pf_count), ".
		//     "pf_time=pf_time + VALUES(pf_time)"; 
	}

}


$wgProfiler = new Profiler();
$wgProfiler->profileIn( "-total" );
?>
