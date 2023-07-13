<?php

/**
 * Bootstrapping for MediaWiki PHPUnit tests that allows running integration tests.
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

use MediaWiki\MediaWikiServices;

function wfPHPUnitFinalSetup() {
	global $wgDBadminuser, $wgDBadminpassword;
	global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

	// These are already set in suite.xml, but set them again in case they were changed in a settings file
	ini_set( 'memory_limit', -1 );
	ini_set( 'max_execution_time', 0 );

	if ( isset( $wgDBadminuser ) ) {
		$wgDBuser = $wgDBadminuser;
		$wgDBpassword = $wgDBadminpassword;

		if ( $wgDBservers ) {
			/**
			 * @var array $wgDBservers
			 */
			foreach ( $wgDBservers as $i => $server ) {
				$wgDBservers[$i]['user'] = $wgDBuser;
				$wgDBservers[$i]['password'] = $wgDBpassword;
			}
		}
		if ( isset( $wgLBFactoryConf['serverTemplate'] ) ) {
			$wgLBFactoryConf['serverTemplate']['user'] = $wgDBuser;
			$wgLBFactoryConf['serverTemplate']['password'] = $wgDBpassword;
		}
		$service = MediaWikiServices::getInstance()->peekService( 'DBLoadBalancerFactory' );
		if ( $service ) {
			$service->destroy();
		}
	}

	TestSetup::requireOnceInGlobalScope( __DIR__ . '/../common/TestsAutoLoader.php' );

	TestSetup::applyInitialConfig();

	ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
}

require_once __DIR__ . '/bootstrap.common.php';
$IP = $GLOBALS['IP'];

define( 'MW_SETUP_CALLBACK', 'wfPHPUnitFinalSetup' );

TestSetup::requireOnceInGlobalScope( "$IP/includes/Setup.php" );
// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
// stays in tact. Needs to happen after including Setup.php, which calls MWExceptionHandler::installHandle().
restore_error_handler();

TestSetup::maybeCheckComposerLockUpToDate();
