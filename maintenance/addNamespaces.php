<?php

namespace Miraheze\ManageWiki\Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

use Maintenance;
use MediaWiki\MediaWikiServices;
use Miraheze\ManageWiki\Helpers\ManageWikiNamespaces;

class AddNamespaces extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'default', 'Wheather to add the namespace to the \'default\' db name (Defaults to wgDBname).' );
		$this->addOption( 'id', 'The namespace id e.g 1.', true, true );
		$this->addOption( 'name', 'The name of the namespace e.g \'Module\'.', true, true );
		$this->addOption( 'searchable', 'Whether the namespace is searchable.' );
		$this->addOption( 'subpages', 'Whether the namespace has a subpage.' );
		$this->addOption( 'content', 'Whether the namespace has content' );
		$this->addOption( 'contentmodel', 'The content model to use for the namespace.', true, true );
		$this->addOption( 'protection', 'Whether this namespace has protection.', true, true );
		$this->addOption( 'core', 'Whether to allow the namespaces to be renamed or not.' );
	}

	public function execute() {
		$dbname = $this->getOption( 'default' ) ? 'default' : MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' )->get( 'DBname' );

		$mwNamespaces = new ManageWikiNamespaces( $dbname );

		$nsData = [
			'name' => (string)$this->getOption( 'name' ),
			'searchable' => (int)$this->getOption( 'searchable' ),
			'subpages' => (int)$this->getOption( 'subpages' ),
			'protection' => (string)$this->getOption( 'protection' ),
			'content' => (int)$this->getOption( 'content' ),
			'contentmodel' => (string)$this->getOption( 'contentmodel' ),
			'core' => (int)$this->getOption( 'core' )
		];

		$mwNamespaces->modify( (int)$this->getOption( 'id' ), $nsData );
		$mwNamespaces->commit();
	}
}

$maintClass = AddNamespaces::class;
require_once RUN_MAINTENANCE_IF_MAIN;
