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

use Wikimedia\Rdbms\DBError;

/**
 * Logs profiling data into the local DB
 *
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputDb extends ProfilerOutput {
	/** @var bool Whether to store host data with profiling calls */
	private $perHost = false;

	public function __construct( Profiler $collector, array $params ) {
		parent::__construct( $collector, $params );

		// Initialize per-host profiling from config, back-compat if available
		if ( isset( $this->params['perHost'] ) ) {
			$this->perHost = $this->params['perHost'];
		}
	}

	public function canUse() {
		# Do not log anything if database is readonly (T7375)
		return !wfReadOnly();
	}

	public function log( array $stats ) {
		$pfhost = $this->perHost ? wfHostname() : '';

		try {
			$dbw = wfGetDB( DB_MASTER );
			$useTrx = ( $dbw->getType() === 'sqlite' ); // much faster
			if ( $useTrx ) {
				$dbw->startAtomic( __METHOD__ );
			}
			foreach ( $stats as $data ) {
				$name = $data['name'];
				$eventCount = $data['calls'];
				$timeSum = (float)$data['real'];
				$memorySum = (float)$data['memory'];
				$name = substr( $name, 0, 255 );

				// Kludge
				$timeSum = $timeSum >= 0 ? $timeSum : 0;
				$memorySum = $memorySum >= 0 ? $memorySum : 0;

				$dbw->upsert( 'profiling',
					[
						'pf_name' => $name,
						'pf_count' => $eventCount,
						'pf_time' => $timeSum,
						'pf_memory' => $memorySum,
						'pf_server' => $pfhost
					],
					[ [ 'pf_name', 'pf_server' ] ],
					[
						"pf_count=pf_count+{$eventCount}",
						"pf_time=pf_time+{$timeSum}",
						"pf_memory=pf_memory+{$memorySum}",
					],
					__METHOD__
				);
			}
			if ( $useTrx ) {
				$dbw->endAtomic( __METHOD__ );
			}
		} catch ( DBError $e ) {
		}
	}
}
