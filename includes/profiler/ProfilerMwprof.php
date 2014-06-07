<?php
/**
 * Profiler class for Mwprof.
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
 * Profiler class for Mwprof.
 *
 * Mwprof is a high-performance MediaWiki profiling data collector, designed to
 * collect profiling data from multiple hosts running in tandem. This class
 * serializes profiling samples into MessagePack arrays and sends them to an
 * Mwprof instance via UDP.
 *
 * @see https://github.com/wikimedia/operations-software-mwprof
 * @since 1.23
 */
class ProfilerMwprof extends Profiler {

	// Message types

	const TYPE_SINGLE = 1;
	const TYPE_RUNNING = 2;

	/**
	 * Indicate that this Profiler subclass is persistent.
	 *
	 * Called by Parser::braceSubstitution. If true, the parser will not
	 * generate per-title profiling sections, to avoid overloading the
	 * profiling data collector.
	 *
	 * @return bool true
	 */
	public function isPersistent() {
		return true;
	}

	/**
	 * Start a profiling section.
	 *
	 * Marks the beginning of the function or code-block that should be time
	 * and logged under some specific name.
	 *
	 * @param string $inName Section to start
	 */
	public function profileIn( $inName ) {
		$this->mWorkStack[] = array( $inName, count( $this->mWorkStack ),
			$this->getTime(), $this->getTime( 'cpu' ) );
	}

	/**
	 * Produce an empty function report.
	 *
	 * ProfileMwprof does not provide a function report.
	 *
	 * @return string Empty string.
	 */
	public function getFunctionReport() {
		return '';
	}

	/**
	 * Close a profiling section.
	 *
	 * Marks the end of the function or code-block that should be timed and
	 * logged under some specific name.
	 *
	 * @param string $outName Section to close
	 */
	public function profileOut( $outName ) {
		list( $inName, $inCount, $inWall, $inCpu ) = array_pop( $this->mWorkStack );

		// Check for unbalanced profileIn / profileOut calls.
		// Bad entries are logged but not sent.
		if ( $inName !== $outName ) {
			$this->debugGroup( 'ProfilerUnbalanced', json_encode( array( $inName, $outName ) ) );
			return;
		}

		$elapsedCpu = $this->getTime( 'cpu' ) - $inCpu;
		$elapsedWall = $this->getTime() - $inWall;
		$this->updateEntry( $outName, $elapsedCpu, $elapsedWall );
		$this->updateTrxProfiling( $outName, $elapsedWall );
	}

	/**
	 * Update an entry with timing data.
	 *
	 * @param string $name Section name
	 * @param float $elapsedCpu elapsed CPU time
	 * @param float $elapsedWall elapsed wall-clock time
	 */
	public function updateEntry( $name, $elapsedCpu, $elapsedWall ) {
		// If this is the first measurement for this entry, store plain values.
		// Many profiled functions will only be called once per request.
		if ( !isset( $this->mCollated[$name] ) ) {
			$this->mCollated[$name] = array(
				'cpu'   => $elapsedCpu,
				'wall'  => $elapsedWall,
				'count' => 1,
			);
			return;
		}

		$entry = &$this->mCollated[$name];

		// If it's the second measurement, convert the plain values to
		// RunningStat instances, so we can push the incoming values on top.
		if ( $entry['count'] === 1 ) {
			$cpu = new RunningStat();
			$cpu->push( $entry['cpu'] );
			$entry['cpu'] = $cpu;

			$wall = new RunningStat();
			$wall->push( $entry['wall'] );
			$entry['wall'] = $wall;
		}

		$entry['count']++;
		$entry['cpu']->push( $elapsedCpu );
		$entry['wall']->push( $elapsedWall );
	}

	/**
	 * Serialize profiling data and send to a profiling data aggregator.
	 *
	 * Individual entries are represented as arrays and then encoded using
	 * MessagePack, an efficient binary data-interchange format. Encoded
	 * entries are accumulated into a buffer and sent in batch via UDP to the
	 * profiling data aggregator.
	 */
	public function logData() {
		global $wgUDPProfilerHost, $wgUDPProfilerPort;

		$this->close();

		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		socket_connect( $sock, $wgUDPProfilerHost, $wgUDPProfilerPort );
		$bufferLength = 0;
		$buffer = '';
		foreach ( $this->mCollated as $name => $entry ) {
			$count = $entry['count'];
			$cpu = $entry['cpu'];
			$wall = $entry['wall'];

			if ( $count === 1 ) {
				$data = array( self::TYPE_SINGLE, $name, $cpu, $wall );
			} else {
				$data = array( self::TYPE_RUNNING, $name, $count,
					$cpu->m1, $cpu->m2, $cpu->min, $cpu->max,
					$wall->m1, $wall->m2, $wall->min, $wall->max );
			}

			$encoded = MWMessagePack::pack( $data );
			$length = strlen( $encoded );

			// If adding this entry would cause the size of the buffer to
			// exceed the standard ethernet MTU size less the UDP header,
			// send all pending data and reset the buffer. Otherwise, continue
			// accumulating entries into the current buffer.
			if ( $length + $bufferLength > 1450 ) {
				socket_send( $sock, $buffer, $bufferLength, 0 );
				$buffer = '';
				$bufferLength = 0;
			}
			$buffer .= $encoded;
			$bufferLength += $length;
		}
		if ( $bufferLength !== 0 ) {
			socket_send( $sock, $buffer, $bufferLength, 0 );
		}
	}
}
