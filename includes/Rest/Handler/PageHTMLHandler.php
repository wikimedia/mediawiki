<?php

namespace MediaWiki\Rest\Handler;

use Config;
use LogicException;
use MediaWiki\Page\PageLookup;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use TitleFormatter;
use Wikimedia\Assert\Assert;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /page/{title}/html,
 * - /page/{title}/with_html
 *
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends SimpleHandler {

	/** @var ParsoidHTMLHelper */
	private $htmlHelper;

	/** @var PageContentHelper */
	private $contentHelper;

	public function __construct(
		Config $config,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		ParserCacheFactory $parserCacheFactory,
		GlobalIdGenerator $globalIdGenerator,
		PageLookup $pageLookup
	) {
		$this->contentHelper = new PageContentHelper(
			$config,
			$revisionLookup,
			$titleFormatter,
			$pageLookup
		);
		$this->htmlHelper = new ParsoidHTMLHelper(
			$parserCacheFactory->getParserCache( 'parsoid' ),
			$parserCacheFactory->getRevisionOutputCache( 'parsoid' ),
			$globalIdGenerator
		);
	}

	protected function postValidationSetup() {
		$this->contentHelper->init( $this->getAuthority(), $this->getValidatedParams() );

		$page = $this->contentHelper->getPage();
		if ( $page ) {
			$this->htmlHelper->init( $page );
		}
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$page = $this->contentHelper->getPage();

		// The call to $this->contentHelper->getPage() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $page !== null, 'Page should be known' );

		$parserOutput = $this->htmlHelper->getHtml();
		// Do not de-duplicate styles, Parsoid already does it in a slightly different way (T300325)
		$parserOutputHtml = $parserOutput->getText( [ 'deduplicateStyles' => false ] );

		$outputMode = $this->getOutputMode();
		switch ( $outputMode ) {
			case 'html':
				$response = $this->getResponseFactory()->create();
				// TODO: need to respect content-type returned by Parsoid.
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutputHtml ) );
				break;
			case 'with_html':
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutputHtml;
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
}
