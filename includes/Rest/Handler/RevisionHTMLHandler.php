<?php

namespace MediaWiki\Rest\Handler;

use Config;
use LogicException;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use TitleFactory;
use TitleFormatter;
use Wikimedia\Assert\Assert;
use Wikimedia\UUID\GlobalIdGenerator;

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
		TitleFactory $titleFactory,
		ParserCacheFactory $parserCacheFactory,
		WikiPageFactory $wikiPageFactory,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->contentHelper = new RevisionContentHelper(
			$config,
			$revisionLookup,
			$titleFormatter,
			$titleFactory
		);
		$this->htmlHelper = new ParsoidHTMLHelper(
			$parserCacheFactory->getParserCache( 'parsoid' ),
			$parserCacheFactory->getRevisionOutputCache( 'parsoid' ),
			$wikiPageFactory,
			$globalIdGenerator
		);
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );

		$title = $this->contentHelper->getTitle();
		$revision = $this->contentHelper->getTargetRevision();

		if ( $title && $revision ) {
			$this->htmlHelper->init( $title, $revision );
		}
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$titleObj = $this->contentHelper->getTitle();
		$revisionRecord = $this->contentHelper->getTargetRevision();

		// The call to $this->contentHelper->getTitle() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $titleObj !== null, 'Title should be known' );

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

		return $this->htmlHelper->getETag();
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
		return $this->contentHelper->getParamSettings();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return $this->contentHelper->hasContent();
	}
}
