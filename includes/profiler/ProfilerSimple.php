<?php
/**
 * Base class for simple profiling.
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
 * Simple profiler base class.
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimple extends Profiler {
	var $mMinimumTime = 0;

	var $errorEntry;

	public function getZeroEntry() {
		return array(
			'cpu'     => 0.0,
			'cpu_sq'  => 0.0,
			'real'    => 0.0,
			'real_sq' => 0.0,
			'count'   => 0
		);
	}

	public function getErrorEntry() {
		$entry = $this->getZeroEntry();
		$entry['count'] = 1;
		return $entry;
	}

	public function updateEntry( $name, $elapsedCpu, $elapsedReal ) {
		$entry =& $this->mCollated[$name];
		if ( !is_array( $entry ) ) {
			$entry = $this->getZeroEntry();
			$this->mCollated[$name] =& $entry;
		}
		$entry['cpu'] += $elapsedCpu;
		$entry['cpu_sq'] += $elapsedCpu * $elapsedCpu;
		$entry['real'] += $elapsedReal;
		$entry['real_sq'] += $elapsedReal * $elapsedReal;
		$entry['count']++;
	}

	public function isPersistent() {
		/* Implement in output subclasses */
		return false;
	}

	protected function addInitialStack() {
		$this->errorEntry = $this->getErrorEntry();

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

	function profileIn( $functionname ) {
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) ) . 'Entering ' . $functionname . "\n" );
		}
		$this->mWorkStack[] = array( $functionname, count( $this->mWorkStack ), $this->getTime(), $this->getTime( 'cpu' ) );
	}

	function profileOut( $functionname ) {
		global $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) - 1 ) . 'Exiting ' . $functionname . "\n" );
		}

		list( $ofname, /* $ocount */, $ortime, $octime ) = array_pop( $this->mWorkStack );

		if ( !$ofname ) {
			$this->debugGroup( 'profileerror', "Profiling error: $functionname" );
		} else {
			if ( $functionname == 'close' ) {
				if ( $ofname != '-total' ) {
					$message = "Profile section ended by close(): {$ofname}";
					$this->debugGroup( 'profileerror', $message );
					$this->mCollated[$message] = $this->errorEntry;
				}
				$functionname = $ofname;
			} elseif ( $ofname != $functionname ) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				$this->debugGroup( 'profileerror', $message );
				$this->mCollated[$message] = $this->errorEntry;
			}
			$elapsedcpu = $this->getTime( 'cpu' ) - $octime;
			$elapsedreal = $this->getTime() - $ortime;
			$this->updateEntry( $functionname, $elapsedcpu, $elapsedreal );
			$this->updateTrxProfiling( $functionname, $elapsedreal );
		}
	}

	public function getRawData() {
		// Calling the method of the parent class results in fatal error.
		// @todo Implement this correctly.
		return array();
	}

	public function getFunctionReport() {
		/* Implement in output subclasses */
		return '';
	}

	public function logData() {
		/* Implement in subclasses */
	}
}
