<?php

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestSuite;

/**
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class LessTestSuite extends TestSuite {
	public function __construct() {
		parent::__construct();

		$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();

		foreach ( $resourceLoader->getModuleNames() as $name ) {
			$module = $resourceLoader->getModule( $name );
			if ( !$module || !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			foreach ( $module->getAllStyleFiles() as $styleFile ) {
				// TODO (phuedx, 2014-03-19) The
				// ResourceLoaderFileModule class shouldn't
				// know how to get a file's extension.
				if ( $module->getStyleSheetLang( $styleFile ) !== 'less' ) {
					continue;
				}

				$this->addTest( new LessFileCompilationTest( $styleFile, $module ) );
			}
		}
	}

	public static function suite() {
		return new static;
	}
}
