<?php
/**
 * Populates the rev_len field for old revisions created before MW 1.10.
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PopulateRevisionLength extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populates the rev_len field";
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'populate rev_len';
	}

	protected function updateSkippedMessage() {
		return 'rev_len column of revision table already populated.';
	}

	public function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'revision' ) ) {
			$this->error( "revision table does not exist", true );
		}
		$this->output( "Populating rev_len column\n" );

		$start = $db->selectField( 'revision', 'MIN(rev_id)', false, __METHOD__ );
		$end = $db->selectField( 'revision', 'MAX(rev_id)', false, __METHOD__ );
		if ( !$start || !$end ) {
			$this->output( "...revision table seems to be empty.\n" );
			return true;
		}

		# Do remaining chunks
		$blockStart = intval( $start );
		$blockEnd = intval( $start ) + $this->mBatchSize - 1;
		$count = 0;
		$missing = 0;
		while ( $blockStart <= $end ) {
			$this->output( "...doing rev_id from $blockStart to $blockEnd\n" );
			$res = $db->select( 'revision',
						Revision::selectFields(),
						array( "rev_id >= $blockStart",
						   "rev_id <= $blockEnd",
						   "rev_len IS NULL" ),
						__METHOD__ );
			# Go through and update rev_len from these rows.
			foreach ( $res as $row ) {
				$rev = new Revision( $row );
				$text = $rev->getRawText();
				if ( !is_string( $text ) ) {
					# This should not happen, but sometimes does (bug 20757)
					$this->output( "Text of revision {$row->rev_id} unavailable!\n" );
					$missing++;
				}
				else {
					# Update the row...
					$db->update( 'revision',
							 array( 'rev_len' => strlen( $text ) ),
							 array( 'rev_id' => $row->rev_id ),
							 __METHOD__ );
					$count++;
				}
			}
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}

		$this->output( "rev_len population complete ... {$count} rows changed ({$missing} missing)\n" );
		return true;
	}
}

$maintClass = "PopulateRevisionLength";
require_once( RUN_MAINTENANCE_IF_MAIN );
