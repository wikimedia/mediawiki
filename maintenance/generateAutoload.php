<?php

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class GenerateAutoload extends Maintenance {

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	/** @inheritDoc */
	public function getDbType() {
		return self::DB_NONE;
	}

	public function execute() {
		$generator = new AutoloadGenerator( MW_INSTALL_PATH, 'local' );
		$generator->initMediaWikiDefault();

		// Write out the autoload
		$fileinfo = $generator->getTargetFileinfo();
		file_put_contents(
			$fileinfo['filename'],
			$generator->getAutoload( 'maintenance/generateLocalAutoload.php' )
		);
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateAutoload::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
