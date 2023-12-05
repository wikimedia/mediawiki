<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionRecord;

/**
 * A handler that returns page source and metadata for the following routes:
 * - /revision/{revision}
 * - /revision/{revision}/bare
 */
class RevisionSourceHandler extends SimpleHandler {

	/** @var RevisionContentHelper */
	private $contentHelper;

	/**
	 * @param PageRestHelperFactory $helperFactory
	 */
	public function __construct( PageRestHelperFactory $helperFactory ) {
		$this->contentHelper = $helperFactory->newRevisionContentHelper();
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );
	}

	/**
	 * @param RevisionRecord $rev
	 * @return string
	 */
	private function constructHtmlUrl( RevisionRecord $rev ): string {
		return $this->getRouter()->getRouteUrl(
			'/v1/revision/{id}/html',
			[ 'id' => $rev->getId() ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run() {
		$this->contentHelper->checkAccess();

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'bare':
				$revisionRecord = $this->contentHelper->getTargetRevision();
				$body = $this->contentHelper->constructMetadata();
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable revisionRecord is set when used
				$body['html_url'] = $this->constructHtmlUrl( $revisionRecord );
				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response );
				break;
			case 'source':
				$content = $this->contentHelper->getContent();
				$body = $this->contentHelper->constructMetadata();
				$body['source'] = $content->getText();
				break;
			default:
				throw new LogicException( "Unknown output mode $outputMode" );
		}

		$response = $this->getResponseFactory()->createJson( $body );
		$this->contentHelper->setCacheControl( $response );

		return $response;
	}

	/**
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
