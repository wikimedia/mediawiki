<?php
/**
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
 * Base class for profiling output
 *
 * Since 1.25
 */
abstract class ProfilerOutput {
	/** @var Profiler */
	protected $collector;
	/** @var array Configuration of $wgProfiler */
	protected $params = [];

	/**
	 * Constructor
	 * @param Profiler $collector The actual profiler
	 * @param array $params Configuration array, passed down from $wgProfiler
	 */
	public function __construct( Profiler $collector, array $params ) {
		$this->collector = $collector;
		$this->params = $params;
	}

	/**
	 * Can this output type be used?
	 * @return bool
	 */
	public function canUse() {
		return true;
	}

	/**
	 * Log MediaWiki-style profiling data
	 *
	 * @param array $stats Result of Profiler::getFunctionStats()
	 */
	abstract public function log( array $stats );
}
