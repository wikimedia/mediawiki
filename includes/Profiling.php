<?
# This file is only included if profiling is enabled
$wgDebugProfiling = true;

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
	var $mCalls = array(), $mTotals = array();
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
		array_push( $this->mWorkStack, array($functionname, count( $this->mWorkStack ), microtime() ) );
	}

	function profileOut( $functionname) 
	{
		global $wgDebugProfiling;
		$bit = array_pop( $this->mWorkStack );
		
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
	
	function close() 
	{
		while ( count( $this->mWorkStack ) ) {
			$this->profileOut( "close" );
		}
	}

	function getOutput( $scriptStart, $scriptElapsed )
	{
		if( !count( $this->mStack ) ) {
			return "No profiling output\n";
		}
		
		$format = "%-49s %6d %6.3f %6.3f %6.3f%%\n";
		$titleFormat = "%-49s %9s %9s %9s %9s\n";
		$prof = "\nProfiling data\n";
		$prof .= sprintf( $titleFormat, "Name", "Calls", "Total", "Each", "%" );
		$this->mCollated = array();
		$this->mCalls = array();
		$total = 0;
		
		# Estimate profiling overhead
		$profileCount = count( $this->mStack );
		for ($i=0; $i<$profileCount ; $i++) {
			wfProfileIn( "--profiling overhead--" );
			wfProfileOut( "--profiling overhead--" );
		}
		
		# Collate
		foreach ( $this->mStack as $entry ) {
			$fname = $entry[0];
			$thislevel = $entry[1];
			$start = explode( " ", $entry[2]);
			$start = (float)$start[0] + (float)$start[1];
			$end = explode( " ", $entry[3]);
			$end = (float)$end[0] + (float)$end[1];
			$elapsed = $end - $start;
			$this->mCollated[$fname] += $elapsed;
			$this->mCalls[$fname] ++;
			
			if ( $fname != "--profiling overhead--" ) {
				$total += $elapsed;
			}
		}
		
		$overhead = $this->mCollated["--profiling overhead--"] / $this->mCalls["--profiling overhead--"];
		
		# Output
		foreach ( $this->mCollated as $fname => $elapsed ) {
			$calls = $this->mCalls[$fname];
			# Adjust for overhead
			if ( $fname != "--profiling overhead--" ) {
				$elapsed -= $overhead * $calls;
			}
			
			$percent = $total ? 100. * $elapsed / $total : 0;
			$prof .= sprintf( $format, $fname, $calls, (float)($elapsed * 1000), 
				(float)($elapsed * 1000) / $calls, $percent );
		}
		$prof .= "\nTotal: $total\n\n";

		return $prof;
	}
}

$wgProfiler = new Profiler();

?>
