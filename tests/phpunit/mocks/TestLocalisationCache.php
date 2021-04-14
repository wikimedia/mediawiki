<?php

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

	/**
	 * Recurse through the given array and replace every object by a scalar value that can be
	 * serialized as JSON to use as a hash key.
	 *
	 * @param array $arr
	 * @return array
	 */
	private static function hashiblifyArray( array $arr ) : array {
		foreach ( $arr as $key => $val ) {
			if ( is_array( $val ) ) {
				$arr[$key] = self::hashiblifyArray( $val );
			} elseif ( is_object( $val ) ) {
				// spl_object_hash() may return duplicate values if an object is destroyed and a new
				// one gets its hash and happens to be registered in the same hook in the same
				// location. This seems unlikely, but let's be safe and maintain a reference so it
				// can't happen. (In practice, there are probably no objects in the hooks at all.)
				static $objects = [];
				if ( !in_array( $val, $objects, true ) ) {
					$objects[] = $val;
				}
				$arr[$key] = spl_object_hash( $val );
			}
		}
		return $arr;
	}

	public function recache( $code ) {
		// Test run performance is killed if we have to regenerate l10n for every test
		$cacheKey = sha1( json_encode( [
			$code,
			$this->selfAccess->options->get( 'ExtensionMessagesFiles' ),
			$this->selfAccess->options->get( 'MessagesDirs' ),
			// json_encode doesn't handle objects well
			self::hashiblifyArray( Hooks::getHandlers( 'LocalisationCacheRecacheFallback' ) ),
			self::hashiblifyArray( Hooks::getHandlers( 'LocalisationCacheRecache' ) ),
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
