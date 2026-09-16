<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Assert\Assert;

/**
 * Handler class for the Core REST API revision endpoint. It returns revision
 * metadata and, depending on the requested "prop" values, the source and/or
 * HTML. It backs the following routes:
 * - /revision/{id}           (prop: source)
 * - /revision/{id}/bare      (prop: none)
 * - /revision/{id}/with_html (prop: html)
 *
 * This replaces RevisionSourceHandler. RevisionHTMLHandler stays, but is only
 * used for top-level HTML output. The with_html mode is absorbed by
 * RevisionHandler.
 *
 * Instead of the old format / output mode configuration, RevisionHandler
 * supports a "prop" parameter, like the action API would. The value of "prop"
 * can also be fixed in the route config, so RevisionHandler can also be used to
 * implement the old .../with_html route.
 *
 * @internal
 */
class RevisionHandler extends PageRevisionContentHandler {

	protected function newContentHelper(): RevisionContentHelper {
		return $this->helperFactory->newRevisionContentHelper();
	}

	protected function newHtmlOutputHelper( PageIdentity $page, Authority $authority ): HtmlOutputHelper {
		$revision = $this->contentHelper->getTargetRevision();

		$htmlHelper = $this->helperFactory->newHtmlOutputRendererHelper(
			$page, $this->getValidatedParams(), $authority, $revision
		);

		$request = $this->getRequest();
		$acceptLanguage = $request->getHeaderLine( 'Accept-Language' ) ?: null;
		if ( $acceptLanguage ) {
			$htmlHelper->setVariantConversionLanguage( $acceptLanguage );
		}

		return $htmlHelper;
	}

	private function constructHtmlUrl( RevisionRecord $rev ): string {
		// TODO: once legacy "v1" routes are removed, just use the path prefix from the module.
		$pathPrefix = $this->getModule()->getPathPrefix();
		if ( $pathPrefix === '' ) {
			$pathPrefix = 'v1';
		}

		return $this->getRouter()->getRouteUrl(
			'/' . $pathPrefix . '/revision/{id}/html',
			[ 'id' => $rev->getId() ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		// Explicit assertion to make Phan happy (PhpStorm still has doubts).
		// True by virtue of this class' implementation of newContentHelper().
		Assert::invariant(
			$this->contentHelper instanceof RevisionContentHelper,
			'$this->contentHelper must be a RevisionContentHelper'
		);
		$this->contentHelper->checkAccessible();

		$cacheExpiry = null;
		$props = array_fill_keys( $this->getOutputProps(), true );
		if ( $this->useRestbaseMode() ) {
			$body = [ 'items' => [ $this->contentHelper->constructRestbaseCompatibleMetadata() ] ];
		} else {
			$body = $this->contentHelper->constructMetadata();

			if ( $props['source'] ?? false ) {
				$content = $this->contentHelper->getContent();
				$body['source'] = $content->getText();
			}

			if ( $props['html'] ?? false ) {
				$body['html'] = $this->htmlHelper->getPageBundle()->html;
				$cacheExpiry = $this->htmlHelper->getHtml()->getCacheExpiry();
			} else {
				$revision = $this->contentHelper->getTargetRevision();
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable revision is set when accessible
				$body['html_url'] = $this->constructHtmlUrl( $revision );
			}
		}

		$response = $this->getResponseFactory()->createJson( $body );
		$this->contentHelper->setCacheControl( $response, $cacheExpiry );

		if ( $props['html'] ?? false ) {
			// The body is JSON with the HTML embedded, so don't set the
			// content language header (that is for raw HTML responses).
			$this->htmlHelper->putHeaders( $response, forHtml: false );
		}

		return $response;
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		// XXX: Should we return a more specific schema when using fixed
		//      configured prop?
		return __DIR__ . '/Schema/ExistingRevisionV2.json';
	}
}
