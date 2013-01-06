<?php
require_once( __DIR__ . '/Maintenance.php' );

class TidyUpBug37714 extends Maintenance {
	public function execute() {
		$result = wfGetDB( DB_SLAVE )->select( // Search for all log entries which are about changing the visability of other log entries.
			'logging',
			array( 'log_id', 'log_params' ),
			array(
				'log_type' => array( 'suppress', 'delete' ),
				'log_action' => 'event',
				'log_namespace' => NS_SPECIAL,
				'log_title' => SpecialPage::getTitleFor( 'Log' )->getText()
			),
			__METHOD__
		);

		foreach ( $result as $row ) {
			$paramLines = explode( "\n", $row->log_params );
			$ids = explode( ",", $paramLines[0] ); // Array dereferencing is PHP >= 5.4 :(
			$result = wfGetDB( DB_SLAVE )->select( // Work out what log entries were changed here.
				'logging',
				'log_type',
				array( 'log_id' => $ids ),
				__METHOD__,
				'DISTINCT'
			);
			if ( $result->numRows() == 1 ) {
				// If there's only one type, the target title can be set to include it.
				$this->output( 'Set log_title to "' . SpecialPage::getTitleFor( 'Log', $result->current()->log_type )->getText() . '" for log entry ' . $row->log_id . ".\n" );
				wfGetDB( DB_MASTER )->update(
					'logging',
					array( 'log_title' => SpecialPage::getTitleFor( 'Log', $result->current()->log_type )->getText() ),
					array( 'log_id' => $row->log_id ),
					__METHOD__
				);
			}
		}
	}
}

$maintClass = 'TidyUpBug37714';
require_once( RUN_MAINTENANCE_IF_MAIN );
