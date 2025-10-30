<?php
/**
 * Parser cache specific expiry check.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Parser cache specific expiry check.
 *
 * @ingroup Parser
 */
class CacheTime implements ParserCacheMetadata, JsonCodecable {
	use JsonCodecableTrait;

	/**
	 * @var array<string,true> ParserOptions which have been taken into account
	 * to produce output, option names stored in array keys.
	 */
	protected array $mParseUsedOptions = [];

	/**
	 * @var string|int TS_MW timestamp when this object was generated, or -1 for not cacheable. Used
	 * in ParserCache.
	 */
	protected string|int $mCacheTime = '';

	/**
	 * @var int|null Seconds after which the object should expire, use 0 for not cacheable. Used in
	 * ParserCache.
	 */
	protected ?int $mCacheExpiry = null;

	/**
	 * @var int|null Revision ID that was parsed
	 */
	protected ?int $mCacheRevisionId = null;

	/**
	 * @return string|int TS_MW timestamp
	 */
	public function getCacheTime() {
		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( $this->mCacheTime === '' ) {
			$this->mCacheTime = MWTimestamp::now();
		}
		return $this->mCacheTime;
	}

	/**
	 * @return bool true if a cache time has been set
	 */
	public function hasCacheTime(): bool {
		return $this->mCacheTime !== '';
	}

	/**
	 * setCacheTime() sets the timestamp expressing when the page has been rendered.
	 * This does not control expiry, see updateCacheExpiry() for that!
	 * @param string $t TS_MW timestamp
	 * @return string
	 */
	public function setCacheTime( $t ) {
		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( is_string( $t ) && $t !== '-1' ) {
			$t = MWTimestamp::convert( TS_MW, $t );
		}

		if ( $t === -1 || $t === '-1' ) {
			wfDeprecatedMsg( __METHOD__ . ' called with -1 as an argument', '1.36' );
		}

		return wfSetVar( $this->mCacheTime, $t );
	}

	/**
	 * @since 1.23
	 * @return int|null Revision id, if any was set
	 */
	public function getCacheRevisionId(): ?int {
		return $this->mCacheRevisionId;
	}

	/**
	 * @since 1.23
	 * @param int|null $id Revision ID
	 */
	public function setCacheRevisionId( $id ) {
		$this->mCacheRevisionId = $id;
	}

	/**
	 * Sets the number of seconds after which this object should expire.
	 *
	 * This value is used with the ParserCache.
	 * If called with a value greater than the value provided at any previous call,
	 * the new call has no effect. The value returned by getCacheExpiry is smaller
	 * or equal to the smallest number that was provided as an argument to
	 * updateCacheExpiry().
	 *
	 * Avoid using 0 if at all possible. Consider JavaScript for highly dynamic content.
	 *
	 * NOTE: Beware that reducing the TTL for reasons that do not relate to "dynamic content",
	 * may have the side-effect of incurring more RefreshLinksJob executions.
	 * See also WikiPage::triggerOpportunisticLinksUpdate.
	 *
	 * @param int $seconds
	 */
	public function updateCacheExpiry( $seconds ) {
		$seconds = (int)$seconds;

		if ( $this->mCacheExpiry === null || $this->mCacheExpiry > $seconds ) {
			$this->mCacheExpiry = $seconds;
		}
	}

	/**
	 * Returns the number of seconds after which this object should expire.
	 * This method is used by ParserCache to determine how long the ParserOutput can be cached.
	 * The timestamp of expiry can be calculated by adding getCacheExpiry() to getCacheTime().
	 * The value returned by getCacheExpiry is smaller or equal to the smallest number
	 * that was provided to a call of updateCacheExpiry(), and smaller or equal to the
	 * value of $wgParserCacheExpireTime.
	 */
	public function getCacheExpiry(): int {
		$parserCacheExpireTime = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ParserCacheExpireTime );

		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( $this->mCacheTime !== '' && $this->mCacheTime < 0 ) {
			return 0;
		}

		$expire = min( $this->mCacheExpiry ?? $parserCacheExpireTime, $parserCacheExpireTime );
		return $expire > 0 ? $expire : 0;
	}

	/**
	 * @return bool
	 */
	public function isCacheable() {
		return $this->getCacheExpiry() > 0;
	}

	/**
	 * Return true if this cached output object predates the global or
	 * per-article cache invalidation timestamps, or if it comes from
	 * an incompatible older version.
	 *
	 * @param string $touched The affected article's last touched timestamp
	 * @return bool
	 */
	public function expired( $touched ) {
		$cacheEpoch = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::CacheEpoch );

		$expiry = MWTimestamp::convert( TS_MW, MWTimestamp::time() - $this->getCacheExpiry() );

		return !$this->isCacheable() // parser says it's not cacheable
			|| $this->getCacheTime() < $touched
			|| $this->getCacheTime() <= $cacheEpoch
			|| $this->getCacheTime() < $expiry; // expiry period has passed
	}

	/**
	 * Return true if this cached output object is for a different revision of
	 * the page.
	 *
	 * @todo We always return false if $this->getCacheRevisionId() is null;
	 * this prevents invalidating the whole parser cache when this change is
	 * deployed. Someday that should probably be changed.
	 *
	 * @since 1.23
	 * @param int $id The affected article's current revision id
	 * @return bool
	 */
	public function isDifferentRevision( $id ) {
		$cached = $this->getCacheRevisionId();
		return $cached !== null && $id !== $cached;
	}

	/**
	 * Returns the options from its ParserOptions which have been taken
	 * into account to produce the output.
	 * @since 1.36
	 * @return string[]
	 */
	public function getUsedOptions(): array {
		return array_keys( $this->mParseUsedOptions );
	}

	/**
	 * Tags a parser option for use in the cache key for this parser output.
	 * Registered as a watcher at ParserOptions::registerWatcher() by Parser::clearState().
	 * The information gathered here is available via getUsedOptions(),
	 * and is used by ParserCache::save().
	 *
	 * @see ParserCache::getMetadata
	 * @see ParserCache::save
	 * @see ParserOptions::addExtraKey
	 * @see ParserOptions::optionsHash
	 * @param string $option
	 */
	public function recordOption( string $option ) {
		$this->mParseUsedOptions[$option] = true;
	}

	/**
	 * Tags a list of parser option names for use in the cache key for this parser output.
	 *
	 * @see recordOption()
	 * @param string[] $options
	 */
	public function recordOptions( array $options ) {
		$this->mParseUsedOptions = array_merge(
			$this->mParseUsedOptions,
			array_fill_keys( $options, true )
		);
	}

	/**
	 * Returns a JSON serializable structure representing this CacheTime instance.
	 * @see ::newFromJsonArray()
	 *
	 * @return array
	 */
	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!

		return [
			'ParseUsedOptions' => $this->mParseUsedOptions,
			'CacheExpiry' => $this->mCacheExpiry,
			'CacheTime' => $this->mCacheTime,
			'CacheRevisionId' => $this->mCacheRevisionId,
		];
	}

	public static function newFromJsonArray( array $json ): self {
		$cacheTime = new CacheTime();
		$cacheTime->initFromJson( $json );
		return $cacheTime;
	}

	/**
	 * Initialize member fields from an array returned by toJsonArray().
	 * @param array $jsonData
	 */
	protected function initFromJson( array $jsonData ) {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!

		$this->mParseUsedOptions = $jsonData['ParseUsedOptions'] ?? [];
		$this->mCacheExpiry = $jsonData['CacheExpiry'] ?? null;
		$this->mCacheTime = $jsonData['CacheTime'] ?? '';
		$this->mCacheRevisionId = $jsonData['CacheRevisionId'] ?? null;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( CacheTime::class, 'CacheTime' );
