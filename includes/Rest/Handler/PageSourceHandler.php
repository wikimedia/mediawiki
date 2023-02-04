<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * Handler class for Core REST API Page Source endpoint with the following routes:
 * - /page/{title}
 * - /page/{title}/bare
 */
class PageSourceHandler extends SimpleHandler {
	use PageRedirectHandlerTrait;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var PageContentHelper */
	private $contentHelper;

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
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );
	}

	/**
	 * @param PageReference $page
	 * @return string
	 */
	private function constructHtmlUrl( PageReference $page ): string {
		return $this->getRouter()->getRouteUrl(
			'/v1/page/{title}/html',
			[ 'title' => $this->titleFormatter->getPrefixedText( $page ) ]
		);
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

		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectResponse = $this->createNormalizationRedirectResponseIfNeeded(
			$page,
			$this->contentHelper->getTitleText(),
			$this->titleFormatter
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'bare':
				$body = $this->contentHelper->constructMetadata();
				$body['html_url'] = $this->constructHtmlUrl( $page );
				break;
			case 'source':
				$content = $this->contentHelper->getContent();
				$body = $this->contentHelper->constructMetadata();
				$body['source'] = $content->getText();
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

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
		$this->contentHelper->setCacheControl( $response );

		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	protected function getETag(): ?string {
		return $this->contentHelper->getETag();
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		return $this->contentHelper->getLastModified();
	}

	private function getOutputMode(): string {
		return $this->getConfig()['format'];
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return $this->contentHelper->getParamSettings();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return $this->contentHelper->hasContent();
	}
}
