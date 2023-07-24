<?php
require_once __DIR__ . '/Maintenance.php';

class GenerateAutoload extends Maintenance {

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	public function getDbType() {
		return self::DB_NONE;
	}

	public function execute() {
		$generator = new AutoloadGenerator( MW_INSTALL_PATH, 'local' );
		$generator->setPsr4Namespaces( AutoLoader::CORE_NAMESPACES );
		$generator->initMediaWikiDefault();

		// Write out the autoload
		$fileinfo = $generator->getTargetFileinfo();
		file_put_contents(
			$fileinfo['filename'],
			$generator->getAutoload( 'maintenance/generateLocalAutoload.php' )
		);
	}
}

$maintClass = GenerateAutoload::class;
require_once RUN_MAINTENANCE_IF_MAIN;
