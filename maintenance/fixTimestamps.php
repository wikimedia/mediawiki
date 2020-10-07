<?php
/**
 * Fixes timestamp corruption caused by one or more webservers temporarily
 * being set to the wrong time.
 * The time offset must be known and consistent. Start and end times
 * (in 14-character format) restrict the search, and must bracket the damage.
 * There must be a majority of good timestamps in the search period.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that fixes timestamp corruption caused by one or
 * more webservers temporarily being set to the wrong time.
 *
 * @ingroup Maintenance
 */
class FixTimestamps extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( '' );
		$this->addArg( 'offset', '' );
		$this->addArg( 'start', 'Starting timestamp' );
		$this->addArg( 'end', 'Ending timestamp' );
	}

	public function execute() {
		$offset = $this->getArg( 0 ) * 3600;
		$start = $this->getArg( 1 );
		$end = $this->getArg( 2 );
		// maximum normal clock offset
		$grace = 60;

		# Find bounding revision IDs
		$dbw = $this->getDB( DB_MASTER );
		$revisionTable = $dbw->tableName( 'revision' );
		$res = $dbw->query( "SELECT MIN(rev_id) as minrev, MAX(rev_id) as maxrev FROM $revisionTable " .
			"WHERE rev_timestamp BETWEEN '{$start}' AND '{$end}'", __METHOD__ );
		$row = $dbw->fetchObject( $res );

		if ( $row->minrev === null ) {
			$this->fatalError( "No revisions in search period." );
		}

		$minRev = $row->minrev;
		$maxRev = $row->maxrev;

		# Select all timestamps and IDs
		$sql = "SELECT rev_id, rev_timestamp FROM $revisionTable " .
			"WHERE rev_id BETWEEN $minRev AND $maxRev";
		if ( $offset > 0 ) {
			$sql .= " ORDER BY rev_id DESC";
			$expectedSign = -1;
		} else {
			$expectedSign = 1;
		}

		$res = $dbw->query( $sql, __METHOD__ );

		$lastNormal = 0;
		$badRevs = [];
		$numGoodRevs = 0;

		foreach ( $res as $row ) {
			$timestamp = wfTimestamp( TS_UNIX, $row->rev_timestamp );
			$delta = $timestamp - $lastNormal;
			$sign = $delta == 0 ? 0 : $delta / abs( $delta );
			if ( $sign == 0 || $sign == $expectedSign ) {
				// Monotonic change
				$lastNormal = $timestamp;
				++$numGoodRevs;
				continue;
			} elseif ( abs( $delta ) <= $grace ) {
				// Non-monotonic change within grace interval
				++$numGoodRevs;
				continue;
			} else {
				// Non-monotonic change larger than grace interval
				$badRevs[] = $row->rev_id;
			}
		}

		$numBadRevs = count( $badRevs );
		if ( $numBadRevs > $numGoodRevs ) {
			$this->fatalError(
				"The majority of revisions in the search interval are marked as bad.

		Are you sure the offset ($offset) has the right sign? Positive means the clock
		was incorrectly set forward, negative means the clock was incorrectly set back.

		If the offset is right, then increase the search interval until there are enough
		good revisions to provide a majority reference." );
		} elseif ( $numBadRevs == 0 ) {
			$this->output( "No bad revisions found.\n" );
			exit( 0 );
		}

		$this->output( sprintf( "Fixing %d revisions (%.2f%% of revisions in search interval)\n",
			$numBadRevs, $numBadRevs / ( $numGoodRevs + $numBadRevs ) * 100 ) );

		$fixup = -$offset;
		$sql = "UPDATE $revisionTable " .
			"SET rev_timestamp="
				. "DATE_FORMAT(DATE_ADD(rev_timestamp, INTERVAL $fixup SECOND), '%Y%m%d%H%i%s') " .
			"WHERE rev_id IN (" . $dbw->makeList( $badRevs ) . ')';
		$dbw->query( $sql, __METHOD__ );
		$this->output( "Done\n" );
	}
}

$maintClass = FixTimestamps::class;
require_once RUN_MAINTENANCE_IF_MAIN;
