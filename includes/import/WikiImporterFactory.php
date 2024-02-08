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
 * @author Zabe
 */

use MediaWiki\Config\Config;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
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
	public function getWikiImporter( ImportSource $source, Authority $performer = null ): WikiImporter {
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
