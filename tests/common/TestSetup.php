<?php

/**
 * Common code for test environment initialisation and teardown
 */
class TestSetup {
	public static $bootstrapGlobals;

	/**
	 * For use in MediaWikiUnitTestCase.
	 *
	 * This should be called before DefaultSettings.php or Setup.php loads.
	 */
	public static function snapshotGlobals() {
		self::$bootstrapGlobals = [];
		foreach ( $GLOBALS as $key => $_ ) {
			// Support: HHVM (avoid self-ref)
			if ( $key !== 'GLOBALS' ) {
				self::$bootstrapGlobals[ $key ] =& $GLOBALS[$key];
			}
		}
	}

	/**
	 * This should be called before Setup.php, e.g. from the finalSetup() method
	 * of a Maintenance subclass
	 */
	public static function applyInitialConfig() {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType, $wgMainWANCache;
		global $wgMainStash;
		global $wgObjectCaches;
		global $wgLanguageConverterCacheType, $wgUseDatabaseMessages;
		global $wgLocaltimezone, $wgLocalisationCacheConf;
		global $wgSearchType;
		global $wgDevelopmentWarnings;
		global $wgSessionProviders, $wgSessionPbkdf2Iterations;
		global $wgJobTypeConf;
		global $wgAuthManagerConfig;
		global $wgShowExceptionDetails;

		$wgShowExceptionDetails = true;

		// wfWarn should cause tests to fail
		$wgDevelopmentWarnings = true;

		// Make sure all caches and stashes are either disabled or use
		// in-process cache only to prevent tests from using any preconfigured
		// cache meant for the local wiki from outside the test run.
		// See also MediaWikiTestCase::run() which mocks CACHE_DB and APC.

		// Disabled in DefaultSettings, override local settings
		$wgMainWANCache =
		$wgMainCacheType = CACHE_NONE;
		// Uses CACHE_ANYTHING in DefaultSettings, use hash instead of db
		$wgMessageCacheType =
		$wgParserCacheType =
		$wgSessionCacheType =
		$wgLanguageConverterCacheType = 'hash';
		// Uses db-replicated in DefaultSettings
		$wgMainStash = 'hash';
		// Use hash instead of db
		$wgObjectCaches['db-replicated'] = $wgObjectCaches['hash'];
		// Use memory job queue
		$wgJobTypeConf = [
			'default' => [ 'class' => JobQueueMemory::class, 'order' => 'fifo' ],
		];

		$wgUseDatabaseMessages = false; # Set for future resets

		// Assume UTC for testing purposes
		$wgLocaltimezone = 'UTC';

		$wgLocalisationCacheConf['class'] = TestLocalisationCache::class;
		$wgLocalisationCacheConf['storeClass'] = LCStoreNull::class;

		// Do not bother updating search tables
		$wgSearchType = SearchEngineDummy::class;

		// Generic MediaWiki\Session\SessionManager configuration for tests
		// We use CookieSessionProvider because things might be expecting
		// cookies to show up in a FauxRequest somewhere.
		$wgSessionProviders = [
			[
				'class' => MediaWiki\Session\CookieSessionProvider::class,
				'args' => [ [
					'priority' => 30,
					'callUserSetCookiesHook' => true,
				] ],
			],
		];

		// Single-iteration PBKDF2 session secret derivation, for speed.
		$wgSessionPbkdf2Iterations = 1;

		// Generic AuthManager configuration for testing
		$wgAuthManagerConfig = [
			'preauth' => [],
			'primaryauth' => [
				[
					'class' => MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider::class,
					'args' => [ [
						'authoritative' => false,
					] ],
				],
				[
					'class' => MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class,
					'args' => [ [
						'authoritative' => true,
					] ],
				],
			],
			'secondaryauth' => [],
		];

		// T46192 Do not attempt to send a real e-mail
		Hooks::clear( 'AlternateUserMailer' );
		Hooks::register(
			'AlternateUserMailer',
			function () {
				return false;
			}
		);
		// xdebug's default of 100 is too low for MediaWiki
		ini_set( 'xdebug.max_nesting_level', 1000 );

		// Bug T116683 serialize_precision of 100
		// may break testing against floating point values
		// treated with PHP's serialize()
		ini_set( 'serialize_precision', 17 );
	}

}
