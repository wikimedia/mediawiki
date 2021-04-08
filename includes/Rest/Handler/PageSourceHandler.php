<?php

namespace MediaWiki\Rest\Handler;

use Config;
use LogicException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionLookup;
use Title;
use TitleFactory;
use TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * Handler class for Core REST API Page Source endpoint with the following routes:
 * - /page/{title}
 * - /page/{title}/bare
 */
class PageSourceHandler extends SimpleHandler {

	/** @var PageContentHelper */
	private $contentHelper;

	public function __construct(
		Config $config,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		$this->contentHelper = new PageContentHelper(
			$config,
			$revisionLookup,
			$titleFormatter,
			$titleFactory
		);
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );
	}

	/**
	 * @param Title $title
	 * @return string
	 */
	private function constructHtmlUrl( Title $title ): string {
		return $this->getRouter()->getRouteUrl(
			'/v1/page/{title}/html',
			[ 'title' => $title->getPrefixedText() ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$titleObj = $this->contentHelper->getTitle();

		// The call to $this->contentHelper->getTitle() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $titleObj !== null, 'Title should be known' );

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'bare':
				$body = $this->contentHelper->constructMetadata();
				$body['html_url'] = $this->constructHtmlUrl( $titleObj );
				break;
			case 'source':
				$content = $this->contentHelper->getContent();
				$body = $this->contentHelper->constructMetadata();
				$body['source'] = $content->getText();
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

		$response = $this->getResponseFactory()->createJson( $body );
		$this->contentHelper->setCacheControl( $response );

		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string
	 */
	protected function getETag(): string {
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
