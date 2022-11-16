<?php

namespace MediaWiki\Rest\Handler;

use LanguageCode;
use LogicException;
use MediaWiki\MediaWikiServices;
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
	use PageRedirectHandlerTrait;

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
		$params = $this->getRequest()->getQueryParams();

		if ( array_key_exists( 'redirect', $params ) ) {
			$followWikiRedirects = $params['redirect'] !== 'no';
		} else {
			$followWikiRedirects = true;
		}

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant(
			$page !== null || $isSystemMessage,
			'Page should be known or be a valid system message page'
		);

		if ( $isSystemMessage ) {
			$parserOutput = $this->getSystemMessageOutput();
		} else {
			'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
			$redirectResponse = $this->createRedirectResponseIfNeeded(
				$page,
				$followWikiRedirects,
				$this->contentHelper->getTitleText(),
				$this->titleFormatter,
				$this->redirectStore
			);

			if ( $redirectResponse !== null ) {
				return $redirectResponse;
			}

			$parserOutput = $this->htmlHelper->getHtml();
		}

		// Do not de-duplicate styles, Parsoid already does it in a slightly different way (T300325)
		$parserOutputHtml = $parserOutput->getText( [ 'deduplicateStyles' => false ] );

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'html':
				$response = $this->getResponseFactory()->create();
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutputHtml ) );
				break;
			case 'with_html':
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutputHtml;

				if ( $page ) {
					// If param redirect=no is present, that means this page can be a redirect
					// check for a redirectTargetUrl and send it to the body as `redirect_target`
					'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
					$redirectTargetUrl = $this->getWikiRedirectTargetUrl(
						$page, $this->redirectStore, $this->titleFormatter
					);

					if ( $redirectTargetUrl ) {
						$body['redirect_target'] = $redirectTargetUrl;
					}
				}

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
