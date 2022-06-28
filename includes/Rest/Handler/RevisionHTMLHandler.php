<?php

namespace MediaWiki\Rest\Handler;

use Config;
use IBufferingStatsdDataFactory;
use LogicException;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageLookup;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /revision/{revision}/html,
 * - /revision/{revision}/with_html
 *
 * Class RevisionHTMLHandler
 * @package MediaWiki\Rest\Handler
 */
class RevisionHTMLHandler extends SimpleHandler {

	/** @var ParsoidHTMLHelper */
	private $htmlHelper;

	/** @var RevisionContentHelper */
	private $contentHelper;

	public function __construct(
		Config $config,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup,
		ParsoidOutputStash $parsoidOutputStash,
		IBufferingStatsdDataFactory $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess
	) {
		$this->contentHelper = new RevisionContentHelper(
			$config,
			$revisionLookup,
			$titleFormatter,
			$pageLookup
		);
		$this->htmlHelper = new ParsoidHTMLHelper(
			$parsoidOutputStash,
			$statsDataFactory,
			$parsoidOutputAccess
		);
	}

	protected function postValidationSetup() {
		// TODO: Once Authority supports rate limit (T310476), just inject the Authority.
		$user = MediaWikiServices::getInstance()->getUserFactory()
			->newFromUserIdentity( $this->getAuthority()->getUser() );

		$this->contentHelper->init( $user, $this->getValidatedParams() );

		$page = $this->contentHelper->getPage();
		$revision = $this->contentHelper->getTargetRevision();

		if ( $page && $revision ) {
			$this->htmlHelper->init( $page, $this->getValidatedParams(), $user, $revision );
		}
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$page = $this->contentHelper->getPage();
		$revisionRecord = $this->contentHelper->getTargetRevision();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		// The call to $this->contentHelper->getTargetRevision() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $revisionRecord !== null, 'Revision should be known' );

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'html':
				$parserOutput = $this->htmlHelper->getHtml();
				$response = $this->getResponseFactory()->create();
				// TODO: need to respect content-type returned by Parsoid.
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutput->getText() ) );
				break;
			case 'with_html':
				$parserOutput = $this->htmlHelper->getHtml();
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutput->getText();
				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $outputMode" );
		}

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

		// Vary eTag based on output mode
		return $this->htmlHelper->getETag( $this->getOutputMode() );
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
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

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return $this->contentHelper->hasContent();
	}
}
