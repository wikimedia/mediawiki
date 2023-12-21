<?php

use PHPUnit\Runner\BeforeFirstTestHook;

/**
 * PHPUnit extension temporarily used to emit a deprecation notice when suite.xml is used
 */
class MediaWikiDeprecatedConfigPHPUnitExtension implements BeforeFirstTestHook {
	public function executeBeforeFirstTest(): void {
		if ( defined( 'PHPUNIT_LEGACY_ENTRYPOINT' ) ) {
			// A deprecation notice was already emitted by phpunit.php, no need to add another one.
			return;
		}
		$msg = <<<EOT
*******************************************************************************
DEPRECATED: The tests/phpunit/suite.xml config file has been deprecated. Use
            phpunit.xml.dist instead.
*******************************************************************************

EOT;

		fwrite( STDERR, $msg );
	}
}
