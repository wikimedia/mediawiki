<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /page/{title}/html,
 * - /page/{title}/with_html
 *
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends SimpleHandler {

	/** @var HtmlOutputRendererHelper */
	private $htmlHelper;

	/** @var PageContentHelper */
	private $contentHelper;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var RedirectStore */
	private $redirectStore;

	public function __construct(
		TitleFormatter $titleFormatter,
		RedirectStore $redirectStore,
		PageRestHelperFactory $helperFactory
	) {
		$this->titleFormatter = $titleFormatter;
		$this->redirectStore = $redirectStore;
		$this->contentHelper = $helperFactory->newPageContentHelper();
		$this->htmlHelper = $helperFactory->newHtmlOutputRendererHelper();
	}

	protected function postValidationSetup() {
		// TODO: Once Authority supports rate limit (T310476), just inject the Authority.
		$user = MediaWikiServices::getInstance()->getUserFactory()
			->newFromUserIdentity( $this->getAuthority()->getUser() );

		$this->contentHelper->init( $user, $this->getValidatedParams() );

		$page = $this->contentHelper->getPage();
		if ( $page ) {
			$this->htmlHelper->init( $page, $this->getValidatedParams(), $user );

			$request = $this->getRequest();
			$acceptLanguage = $request->getHeaderLine( 'Accept-Language' ) ?: null;
			if ( $acceptLanguage ) {
				$this->htmlHelper->setVariantConversionLanguage( $acceptLanguage );
			}
		}
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();
		$page = $this->contentHelper->getPage();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		$pageRedirectResponse = $this->createPageRedirectResponse( $page );

		if ( $pageRedirectResponse !== null ) {
			return $pageRedirectResponse;
		}

		$parserOutput = $this->htmlHelper->getHtml();
		// Do not de-duplicate styles, Parsoid already does it in a slightly different way (T300325)
		$parserOutputHtml = $parserOutput->getText( [ 'deduplicateStyles' => false ] );

		$outputMode = $this->getOutputMode();
		$setContentLanguageHeader = true;
		switch ( $outputMode ) {
			case 'html':
				$response = $this->getResponseFactory()->create();
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->htmlHelper->putHeaders( $response, $setContentLanguageHeader );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutputHtml ) );
				break;
			case 'with_html':
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutputHtml;
				$response = $this->getResponseFactory()->createJson( $body );
				// For JSON content, it doesn't make sense to set content language header
				$this->htmlHelper->putHeaders( $response, !$setContentLanguageHeader );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

		return $response;
	}

	/**
	 * Check for Page Redirects and create a Redirect Response
	 * @param ExistingPageRecord $page
	 * @return Response|null
	 */
	private function createPageRedirectResponse( ExistingPageRecord $page ): ?Response {
		$titleAsRequested = $this->contentHelper->getTitleText();
		$normalizedTitle = $this->titleFormatter->getPrefixedDBkey( $page );

		// Check for normalization redirects
		if ( $titleAsRequested !== $normalizedTitle ) {
			$redirectTargetUrl = $this->getRouteUrl( [
				"title" => $normalizedTitle
			] );
			return $this->getResponseFactory()->createPermanentRedirect( $redirectTargetUrl );
		}

		$params = $this->getRequest()->getQueryParams();
		$redirectParam = $params['redirect'] ?? null;
		$redirectTarget = $this->redirectStore->getRedirectTarget( $page );

		if ( $redirectTarget === null ) {
			return null;
		}

		// Check if page is a redirect
		if ( $page->isRedirect() && $redirectParam !== 'no' ) {
			$redirectTargetUrl = $this->getRouteUrl( [
				"title" => $this->titleFormatter->getPrefixedDBkey(
					$redirectTarget
				)
			] );
			return $this->getResponseFactory()->createTemporaryRedirect( $redirectTargetUrl );
		}

		return null;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	protected function getETag(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
			return null;
		}

		// Vary eTag based on output mode
		return $this->htmlHelper->getETag( $this->getOutputMode() );
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
			return null;
		}
		return $this->htmlHelper->getLastModified();
	}

	private function getOutputMode(): string {
		return $this->getConfig()['format'];
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return array_merge(
			$this->contentHelper->getParamSettings(),
			$this->htmlHelper->getParamSettings()
		);
	}
}
