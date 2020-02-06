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
 */

/**
 * Base class for profiling output.
 *
 * @ingroup Profiler
 * @since 1.25
 */
abstract class ProfilerOutput {
	/** @var Profiler */
	protected $collector;
	/** @var array Configuration of $wgProfiler */
	protected $params = [];

	/**
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
	 * May the log() try to write to standard output?
	 * @return bool
	 * @since 1.33
	 */
	public function logsToOutput() {
		return false;
	}

	/**
	 * Log MediaWiki-style profiling data.
	 *
	 * For classes that enable logsToOutput(), this must not
	 * be called unless Profiler::setAllowOutput is enabled.
	 *
	 * @param array $stats Result of Profiler::getFunctionStats()
	 */
	abstract public function log( array $stats );
}
