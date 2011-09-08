<?php
/**
 * Makes the required database updates for populating the
 * log_search table retroactively
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

class PopulateLogSearch extends LoggedUpdateMaintenance {
	static $tableMap = array( 'rev' => 'revision', 'fa' => 'filearchive', 'oi' => 'oldimage', 'ar' => 'archive' );

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Migrate log params to new table and index for searching";
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return 'populate log_search';
	}

	protected function updateSkippedMessage() {
		return 'log_search table already populated.';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'log_search' ) ) {
			$this->error( "log_search does not exist" );
			return false;
		}
		$start = $db->selectField( 'logging', 'MIN(log_id)', false, __FUNCTION__ );
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );
			return true;
		}
		$end = $db->selectField( 'logging', 'MAX(log_id)', false, __FUNCTION__ );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		$delTypes = array( 'delete', 'suppress' ); // revisiondelete types
		while ( $blockEnd <= $end ) {
			$this->output( "...doing log_id from $blockStart to $blockEnd\n" );
			$cond = "log_id BETWEEN $blockStart AND $blockEnd";
			$res = $db->select( 'logging', '*', $cond, __FUNCTION__ );
			foreach ( $res as $row ) {
				// RevisionDelete logs - revisions
				if ( LogEventsList::typeAction( $row, $delTypes, 'revision' ) ) {
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <urlparam> <item CSV> [<ofield> <nfield>]
					if ( count( $params ) < 2 ) continue; // bad row?
					$field = RevisionDeleter::getRelationType( $params[0] );
					// B/C, the params may start with a title key (<title> <urlparam> <CSV>)
					if ( $field == null ) {
						array_shift( $params ); // remove title param
						$field = RevisionDeleter::getRelationType( $params[0] );
						if ( $field == null ) {
							$this->output( "Invalid param type for {$row->log_id}\n" );
							continue; // skip this row
						} else {
							// Clean up the row...
							$db->update( 'logging',
								array( 'log_params' => implode( ',', $params ) ),
								array( 'log_id' => $row->log_id ) );
						}
					}
					$items = explode( ',', $params[1] );
					$log = new LogPage( $row->log_type );
					// Add item relations...
					$log->addRelations( $field, $items, $row->log_id );
					// Determine what table to query...
					$prefix = substr( $field, 0, strpos( $field, '_' ) ); // db prefix
					if ( !isset( self::$tableMap[$prefix] ) )
						continue; // bad row?
					$table = self::$tableMap[$prefix];
					$userField = $prefix . '_user';
					$userTextField = $prefix . '_user_text';
					// Add item author relations...
					$userIds = $userIPs = array();
					$sres = $db->select( $table,
						array( $userField, $userTextField ),
						array( $field => $items )
					);
					foreach ( $sres as $srow ) {
						if ( $srow->$userField > 0 )
							$userIds[] = intval( $srow->$userField );
						elseif ( $srow->$userTextField != '' )
							$userIPs[] = $srow->$userTextField;
					}
					// Add item author relations...
					$log->addRelations( 'target_author_id', $userIds, $row->log_id );
					$log->addRelations( 'target_author_ip', $userIPs, $row->log_id );
				// RevisionDelete logs - log events
				} elseif ( LogEventsList::typeAction( $row, $delTypes, 'event' ) ) {
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <item CSV> [<ofield> <nfield>]
					if ( count( $params ) < 1 ) continue; // bad row
					$items = explode( ',', $params[0] );
					$log = new LogPage( $row->log_type );
					// Add item relations...
					$log->addRelations( 'log_id', $items, $row->log_id );
					// Add item author relations...
					$userIds = $userIPs = array();
					$sres = $db->select( 'logging',
						array( 'log_user', 'log_user_text' ),
						array( 'log_id' => $items )
					);
					foreach ( $sres as $srow ) {
						if ( $srow->log_user > 0 )
							$userIds[] = intval( $srow->log_user );
						elseif ( IP::isIPAddress( $srow->log_user_text ) )
							$userIPs[] = $srow->log_user_text;
					}
					$log->addRelations( 'target_author_id', $userIds, $row->log_id );
					$log->addRelations( 'target_author_ip', $userIPs, $row->log_id );
				}
			}
			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}
		$this->output( "Done populating log_search table.\n" );
		return true;
	}
}

$maintClass = "PopulateLogSearch";
require_once( RUN_MAINTENANCE_IF_MAIN );
