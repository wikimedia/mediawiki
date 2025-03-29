<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Cache;

use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
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
 * @see MediaWiki\Cache\LinkCache
 * @since 1.35
 * @ingroup Cache
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
