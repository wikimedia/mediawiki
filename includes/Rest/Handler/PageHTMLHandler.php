<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseHeaders;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * A handler that returns Parsoid HTML for the following route:
 * - /page/{title}/html
 *
 * (The /page/{title}/with_html route is served by PageHandler via prop=html.)
 *
 * @internal
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends SimpleHandler {

	private HtmlOutputHelper $htmlHelper;
	private readonly PageContentHelper $contentHelper;

	public function __construct(
		private readonly PageRestHelperFactory $helperFactory,
	) {
		$this->contentHelper = $helperFactory->newPageContentHelper();
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getRoutePath(),
			$this->getRequest()
		);
	}

	protected function postValidationSetup() {
		$authority = $this->getAuthority();
		$this->contentHelper->init( $authority, $this->getValidatedParams() );

		$page = $this->contentHelper->getPageIdentity();
		$isShadowPage = $this->contentHelper->useShadowContent();

		if ( $page ) {
			if ( $isShadowPage ) {
				$this->htmlHelper = $this->helperFactory->newHtmlShadowOutputHelper(
					$page,
					ParserOptions::newFromAnon()
				);
			} else {
				$revision = $this->contentHelper->getTargetRevision();
				$this->htmlHelper = $this->helperFactory->newHtmlOutputRendererHelper(
					$page, $this->getValidatedParams(), $authority, $revision
				);

				$request = $this->getRequest();
				$acceptLanguage = $request->getHeaderLine( 'Accept-Language' ) ?: null;
				if ( $acceptLanguage ) {
					$this->htmlHelper->setVariantConversionLanguage(
						$acceptLanguage
					);
				}
			}
		}
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccessPermission();
		$page = $this->contentHelper->getPageIdentity();

		$followWikiRedirects = $this->contentHelper->getRedirectsAllowed();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccessPermission() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		$redirectHelper = $this->getRedirectHelper();
		$redirectHelper->setFollowWikiRedirects( $followWikiRedirects );
		// Should treat variant redirects a special case as wiki redirects
		// if ?redirect=no language variant should do nothing and fall into the 404 path
		$redirectResponse = $redirectHelper->createRedirectResponseIfNeeded(
			$page,
			$this->contentHelper->getTitleText()
		);

		if ( $redirectResponse !== null ) {
			// XXX: Do we really want 60 second max-age for wiki redirects as well?
			//      Shouldn't that use the same max-age as page content?
			//      PageRedirectHelper already sets a longer max-age for normalization redirects.
			$redirectResponse->setHeader( ResponseHeaders::CACHE_CONTROL, 'max-age=60' );
			return $redirectResponse;
		}

		// We could have a missing page at this point, check and return 404 if that's the case
		$this->contentHelper->checkHasContent();

		$cacheExpiry = $this->htmlHelper->getHtml()->getCacheExpiry();

		// This endpoint emits a full document from the page bundle
		$parserOutputHtml = $this->htmlHelper->getPageBundle()->html;

		$response = $this->getResponseFactory()->create();
		$this->contentHelper->setCacheControl( $response, $cacheExpiry );
		$response->setBody( new StringStream( $parserOutputHtml ) );

		$this->htmlHelper->putHeaders( $response );
		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	protected function getETag(): ?string {
		if ( !$this->contentHelper->isAccessible() || !$this->contentHelper->hasContent() ) {
			return null;
		}

		return $this->htmlHelper->getETag();
	}

	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() || !$this->contentHelper->hasContent() ) {
			return null;
		}

		return $this->htmlHelper->getLastModified();
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return array_merge(
			$this->contentHelper->getParamSettings(),
			// Note that postValidation we might end up using
			// a HtmlShadowOutputHelper, but the param settings
			// for that are a subset of those for HtmlOutputRendererHelper
			HtmlOutputRendererHelper::getParamSettings()
		);
	}

	public function getHeaderParamSettings(): array {
		return [
			'Accept-Language' => [
				self::PARAM_SOURCE => 'header',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-requestheader-desc-acceptlanguage' ),
				Handler::PARAM_EXAMPLE => 'en',
			],
		];
	}

	protected function generateResponseSpec( string $method ): array {
		$spec = parent::generateResponseSpec( $method );

		// TODO: Consider if we prefer something like:
		//    text/html; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/HTML/2.8.0"
		//  That would be more specific, but fragile when the profile version changes. It could
		//  also be inaccurate if the page content was not in fact produced by Parsoid.
		unset( $spec['200']['content']['application/json'] );
		$spec['200']['content']['text/html']['schema']['type'] = 'string';
		$spec['200']['content']['text/html']['example'] = '<h2 id="mwAA">Hello world</h2>';

		return $spec;
	}

}
