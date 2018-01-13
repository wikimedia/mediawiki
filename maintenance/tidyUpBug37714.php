<?php
require_once __DIR__ . '/Maintenance.php';

/**
 * Fixes all rows affected by https://bugzilla.wikimedia.org/show_bug.cgi?id=37714
 */
class TidyUpBug37714 extends Maintenance {
	public function execute() {
		// Search for all log entries which are about changing the visability of other log entries.
		$result = $this->getDB( DB_REPLICA )->select(
			'logging',
			[ 'log_id', 'log_params' ],
			[
				'log_type' => [ 'suppress', 'delete' ],
				'log_action' => 'event',
				'log_namespace' => NS_SPECIAL,
				'log_title' => SpecialPage::getTitleFor( 'Log' )->getText()
			],
			__METHOD__
		);

		foreach ( $result as $row ) {
			$ids = explode( ',', explode( "\n", $row->log_params )[0] );
			$result = $this->getDB( DB_REPLICA )->select( // Work out what log entries were changed here.
				'logging',
				'log_type',
				[ 'log_id' => $ids ],
				__METHOD__,
				'DISTINCT'
			);
			if ( $result->numRows() === 1 ) {
				// If there's only one type, the target title can be set to include it.
				$logTitle = SpecialPage::getTitleFor( 'Log', $result->current()->log_type )->getText();
				$this->output( 'Set log_title to "' . $logTitle . '" for log entry ' . $row->log_id . ".\n" );
				$this->getDB( DB_MASTER )->update(
					'logging',
					[ 'log_title' => $logTitle ],
					[ 'log_id' => $row->log_id ],
					__METHOD__
				);
				wfWaitForSlaves();
			}
		}
	}
}

$maintClass = TidyUpBug37714::class;
require_once RUN_MAINTENANCE_IF_MAIN;
