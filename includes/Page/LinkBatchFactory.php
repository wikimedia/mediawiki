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
}

/** @deprecated class alias since 1.45 */
class_alias( LinkBatchFactory::class, 'MediaWiki\Cache\LinkBatchFactory' );
