<?php
/**
 * Makes the required database updates for Special:ProtectedPages
 * to show all protected pages, even ones before the page restrictions
 * schema change. All remaining page_restriction column values are moved
 * to the new table.
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class PopulateLogSearch extends Maintenance {

	const LOG_SEARCH_BATCH_SIZE = 100;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Migrate log params to new table and index for searching";
	}

	public function execute() {
		$db = wfGetDB( DB_MASTER );
		if ( !$db->tableExists( 'log_search' ) ) {
			$this->error( "log_search does not exist\n", true );
		}
		$start = $db->selectField( 'logging', 'MIN(log_id)', false, __FUNCTION__ );
		if( !$start ) {
			$this->output( "Nothing to do.\n" );
			return true;
		}
		$end = $db->selectField( 'logging', 'MAX(log_id)', false, __FUNCTION__ );
	
		# Do remaining chunk
		$end += self::LOG_SEARCH_BATCH_SIZE - 1;
		$blockStart = $start;
		$blockEnd = $start + self::LOG_SEARCH_BATCH_SIZE - 1;
		while( $blockEnd <= $end ) {
			$this->output( "...doing log_id from $blockStart to $blockEnd\n" );
			$cond = "log_id BETWEEN $blockStart AND $blockEnd";
			$res = $db->select( 'logging', '*', $cond, __FUNCTION__ );
			$batch = array();
			while( $row = $db->fetchObject( $res ) ) {
				// RevisionDelete logs - revisions
				if( LogEventsList::typeAction( $row, array('delete','suppress'), 'revision' ) ) {
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <urlparam> <item CSV> [<ofield> <nfield>]
					if( count($params) >= 2 ) {
						$field = RevisionDeleter::getRelationType($params[0]);
						// B/C, the params may start with a title key
						if( $field == null ) {
							array_shift($params);
							$field = RevisionDeleter::getRelationType($params[0]);
						}
						if( $field == null ) {
							$this->output( "Invalid param type for $row->log_id\n" );
							continue; // skip this row
						}
						$items = explode(',',$params[1]);
						$log = new LogPage( $row->log_type );
						$log->addRelations( $field, $items, $row->log_id );
					}
				// RevisionDelete logs - log events
				} else if( LogEventsList::typeAction( $row, array('delete','suppress'), 'event' ) ) {
					$params = LogPage::extractParams( $row->log_params );
					// Param format: <item CSV> [<ofield> <nfield>]
					if( count($params) >= 1 ) {
						$items = explode(',',$params[0]);
						$log = new LogPage( $row->log_type );
						$log->addRelations( 'log_id', $items, $row->log_id );
					}
				}
			}
			$blockStart += self::LOG_SEARCH_BATCH_SIZE;
			$blockEnd += self::LOG_SEARCH_BATCH_SIZE;
			wfWaitForSlaves( 5 );
		}
		if( $db->insert(
				'updatelog',
				array( 'ul_key' => 'populate log_search' ),
				__FUNCTION__,
				'IGNORE'
			)
		) {
			$this->output( "log_search population complete.\n" );
			return true;
		} else {
			$this->output( "Could not insert log_search population row.\n" );
			return false;
		}
	}
}

$maintClass = "PopulateLogSearch";
require_once( DO_MAINTENANCE );
