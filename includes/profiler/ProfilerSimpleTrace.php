<?php
/**
 * Profiler showing execution trace.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Profiler
 */

/**
 * Execution trace
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimpleTrace extends ProfilerSimple {
	var $trace = "Beginning trace: \n";
	var $memory = 0;

	function profileIn( $functionname ) {
		parent::profileIn( $functionname );
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
