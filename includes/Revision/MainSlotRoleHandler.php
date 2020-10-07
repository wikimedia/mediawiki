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

use Hooks;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Linker\LinkTarget;
use MWUnknownContentModelException;
use Title;

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

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @param string[] $namespaceContentModels A mapping of namespaces to content models,
	 *        typically from $wgNamespaceContentModels.
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct(
		array $namespaceContentModels,
		IContentHandlerFactory $contentHandlerFactory
	) {
		parent::__construct( 'main', CONTENT_MODEL_WIKITEXT );
		$this->namespaceContentModels = $namespaceContentModels;
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	public function supportsArticleCount() {
		return true;
	}

	/**
	 * @param string $model
	 * @param LinkTarget $page
	 *
	 * @return bool
	 * @throws MWUnknownContentModelException
	 */
	public function isAllowedModel( $model, LinkTarget $page ) {
		$title = Title::newFromLinkTarget( $page );
		$handler = $this->contentHandlerFactory->getContentHandler( $model );

		return $handler->canBeUsedOn( $title );
	}

	/**
	 * @param LinkTarget $page
	 *
	 * @return string
	 */
	public function getDefaultModel( LinkTarget $page ) {
		// NOTE: this method must not rely on $title->getContentModel() directly or indirectly,
		//       because it is used to initialize the mContentModel member.

		$ext = '';
		$ns = $page->getNamespace();
		$model = $this->namespaceContentModels[$ns] ?? null;

		// Hook can determine default model
		$title = Title::newFromLinkTarget( $page );
		if ( !Hooks::runner()->onContentHandlerDefaultModelFor( $title, $model ) && $model !== null ) {
			return $model;
		}

		// Could this page contain code based on the title?
		$isCodePage = $ns === NS_MEDIAWIKI && preg_match( '!\.(css|js|json)$!u', $title->getText(), $m );
		if ( $isCodePage ) {
			$ext = $m[1];
		}

		// Is this a user subpage containing code?
		$isCodeSubpage = $ns === NS_USER
			&& !$isCodePage
			&& preg_match( "/\\/.*\\.(js|css|json)$/", $title->getText(), $m );

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
				default:
					return $model ?? CONTENT_MODEL_TEXT;
			}
		}

		// We established that it must be wikitext

		return CONTENT_MODEL_WIKITEXT;
	}

}
