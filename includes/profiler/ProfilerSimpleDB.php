<?php
/**
 * Profiler storing information in the DB.
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
 * $wgProfiler['class'] = 'ProfilerSimpleDB';
 *
 * @ingroup Profiler
 */
class ProfilerSimpleDB extends ProfilerStandard {
	protected function collateOnly() {
		return true;
	}

	public function isPersistent() {
		return true;
	}

	/**
	 * Log the whole profiling data into the database.
	 */
	public function logData() {
		global $wgProfilePerHost;

		# Do not log anything if database is readonly (bug 5375)
		if ( wfReadOnly() ) {
			return;
		}

		if ( $wgProfilePerHost ) {
			$pfhost = wfHostname();
		} else {
			$pfhost = '';
		}

		try {
			$this->collateData();

			$dbw = wfGetDB( DB_MASTER );
			$useTrx = ( $dbw->getType() === 'sqlite' ); // much faster
			if ( $useTrx ) {
				$dbw->begin();
			}
			foreach ( $this->mCollated as $name => $data ) {
				$eventCount = $data['count'];
				$timeSum = (float)( $data['real'] * 1000 );
				$memorySum = (float)$data['memory'];
				$name = substr( $name, 0, 255 );

				// Kludge
				$timeSum = $timeSum >= 0 ? $timeSum : 0;
				$memorySum = $memorySum >= 0 ? $memorySum : 0;

				$dbw->update( 'profiling',
					array(
						"pf_count=pf_count+{$eventCount}",
						"pf_time=pf_time+{$timeSum}",
						"pf_memory=pf_memory+{$memorySum}",
					),
					array(
						'pf_name' => $name,
						'pf_server' => $pfhost,
					),
					__METHOD__ );

				$rc = $dbw->affectedRows();
				if ( $rc == 0 ) {
					$dbw->insert( 'profiling',
						array(
							'pf_name' => $name,
							'pf_count' => $eventCount,
							'pf_time' => $timeSum,
							'pf_memory' => $memorySum,
							'pf_server' => $pfhost
						),
						__METHOD__,
						array( 'IGNORE' )
					);
				}
				// When we upgrade to mysql 4.1, the insert+update
				// can be merged into just a insert with this construct added:
				//     "ON DUPLICATE KEY UPDATE ".
				//     "pf_count=pf_count + VALUES(pf_count), ".
				//     "pf_time=pf_time + VALUES(pf_time)";
			}
			if ( $useTrx ) {
				$dbw->commit();
			}
		} catch ( DBError $e ) {
		}
	}
}
