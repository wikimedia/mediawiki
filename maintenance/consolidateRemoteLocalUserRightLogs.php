<?php
require_once( __DIR__ . '/Maintenance.php' );

class ConsolidateRemoteLocalUserRightLogs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fix remote user rights logs for the local wiki. E.g. User:Krenair@currentwiki -> User:Krenair";
	}

	public function execute() {
		global $wgUserrightsInterwikiDelimiter;
		$result = wfGetDB( DB_SLAVE )->select(
			'logging',
			array( 'log_id', 'log_title' ),
			array( 'log_title LIKE %' . $wgUserrightsInterwikiDelimiter . wfWikiID() ),
			__METHOD__
		);

		foreach ( $result as $row ) {
			list( $name, $database ) = explode( $wgUserrightsInterwikiDelimiter, $row->log_title );

			$this->output( "Changed '{$row->log_title}' to '{$name}'.\n" );
			wfGetDB( DB_MASTER )->update(
				'logging',
				array( 'log_title' => $name ),
				array( 'log_id' => $row->log_id ),
				__METHOD__
			);
		}
	}
}

$maintClass = "ConsolidateRemoteLocalUserRightLogs";
require_once( RUN_MAINTENANCE_IF_MAIN );