<?php

namespace MediaWiki\Parser;

/**
 * Read-only interface for metadata about a ParserCache entry.
 * @since 1.36
 */
interface ParserCacheMetadata {

	/**
	 * Returns the timestamp when the output was cached
	 * or -1 for uncacheable for legacy reasons.
	 * @todo remove legacy -1
	 *
	 * @return string|int TS_MW timestamp
	 */
	public function getCacheTime();

	/**
	 * @return int|null Revision id, if any was set
	 */
	public function getCacheRevisionId(): ?int;

	/**
	 * Returns the number of seconds after which this object should expire.
	 * This method is used by ParserCache to determine how long the ParserOutput can be cached.
	 * The timestamp of expiry can be calculated by adding getCacheExpiry() to getCacheTime().
	 * The value returned by getCacheExpiry is smaller or equal to the smallest number
	 * that was provided to a call of ParserOutput::updateCacheExpiry(),
	 * and smaller or equal to the value of $wgParserCacheExpireTime.
	 *
	 * @return int
	 */
	public function getCacheExpiry(): int;

	/**
	 * Returns the options from its ParserOptions which have been taken
	 * into account to produce the output.
	 *
	 * @return string[]
	 */
	public function getUsedOptions(): array;
}
