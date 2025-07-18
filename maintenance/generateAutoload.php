<?php

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// This maintenance script is run after renaming class files,
// and is ideally standalone, invoking only a handful of classes.
// Otherwise, it will crash and defeat its purpose for being when
// it calls a class impacted by the renamed file.
// The below skips SessionManager::singleton() in Setup.php, which
// indirectly loads thousands of classes across the codebase.
define( 'MW_NO_SESSION_HANDLER', 1 );
// If something still tries to call it, fail hard.
define( 'MW_NO_SESSION', 1 );
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
