<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\Page\PageReference;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Message\MessageValue;

/**
 * Handler class for Core REST API Page Source endpoint with the following routes:
 * - /page/{title}
 * - /page/{title}/bare
 */
class PageSourceHandler extends SimpleHandler {

	private TitleFormatter $titleFormatter;
	private PageRestHelperFactory $helperFactory;
	private PageContentHelper $contentHelper;

	public function __construct(
		TitleFormatter $titleFormatter,
		PageRestHelperFactory $helperFactory
	) {
		$this->titleFormatter = $titleFormatter;
		$this->contentHelper = $helperFactory->newPageContentHelper();
		$this->helperFactory = $helperFactory;
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
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );
	}

	/**
	 * @param PageReference $page
	 * @return string
	 */
	private function constructHtmlUrl( PageReference $page ): string {
		// TODO: once legacy "v1" routes are removed, just use the path prefix from the module.
		$pathPrefix = $this->getModule()->getPathPrefix();
		if ( strlen( $pathPrefix ) == 0 ) {
			$pathPrefix = 'v1';
		}

		return $this->getRouter()->getRouteUrl(
			'/' . $pathPrefix . '/page/{title}/html',
			[ 'title' => $this->titleFormatter->getPrefixedText( $page ) ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();
		$page = $this->contentHelper->getPageIdentity();

		if ( !$page->exists() ) {
			// We may get here for "known" but non-existing pages, such as
			// message pages. Since there is no page, we should still return
			// a 404. See T349677 for discussion.
			$titleText = $this->contentHelper->getTitleText() ?? '(unknown)';
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )
					->plaintextParams( $titleText ),
				404
			);
		}

		$redirectHelper = $this->getRedirectHelper();

		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectResponse = $redirectHelper->createNormalizationRedirectResponseIfNeeded(
			$page,
			$this->contentHelper->getTitleText()
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'restbase': // compatibility for restbase migration
				$body = [ 'items' => [ $this->contentHelper->constructRestbaseCompatibleMetadata() ] ];
				break;
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

		// If param redirect=no is present, that means this page can be a redirect
		// check for a redirectTargetUrl and send it to the body as `redirect_target`
		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectTargetUrl = $redirectHelper->getWikiRedirectTargetUrl( $page );

		if ( $redirectTargetUrl ) {
			$body['redirect_target'] = $redirectTargetUrl;
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
		if ( $this->getRouter()->isRestbaseCompatEnabled( $this->getRequest() ) ) {
			return 'restbase';
		}
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

	public function getResponseBodySchemaFileName( string $method ): ?string {
		// This does not include restbase compatibility mode, which is triggered by request
		// headers. Presumably, such callers will look at the RESTBase spec instead.
		switch ( $this->getConfig()['format'] ) {
			case 'bare':
				$schema = 'includes/Rest/Handler/Schema/ExistingPageBare.json';
				break;
			case 'source':
				$schema = 'includes/Rest/Handler/Schema/ExistingPageSource.json';
				break;
			default:
				$schema = null;
				break;
		}

		return $schema;
	}
}
