<?php

namespace Miraheze\ManageWiki\Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

use Maintenance;
use MediaWiki\MediaWikiServices;
use Miraheze\ManageWiki\ManageWiki;
use Wikimedia\AtEase\AtEase;

class PopulateNamespaces extends Maintenance {
	public function __construct() {
		parent::__construct();
	}

	public function execute() {
		if ( ManageWiki::checkSetup( 'namespaces' ) ) {
			$this->fatalError( 'Disable ManageWiki Namespaces on this wiki.' );
		}

		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$dbw = $this->getDB( DB_PRIMARY, [], $config->get( 'CreateWikiDatabase' ) );

		$namespaces = $config->get( 'CanonicalNamespaceNames' ) + [ 0 => '<Main>' ];

		AtEase::suppressWarnings();

		foreach ( $namespaces as $id => $name ) {
			if ( $id < 0 ) {
				// We don't like 'imaginary' namespaces
				continue;
			}

			$matchedNSKeys = array_keys( $config->get( 'NamespaceAliases' ), $id );
			$nsAliases = [];

			foreach ( $matchedNSKeys as $o => $n ) {
				$nsAliases[] = $n;
			}

			$res = $dbw->select(
				'mw_namespaces',
				[
					'ns_namespace_name',
					'ns_namespace_id'
				],
				[
					'ns_dbname' => $config->get( 'DBname' )
				],
				__METHOD__
			);

			if ( !$res || !is_object( $res ) ) {
				$this->insertNamespace( $dbw, $config, $id, $name, $nsAliases );
			} else {
				foreach ( $res as $row ) {
					if ( $row->ns_namespace_id !== (int)$id ) {
						$this->insertNamespace( $dbw, $config, $id, $name, $nsAliases );
					}
				}
			}
		}

		AtEase::restoreWarnings();
	}

	public function insertNamespace( $dbw, $config, $id, $name, $nsAliases ) {
		$dbw->insert(
			'mw_namespaces',
			[
				'ns_dbname' => $config->get( 'DBname' ),
				'ns_namespace_id' => (int)$id,
				'ns_namespace_name' => (string)$name,
				'ns_searchable' => (int)$config->get( 'NamespacesToBeSearchedDefault' )[$id],
				'ns_subpages' => (int)$config->get( 'NamespacesWithSubpages' )[$id],
				'ns_content' => (int)$config->get( 'ContentNamespaces' )[$id],
				'ns_content_model' => isset( $config->get( 'NamespaceContentModels' )[$id] ) ? (string)$config->get( 'NamespaceContentModels' )[$id] : 'wikitext',
				'ns_protection' => ( is_array( $config->get( 'NamespaceProtection' )[$id] ) ) ? (string)$config->get( 'NamespaceProtection' )[$id][0] : (string)$config->get( 'NamespaceProtection' )[$id],
				'ns_aliases' => (string)json_encode( $nsAliases ),
				'ns_core' => (int)( $id < 1000 ),
				'ns_additional' => (string)json_encode( [] ),
			],
			__METHOD__
		);
	}
}

$maintClass = PopulateNamespaces::class;
require_once RUN_MAINTENANCE_IF_MAIN;
