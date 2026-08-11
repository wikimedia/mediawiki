<?php

namespace MediaWiki\Page;

use LogicException;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleFactory;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\ObjectCache\MapCacheLRU;

class LinkAlwaysKnownLookup {

	private readonly MapCacheLRU $cache;
	/** @var int In practice, this is global, as services are singletons */
	private int $individualLookupsInRequest = 0;
	private const int MAX_INDIVIDUAL_LOOKUPS_PER_REQ = 50;

	public function __construct(
		private readonly HookRunner $hookRunner,
		private readonly TitleFactory $titleFactory,
		private readonly ShadowPageLoader $shadowPageLoader,
		private readonly RepoGroup $repoGroup,
		private readonly SpecialPageFactory $specialPageFactory,
		private readonly LoggerInterface $logger
	) {
		$this->cache = new MapCacheLRU( 100_000 );
	}

	/**
	 * Compute the unbatched part of "is link always known"
	 *
	 * This is complementary to computeIsAlwaysKnownBatch, and should be called
	 * in cases when computeIsAlwaysKnownBatch did not determine the result.
	 *
	 * Result is written back into $this->cache.
	 *
	 * @note Ideally, this method would not exist and all computation would be batched.
	 * @see https://phabricator.wikimedia.org/T433232
	 * @param LinkTarget $link
	 */
	private function computeIsAlwaysKnownUnbatchedParts( LinkTarget $link ): bool {
		// sanity check
		$cacheKey = CacheKeyHelper::getKeyForPage( $link );
		if ( !$this->cache->has( $cacheKey ) || $this->cache->get( $cacheKey ) !== null ) {
			throw new LogicException(
				__METHOD__ . ' can be only called when cached value is null'
			);
		}

		// TODO: Remove this hook once all callers are mitigated (T433161)
		$this->hookRunner->onTitleIsAlwaysKnown(
			$this->titleFactory->newFromLinkTarget( $link ),
			$isKnown
		);

		if ( $isKnown === null ) {
			// Even the second hook made no decision for us, we REALLY
			// have to decide ourselves...
			if ( $link->isExternal() ) {
				// any interwiki link might be viewable, for all we know
				$isKnown = true;
			} elseif ( $this->shadowPageLoader->existsForLink( $link ) ) {
				$isKnown = true;
			} else {
				$isKnown = match ( $link->getNamespace() ) {
					// file exists, possibly in a foreign repo
					// TODO: it might make sense to switch to RepoGroup::findFiles and
					// batch this as well
					NS_MEDIA, NS_FILE => (bool)$this->repoGroup->findFile( $link ),
					// if the title is a valid special page, it exists
					NS_SPECIAL => $this->specialPageFactory->exists( $link->getDBkey() ),
					// self-link, possibly with fragment
					NS_MAIN => $link->getDBkey() == '',
					default => false,
				};
			}
		}

		// sanity check
		if ( $isKnown === null ) {
			throw new LogicException(
				__METHOD__ . ' should have the final call; $isKnown === null should not be possible'
			);
		}

		$this->cache->set( $cacheKey, $isKnown );
		return $isKnown;
	}

	/**
	 * Trigger batch lookups for $links
	 *
	 * If the isAlwaysKnown status for a link can be evaluated in a batched way, this method
	 * writes the outcome back into $this->cache.
	 *
	 * $this->cache might contain null values for certain links once this method finishes. In
	 * that case, caller needs to run computeIsAlwaysKnownUnbatchedParts() for those links to
	 * determine the final result.
	 *
	 * @param LinkTarget[] $links
	 */
	private function computeIsAlwaysKnownBatch( array $links ): void {
		$isKnownArr = array_fill_keys( array_keys( $links ), null );
		$this->hookRunner->onLinkTargetIsAlwaysKnownBatch( $links, $isKnownArr );

		foreach ( $links as $i => $link ) {
			$isKnown = $isKnownArr[$i] ?? null;

			// NOTE: $isKnown can be null, which means "batched computation was executed, and
			// result was not determined". Caller interprets that as "run
			// computeIsAlwaysKnownUnbatchedParts()".
			$this->cache->set(
				CacheKeyHelper::getKeyForPage( $link ),
				$isKnown
			);
		}
	}

	/**
	 * @param LinkTarget[] $links
	 */
	public function preload( array $links ): void {
		$uncachedLinks = [];
		foreach ( $links as $link ) {
			if ( !$this->cache->has( CacheKeyHelper::getKeyForPage( $link ) ) ) {
				$uncachedLinks[] = $link;
			}
		}

		// Batched lookups should be (relatively) cheap. Compute what can be computed
		// on a preload. If the result cannot be determined in batched way,
		// isAlwaysKnown will call computeIsAlwaysKnownUnbatchedParts() to determine
		// the final result
		// NOTE: computeIsAlwaysKnownBatch is responsible for writing back into the cache
		if ( $uncachedLinks ) {
			$this->computeIsAlwaysKnownBatch( $uncachedLinks );
		}
	}

	public function isAlwaysKnown( LinkTarget $page ): bool {
		$key = CacheKeyHelper::getKeyForPage( $page );
		if ( !$this->cache->has( $key ) ) {
			// The compute method writes back to the cache
			$this->computeIsAlwaysKnownBatch( [ $page ] );

			if ( ++$this->individualLookupsInRequest >= self::MAX_INDIVIDUAL_LOOKUPS_PER_REQ ) {
				$this->logger->warning(
					__METHOD__ . ' was used more than {limit} times (value: {value}), use batching',
					[
						'limit' => self::MAX_INDIVIDUAL_LOOKUPS_PER_REQ,
						'value' => $this->individualLookupsInRequest,
						'exception' => new RuntimeException,
					]
				);
			}
		}

		$cachedValue = $this->cache->get( $key );
		if ( $cachedValue !== null ) {
			return (bool)$cachedValue;
		}

		// Batched computation did not determine the final result
		// Cache was written to by callee
		return $this->computeIsAlwaysKnownUnbatchedParts( $page );
	}
}
