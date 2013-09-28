<?php

require_once __DIR__ . '/Maintenance.php';

class CheckLess extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Checks LESS files for errors';
	}

	public function execute() {
		$result = 0;

		$compiler = ResourceLoader::getLessCompiler();
		$resourceLoader = new ResourceLoader();
		foreach( $resourceLoader->getModuleNames() as $name ) {
			/** @var ResourceLoaderFileModule $module */
			$module = $resourceLoader->getModule( $name );
			if ( !$module || !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$hadErrors = false;
			foreach ( $module->getAllStyleFiles() as $file ) {
				try {
					$compiler->compileFile( $file );
				} catch ( Exception $e ) {
					if ( !$hadErrors ) {
						$this->error( "Errors checking module $name:\n" );
						$hadErrors = true;
					}
					$this->error( $e->getMessage() . "\n" );
					$result = 1;
				}
			}
		}
		if ( !$result ) {
			$this->output( "No errors found\n" );
		}

		return $result;
	}
}

$maintClass = 'CheckLess';
require_once RUN_MAINTENANCE_IF_MAIN;
