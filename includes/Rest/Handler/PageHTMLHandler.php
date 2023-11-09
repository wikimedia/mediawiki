<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use Wikimedia\Assert\Assert;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /page/{title}/html,
 * - /page/{title}/with_html
 *
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends SimpleHandler {

	private HtmlOutputHelper $htmlHelper;
	private PageContentHelper $contentHelper;
	private PageRestHelperFactory $helperFactory;

	public function __construct(
		PageRestHelperFactory $helperFactory
	) {
		$this->contentHelper = $helperFactory->newPageContentHelper();
		$this->helperFactory = $helperFactory;
		$this->htmlHelper = $helperFactory->newHtmlOutputRendererHelper();
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getPath(),
			$this->getRequest()
		);
	}

	protected function postValidationSetup() {
		$authority = $this->getAuthority();
		$this->contentHelper->init( $authority, $this->getValidatedParams() );

		$page = $this->contentHelper->getPageIdentity();
		$isSystemMessage = $this->contentHelper->useDefaultSystemMessage();

		if ( $page ) {
			if ( $isSystemMessage ) {
				$this->htmlHelper = $this->helperFactory->newHtmlMessageOutputHelper();
				$this->htmlHelper->init( $page );
			} else {
				$revision = $this->contentHelper->getTargetRevision();
				// NOTE: We know that $this->htmlHelper is an instance of HtmlOutputRendererHelper
				//       because we set it in the constructor.
				$this->htmlHelper->init( $page, $this->getValidatedParams(), $authority, $revision );

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
		$params = $this->getRequest()->getQueryParams();

		if ( array_key_exists( 'redirect', $params ) ) {
			$followWikiRedirects = $params['redirect'] !== 'no';
		} else {
			$followWikiRedirects = true;
		}

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
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
			return $redirectResponse;
		}

		// We could have a missing page at this point, check and return 404 if that's the case
		$this->contentHelper->checkHasContent();

		$parserOutput = $this->htmlHelper->getHtml();
		$parserOutputHtml = $parserOutput->getRawText();

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'html':
				$response = $this->getResponseFactory()->create();
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutputHtml ) );
				break;
			case 'with_html':
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutputHtml;

				$redirectTargetUrl = $redirectHelper->getWikiRedirectTargetUrl( $page );

				if ( $redirectTargetUrl ) {
					$body['redirect_target'] = $redirectTargetUrl;
				}

				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

		$setContentLanguageHeader = ( $outputMode === 'html' );
		$this->htmlHelper->putHeaders( $response, $setContentLanguageHeader );

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

		// Vary eTag based on output mode
		return $this->htmlHelper->getETag( $this->getOutputMode() );
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() || !$this->contentHelper->hasContent() ) {
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
