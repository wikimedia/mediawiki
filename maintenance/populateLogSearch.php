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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that makes the required database updates for populating the
 * log_search table retroactively
 *
 * @ingroup Maintenance
 */
class PopulateLogSearch extends LoggedUpdateMaintenance {
	private static $tableMap = [
		'rev' => 'revision',
		'fa' => 'filearchive',
		'oi' => 'oldimage',
		'ar' => 'archive'
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrate log params to new table and index for searching' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return 'populate log_search';
	}

	protected function updateSkippedMessage() {
		return 'log_search table already populated.';
	}

	protected function doDBUpdates() {
		$batchSize = $this->getBatchSize();
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'log_search' ) ) {
			$this->error( "log_search does not exist" );

			return false;
		}
		$start = $db->selectField( 'logging', 'MIN(log_id)', '', __FUNCTION__ );
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );

			return true;
		}
		$end = $db->selectField( 'logging', 'MAX(log_id)', '', __FUNCTION__ );

		// This maintenance script is for updating pre-1.16 to 1.16. The target_author_id and
		// target_author_ip relations it adds will later be migrated to target_author_actor by
		// migrateActors.php. If the schema is already 1.34, we should have nothing to do.
		if ( !$db->fieldExists( 'logging', 'log_user' ) ) {
			$this->output(
				"This does not appear to be an upgrade from MediaWiki pre-1.16 "
				. "(logging.log_user does not exist).\n"
			);
			$this->output( "Nothing to do.\n" );

			return true;
		}

		# Do remaining chunk
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;

		$delTypes = [ 'delete', 'suppress' ]; // revisiondelete types
		while ( $blockEnd <= $end ) {
			$this->output( "...doing log_id from $blockStart to $blockEnd\n" );
			$cond = "log_id BETWEEN " . (int)$blockStart . " AND " . (int)$blockEnd;
			$res = $db->select(
				'logging', [ 'log_id', 'log_type', 'log_action', 'log_params' ], $cond, __FUNCTION__
			);
			foreach ( $res as $row ) {
				// RevisionDelete logs - revisions
				if ( LogEventsList::typeAction( $row, $delTypes, 'revision' ) ) {
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <urlparam> <item CSV> [<ofield> <nfield>]
					if ( count( $params ) < 2 ) {
						continue; // bad row?
					}
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
								[ 'log_params' => implode( ',', $params ) ],
								[ 'log_id' => $row->log_id ] );
						}
					}
					$items = explode( ',', $params[1] );
					$log = new LogPage( $row->log_type );
					// Add item relations...
					$log->addRelations( $field, $items, $row->log_id );
					// Determine what table to query...
					$prefix = substr( $field, 0, strpos( $field, '_' ) ); // db prefix
					if ( !isset( self::$tableMap[$prefix] ) ) {
						continue; // bad row?
					}
					$table = self::$tableMap[$prefix];
					$userField = $prefix . '_user';
					$userTextField = $prefix . '_user_text';
					// Add item author relations...
					$userIds = $userIPs = [];
					$sres = $db->select( $table,
						[ $userField, $userTextField ],
						[ $field => $items ]
					);
					foreach ( $sres as $srow ) {
						if ( $srow->$userField > 0 ) {
							$userIds[] = intval( $srow->$userField );
						} elseif ( $srow->$userTextField != '' ) {
							$userIPs[] = $srow->$userTextField;
						}
					}
					// Add item author relations...
					$log->addRelations( 'target_author_id', $userIds, $row->log_id );
					$log->addRelations( 'target_author_ip', $userIPs, $row->log_id );
				} elseif ( LogEventsList::typeAction( $row, $delTypes, 'event' ) ) {
					// RevisionDelete logs - log events
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <item CSV> [<ofield> <nfield>]
					if ( count( $params ) < 1 ) {
						continue; // bad row
					}
					$items = explode( ',', $params[0] );
					$log = new LogPage( $row->log_type );
					// Add item relations...
					$log->addRelations( 'log_id', $items, $row->log_id );
					// Add item author relations...
					$userIds = $userIPs = [];
					$sres = $db->select( 'logging',
						[ 'log_user', 'log_user_text' ],
						[ 'log_id' => $items ]
					);
					foreach ( $sres as $srow ) {
						if ( $srow->log_user > 0 ) {
							$userIds[] = intval( $srow->log_user );
						} elseif ( IP::isIPAddress( $srow->log_user_text ) ) {
							$userIPs[] = $srow->log_user_text;
						}
					}
					$log->addRelations( 'target_author_id', $userIds, $row->log_id );
					$log->addRelations( 'target_author_ip', $userIPs, $row->log_id );
				}
			}
			$blockStart += $batchSize;
			$blockEnd += $batchSize;
			wfWaitForSlaves();
		}
		$this->output( "Done populating log_search table.\n" );

		return true;
	}
}

$maintClass = PopulateLogSearch::class;
require_once RUN_MAINTENANCE_IF_MAIN;
