<?php
/**
 * @file
 * @ingroup MaintenanceLanguage
 *
 * Get all the translations messages, as defined in the English language file.
 */

require_once( dirname(__FILE__) . '/../Maintenance.php' );

class AllTrans extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Get all messages as defined by the English language file";
	}

	public function execute() {
		$wgEnglishMessages = array_keys( Language::getMessagesFor( 'en' ) );
		foreach( $wgEnglishMessages as $key ) {
			$this->output( "$key\n" );
		}
	}
}

$maintClass = "AllTrans";
require_once( DO_MAINTENANCE );
