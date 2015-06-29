<?php

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../..';
}
require_once "$IP/maintenance/Maintenance.php";

class ChangeContentModel extends Maintenance {
	/**
	 * Map of namespaces to types, only for namespaces with a single type
	 * namespace constant name (as string) => type
	 */
	public static $namespaceMap = array(
		'NS_MODULE' => 'scribunto',
		'NS_CAMPAIGN' => 'campaign',
	);

	public function __construct() {
		parent::__construct();
		$this->addArg( 'page', 'Page to fix', true );
		$this->addArg( 'type', 'One of: wikitext, js, css, scribunto, campaign', false );
		$this->addOption( 'user', 'Username', false, true, 'u' );
		$this->addOption( 'module', 'Module', false, false );
	}

	public function execute() {
		if ( $this->hasOption( 'module' ) ) {
			$this->fixModules();
			return;
		}
		$this->fixTitle( Title::newFromText( $this->getArg( 0 ) ) );
	}

	/**
	 * @var WikiPage $page
	 */
	protected function getType( $page ) {
		foreach ( self::$namespaceMap as $constant => $type ) {
			if ( defined( $constant ) && $page->getTitle()->inNamespace( constant( $constant) ) ) {
				return $type;
			}
		}
		$type = $this->getArg( 1 );
		if ( !$type ) {
			$this->error( 'Please provide the content type', 1 );
		}
		return $type;
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
		if ( !$title->exists() ) {
			$this->error( 'Page does not exist', 1 );
		}
		$page = WikiPage::factory( $title );
		$type = $this->getType( $page );
		$old = $page->getContent()->getNativeData();
		switch ( $type ) {
			case 'wikitext':
				$new = new WikitextContent( $old );
				break;
			case 'js':
				$new = new JavaScriptContent( $old );
				break;
			case 'css':
				$new = new CssContent( $old );
				break;
			case 'scribunto':
				$new = new ScribuntoContent( $old );
				break;
			case 'campaign':
				$new = new CampaignContent( $old );
				break;
			default:
				$this->error( "Invalid content type: $type", 1 );
		}
		if ( get_class( $page->getContent() ) === get_class( $new ) ) {
			$this->error( "Already fixed" );
			return;
		}
		$user = User::newFromName( $this->getOption( 'user', 'Maintenance script' ) );
		$page->doEditContent( $new, 'Fixing content model', EDIT_UPDATE, false, $user );
		$this->output( "Done\n" );
	}
}

$maintClass = 'ChangeContentModel';
require_once RUN_MAINTENANCE_IF_MAIN;

