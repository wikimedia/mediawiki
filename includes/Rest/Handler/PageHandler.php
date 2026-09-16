<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * Handler class for the Core REST API page endpoint. It returns page metadata
 * and, depending on the requested "prop" values, the page source and/or HTML.
 * It backs the following routes:
 * - v1/page/{title}            (prop: [ "source" ])
 * - v1/page/{title}/bare       (prop: [])
 * - v1/page/{title}/with_html  (prop: [ "html" ])
 * - content/v2/page/{title}    (prop as a query param)
 *
 * @internal
 */
class PageHandler extends PageRevisionContentHandler {

	public function __construct(
		private readonly TitleFormatter $titleFormatter,
		PageRestHelperFactory $helperFactory,
	) {
		parent::__construct( $helperFactory );
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getRoutePath(),
			$this->getRequest()
		);
	}

	protected function newContentHelper(): PageContentHelper {
		return $this->helperFactory->newPageContentHelper();
	}

	protected function newHtmlOutputHelper( PageIdentity $page, Authority $authority ): HtmlOutputHelper {
		$isShadowPage = $this->contentHelper->useShadowContent();
		if ( $isShadowPage ) {
			$htmlHelper = $this->helperFactory->newHtmlShadowOutputHelper(
				$page,
				ParserOptions::newFromAnon()
			);
		} else {
			$revision = $this->contentHelper->getTargetRevision();
			$htmlHelper = $this->helperFactory->newHtmlOutputRendererHelper(
				$page,
				$this->getValidatedParams(),
				$authority,
				$revision
			);

			$request = $this->getRequest();
			$acceptLanguage = $request->getHeaderLine(
				'Accept-Language'
			) ?: null;
			if ( $acceptLanguage ) {
				$htmlHelper->setVariantConversionLanguage(
					$acceptLanguage
				);
			}
		}

		return $htmlHelper;
	}

	private function constructHtmlUrl( PageReference $page ): string {
		// TODO: once legacy "v1" routes are removed, just use the path prefix from the module.
		$pathPrefix = $this->getModule()->getPathPrefix();
		if ( $pathPrefix === '' ) {
			$pathPrefix = 'v1';
		}

		return $this->getRouter()->getRouteUrl(
			'/' . $pathPrefix . '/page/{title}/html',
			[ 'title' => $this->titleFormatter->getPrefixedDBkey( $page ) ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccessPermission();

		$page = $this->contentHelper->getPageIdentity();

		// The page identity should not be null if checkAccessPermission() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		$props = array_fill_keys( $this->getOutputProps(), true );
		$wantHtml = $props['html'] ?? false;

		// Rendering a "shadow page" (a "known" but non-existing page such as a
		// system message page, which has no stored content) as HTML is a
		// backwards-compatibility quirk that a route must opt into by setting
		// "allowShadowHtml". Without it, and for source/bare output, a
		// non-existing page is a 404. The future behavior is pending a product
		// decision. See T349677 for discussion.
		//
		// Schema note: serving shadow pages (which have no revision) is why
		// ExistingPageV2.json has to mark latest.timestamp as nullable; see the
		// x-comment there. Tighten the schema if this quirk is removed.
		$allowShadowHtml = $wantHtml && ( $this->getConfig()['allowShadowHtml'] ?? false );

		// Only follow wiki redirects when serving HTML. The metadata output
		// (source/bare) instead reports the redirect via the redirect_target
		// field below. Title normalization redirects (301) happen either way.
		$followWikiRedirects = $wantHtml && $this->contentHelper->getRedirectsAllowed();

		$redirectHelper = $this->getRedirectHelper();
		$redirectHelper->setFollowWikiRedirects( $followWikiRedirects );

		// Should treat variant redirects a special case as wiki redirects
		// if ?redirect=no language variant should do nothing and fall into the 404 path
		$redirectResponse = $redirectHelper->createRedirectResponseIfNeeded(
			$page,
			$this->contentHelper->getTitleText()
		);

		if ( $redirectResponse !== null ) {
			// XXX: Set a Cache-Control: max-age=60 here, like PageHTMLHandler does?
			//      It's not clear why it does that.
			//      PageRedirectHelper already sets a longer max-age for normalization redirects.
			return $redirectResponse;
		}

		// No redirect applies, so the requested page has to actually have content.
		// Checked after the redirect handling above, so that a page which does not
		// exist under the requested title can still be redirected to its normalized
		// or language variant title.
		$this->contentHelper->checkHasContent( $allowShadowHtml );

		$cacheExpiry = null;
		if ( $this->useRestbaseMode() ) {
			// NOTE: RESTbase mode implies "bare". RESTbase mode is a WMF specific
			// compatibility mode, and WMF's API gateway only routes the the /bar
			// path with the compat header set. RESTbase never supported source
			// or HTML to be returned along with the metadata.
			$body = [ 'items' => [ $this->contentHelper->constructRestbaseCompatibleMetadata() ] ];
		} else {
			$body = $this->contentHelper->constructMetadata();

			if ( $props['source'] ?? false ) {
				$content = $this->contentHelper->getContent();
				$body['source'] = $content->getText();
			}

			if ( $wantHtml ) {
				$parserOutputHtml = $this->htmlHelper->getPageBundle()->html;
				$body['html'] = $parserOutputHtml;

				$cacheExpiry = $this->htmlHelper->getHtml()->getCacheExpiry();
			} else {
				// NOTE: This is now set unconditionally, even though the old
				// ExistingPageSource schema doesn't have it (only
				// ExistingPageBare did). This is not a breaking change,
				// since the schema allows additional properties.
				// The new ExistingPageV2 schema declares it (but optional).
				$body['html_url'] = $this->constructHtmlUrl( $page );
			}
		}

		// If the page is a wiki redirect, report the target via `redirect_target`.
		// A shadow page (e.g. a system message) has no ExistingPageRecord, and
		// cannot be a redirect, so skip it in that case.
		$existingPage = $this->contentHelper->getPage();
		if ( $existingPage ) {
			$redirectTargetUrl = $redirectHelper->getWikiRedirectTargetUrl( $existingPage );

			if ( $redirectTargetUrl ) {
				$body['redirect_target'] = $redirectTargetUrl;
			}
		}

		$response = $this->getResponseFactory()->createJson( $body );
		$this->contentHelper->setCacheControl( $response, $cacheExpiry );

		if ( $wantHtml ) {
			// The body is JSON with the HTML embedded, so don't set the
			// content language header (that is for raw HTML responses).
			$this->htmlHelper->putHeaders( $response, forHtml: false );
		}

		return $response;
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		// XXX: Should we return a more specific schema when using fixed
		//      configured prop?
		return __DIR__ . '/Schema/ExistingPageV2.json';
	}
}
