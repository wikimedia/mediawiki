<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Zabe
 */

namespace MediaWiki\Import;

use MediaWiki\Config\Config;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFactory;

/**
 * Factory service for WikiImporter instances.
 *
 * @since 1.37
 */
class WikiImporterFactory {
	public function __construct(
		private readonly Config $config,
		private readonly HookContainer $hookContainer,
		private readonly Language $contentLanguage,
		private readonly NamespaceInfo $namespaceInfo,
		private readonly TitleFactory $titleFactory,
		private readonly WikiPageFactory $wikiPageFactory,
		private readonly UploadRevisionImporter $uploadRevisionImporter,
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly SlotRoleRegistry $slotRoleRegistry,
	) {
	}

	/**
	 * @param ImportSource $source
	 * @param Authority $performer Authority used for permission checks only (to ensure that
	 *     the user performing the import is allowed to edit the pages they're importing). To skip
	 *     the checks, use UltimateAuthority.
	 *     If you want to also log the import actions, see ImportReporter.
	 * @return WikiImporter
	 */
	public function getWikiImporter( ImportSource $source, Authority $performer ): WikiImporter {
		return new WikiImporter(
			$source,
			$performer,
			$this->config,
			$this->hookContainer,
			$this->contentLanguage,
			$this->namespaceInfo,
			$this->titleFactory,
			$this->wikiPageFactory,
			$this->uploadRevisionImporter,
			$this->contentHandlerFactory,
			$this->slotRoleRegistry
		);
	}
}

/** @deprecated class alias since 1.46 */
class_alias( WikiImporterFactory::class, 'WikiImporterFactory' );
