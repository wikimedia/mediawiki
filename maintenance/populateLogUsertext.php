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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PopulateLogUsertext extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populates the log_user_text";
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$db = wfGetDB( DB_MASTER );
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
			$res = $db->select( array( 'logging', 'user' ),
				array( 'log_id', 'user_name' ), $cond, __METHOD__ );
			$batch = array();
			$db->begin();
			foreach ( $res as $row ) {
				$db->update( 'logging', array( 'log_user_text' => $row->user_name ),
					array( 'log_id' => $row->log_id ), __METHOD__ );
			}
			$db->commit();
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves( 5 );
		}
		if ( $db->insert(
				'updatelog',
				array( 'ul_key' => 'populate log_usertext' ),
				__METHOD__,
				'IGNORE'
			)
		) {
			$this->output( "log_usertext population complete.\n" );
			return true;
		} else {
			$this->output( "Could not insert log_usertext population row.\n" );
			return false;
		}
	}
}

$maintClass = "PopulateLogUsertext";
require_once( DO_MAINTENANCE );

