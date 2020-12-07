<?php

namespace MediaWiki\Rest\Handler;

use Config;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionLookup;
use RequestContext;
use TitleFactory;
use TitleFormatter;

/**
 * Handler class for Core REST API Page Source endpoint
 */
class PageSourceHandler extends SimpleHandler {

	/** @var PageContentHelper */
	private $contentHelper;

	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		$this->contentHelper = new PageContentHelper(
			$config,
			$permissionManager,
			$revisionLookup,
			$titleFormatter,
			$titleFactory
		);
	}

	protected function postValidationSetup() {
		// TODO: inject user properly
		$user = RequestContext::getMain()->getUser();
		$this->contentHelper->init( $user, $this->getValidatedParams() );
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$content = $this->contentHelper->getPageContent();
		$body = $this->contentHelper->constructMetadata();
		$body['source'] = $content->getText();

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

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return $this->contentHelper->getParamSettings();
	}
}
