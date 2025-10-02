<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Zabe
 */

use MediaWiki\Config\Config;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\RequestContext;
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
	private Config $config;
	private HookContainer $hookContainer;
	private Language $contentLanguage;
	private NamespaceInfo $namespaceInfo;
	private TitleFactory $titleFactory;
	private WikiPageFactory $wikiPageFactory;
	private UploadRevisionImporter $uploadRevisionImporter;
	private IContentHandlerFactory $contentHandlerFactory;
	private SlotRoleRegistry $slotRoleRegistry;

	public function __construct(
		Config $config,
		HookContainer $hookContainer,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory,
		WikiPageFactory $wikiPageFactory,
		UploadRevisionImporter $uploadRevisionImporter,
		IContentHandlerFactory $contentHandlerFactory,
		SlotRoleRegistry $slotRoleRegistry
	) {
		$this->config = $config;
		$this->hookContainer = $hookContainer;
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->uploadRevisionImporter = $uploadRevisionImporter;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->slotRoleRegistry = $slotRoleRegistry;
	}

	/**
	 * @param ImportSource $source
	 * @param Authority|null $performer Authority used for permission checks only (to ensure that
	 *     the user performing the import is allowed to edit the pages they're importing). To skip
	 *     the checks, use UltimateAuthority.
	 *
	 *     When omitted, defaults to the current global user. This behavior is deprecated since 1.42.
	 *
	 *     If you want to also log the import actions, see ImportReporter.
	 * @return WikiImporter
	 */
	public function getWikiImporter( ImportSource $source, ?Authority $performer = null ): WikiImporter {
		if ( !$performer ) {
			wfDeprecated( __METHOD__ . ' without $performer', '1.42' );
			$performer = RequestContext::getMain()->getAuthority();
		}

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
