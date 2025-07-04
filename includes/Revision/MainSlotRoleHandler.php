<?php
/**
 * This file is part of MediaWiki.
 *
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

namespace MediaWiki\Revision;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\TitleFactory;

/**
 * A SlotRoleHandler for the main slot. While most slot roles serve a specific purpose and
 * thus typically exhibit the same behaviour on all pages, the main slot is used for different
 * things in different pages, typically depending on the namespace, a "file extension" in
 * the page name, or the content model of the slot's content.
 *
 * MainSlotRoleHandler implements some of the per-namespace and per-model behavior that was
 * supported prior to MediaWiki Version 1.33.
 *
 * @since 1.33
 */
class MainSlotRoleHandler extends SlotRoleHandler {

	/**
	 * @var string[] A mapping of namespaces to content models.
	 * @see $wgNamespaceContentModels
	 */
	private $namespaceContentModels;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var HookRunner */
	private $hookRunner;

	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param string[] $namespaceContentModels A mapping of namespaces to content models,
	 *        typically from $wgNamespaceContentModels.
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param HookContainer $hookContainer
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		array $namespaceContentModels,
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer,
		TitleFactory $titleFactory
	) {
		parent::__construct( SlotRecord::MAIN, CONTENT_MODEL_WIKITEXT );
		$this->namespaceContentModels = $namespaceContentModels;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->titleFactory = $titleFactory;
	}

	/** @inheritDoc */
	public function supportsArticleCount() {
		return true;
	}

	/**
	 * @param string $model
	 * @param PageIdentity $page
	 *
	 * @return bool
	 * @throws MWUnknownContentModelException
	 */
	public function isAllowedModel( $model, PageIdentity $page ) {
		$title = $this->titleFactory->newFromPageIdentity( $page );
		$handler = $this->contentHandlerFactory->getContentHandler( $model );

		return $handler->canBeUsedOn( $title );
	}

	/**
	 * @param LinkTarget|PageIdentity $page
	 *
	 * @return string
	 */
	public function getDefaultModel( $page ) {
		// NOTE: this method must not rely on $title->getContentModel() directly or indirectly,
		//       because it is used to initialize the mContentModel member.

		$ext = '';
		$ns = $page->getNamespace();
		$model = $this->namespaceContentModels[$ns] ?? null;

		// Hook can determine default model
		if ( $page instanceof PageIdentity ) {
			$title = $this->titleFactory->newFromPageIdentity( $page );
		} else {
			$title = $this->titleFactory->newFromLinkTarget( $page );
		}
		// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
		if ( !$this->hookRunner->onContentHandlerDefaultModelFor( $title, $model ) && $model !== null ) {
			return $model;
		}

		// Could this page contain code based on the title?
		$isCodePage = $ns === NS_MEDIAWIKI && preg_match( '!\.(css|js|json|vue)$!u', $title->getText(), $m );
		if ( $isCodePage ) {
			$ext = $m[1];
		}

		// Is this a user subpage containing code?
		$isCodeSubpage = $ns === NS_USER
			&& !$isCodePage
			&& preg_match( "/\\/.*\\.(js|css|json|vue)$/", $title->getText(), $m );

		if ( $isCodeSubpage ) {
			$ext = $m[1];
		}

		// Is this wikitext, according to $wgNamespaceContentModels or the DefaultModelFor hook?
		$isWikitext = $model === null || $model == CONTENT_MODEL_WIKITEXT;
		$isWikitext = $isWikitext && !$isCodePage && !$isCodeSubpage;

		if ( !$isWikitext ) {
			switch ( $ext ) {
				case 'js':
					return CONTENT_MODEL_JAVASCRIPT;
				case 'css':
					return CONTENT_MODEL_CSS;
				case 'json':
					return CONTENT_MODEL_JSON;
				case 'vue':
					return CONTENT_MODEL_VUE;
				default:
					return $model ?? CONTENT_MODEL_TEXT;
			}
		}

		// We established that it must be wikitext

		return CONTENT_MODEL_WIKITEXT;
	}

}
