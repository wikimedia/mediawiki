<?php
/**
 * Makes the required database updates for Special:ProtectedPages
 * to show all protected pages, even ones before the page restrictions
 * schema change. All remaining page_restriction column values are moved
 * to the new table.
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
 * Maintenance script that makes the required database updates for
 * Special:ProtectedPages to show all protected pages.
 *
 * @ingroup Maintenance
 */
class PopulateLogUsertext extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates the log_user_text field' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return 'populate log_usertext';
	}

	protected function updateSkippedMessage() {
		return 'log_user_text column of logging table already populated.';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		$start = $db->selectField( 'logging', 'MIN(log_id)', false, __METHOD__ );
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );

			return true;
		}
		$end = $db->selectField( 'logging', 'MAX(log_id)', false, __METHOD__ );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing log_id from $blockStart to $blockEnd\n" );
			$cond = "log_id BETWEEN $blockStart AND $blockEnd AND log_user = user_id";
			$res = $db->select( [ 'logging', 'user' ],
				[ 'log_id', 'user_name' ], $cond, __METHOD__ );

			$this->beginTransaction( $db, __METHOD__ );
			foreach ( $res as $row ) {
				$db->update( 'logging', [ 'log_user_text' => $row->user_name ],
					[ 'log_id' => $row->log_id ], __METHOD__ );
			}
			$this->commitTransaction( $db, __METHOD__ );
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}
		$this->output( "Done populating log_user_text field.\n" );

		return true;
	}
}

$maintClass = "PopulateLogUsertext";
require_once RUN_MAINTENANCE_IF_MAIN;
