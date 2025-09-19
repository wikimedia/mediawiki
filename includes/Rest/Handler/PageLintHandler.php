<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;

/**
 * A handler that returns linter errors for main (text) content of pages
 *
 * @package MediaWiki\Rest\Handler
 */
class PageLintHandler extends SimpleHandler {

	private PageRestHelperFactory $helperFactory;
	private PageContentHelper $contentHelper;
	private LintErrorChecker $lintErrorChecker;

	public function __construct(
		PageRestHelperFactory $helperFactory,
		LintErrorChecker $lintErrorChecker
	) {
		$this->helperFactory = $helperFactory;
		$this->contentHelper = $helperFactory->newPageContentHelper();
		$this->lintErrorChecker = $lintErrorChecker;
	}

	public function getParamSettings(): array {
		return $this->contentHelper->getParamSettings();
	}

	public function needsWriteAccess(): bool {
		return false;
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
		$authority = $this->getAuthority();
		$this->contentHelper->init( $authority, $this->getValidatedParams() );
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccessPermission();
		$page = $this->contentHelper->getPageIdentity();

		$followWikiRedirects = $this->contentHelper->getRedirectsAllowed();

		// The page should be set if checkAccessPermission() didn't throw
		Assert::invariant( $page !== null, 'Page should be known' );

		$redirectHelper = $this->getRedirectHelper();
		$redirectHelper->setFollowWikiRedirects( $followWikiRedirects );
		// Respect wiki redirects and variant redirects unless ?redirect=no was provided.
		// With ?redirect=no, non-existing pages with an existing variant will get a 404.
		$redirectResponse = $redirectHelper->createRedirectResponseIfNeeded(
			$page,
			$this->contentHelper->getTitleText()
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		// We could have a missing page at this point, check and return 404 if that's the case
		$this->contentHelper->checkHasContent();

		// Get the content and make sure that it is text content
		$content = $this->contentHelper->getContent();

		try {
			$lintErrors = $this->lintErrorChecker->check( $content->getText() );
			$response = $this->getResponseFactory()->createJson( $lintErrors );
			$this->contentHelper->setCacheControl( $response );
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-lint-backend-error", [ $e->getMessage() ] ),
				400
			);
		} catch ( ResourceLimitExceededException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-resource-limit-exceeded", [ $e->getMessage() ] ),
				413
			);
		}

		return $response;
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/ContentLintErrors.json';
	}
}
