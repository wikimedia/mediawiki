<?php
/**
 * Find all revisions by logged out users and copy the rev_id,
 * rev_timestamp, and a hex representation of rev_user_text to the
 * new ip_changes table. This table is used to efficiently query for
 * contributions within an IP range.
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

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that will find all rows in the revision table where
 * rev_user = 0 (user is an IP), and copy relevant fields to ip_changes so
 * that historical data will be available when querying for IP ranges.
 *
 * @ingroup Maintenance
 */
class PopulateIpChanges extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( <<<TEXT
This script will find all rows in the revision table where the user is an IP,
and copy relevant fields to the ip_changes table. This backfilled data will
then be available when querying for IP ranges at Special:Contributions.
TEXT
		);
		$this->addOption( 'rev-id', 'The rev_id to start copying from. Default: 0', false, true );
		$this->addOption(
			'max-rev-id',
			'The rev_id to stop at. Default: result of MAX(rev_id)',
			false,
			true
		);
		$this->addOption(
			'throttle',
			'Wait this many milliseconds after copying each batch of revisions. Default: 0',
			false,
			true
		);
		$this->addOption( 'force', 'Run regardless of whether the database says it\'s been run already' );
	}

	public function doDBUpdates() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		$dbw = $this->getDB( DB_MASTER );
		$throttle = intval( $this->getOption( 'throttle', 0 ) );
		$maxRevId = intval( $this->getOption( 'max-rev-id', 0 ) );
		$start = $this->getOption( 'rev-id', 0 );
		$end = $maxRevId > 0
			? $maxRevId
			: $dbw->selectField( 'revision', 'MAX(rev_id)', false, __METHOD__ );
		$blockStart = $start;
		$revCount = 0;

		$this->output( "Copying IP revisions to ip_changes, from rev_id $start to rev_id $end\n" );

		while ( $blockStart <= $end ) {
			$blockEnd = min( $blockStart + 200, $end );
			$rows = $dbr->select(
				'revision',
				[ 'rev_id', 'rev_timestamp', 'rev_user_text' ],
				[ "rev_id BETWEEN $blockStart AND $blockEnd", 'rev_user' => 0 ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_id ASC', 'LIMIT' => $this->mBatchSize ]
			);

			if ( !$rows || $rows->numRows() === 0 ) {
				break;
			}

			$this->output( "...checking $this->mBatchSize revisions for IP edits that need copying, " .
				"between rev_ids $blockStart and $blockEnd\n" );

			$insertRows = [];
			foreach ( $rows as $row ) {
				// Double-check to make sure this is an IP, e.g. not maintenance user or imported revision.
				if ( IP::isValid( $row->rev_user_text ) ) {
					$insertRows[] = [
						'ipc_rev_id' => $row->rev_id,
						'ipc_rev_timestamp' => $row->rev_timestamp,
						'ipc_hex' => IP::toHex( $row->rev_user_text ),
					];

					$revCount++;
				}

				$blockStart = (int)$row->rev_id;
			}

			$blockStart++;

			$dbw->insert(
				'ip_changes',
				$insertRows,
				__METHOD__,
				'IGNORE'
			);

			$lbFactory->waitForReplication();
			usleep( $throttle * 1000 );
		}

		$this->output( "$revCount IP revisions copied.\n" );

		return true;
	}

	protected function getUpdateKey() {
		return 'populate ip_changes';
	}
}

$maintClass = "PopulateIpChanges";
require_once RUN_MAINTENANCE_IF_MAIN;
