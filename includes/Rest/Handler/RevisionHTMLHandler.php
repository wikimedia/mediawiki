<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
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
 * - /revision/{revision}/html
 *
 * (The /revision/{id}/with_html route is served by RevisionHandler via prop=html.)
 *
 * @internal
 */
class RevisionHTMLHandler extends SimpleHandler {

	private ?HtmlOutputRendererHelper $htmlHelper = null;
	private readonly RevisionContentHelper $contentHelper;

	public function __construct(
		private readonly PageRestHelperFactory $helperFactory,
	) {
		$this->contentHelper = $helperFactory->newRevisionContentHelper();
	}

	protected function postValidationSetup() {
		$authority = $this->getAuthority();
		$this->contentHelper->init( $authority, $this->getValidatedParams() );

		$page = $this->contentHelper->getPage();
		$revision = $this->contentHelper->getTargetRevision();

		if ( $page && $revision ) {
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

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccessible();

		$page = $this->contentHelper->getPage();
		$revisionRecord = $this->contentHelper->getTargetRevision();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccessible() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		// The call to $this->contentHelper->getTargetRevision() should not return null if
		// $this->contentHelper->checkAccessible() did not throw.
		Assert::invariant( $revisionRecord !== null, 'Revision should be known' );

		$cacheExpiry = $this->htmlHelper->getHtml()->getCacheExpiry();

		// This endpoint emits a full document from the page bundle
		$parserOutputHtml = $this->htmlHelper->getPageBundle()->html;

		// This endpoint emits a full HTML document.
		$response = $this->getResponseFactory()->create();
		// TODO: need to respect content-type returned by Parsoid.
		$response->setHeader( ResponseHeaders::CONTENT_TYPE, 'text/html' );
		$this->htmlHelper->putHeaders( $response, forHtml: true );
		$this->contentHelper->setCacheControl( $response, $cacheExpiry );
		$response->setBody( new StringStream( $parserOutputHtml ) );

		return $response;
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

		return $this->htmlHelper->getETag();
	}

	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
			return null;
		}

		return $this->htmlHelper->getLastModified();
	}

	public function needsWriteAccess(): bool {
		return false;
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

	public function getParamSettings(): array {
		return array_merge(
			$this->contentHelper->getParamSettings(),
			HtmlOutputRendererHelper::getParamSettings()
		);
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
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

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return $this->contentHelper->hasContent();
	}

	/** @inheritDoc */
	public function getResponseHeaderSettings(): array {
		return array_merge(
			parent::getResponseHeaderSettings(),
			[
				ResponseHeaders::CONTENT_TYPE => ResponseHeaders::RESPONSE_HEADER_DEFINITIONS[
					ResponseHeaders::CONTENT_TYPE
				]
			]
		);
	}
}
