<?php

namespace Miraheze\ManageWiki\Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

use Maintenance;
use MediaWiki\MediaWikiServices;

class MigrateSettingsAndExtensions extends Maintenance {
	public function __construct() {
		parent::__construct();
	}

	public function execute() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$dbw = $this->getDB( DB_PRIMARY, [], $config->get( 'CreateWikiDatabase' ) );

		$res = $dbw->select(
			'cw_wikis',
			[
				'wiki_dbname',
				'wiki_settings',
				'wiki_extensions'
			]
		);

		foreach ( $res as $row ) {
			$extensionsArray = explode( ',', $row->wiki_extensions );
			$extensions = [];

			foreach ( $extensionsArray as $ext ) {
				if ( isset( $config->get( 'ManageWikiExtensions' )[$ext] ) ) {
					$extensions[] = $ext;
				}
			}

			$dbw->insert(
				'mw_settings',
				[
					's_dbname' => $row->wiki_dbname,
					's_settings' => $row->wiki_settings,
					's_extensions' => json_encode( $extensions )
				]
			);
		}
	}
}

$maintClass = MigrateSettingsAndExtensions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
