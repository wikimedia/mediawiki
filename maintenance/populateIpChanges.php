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
		$dbw = $this->getDB( DB_MASTER );

		if ( !$dbw->tableExists( 'ip_changes' ) ) {
			$this->fatalError( 'ip_changes table does not exist' );
		}

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		$throttle = intval( $this->getOption( 'throttle', 0 ) );
		$maxRevId = intval( $this->getOption( 'max-rev-id', 0 ) );
		$start = $this->getOption( 'rev-id', 0 );
		$end = $maxRevId > 0
			? $maxRevId
			: $dbw->selectField( 'revision', 'MAX(rev_id)', '', __METHOD__ );

		if ( empty( $end ) ) {
			$this->output( "No revisions found, aborting.\n" );
			return true;
		}

		$blockStart = $start;
		$attempted = 0;
		$inserted = 0;

		$this->output( "Copying IP revisions to ip_changes, from rev_id $start to rev_id $end\n" );

		$actorMigration = ActorMigration::newMigration();
		$actorQuery = $actorMigration->getJoin( 'rev_user' );
		$revUserIsAnon = $actorMigration->isAnon( $actorQuery['fields']['rev_user'] );

		while ( $blockStart <= $end ) {
			$blockEnd = min( $blockStart + $this->getBatchSize(), $end );
			$rows = $dbr->select(
				[ 'revision' ] + $actorQuery['tables'],
				[ 'rev_id', 'rev_timestamp', 'rev_user_text' => $actorQuery['fields']['rev_user_text'] ],
				[ "rev_id BETWEEN " . (int)$blockStart . " AND " . (int)$blockEnd, $revUserIsAnon ],
				__METHOD__,
				[],
				$actorQuery['joins']
			);

			$numRows = $rows->numRows();

			if ( !$rows || $numRows === 0 ) {
				$blockStart = $blockEnd + 1;
				continue;
			}

			$this->output( "...checking $numRows revisions for IP edits that need copying, " .
				"between rev_ids $blockStart and $blockEnd\n" );

			$insertRows = [];
			foreach ( $rows as $row ) {
				// Make sure this is really an IP, e.g. not maintenance user or imported revision.
				if ( IP::isValid( $row->rev_user_text ) ) {
					$insertRows[] = [
						'ipc_rev_id' => $row->rev_id,
						'ipc_rev_timestamp' => $row->rev_timestamp,
						'ipc_hex' => IP::toHex( $row->rev_user_text ),
					];

					$attempted++;
				}
			}

			if ( $insertRows ) {
				$dbw->insert( 'ip_changes', $insertRows, __METHOD__, 'IGNORE' );

				$inserted += $dbw->affectedRows();
			}

			$lbFactory->waitForReplication();
			usleep( $throttle * 1000 );

			$blockStart = $blockEnd + 1;
		}

		$this->output( "Attempted to insert $attempted IP revisions, $inserted actually done.\n" );

		return true;
	}

	protected function getUpdateKey() {
		return 'populate ip_changes';
	}
}

$maintClass = PopulateIpChanges::class;
require_once RUN_MAINTENANCE_IF_MAIN;
