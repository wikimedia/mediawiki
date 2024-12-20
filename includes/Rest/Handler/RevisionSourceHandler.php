<?php

namespace MediaWiki\Rest\Handler;

use LogicException;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Message\MessageValue;

/**
 * A handler that returns page source and metadata for the following routes:
 * - /revision/{revision}
 * - /revision/{revision}/bare
 */
class RevisionSourceHandler extends SimpleHandler {

	private RevisionContentHelper $contentHelper;

	public function __construct( PageRestHelperFactory $helperFactory ) {
		$this->contentHelper = $helperFactory->newRevisionContentHelper();
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );
	}

	private function constructHtmlUrl( RevisionRecord $rev ): string {
		// TODO: once legacy "v1" routes are removed, just use the path prefix from the module.
		$pathPrefix = $this->getModule()->getPathPrefix();
		if ( $pathPrefix === '' ) {
			$pathPrefix = 'v1';
		}

		return $this->getRouter()->getRouteUrl(
			'/' . $pathPrefix . '/revision/{id}/html',
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
			case 'restbase': // compatibility for restbase migration
				$body = [ 'items' => [ $this->contentHelper->constructRestbaseCompatibleMetadata() ] ];
				break;
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

	private function getTargetFormat(): string {
		return $this->getConfig()['format'];
	}

	protected function getResponseBodySchemaFileName( string $method ): ?string {
		switch ( $this->getTargetFormat() ) {
			case 'bare':
				return 'includes/Rest/Handler/Schema/RevisionMetaDataBare.json';

			case 'source':
				return 'includes/Rest/Handler/Schema/RevisionMetaDataWithSource.json';

			default:
				throw new LocalizedHttpException(
					new MessageValue( "rest-unsupported-target-format" ), 500
				);
		}
	}

	protected function getETag(): ?string {
		return $this->contentHelper->getETag();
	}

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
}
