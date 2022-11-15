<?php

namespace MediaWiki\Rest\Handler;

use LanguageCode;
use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use ParserOutput;
use TitleFormatter;
use Wikimedia\Assert\Assert;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;

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

		$page = $this->contentHelper->getPageIdentity();

		if ( $this->contentHelper->useDefaultSystemMessage() ) {
			// We can't use the helper object with system messages.
			// TODO: We should have an implementation of HtmlOutputRendererHelper
			//       for system messages in the future.
			// Currently NO OP.
		} elseif ( $page ) {
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
		$isSystemMessage = $this->contentHelper->useDefaultSystemMessage();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant(
			$page !== null || $isSystemMessage,
			'Page should be known or be a valid system message page'
		);

		if ( $isSystemMessage ) {
			$parserOutput = $this->getSystemMessageOutput();
		} else {
			'@phan-var ExistingPageRecord $page';
			$pageRedirectResponse = $this->createPageRedirectResponse( $page );

			if ( $pageRedirectResponse !== null ) {
				return $pageRedirectResponse;
			}
			$parserOutput = $this->htmlHelper->getHtml();
		}

		// Do not de-duplicate styles, Parsoid already does it in a slightly different way (T300325)
		$parserOutputHtml = $parserOutput->getText( [ 'deduplicateStyles' => false ] );

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
				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

		if ( !$isSystemMessage ) {
			$setContentLanguageHeader = ( $outputMode === 'html' );
			$this->htmlHelper->putHeaders( $response, $setContentLanguageHeader );
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

	private function getSystemMessageOutput(): ParserOutput {
		$message = $this->contentHelper->getDefaultSystemMessage();

		$messageDom = DOMUtils::parseHTML( $message->parse() );
		DOMUtils::appendToHead( $messageDom, 'meta', [
			'http-equiv' => 'content-language',
			'content' => LanguageCode::bcp47( $message->getLanguage()->getCode() ),
		] );

		$messageDocHtml = ContentUtils::toXML( $messageDom );

		// TODO: Set language in the response headers.
		return new ParserOutput( $messageDocHtml );
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

		if ( $this->contentHelper->useDefaultSystemMessage() ) {
			// XXX: We end up generating the HTML twice. Would be nice to avoid that.
			// But messages are small, and not hit a lot...
			$output = $this->getSystemMessageOutput();
			return '"message/' . sha1( $output->getRawText() ) . '/' . $this->getOutputMode() . '"';
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

		if ( $this->contentHelper->useDefaultSystemMessage() ) {
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
