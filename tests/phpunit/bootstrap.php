<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	echo <<<EOF
You are running these tests directly from phpunit. You may not have all globals correctly set.
Running phpunit.php instead is recommended.
EOF;
	require_once ( dirname( __FILE__ ) . "/phpunit.php" );
}

// Output a notice when running with older versions of PHPUnit
if ( !version_compare( PHPUnit_Runner_Version::id(), "3.4.1", ">" ) ) {
  echo <<<EOF
********************************************************************************

These tests run best with version PHPUnit 3.4.2 or better. Earlier versions may
show failures because earlier versions of PHPUnit do not properly implement
dependencies.

********************************************************************************

EOF;
}

global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
global $wgMessageCache, $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry, $wgMemc;
$wgMainCacheType = CACHE_NONE;
$wgMessageCacheType = CACHE_NONE;
$wgParserCacheType = CACHE_NONE;
$wgUseDatabaseMessages = false; # Set for future resets
$wgMemc = new FakeMemCachedClient;

# The message cache was already created in Setup.php
$wgMessageCache = new StubObject( 'wgMessageCache', 'MessageCache',
	array( $messageMemc, $wgUseDatabaseMessages, $wgMsgCacheExpiry ) );
