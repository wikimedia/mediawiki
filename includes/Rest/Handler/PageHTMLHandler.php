<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
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
	use PageRedirectHandlerTrait;

	/** @var HtmlOutputHelper */
	private $htmlHelper;

	/** @var PageContentHelper */
	private $contentHelper;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var RedirectStore */
	private $redirectStore;

	private PageRestHelperFactory $helperFactory;

	public function __construct(
		TitleFormatter $titleFormatter,
		RedirectStore $redirectStore,
		PageRestHelperFactory $helperFactory
	) {
		$this->titleFormatter = $titleFormatter;
		$this->redirectStore = $redirectStore;
		$this->contentHelper = $helperFactory->newPageContentHelper();
		$this->helperFactory = $helperFactory;
		$this->htmlHelper = $helperFactory->newHtmlOutputRendererHelper();
	}

	protected function postValidationSetup() {
		// TODO: Once Authority supports rate limit (T310476), just inject the Authority.
		$user = MediaWikiServices::getInstance()->getUserFactory()
			->newFromUserIdentity( $this->getAuthority()->getUser() );

		$this->contentHelper->init( $user, $this->getValidatedParams() );

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
				$this->htmlHelper->init( $page, $this->getValidatedParams(), $user, $revision );

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
		$this->contentHelper->checkAccess();
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

				$redirectTargetUrl = $this->getWikiRedirectTargetUrl(
					$page, $this->redirectStore, $this->titleFormatter
				);

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
