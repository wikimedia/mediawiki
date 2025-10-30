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

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @var GenderCache
	 */
	private $genderCache;

	/**
	 * @var IConnectionProvider
	 */
	private $dbProvider;

	/** @var LinksMigration */
	private $linksMigration;

	private TempUserDetailsLookup $tempUserDetailsLookup;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(
		LinkCache $linkCache,
		TitleFormatter $titleFormatter,
		Language $contentLanguage,
		GenderCache $genderCache,
		IConnectionProvider $dbProvider,
		LinksMigration $linksMigration,
		TempUserDetailsLookup $tempUserDetailsLookup,
		LoggerInterface $logger
	) {
		$this->linkCache = $linkCache;
		$this->titleFormatter = $titleFormatter;
		$this->contentLanguage = $contentLanguage;
		$this->genderCache = $genderCache;
		$this->dbProvider = $dbProvider;
		$this->linksMigration = $linksMigration;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;
		$this->logger = $logger;
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
			$this->logger
		);
	}
}

/** @deprecated class alias since 1.45 */
class_alias( LinkBatchFactory::class, 'MediaWiki\Cache\LinkBatchFactory' );
