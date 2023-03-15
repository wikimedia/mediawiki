<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * A test-only LocalisationCache that caches all data in memory for test speed.
 */
class TestLocalisationCache extends LocalisationCache {

	/**
	 * A cache of the parsed data for tests. Services are reset between every test, which forces
	 * localization to be recached between every test, which is unreasonably slow. As an
	 * optimization, we cache our data in a static member for tests.
	 *
	 * @var array
	 */
	private static $testingCache = [];

	private $selfAccess;

	public function __construct() {
		parent::__construct( ...func_get_args() );
		$this->selfAccess = TestingAccessWrapper::newFromObject( $this );
	}

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
		if ( isset( self::$testingCache[$cacheKey] ) ) {
			$this->data[$code] = self::$testingCache[$cacheKey];
			foreach ( self::$testingCache[$cacheKey] as $key => $item ) {
				$loadedItems = $this->selfAccess->loadedItems;
				$loadedItems[$code][$key] = true;
				$this->selfAccess->loadedItems = $loadedItems;
			}
			return;
		}

		parent::recache( $code );

		if ( count( self::$testingCache ) > 4 ) {
			// Don't store more than a few $data's, they can add up to a lot of memory if
			// they're kept around for the whole test duration
			array_pop( self::$testingCache );
		}
		// Put the new one in front
		self::$testingCache = array_merge( [ $cacheKey => $this->data[$code] ], self::$testingCache );
	}
}
