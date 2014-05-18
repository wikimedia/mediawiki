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
 * Execution trace profiler
 * @todo document methods (?)
 * @ingroup Profiler
 */
class ProfilerSimpleTrace extends ProfilerStandard {
	protected $trace = "Beginning trace: \n";
	protected $memory = 0;

	protected function collateOnly() {
		return true;
	}

	public function profileIn( $functionname ) {
		parent::profileIn( $functionname );

		$this->trace .= "         " . sprintf( "%6.1f", $this->memoryDiff() ) .
			str_repeat( " ", count( $this->mWorkStack ) ) . " > " . $functionname . "\n";
	}

	public function profileOut( $functionname ) {
		$item = end( $this->mWorkStack );

		parent::profileOut( $functionname );

		if ( !$item ) {
			$this->trace .= "Profiling error: $functionname\n";
		} else {
			list( $ofname, /* $ocount */, $ortime ) = $item;
			if ( $functionname == 'close' ) {
				$message = "Profile section ended by close(): {$ofname}";
				$functionname = $ofname;
				$this->trace .= $message . "\n";
			} elseif ( $ofname != $functionname ) {
				$this->trace .= "Profiling error: in({$ofname}), out($functionname)";
			}
			$elapsedreal = $this->getTime() - $ortime;
			$this->trace .= sprintf( "%03.6f %6.1f", $elapsedreal, $this->memoryDiff() ) .
				str_repeat( " ", count( $this->mWorkStack ) + 1 ) . " < " . $functionname . "\n";
		}
	}

	protected function memoryDiff() {
		$diff = memory_get_usage() - $this->memory;
		$this->memory = memory_get_usage();
		return $diff / 1024;
	}

	public function logData() {
		if ( $this->mTemplated ) {
			if ( PHP_SAPI === 'cli' ) {
				print "<!-- \n {$this->trace} \n -->";
			} elseif ( $this->getContentType() === 'text/html' ) {
				print "<!-- \n {$this->trace} \n -->";
			} elseif ( $this->getContentType() === 'text/javascript' ) {
				print "\n/*\n {$this->trace}\n*/";
			} elseif ( $this->getContentType() === 'text/css' ) {
				print "\n/*\n {$this->trace}\n*/";
			}
		}
	}
}
