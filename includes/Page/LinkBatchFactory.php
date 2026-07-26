<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Factory for LinkBatch objects to batch query page metadata.
 *
 * Use via MediaWikiServices::getLinkBatchFactory()->newLinkBatch(), and
 * then call LinkBatch::execute().
 *
 * @see docs/LinkCache.md
 * @see MediaWiki\Page\LinkCache
 * @since 1.35
 * @ingroup Page
 */
class LinkBatchFactory {

	public function __construct(
		private LinkCache $linkCache,
		private TitleFormatter $titleFormatter,
		private Language $contentLanguage,
		private GenderCache $genderCache,
		private IConnectionProvider $dbProvider,
		private LinksMigration $linksMigration,
		private TempUserDetailsLookup $tempUserDetailsLookup,
		private LinkAlwaysKnownLookup $linkAlwaysKnownLookup,
		private LoggerInterface $logger
	) {
	}

	/**
	 * @param iterable<LinkTarget>|iterable<PageReference> $titles Initial titles for this batch
	 * @return LinkBatch
	 */
	public function newLinkBatch( iterable $titles = [] ): LinkBatch {
		return new LinkBatch(
			$titles,
			$this->linkCache,
			$this->titleFormatter,
			$this->contentLanguage,
			$this->genderCache,
			$this->dbProvider,
			$this->linksMigration,
			$this->tempUserDetailsLookup,
			$this->linkAlwaysKnownLookup,
			$this->logger
		);
	}

	/**
	 * Warm-up titles that qualify for the persistent version of LinkCache
	 *
	 * If your use case involves a set of titles that:
	 * - qualify for the persistent cache (see LinkCache::usePersistentCache
	 *   and [the architecture doc](@ref linkcache) at docs/LinkCache.md),
	 * - and are constant between requests (likely all together a cache-hit),
	 * - and that you know upfront (i.e. can batch)
	 * - and that eliminate the need for a database query if they are all a hit
	 *
	 * Then consider using this method instead of ::newLinkBatch.
	 *
	 * This method tries WANObjectCache first and falls back to backfilling
	 * from a database query using ::newLinkBatch. It then warms up the
	 * in-process LinkCache with the results.
	 *
	 * Designed for use by ResourceLoader\WikiModule::preloadTitleInfo (T393835).
	 *
	 * @since 1.47
	 * @param string[] $pages
	 */
	public function preloadPersistentCache( array $pages, string $fname ): void {
		$this->linkCache->preloadPersistentCache( $pages, $fname, $this );
	}
}

/** @deprecated class alias since 1.45 */
class_alias( LinkBatchFactory::class, 'MediaWiki\Cache\LinkBatchFactory' );
