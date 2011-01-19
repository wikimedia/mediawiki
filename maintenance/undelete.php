<?php
/**
 * Undelete a page by fetching it from the archive table
 *
 * @file
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class Undelete extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Undelete a page";
		$this->addOption( 'u', 'The user to perform the undeletion', false, true );
		$this->addOption( 'r', 'The reason to undelete', false, true );
		$this->addArg( 'pagename', 'Page to undelete' );
	}

	public function execute() {
		global $wgUser;

		$user = $this->getOption( 'u', 'Command line script' );
		$reason = $this->getOption( 'r', '' );
		$pageName = $this->getArg();

		$title = Title::newFromText( $pageName );
		if ( !$title ) {
			$this->error( "Invalid title", true );
		}
		$wgUser = User::newFromName( $user );
		$archive = new PageArchive( $title );
		$this->output( "Undeleting " . $title->getPrefixedDBkey() . '...' );
		$archive->undelete( array(), $reason );
		$this->output( "done\n" );
	}
}

$maintClass = "Undelete";
require_once( RUN_MAINTENANCE_IF_MAIN );
