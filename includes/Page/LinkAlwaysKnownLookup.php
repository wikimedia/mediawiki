<?php

namespace MediaWiki\Page;

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
	 * @param LinkTarget[] $links
	 */
	private function computeIsAlwaysKnownBatch( array $links ): array {
		$isKnownArr = array_fill_keys( array_keys( $links ), null );
		$this->hookRunner->onLinkTargetIsAlwaysKnownBatch( $links, $isKnownArr );

		foreach ( $links as $i => $link ) {
			$isKnown = $isKnownArr[$i] ?? null;

			if ( $isKnown === null ) {
				// If the value is null, the hook didn't make a decision for us

				// TODO: Remove this hook once all callers are mitigated
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
			}

			$this->cache->set(
				CacheKeyHelper::getKeyForPage( $link ),
				$isKnown
			);
			$isKnownArr[$i] = $isKnown;
		}

		return $isKnownArr;
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

		// If there is something to compute, compute it
		// computeIsAlwaysKnownBatch is responsible for writing back into the cache
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
		return (bool)$this->cache->get( $key );
	}
}
