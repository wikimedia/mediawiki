<?php

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../..';
}
require_once "$IP/maintenance/Maintenance.php";

class ChangeContentModel extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'page', 'Page to fix', true );
		$this->addArg( 'type', 'Either js or css', true );
		$this->addOption( 'module', 'Module', false, false );
	}

	public function execute() {
		if ( $this->hasOption( 'module' ) ) {
			$this->fixModules();
			return;
		}
		$this->fixTitle( Title::newFromText( $this->getArg( 0 ) ) );
	}

	protected function fixModules() {
		$dbr = wfGetDB( DB_SLAVE );
		$pages = $dbr->select( 'page', array( 'page_namespace', 'page_title' ), array( 'page_namespace' => NS_MODULE ), __METHOD__ );
		foreach ( $pages as $row ) {
			$title = Title::newFromRow( $row );
			$this->output( "$title\n" );
			$this->fixTitle( $title );
		}
	}

	protected function fixTitle( Title $title ) {
		$page = WikiPage::factory( $title );
		$old = $page->getContent()->getNativeData();
		$model = $this->getArg( 1 );
		if ( $page->getTitle()->inNamespace( NS_MODULE ) ) {
			$model = 'scribunto';
		}
		switch ( $model ) {
			case 'js':
				$new = new JavaScriptContent( $old );
				break;
			case 'css':
				$new = new CssContent( $old );
				break;
			case 'scribunto':
				$new = new ScribuntoContent( $old );
				break;
			default:
				throw new Exception( "Invalid arg" );
		}
		if ( get_class( $page->getContent() ) === get_class( $new ) ) {
			$this->error( "Already fixed" );
			return;
		}
		$page->doEditContent( $new, 'Fixing content model', EDIT_UPDATE, false, User::newFromName( 'Legoktm' ) );
		$this->output( "Done\n" );
	}
}

$maintClass = 'ChangeContentModel';
require_once RUN_MAINTENANCE_IF_MAIN;
