<?php
/**
 * Profiler dumping output in xhprof dump file
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
 * Profiler dumping output in xhprof dump file
 * @ingroup Profiler
 *
 * @since 1.25
 * @property ProfilerXhprof $collector
 */
class ProfilerOutputDump extends ProfilerOutput {

	protected $suffix = ".xhprof";

	/**
	 * Can this output type be used?
	 *
	 * @return bool
	 */
	public function canUse() {
		if ( empty( $this->params['outputDir'] ) ) {
			return false;
		}
		return true;
	}

	public function log( array $stats ) {
		$data = $this->collector->getRawData();
		$filename = sprintf( "%s/%s.%s%s",
			$this->params['outputDir'],
			uniqid(),
			$this->collector->getProfileID(),
			$this->suffix );
		file_put_contents( $filename, serialize( $data ) );
	}
}
