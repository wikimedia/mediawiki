<?php
require_once( __DIR__ . '/Maintenance.php' );

class ConsolidateRemoteLocalUserRightLogs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fix remote user rights logs for the local wiki. E.g. User:Krenair@currentwiki -> User:Krenair";
	}

	public function execute() {
		global $wgUserrightsInterwikiDelimiter;
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'logging',
			'log_title',
			array( 'log_title' . $dbr->buildLike( $dbr->anyString(), $wgUserrightsInterwikiDelimiter, wfWikiID() ) ),
			__METHOD__,
			'DISTINCT'
		);

		foreach ( $result as $row ) {
			list( $name, $database ) = explode( $wgUserrightsInterwikiDelimiter, $row->log_title );

			$this->output( "Changed '{$row->log_title}' to '{$name}'.\n" );
			wfGetDB( DB_MASTER )->update(
				'logging',
				array( 'log_title' => $name ),
				array( 'log_title' => $row->log_title ),
				__METHOD__
			);
		}
	}
}

$maintClass = "ConsolidateRemoteLocalUserRightLogs";
require_once( RUN_MAINTENANCE_IF_MAIN );