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
		set_time_limit( 0 );
		$width = 80;
		$format = "%-39s %6.2f / %6.2f = %6.2f %6.2f%%\n";
		$prof = "";
		if( !count( $this->mStack ) ) {
			return "No profiling output\n";
		}
		$this->mCollated = array();

		$top = $this->doLevel( 0, true );
		$this->merge( "WIKI.PHTML", $top, $scriptElapsed );
		$this->mCollated = array_reverse( $this->mCollated, true );
/*
		# Calculate totals
		foreach ( $this->mCollated as $f1 => $f1data ) {
			$total = 0;
			foreach ( $f1data as $f2 => $t ) {
				$total += $t;
			}
			$this->mCollated[$f1][0] = $total;
		}
*/
		# Output
		foreach ( $this->mCollated as $f1 => $f1data ) {
			$prof .= "\n" . str_repeat( "-", $width ) . "\n";
			$t = $this->mTotals[$f1] * 1000;
			$calls = $this->mCalls[$f1];
			if ( $calls == 0 ) {
				$calls = 1;
			}
			$each = $t / $calls;
			$percent = $this->mTotals[$f1] / $scriptElapsed * 100;
			$prof .= sprintf( $format, "| $f1", $t, $calls, $each, $percent );
			$prof .= str_repeat( "-", $width ) . "\n";
			foreach ( $f1data as $f2 => $t ) {
				$percent = $t / $this->mTotals[$f1] * 100;
				$t *= 1000;
				$calls = $this->mCalls[$f1];
				if ( $calls == 0 ) {
					$calls = 1;
				}
				$each = $t / $calls;
				$percent = $this->mTotals[$f1] / $scriptElapsed * 100;
				$prof .= sprintf( $format, $f2, $t, $calls, $each, $percent );
			}
		}
		$prof .= str_repeat( "-", $width ) . "\n";
		return $prof;
	}
		

	function doLevel( $p, $fTop ) 
	{
		$level = false;
		$getOut = false;
		$hotArray = false;
		$tempArray = array();
		do {
			$fname = $this->mStack[$p][0];
			$thislevel = $this->mStack[$p][1];
			$start = (float)$this->mStack[$p][2] + (float)$this->mStack[$p][3];
			$end = (float)$this->mStack[$p][4] + (float)$this->mStack[$p][5];
			$elapsed = $end - $start;
			if ( $hotArray !== false ) {
				# Just dropped down a level
				# Therefore this entry is the parent of $hotArray
				$this->merge( $fname, $hotArray, $elapsed );
				$hotArray = false;
			}

			if ( $level === false ) {
				$level = $thislevel;
			}

			if ( $thislevel == $level ) {
				$tempArray[$fname] += $elapsed;
				#$this->mTotals[$fname] += elapsed;
				$this->mCalls[$fname] ++;
			} elseif ($thislevel > $level ) {
				$hotArray = $this->doLevel( $p, false );
			} else {
				$getOut = true;
			}

			# Special case: top of hierarchy
			# File starts with lvl 1 entry, then drops back to lvl 0
			if ( $fTop && $getOut ) {
				$hotArray = $tempArray;
				$getOut = false;
			}

			$p++;
		} while ( !$getOut && $p < count( $this->mStack ) );
		return $tempArray;
	}

	function merge( $f1, $a, $parentTime ) 
	{
		foreach ( $a as $f2 => $elapsed ) {
			$this->mCollated[$f1][$f2] += $elapsed;
		}
		$this->mTotals[$f1] += $parentTime;
	}
}

$wgProfiler = new Profiler();

?>
