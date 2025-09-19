<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;

/**
 * A handler that returns linter errors for main (text) content revisions
 *
 * @package MediaWiki\Rest\Handler
 */
class RevisionLintHandler extends SimpleHandler {

	private RevisionContentHelper $contentHelper;
	private LintErrorChecker $lintErrorChecker;

	public function __construct(
		PageRestHelperFactory $helperFactory,
		LintErrorChecker $lintErrorChecker
	) {
		$this->contentHelper = $helperFactory->newRevisionContentHelper();
		$this->lintErrorChecker = $lintErrorChecker;
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
		$this->contentHelper->checkAccess();

		$page = $this->contentHelper->getPage();
		$revisionRecord = $this->contentHelper->getTargetRevision();

		// The page should be set if checkAccess() didn't throw
		Assert::invariant( $page !== null, 'Page should be known' );
		// The revision should be set if checkAccess() didn't throw
		Assert::invariant( $revisionRecord !== null, 'Revision should be known' );

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

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return $this->contentHelper->getParamSettings();
	}

	/**
	 * @inheritDoc
	 */
	protected function hasRepresentation() {
		return $this->contentHelper->hasContent();
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/ContentLintErrors.json';
	}
}
