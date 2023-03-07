<?php

/**
 * Common code for test environment initialisation and teardown
 */
class TestSetup {
	/** @var array */
	public static $bootstrapGlobals;

	/**
	 * For use in MediaWikiUnitTestCase.
	 *
	 * This should be called before default settings are applied or Setup.php loads.
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
	 * Overrides config settings for testing.
	 * This should be called after loading local settings, typically from the finalSetup() method
	 * of a Maintenance subclass which then gets called via MW_SETUP_CALLBACK in Setup.php.
	 */
	public static function applyInitialConfig() {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType, $wgSessionCacheType;
		global $wgMainStash, $wgChronologyProtectorStash;
		global $wgObjectCaches;
		global $wgLanguageConverterCacheType, $wgUseDatabaseMessages;
		global $wgLocaltimezone, $wgLocalTZoffset, $wgLocalisationCacheConf;
		global $wgSearchType;
		global $wgDevelopmentWarnings;
		global $wgSessionProviders, $wgSessionPbkdf2Iterations;
		global $wgJobTypeConf;
		global $wgMWLoggerDefaultSpi;
		global $wgAuthManagerConfig;
		global $wgShowExceptionDetails, $wgShowHostnames;

		$wgShowExceptionDetails = true;
		$wgShowHostnames = true;

		// wfWarn should cause tests to fail
		$wgDevelopmentWarnings = true;

		// Make sure all caches and stashes are either disabled or use
		// in-process cache only to prevent tests from using any preconfigured
		// cache meant for the local wiki from outside the test run.
		// See also MediaWikiIntegrationTestCase::run() which mocks CACHE_DB and APC.

		// Disabled per default in MainConfigSchema, override local settings
		$wgMainCacheType = CACHE_NONE;
		// Uses CACHE_ANYTHING per default in MainConfigSchema, use hash instead of db
		$wgMessageCacheType =
		$wgParserCacheType =
		$wgSessionCacheType =
		$wgLanguageConverterCacheType = 'hash';
		// Uses db-replicated per default in MainConfigSchema
		$wgMainStash = 'hash';
		$wgChronologyProtectorStash = 'hash';
		// Use hash instead of db
		$wgObjectCaches['db-replicated'] = $wgObjectCaches['hash'];
		// Use memory job queue
		$wgJobTypeConf = [
			'default' => [ 'class' => JobQueueMemory::class, 'order' => 'fifo' ],
		];
		// Always default to LegacySpi and LegacyLogger during test
		// See also MediaWikiIntegrationTestCase::setNullLogger().
		// Note that MediaWikiLoggerPHPUnitTestListener may wrap this in
		// a MediaWiki\Logger\LogCapturingSpi at run-time.
		$wgMWLoggerDefaultSpi = [
			'class' => \MediaWiki\Logger\LegacySpi::class,
		];

		$wgUseDatabaseMessages = false; # Set for future resets

		// Assume UTC for testing purposes
		$wgLocaltimezone = 'UTC';
		$wgLocalTZoffset = 0;

		$wgLocalisationCacheConf['class'] = TestLocalisationCache::class;
		$wgLocalisationCacheConf['storeClass'] = LCStoreNull::class;

		// Do not bother updating search tables
		$wgSearchType = SearchEngineDummy::class;

		// Generic MediaWiki\Session\SessionManager configuration for tests
		// We use CookieSessionProvider because things might be expecting
		// cookies to show up in a MediaWiki\Request\FauxRequest somewhere.
		$wgSessionProviders = [
			[
				'class' => MediaWiki\Session\CookieSessionProvider::class,
				'args' => [ [
					'priority' => 30,
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
					'services' => [
						'DBLoadBalancer',
						'UserOptionsLookup',
					],
					'args' => [ [
						'authoritative' => false,
					] ],
				],
				[
					'class' => MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class,
					'services' => [
						'DBLoadBalancer',
					],
					'args' => [ [
						'authoritative' => true,
					] ],
				],
			],
			'secondaryauth' => [],
		];

		// xdebug's default of 100 is too low for MediaWiki
		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal
		ini_set( 'xdebug.max_nesting_level', 1000 );

		// Make sure that serialize_precision is set to its default value
		// so floating-point numbers within serialized or JSON-encoded data
		// will match the expected string representations (T116683).
		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal
		ini_set( 'serialize_precision', -1 );
	}

	/**
	 * @internal Should only be used in bootstrap.php and boostrap.maintenance.php
	 *
	 * PHPUnit includes the bootstrap file inside a method body, while most MediaWiki startup files
	 * assume to be included in the global scope.
	 * This utility provides a way to include these files: it makes all globals available in the
	 * inclusion scope before including the file, then exports all new or changed globals.
	 *
	 * @param string $fileName the file to include
	 */
	public static function requireOnceInGlobalScope( string $fileName ): void {
		$originalGlobals = $GLOBALS;
		foreach ( array_keys( $GLOBALS ) as $key ) {
			if ( $key === 'fileName' || $key === 'originalGlobals' ) {
				continue;
			}
			// phpcs:ignore MediaWiki.VariableAnalysis.UnusedGlobalVariables.UnusedGlobal$key,MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
			global $$key;
		}

		require_once $fileName;

		foreach ( get_defined_vars() as $varName => $value ) {
			if ( $varName === 'fileName' || $varName === 'originalGlobals' || $varName === 'key' ) {
				continue;
			}
			if ( array_key_exists( $varName, $originalGlobals ) ) {
				continue;
			}
			$GLOBALS[$varName] = $value;
		}
	}

}
