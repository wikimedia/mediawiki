<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\TestingAccessWrapper;

/**
 * A test-only LocalisationCache that caches all data in memory for test speed.
 */
class TestLocalisationCache extends LocalisationCache {

	/**
	 * A cache of the parsed data for tests. Services are reset between every test, which forces
	 * localization to be recached between every test, which is unreasonably slow. As an
	 * optimization, we cache our data in a static member for tests.
	 */
	private static MapCacheLRU $testingCache;

	private const PROPERTY_NAMES = [ 'data', 'sourceLanguage' ];

	/** @var self */
	private $selfAccess;

	/** @inheritDoc */
	public function __construct( ...$params ) {
		parent::__construct( ...$params );

		// Limit the cache size (entries are approx. 1 MB each) but not too much. Critical for tests
		// that use e.g. 5 different languages, and then the same 5 languages again, and again, …
		self::$testingCache ??= new MapCacheLRU( 16 );
		$this->selfAccess = TestingAccessWrapper::newFromObject( $this );
	}

	/** @inheritDoc */
	public function recache( $code ) {
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		// Test run performance is killed if we have to regenerate l10n for every test
		$cacheKey = sha1( json_encode( [
			$code,
			$this->selfAccess->options->get( MainConfigNames::ExtensionMessagesFiles ),
			$this->selfAccess->options->get( MainConfigNames::MessagesDirs ),
			$hookContainer->getHandlerDescriptions( 'LocalisationCacheRecacheFallback' ),
			$hookContainer->getHandlerDescriptions( 'LocalisationCacheRecache' ),
		] ) );
		$cache = self::$testingCache->get( $cacheKey );
		if ( $cache ) {
			foreach ( self::PROPERTY_NAMES as $prop ) {
				$this->$prop[$code] = $cache[$prop];
			}
			$loadedItems = $this->selfAccess->loadedItems;
			foreach ( $cache['data'] as $key => $_ ) {
				$loadedItems[$code][$key] = true;
			}
			$this->selfAccess->loadedItems = $loadedItems;
			return;
		}

		parent::recache( $code );

		$cache = [];
		foreach ( self::PROPERTY_NAMES as $prop ) {
			$cache[$prop] = $this->$prop[$code];
		}
		self::$testingCache->set( $cacheKey, $cache );
	}
}
