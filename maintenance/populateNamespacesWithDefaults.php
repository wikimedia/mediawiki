<?php

namespace Miraheze\ManageWiki\Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

use Maintenance;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;
use Miraheze\ManageWiki\Helpers\ManageWikiNamespaces;

class PopulateNamespacesWithDefaults extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'overwrite', 'This overwrites namespaces to reset them back to the default.', false, false );
	}

	public function execute() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$dbw = $this->getDB( DB_PRIMARY, [], $config->get( 'CreateWikiDatabase' ) );

		if ( $this->getOption( 'overwrite' ) ) {
			$dbw->delete(
				'mw_namespaces',
				[
					'ns_dbname' => $config->get( 'DBname' )
				],
				__METHOD__
			);
		}

		$checkRow = $dbw->selectRow(
			'mw_namespaces',
			[
				'*'
			],
			[
				'ns_dbname' => $config->get( 'DBname' )
			]
		);

		if ( !$checkRow ) {
			$mwNamespaces = new ManageWikiNamespaces( $config->get( 'DBname' ) );
			$mwNamespacesDefault = new ManageWikiNamespaces( 'default' );
			$defaultNamespaces = array_keys( $mwNamespacesDefault->list() );

			foreach ( $defaultNamespaces as $namespace ) {
				$mwNamespaces->modify( $namespace, $mwNamespacesDefault->list( $namespace ) );
				$mwNamespaces->commit();
			}

			$cWJ = new CreateWikiJson( $config->get( 'DBname' ) );
			$cWJ->resetWiki();
		}
	}
}

$maintClass = PopulateNamespacesWithDefaults::class;
require_once RUN_MAINTENANCE_IF_MAIN;
