<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Shared base class for the REST handlers that serve page and revision content
 * (PageHandler and RevisionHandler). It provides the "prop"-based output
 * selection (source and/or HTML) and the related ETag, Last-Modified, param,
 * and representation plumbing that both share.
 *
 * Each subclass keeps its own constructor, run(), and post-validation setup,
 * since those differ (for example, page redirects and shadow pages).
 *
 * @internal
 */
abstract class PageRevisionContentHandler extends SimpleHandler {

	/**
	 * The page content helper, set up during post-validation setup.
	 */
	protected ?PageContentHelper $contentHelper;

	/**
	 * The HTML output helper, set up during post-validation setup when the
	 * requested page/revision exists. Null when there is nothing to render,
	 * or no HTML output was requested according to getOutputProps().
	 */
	protected ?HtmlOutputHelper $htmlHelper = null;

	public function __construct(
		protected readonly PageRestHelperFactory $helperFactory,
	) {
	}

	/**
	 * Constructs the content helper backing this handler.
	 * Should use $this->helperFactory.
	 */
	abstract protected function newContentHelper(): PageContentHelper;

	/**
	 * Constructs the HTML output helper backing this handler.
	 * Should use $this->helperFactory.
	 * Can rely on $this->$contentHelper.
	 */
	abstract protected function newHtmlOutputHelper(
		PageIdentity $page,
		Authority $authority
	): HtmlOutputHelper;

	protected function postValidationSetup() {
		$this->contentHelper = $this->newContentHelper();

		$authority = $this->getAuthority();
		$this->contentHelper->init( $authority, $this->getValidatedParams() );

		if ( $this->isHtmlRequested() ) {
			$page = $this->contentHelper->getPageIdentity();

			if ( $page ) {
				$this->htmlHelper = $this->newHtmlOutputHelper(
					$page,
					$authority
				);
			}
		}
	}

	/**
	 * The content properties ("source", "html") to include in the response,
	 * either pinned in the route config or taken from the "prop" query param.
	 *
	 * @return string[]
	 */
	protected function getOutputProps(): array {
		$prop = $this->getConfig()['prop'] ?? null;
		if ( $prop !== null ) {
			return $prop;
		}

		$params = $this->getValidatedParams();
		return $params['prop']; // rely on default from getParamSettings()
	}

	protected function isHtmlRequested(): bool {
		return in_array( 'html', $this->getOutputProps() );
	}

	protected function useRestbaseMode(): bool {
		return $this->getRouter()->isRestbaseCompatEnabled(
			$this->getRequest()
		);
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		// XXX: how about shadow content?
		return $this->contentHelper->hasContent();
	}

	/**
	 * Returns an ETag representing the requested content. When HTML is part of
	 * the output, the ETag is based on the HTML helper so that it reflects the
	 * Parsoid render, which varies for example by language variant. The prop
	 * suffix keeps distinct prop combinations (such as "html" and "source|html")
	 * from sharing an ETag.
	 * @return string|null
	 */
	protected function getETag(): ?string {
		if ( !$this->contentHelper->isAccessible() || !$this->contentHelper->hasContent() ) {
			return null;
		}

		// XXX: Make distinct based on endpoint as well?
		//      Two endpoints returning the same etag for different content
		//      feels wrong, even though it's ok based on HTTP semantics
		//      (Tags only have to be unique per resource, that is, per endpoint)
		$mode = implode( '|', $this->getOutputProps() );
		if ( $this->htmlHelper && !$this->useRestbaseMode() ) {
			return $this->htmlHelper->getETag( $mode );
		} else {
			return $this->contentHelper->getETag( $mode );
		}
	}

	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() || !$this->contentHelper->hasContent() ) {
			return null;
		}

		// HTML can vary without the page being edited.
		if ( $this->htmlHelper ) {
			return $this->htmlHelper->getLastModified();
		} else {
			return $this->contentHelper->getLastModified();
		}
	}

	public function getParamSettings(): array {
		// NOTE: getParamSettings() runs during validation, before
		// postValidationSetup() has created $this->contentHelper, so use a
		// throwaway helper here. Only static parameter definitions are needed,
		// which do not require init().
		$settings = $this->newContentHelper()->getParamSettings();

		// NOTE: we do not include the params from HtmlOutputRendererHelper
		// here. They add support for 'stash' and 'flavor'.
		// We'll leave that to PageHTMLRenderer.

		// If prop is not fixed in the route config, make it a query param.
		if ( !isset( $this->getConfig()['prop'] ) ) {
			$settings['prop'] = [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => [ 'source', 'html' ],
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => [],
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-page-prop' ),
				Handler::PARAM_EXAMPLE => 'html',
			];
		}

		return $settings;
	}
}
