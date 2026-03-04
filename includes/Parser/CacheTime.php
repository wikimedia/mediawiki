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
use Wikimedia\Assert\Assert;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;
use Wikimedia\Timestamp\TimestampFormat as TS;

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
	 * @var string TS::MW timestamp when this object was generated, or
	 *  '' if not yet set. Used in ParserCache.
	 */
	protected string $mCacheTime = '';

	/**
	 * @var int|null Seconds after which the object should expire, use 0
	 *  for not cacheable and null for "the default cache expiration time"
	 *  (which is assumed to be greater than zero).
	 *  Used in ParserCache.
	 */
	protected ?int $mCacheExpiry = null;

	/**
	 * @var int|null Revision ID that was parsed
	 */
	protected ?int $mCacheRevisionId = null;

	/**
	 * @var string|null Human-readable label identifying what caused the
	 * current cache expiry value (e.g. "Template:Foo (currentday)").
	 * Updated only when updateCacheExpiry() actually lowers the TTL.
	 */
	protected ?string $mCacheExpirySource = null;

	/**
	 * @return string TS::MW timestamp
	 */
	public function getCacheTime() {
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
	 * @param string $t TS::MW timestamp
	 * @return string
	 */
	public function setCacheTime( $t ): string {
		if ( !is_string( $t ) ) {
			wfDeprecated( __METHOD__ . ": with non-string argument (" . get_debug_type( $t ) . ")", "1.46" );
			$t = (string)$t;
		}
		// A long time ago we used to use -1 to mean "not cacheable"
		Assert::invariant( $t !== '-1', "not a TS::MW timestamp" );

		$old = $this->mCacheTime;
		$this->mCacheTime = MWTimestamp::convert( TS::MW, $t );
		return $old;
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
	 * Reduce the number of seconds after which this object should expire.
	 *
	 * This value is used with the ParserCache.
	 * If called with a value greater than the value provided at any previous call,
	 * the new call has no effect.
	 *
	 * Avoid using 0 if at all possible. Consider JavaScript for highly dynamic content.
	 *
	 * NOTE: Beware that reducing the TTL for reasons that do not relate to "dynamic content",
	 * may have the side-effect of incurring more RefreshLinksJob executions.
	 * See also WikiPage::triggerOpportunisticLinksUpdate.
	 *
	 * @param int $seconds
	 * @param string|null $source Human-readable label identifying what is
	 *   responsible for this TTL (e.g. a magic word or template name).
	 *   Recorded only when the expiry is actually lowered. @since 1.46
	 * @deprecated since 1.46 Calling this method without $source.
	 */
	public function updateCacheExpiry( $seconds, ?string $source = null ) {
		$seconds = (int)$seconds;

		if ( $this->mCacheExpiry === null || $this->mCacheExpiry > $seconds ) {
			$this->mCacheExpiry = $seconds;
			$this->mCacheExpirySource = $source;
		}
	}

	/**
	 * Returns the number of seconds after which this object should expire.
	 * This method is used by ParserCache to determine how long the ParserOutput can be cached.
	 * The timestamp of expiry can be calculated by adding getCacheExpiry() to getCacheTime().
	 * The value returned by getCacheExpiry() is smaller or equal to the
	 * value of $wgParserCacheExpireTime and influenced by the values provided
	 * to calls to updateCacheExpiry(), but child classes may adjust the raw
	 * value: for example, to add minimums, dynamic adjustments, or reductions
	 * to the default expiry based on output properties.
	 *
	 * @note Use the protected $mCacheExpiry property to access the "real"
	 * minimum value provided to `updateCacheExpiry`, but as this should
	 * generally not be accessed outside this class no public getter method
	 * has been provided.
	 * @note This method should return 0 if and only if ::isCacheable()
	 *   returns false.
	 */
	public function getCacheExpiry(): int {
		$parserCacheExpireTime = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ParserCacheExpireTime );

		$expire = min( $this->mCacheExpiry ?? $parserCacheExpireTime, $parserCacheExpireTime );
		return $expire > 0 ? $expire : 0;
	}

	/**
	 * @return string|null Human-readable label identifying what caused the
	 *   current cache expiry, or null if no source was recorded.
	 * @see updateCacheExpiry()
	 * @since 1.46
	 */
	public function getCacheExpirySource(): ?string {
		return $this->mCacheExpirySource;
	}

	/**
	 * @return bool
	 */
	public function isCacheable() {
		// Must return false if $mCacheExpiry is 0, but may return false
		// in other cases as well, if subclasses wish to extend this.
		return $this->mCacheExpiry === null || $this->mCacheExpiry > 0;
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

		$expiry = MWTimestamp::convert( TS::MW, MWTimestamp::time() - $this->getCacheExpiry() );

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
			'CacheExpirySource' => $this->mCacheExpirySource,
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
		$this->mCacheExpirySource = $jsonData['CacheExpirySource'] ?? null;

		// Backward compatibility
		if ( $this->mCacheTime === '-1' ) {
			$this->mCacheExpiry = 0;
			$this->mCacheTime = '';
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( CacheTime::class, 'CacheTime' );
