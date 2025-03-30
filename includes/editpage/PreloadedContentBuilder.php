<?php

namespace MediaWiki\EditPage;

use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use Wikimedia\Assert\Assert;

/**
 * Provides the initial content of the edit box displayed in an edit form
 * when creating a new page or a new section.
 *
 * Used by EditPage, and may be used by extensions providing alternative editors.
 *
 * @since 1.41
 */
class PreloadedContentBuilder {

	use ParametersHelper;

	private IContentHandlerFactory $contentHandlerFactory;
	private WikiPageFactory $wikiPageFactory;
	private RedirectLookup $redirectLookup;
	private SpecialPageFactory $specialPageFactory;
	private ContentTransformer $contentTransformer;
	private HookRunner $hookRunner;

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		WikiPageFactory $wikiPageFactory,
		RedirectLookup $redirectLookup,
		SpecialPageFactory $specialPageFactory,
		ContentTransformer $contentTransformer,
		HookContainer $hookContainer
	) {
		// Services
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->redirectLookup = $redirectLookup;
		$this->specialPageFactory = $specialPageFactory;
		$this->contentTransformer = $contentTransformer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Get the initial content of the edit box displayed in an edit form
	 * when creating a new page or a new section.
	 *
	 * @param ProperPageIdentity $page
	 * @param Authority $performer
	 * @param string|null $preload
	 * @param string[] $preloadParams
	 * @param string|null $section
	 * @return Content
	 */
	public function getPreloadedContent(
		ProperPageIdentity $page,
		Authority $performer,
		?string $preload,
		array $preloadParams,
		?string $section
	): Content {
		Assert::parameterElementType( 'string', $preloadParams, '$preloadParams' );

		$content = null;
		if ( $section !== 'new' ) {
			$content = $this->getDefaultContent( $page );
		}
		if ( $content === null ) {
			if ( ( $preload === null || $preload === '' ) && $section === 'new' ) {
				// Custom preload text for new sections
				$preload = 'MediaWiki:addsection-preload';
			}
			$content = $this->getPreloadedContentFromParams( $page, $performer, $preload, $preloadParams );
		}
		$title = Title::newFromPageIdentity( $page );
		if ( !$title->getArticleID() ) {
			$contentModel = $title->getContentModel();
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $contentModel );
			$contentFormat = $contentHandler->getDefaultFormat();
			$text = $contentHandler->serializeContent( $content, $contentFormat );
			$this->hookRunner->onEditFormPreloadText( $text, $title );
			$content = $contentHandler->unserializeContent( $text, $contentFormat );
		}
		return $content;
	}

	/**
	 * Get the content that is displayed when viewing a page that does not exist.
	 * Users should be discouraged from saving the page with identical content to this.
	 *
	 * Some code may depend on the fact that this is only non-null for the 'MediaWiki:' namespace.
	 * Beware.
	 *
	 * @param ProperPageIdentity $page
	 * @return Content|null
	 */
	public function getDefaultContent( ProperPageIdentity $page ): ?Content {
		$title = Title::newFromPageIdentity( $page );
		$contentModel = $title->getContentModel();
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $contentModel );
		$contentFormat = $contentHandler->getDefaultFormat();
		if ( $title->getNamespace() === NS_MEDIAWIKI ) {
			// If this is a system message, get the default text.
			$text = $title->getDefaultMessageText();
			if ( $text !== false ) {
				return $contentHandler->unserializeContent( $text, $contentFormat );
			}
		}
		return null;
	}

	/**
	 * Get the contents to be preloaded into the box by loading the given page.
	 *
	 * @param ProperPageIdentity $contextPage
	 * @param Authority $performer
	 * @param string|null $preload Representing the title to preload from.
	 * @param string[] $preloadParams Parameters to use (interface-message style) in the preloaded text
	 * @return Content
	 */
	private function getPreloadedContentFromParams(
		ProperPageIdentity $contextPage,
		Authority $performer,
		?string $preload,
		array $preloadParams
	): Content {
		$contextTitle = Title::newFromPageIdentity( $contextPage );
		$contentModel = $contextTitle->getContentModel();
		$handler = $this->contentHandlerFactory->getContentHandler( $contentModel );

		// T297725: Don't trick users into making edits to e.g. .js subpages
		if ( !$handler->supportsPreloadContent() || $preload === null || $preload === '' ) {
			return $handler->makeEmptyContent();
		}

		$title = Title::newFromText( $preload );

		if ( $title && $title->getNamespace() == NS_MEDIAWIKI ) {
			// When the preload source is in NS_MEDIAWIKI, get the content via wfMessage, to
			// enable preloading from i18n messages. The message framework can work with normal
			// pages in NS_MEDIAWIKI, so this does not restrict preloading only to i18n messages.
			$msg = wfMessage( $title->getText() );

			if ( $msg->isDisabled() ) {
				// Message is disabled and should not be used for preloading
				return $handler->makeEmptyContent();
			}

			return $this->transform(
				$handler->unserializeContent( $msg
					->page( $title )
					->params( $preloadParams )
					->inContentLanguage()
					->plain()
				),
				$title
			);
		}

		// (T299544) Use SpecialMyLanguage redirect so that nonexistent translated pages can
		// fall back to the corresponding page in a suitable language
		$title = $this->getTargetTitleIfSpecialMyLanguage( $title );

		# Check for existence to avoid getting MediaWiki:Noarticletext
		if ( !$this->isPageExistingAndViewable( $title, $performer ) ) {
			// TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		$page = $this->wikiPageFactory->newFromTitle( $title );
		if ( $page->isRedirect() ) {
			$redirTarget = $this->redirectLookup->getRedirectTarget( $title );
			$redirTarget = Title::castFromLinkTarget( $redirTarget );
			# Same as before
			if ( !$this->isPageExistingAndViewable( $redirTarget, $performer ) ) {
				// TODO: somehow show a warning to the user!
				return $handler->makeEmptyContent();
			}
			$page = $this->wikiPageFactory->newFromTitle( $redirTarget );
		}

		$content = $page->getContent( RevisionRecord::RAW );

		if ( !$content ) {
			// TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		if ( $content->getModel() !== $handler->getModelID() ) {
			$converted = $content->convert( $handler->getModelID() );

			if ( !$converted ) {
				// TODO: somehow show a warning to the user!
				wfDebug( "Attempt to preload incompatible content: " .
					"can't convert " . $content->getModel() .
					" to " . $handler->getModelID() );

				return $handler->makeEmptyContent();
			}

			$content = $converted;
		}
		return $this->transform( $content, $title, $preloadParams );
	}

	private function transform(
		Content $content,
		PageReference $title,
		array $preloadParams = []
	): Content {
		return $this->contentTransformer->preloadTransform(
			$content,
			$title,
			// The preload transformations don't depend on the user anyway
			ParserOptions::newFromAnon(),
			$preloadParams
		);
	}
}
