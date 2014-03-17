<?php

/**
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class LessTestSuite {
	public static function suite() {
		$resourceLoader = new ResourceLoader();
		$suite = new PHPUnit_Framework_TestSuite();

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

				$suite->addTest( new LessTestCase( $styleFile ) );
			}
		}

		return $suite;
	}
}
