<?php
# This file is only included if profiling is enabled
function wfProfileIn( $functionname )
{
	global $wgProfiler;
	$wgProfiler->profileIn( $functionname );
}

function wfProfileOut( $functionname = 'missing' ) 
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

if( !function_exists( 'memory_get_usage' ) ) {
	# Old PHP or --enable-memory-limit not compiled in
	function memory_get_usage() {
		return 0;
	}
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
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry && function_exists( 'wfDebug' ) ) {
			wfDebug( str_repeat( ' ', count( $this->mWorkStack ) ) . 'Entering '.$functionname."\n" );
		}
		array_push( $this->mWorkStack, array($functionname, count( $this->mWorkStack ), microtime(), memory_get_usage() ) );
	}

	function profileOut( $functionname )
	{
		$memory = memory_get_usage();
		global $wgDebugProfiling, $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry && function_exists( 'wfDebug' ) ) {
			wfDebug( str_repeat( ' ', count( $this->mWorkStack ) - 1 ) . 'Exiting '.$functionname."\n" );
		}
		
		$bit = array_pop( $this->mWorkStack );
		
		if ( !$bit ) {
			wfDebug( "Profiling error, !\$bit: $functionname\n" );
		} else {
			if ( $wgDebugProfiling ) {
				if ( $functionname == 'close' ) {
					wfDebug( "Profile section ended by close(): {$bit[0]}\n" );
				} elseif ( $bit[0] != $functionname ) {
					wfDebug( "Profiling error: in({$bit[0]}), out($functionname)\n" );
				}
			}
			array_push( $bit, microtime() );
			array_push( $bit, $memory );
			array_push( $this->mStack, $bit );
		}
	}
	
	function close() 
	{
		while ( count( $this->mWorkStack ) ) {
			$this->profileOut( 'close' );
		}
	}

	function getOutput()
	{
		global $wgDebugFunctionEntry;
		$wgDebugFunctionEntry = false;

		if( !count( $this->mStack ) ) {
			return "No profiling output\n";
		}
		$this->close();
		$width = 125;
		$format = "%-" . ($width - 34) . "s %6d %6.3f %6.3f %6.3f%% %6d\n";
		$titleFormat = "%-" . ($width - 34) . "s %9s %9s %9s %9s %6s\n";
		$prof = "\nProfiling data\n";
		$prof .= sprintf( $titleFormat, 'Name', 'Calls', 'Total', 'Each', '%', 'Mem' );
		$this->mCollated = array();
		$this->mCalls = array();
		$this->mMemory = array();
		
		# Estimate profiling overhead
		$profileCount = count( $this->mStack );
		wfProfileIn( '-overhead-total' );
		for ($i=0; $i<$profileCount ; $i++) {
			wfProfileIn( '-overhead-internal' );
			wfProfileOut( '-overhead-internal' );
		}
		wfProfileOut( '-overhead-total' );
		
		# Collate
		foreach ( $this->mStack as $entry ) {
			$fname = $entry[0];
			$thislevel = $entry[1];
			$start = explode( ' ', $entry[2]);
			$start = (float)$start[0] + (float)$start[1];
			$end = explode( ' ', $entry[4]);
			$end = (float)$end[0] + (float)$end[1];
			$elapsed = $end - $start;
			
			$memory = $entry[5] - $entry[3];
			
			if ( !array_key_exists( $fname, $this->mCollated ) ) {
				$this->mCollated[$fname] = 0;
				$this->mCalls[$fname] = 0;
				$this->mMemory[$fname] = 0;
			}

			$this->mCollated[$fname] += $elapsed;
			$this->mCalls[$fname] ++;
			$this->mMemory[$fname] += $memory;
		}

		$total = @$this->mCollated['-total'];
		$overhead = $this->mCollated['-overhead-internal'] / $profileCount;
		$this->mCalls['-overhead-total'] = $profileCount;

		# Output
		arsort( $this->mCollated, SORT_NUMERIC );
		foreach ( $this->mCollated as $fname => $elapsed ) {
			$calls = $this->mCalls[$fname];
			# Adjust for overhead
			if ( $fname[0] != '-' ) {
				$elapsed -= $overhead * $calls;
			}

			$percent = $total ? 100. * $elapsed / $total : 0;
			$memory = $this->mMemory[$fname];
			$prof .= sprintf( $format, $fname, $calls, (float)($elapsed * 1000), 
					(float)($elapsed * 1000) / $calls, $percent, $memory );

			global $wgProfileToDatabase;
			if( $wgProfileToDatabase ) {
				Profiler::logToDB( $fname, (float)($elapsed * 1000), $calls );
			}
		}
		$prof .= "\nTotal: $total\n\n";

		return $prof;
	}


	/* static */ function logToDB($name, $timeSum, $eventCount) 
	{
		$dbw =& wfGetDB( DB_MASTER );
		$profiling = $dbw->tableName( 'profiling' );

		$name = $dbw->strencode( $name );
		$sql = "UPDATE $profiling ".
			"SET pf_count=pf_count+{$eventCount}, ".
			"pf_time=pf_time + {$timeSum} ".
			"WHERE pf_name='{$name}'";
		$dbw->query($sql);

		$rc = $dbw->affectedRows();	
		if( $rc == 0) {
			$sql = "INSERT IGNORE INTO $profiling (pf_name,pf_count,pf_time) ".
				"VALUES ('{$name}', {$eventCount}, {$timeSum}) ";
			$dbw->query($sql , DB_MASTER);
		}
		// When we upgrade to mysql 4.1, the insert+update
		// can be merged into just a insert with this construct added:
		//     "ON DUPLICATE KEY UPDATE ".
		//     "pf_count=pf_count + VALUES(pf_count), ".
		//     "pf_time=pf_time + VALUES(pf_time)"; 
	}

}


$wgProfiler = new Profiler();
$wgProfiler->profileIn( '-total' );
?>
