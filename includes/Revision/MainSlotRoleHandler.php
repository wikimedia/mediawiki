<?php
/**
 * This file is part of MediaWiki.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\UnknownContentModelException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
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
	 * @throws UnknownContentModelException
	 */
	public function isAllowedModel( $model, PageIdentity $page ) {
		$title = $this->titleFactory->newFromPageIdentity( $page );
		$handler = $this->contentHandlerFactory->getContentHandler( $model );

		return $handler->canBeUsedOn( $title );
	}

	/**
	 * @param LinkTarget|PageReference $page
	 *
	 * @return string
	 */
	public function getDefaultModel( $page ) {
		// NOTE: this method must not rely on $title->getContentModel() directly or indirectly,
		//       because it is used to initialize the mContentModel member.

		$ns = $page->getNamespace();
		$model = $this->namespaceContentModels[$ns] ?? null;

		// Hook can determine default model
		if ( $page instanceof PageReference ) {
			$title = $this->titleFactory->newFromPageReference( $page );
		} else {
			$title = $this->titleFactory->newFromLinkTarget( $page );
		}
		// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
		if ( !$this->hookRunner->onContentHandlerDefaultModelFor( $title, $model ) && $model !== null ) {
			return $model;
		}

		// Code can only exist in these namespaces
		if ( $ns === NS_MEDIAWIKI ||
			// Code in the user namespace can only exist on subpages
			( $ns === NS_USER && str_contains( $title->getDBkey(), '/' ) )
		) {
			// Could this page contain code based on the title?
			if ( preg_match( '/\.(css|js|json|vue)$/', $title->getDBkey(), $m ) ) {
				return match ( $m[1] ) {
					'css' => CONTENT_MODEL_CSS,
					'js' => CONTENT_MODEL_JAVASCRIPT,
					'json' => CONTENT_MODEL_JSON,
					'vue' => CONTENT_MODEL_VUE,
				};
			}
		}

		// Is this wikitext, according to $wgNamespaceContentModels or the DefaultModelFor hook?
		return $model ?? CONTENT_MODEL_WIKITEXT;
	}

}
