<?php
/**
 * Populates the ar_len field
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that populates the ar_len field
 *
 * @since 1.21
 *
 * @ingroup Maintenance
 */
class PopulateArchiveLength extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populates the ar_len field";
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'populate ar_len';
	}

	protected function updateSkippedMessage() {
		return 'ar_len column of archive table already populated.';
	}

	public function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'archive' ) ) {
			$this->error( "archive table does not exist", true );
		} else if ( !$db->fieldExists( 'archive', 'ar_len', __METHOD__ ) ) {
			$this->output( "ar_len column does not exist\n\n", true );
			return false;
		}

		$this->output( "Populating ar_len column\n" );

		$start = $db->selectField( 'archive', 'MIN(ar_id)', false, __METHOD__ );
		$end = $db->selectField( 'archive', 'MAX(ar_id)', false, __METHOD__ );
		if ( !$start || !$end ) {
			$this->output( "...archive table seems to be empty.\n" );
			return true;
		}

		# Do remaining chunks
		$blockStart = intval( $start );
		$blockEnd = intval( $start ) + $this->mBatchSize - 1;
		$count = 0;
		$missing = 0;
		$fields = array(
			'ar_id', 'ar_page_id', 'ar_rev_id', 'ar_comment', 'ar_user', 'ar_user_text',
			'ar_timestamp', 'ar_minor_edit', 'ar_text_id', 'ar_deleted', 'ar_len',
			'ar_sha1', 'ar_content_model', 'ar_content_format'
		);
		while ( $blockStart <= $end ) {
			$this->output( "...doing ar_id from $blockStart to $blockEnd\n" );
			$res = $db->select( 'archive',
						$fields,
						array( "ar_id >= $blockStart",
						   "ar_id <= $blockEnd",
						   "ar_len IS NULL" ),
						__METHOD__ );
			# Go through and update ar_len from these rows.
			foreach ( $res as $row ) {
				$rev = Revision::newFromArchiveRow( $row );
				$content = $rev->getContent();
				if ( !$content ) {
					# This should not happen, but sometimes does (bug 20757)
					$this->output( "Content of archive {$row->ar_id} unavailable!\n" );
					$missing++;
				}
				else {
					# Update the row...
					$db->update( 'archive',
							 array( 'ar_len' => $content->getSize() ),
							 array( 'ar_id' => $row->ar_id ),
							 __METHOD__ );
					$count++;
				}
			}
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}

		$this->output( "ar_len population complete ... {$count} rows changed ({$missing} missing)\n" );
		return true;
	}
}

$maintClass = "PopulateArchiveLength";
require_once( RUN_MAINTENANCE_IF_MAIN );
