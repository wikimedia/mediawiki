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
class PopulateIpChanges extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( <<<TEXT
This script will find all rows in the revision table where rev_user=0 (user is
an IP), and copy relevant fields to the ip_changes table. This backfilled data
will then be available when querying for IP ranges at Special:Contributions.
TEXT
		);
		$this->addOption( 'lastRevId', "Highest rev_id of IP revision that has " .
			"been backported to the ip_changes table.", false, true );
	}

	public function execute() {
		$db = $this->getDB( DB_MASTER );

		$start = $this->getOption( 'lastRevId' );
		if ( !$start ) {
			$start = 0;
		}

		$this->output( "Copying IP revisions to ip_changes, starting with rev_id $start.\n" );

		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		while ( $blockEnd <= $end ) {
			$this->output( "...doing rev_id from $blockStart to $blockEnd\n" );
			$cond = "rev_id BETWEEN $blockStart AND $blockEnd AND rev_user = 0";
			$res = $db->select( 'revision', [ 'rev_id', 'rev_timestamp', 'rev_user_text' ], $cond, __METHOD__ );

			foreach ( $res as $row ) {
				// Double-check to make sure this is an IP, e.g. not maintenance user or imported revision.
				if ( !IP::isValid( $row->rev_user_text ) ) {
					continue;
				}

				$db->update( 'ip_changes',
					[ 'ipc_rev_id' => $row->rev_id ],
					[ 'ipc_rev_timestamp' => $row->rev_timestamp ],
					[ 'ipc_hex' => IP::toHex( $rowp->rev_user_text ) ],
					__METHOD__
				);
				$blockStart += $this->mBatchSize - 1;
				$blockEnd += $this->mBatchSize - 1;
				wfWaitForSlaves();
			}
		}

		return true;
	}
}

$maintClass = "PopulateIpChanges";
require_once RUN_MAINTENANCE_IF_MAIN;
