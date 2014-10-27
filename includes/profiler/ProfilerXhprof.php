<?php
/**
 * @section LICENSE
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
 * Profiler wrapper for XHProf extension.
 *
 * Example StartProfiler.php:
 * @code
 * $wgProfiler['class'] = 'ProfilerXhprof';
 * $wgProfiler['flags'] = XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_MEMORY;
 * $wgProfiler['exclude'] = array( 'call_user_func', 'call_user_func_array' );
 * $wgProfiler['sort'] = 'wt';
 * @endcode
 *
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 * @ingroup Profiler
 */
class ProfilerXhprof extends Profiler {

	/**
	 * @var Xhprof
	 */
	protected $xhprof;

	/**
	 * @param array $params
	 * @see Xhprof::__construct()
	 */
	public function __construct( array $params = array() ) {
		parent::__construct( $params );
		$this->xhprof = new Xhprof( $params );
		// TODO: add support for specfying output formatting
	}

	public function isStub() {
		return false;
	}

	public function isPersistent() {
		// Disable per-title profiling sections
		return true;
	}

	/**
	 * No-op for xhprof profiling.
	 *
	 * Use the 'include' configuration key instead if you need to constrain
	 * the functions that are profiled.
	 *
	 * @param string $functionname
	 */
	public function profileIn( $functionname ) {
	}

	/**
	 * No-op for xhprof profiling.
	 *
	 * Use the 'include' configuration key instead if you need to constrain
	 * the functions that are profiled.
	 *
	 * @param string $functionname
	 */
	public function profileOut( $functionname ) {
	}

	public function close() {
	}

	public function getCurrentSection() {
		return '';
	}

	public function getRawData() {
		// TODO: should there be a way to get the hierarchial data as well?
		return $this->xhprof->getCompleteMetrics();
	}

	/**
	 * Log the data to some store or even the page output
	 */
	public function logData() {
		// TODO: add support for specfying output formatting
		$data = $this->getRawData();
		wfDebugLog( 'xhprof', var_export( $data, true ) );
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	public function getOutput() {
		// TODO: add support for specfying output formatting
		$data = $this->getRawData();
		return var_export( $data, true );
	}

}
