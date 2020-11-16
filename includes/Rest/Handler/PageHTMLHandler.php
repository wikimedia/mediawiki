<?php

namespace MediaWiki\Rest\Handler;

use Config;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use ParserCache;
use ParserOptions;
use ParserOutput;
use TitleFactory;
use TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /page/{title}/html,
 * - /page/{title}/with_html
 * - /page/{title}/bare routes.
 * Currently the HTML is fetched from RESTBase, thus in order to use the routes,
 * RESTBase must be installed and VirtualRESTService for RESTBase needs to be configured.
 *
 * Class PageHTMLHandler
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends LatestPageContentHandler {
	private const MAX_AGE_200 = 5;

	/** @var ParserCache */
	private $parserCache;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var Parsoid|null */
	private $parsoid;

	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory,
		ParserCacheFactory $parserCacheFactory,
		WikiPageFactory $wikiPageFactory
	) {
		parent::__construct(
			$config,
			$permissionManager,
			$revisionLookup,
			$titleFormatter,
			$titleFactory
		);
		$this->parserCache = $parserCacheFactory->getInstance( 'parsoid' );
		$this->wikiPageFactory = $wikiPageFactory;
	}

	/**
	 * @param LinkTarget $title
	 * @return string
	 */
	private function constructHtmlUrl( LinkTarget $title ): string {
		return $this->getRouter()->getRouteUrl( '/v1/page/{title}/html', [ 'title' => $title ] );
	}

	/**
	 * Sets the 'Cache-Control' header no more then provided $expiry.
	 * @param ResponseInterface $response
	 * @param int|null $expiry
	 */
	private function setCacheControl( ResponseInterface $response, int $expiry = null ) {
		if ( $expiry === null ) {
			$maxAge = self::MAX_AGE_200;
		} else {
			$maxAge = min( self::MAX_AGE_200, $expiry );
		}
		$response->setHeader(
			'Cache-Control',
			'max-age=' . $maxAge
		);
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( string $title ): Response {
		$titleObj = $this->getTitle();
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$this->isAccessible( $titleObj ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}

		$revision = $this->getLatestRevision();
		if ( !$revision ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-no-revision' )->plaintextParams( $title ),
				404
			);
		}

		$htmlType = $this->getHtmlType();
		switch ( $htmlType ) {
			case 'bare':
				$body = $this->constructMetadata( $titleObj, $revision );
				$body['html_url'] = $this->constructHtmlUrl( $titleObj );
				$response = $this->getResponseFactory()->createJson( $body );
				$this->setCacheControl( $response );
				break;
			case 'html':
				$parserOutput = $this->getHtmlFromCache( $titleObj );
				$response = $this->getResponseFactory()->create();
				// TODO: need to respect content-type returned by Parsoid.
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutput->getText() ) );
				break;
			case 'with_html':
				$parserOutput = $this->getHtmlFromCache( $titleObj );
				$body = $this->constructMetadata( $titleObj, $revision );
				$body['html'] = $parserOutput->getText();
				$response = $this->getResponseFactory()->createJson( $body );
				$this->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $htmlType" );
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
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() || !$this->isAccessible( $title ) ) {
			return null;
		}
		if ( $this->getHtmlType() === 'bare' ) {
			return '"' . $this->getLatestRevision()->getId() . '"';
		}
		// While we are not differentiating the output by parser options and
		// only provide anon parses, cache time or page touched provides a good
		// reference for etag. Once we start doing non-anon parses, this needs
		// to start incorporating current users ParserOptions.
		return '"' . $this->getLastModified() . '"';
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() || !$this->isAccessible( $title ) ) {
			return null;
		}
		if ( $this->getHtmlType() === 'bare' ) {
			return $this->getLatestRevision()->getTimestamp();
		}
		$wikiPage = $this->wikiPageFactory->newFromTitle( $title );
		// The cache time of the metadata belongs to the ParserOutput
		// variant cached last. While we are not differentiating the
		// parser options, it's fine. Once we start supporting non-anon
		// parses, we would need to fetch the actual ParserOutput to find
		// out it's cache time.
		$cacheMetadata = $this->parserCache->getMetadata(
			$wikiPage,
			ParserCache::USE_CURRENT_ONLY
		);
		if ( $cacheMetadata ) {
			return $cacheMetadata->getCacheTime();
		} else {
			return $wikiPage->getTouched();
		}
	}

	private function getHtmlType(): string {
		return $this->getConfig()['format'];
	}

	/**
	 * Assert that Parsoid services are available.
	 * TODO: once parsoid glue services are in core,
	 * this will become a no-op and will be removed.
	 * See T265518
	 * @throws LocalizedHttpException
	 */
	private function assertParsoidInstalled() {
		$services = MediaWikiServices::getInstance();
		if ( $services->has( 'ParsoidSiteConfig' ) &&
			$services->has( 'ParsoidPageConfigFactory' ) &&
			$services->has( 'ParsoidDataAccess' ) ) {
			return;
		}
		throw new LocalizedHttpException(
			MessageValue::new( 'rest-html-backend-error' ), 501 );
	}

	/**
	 * @return Parsoid
	 * @throws LocalizedHttpException
	 */
	protected function createParsoid(): Parsoid {
		$this->assertParsoidInstalled();
		if ( $this->parsoid === null ) {
			// TODO: once parsoid glue services are in core,
			// this will need to use normal DI.
			// See T265518
			$services = MediaWikiServices::getInstance();
			$this->parsoid = new Parsoid(
				$services->get( 'ParsoidSiteConfig' ),
				$services->get( 'ParsoidDataAccess' )
			);
		}
		return $this->parsoid;
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return PageConfig
	 * @throws LocalizedHttpException
	 */
	private function createPageConfig(
		LinkTarget $linkTarget
	): PageConfig {
		$this->assertParsoidInstalled();
		// Currently everything is parsed as anon since Parsoid
		// can't report the used options.
		// Already checked that title/revision exist and accessible.
		return MediaWikiServices::getInstance()
			->get( 'ParsoidPageConfigFactory' )
			->create( $linkTarget );
	}

	/**
	 * @param LinkTarget $title
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 */
	private function getHtmlFromCache( LinkTarget $title ): ParserOutput {
		$wikiPage = $this->wikiPageFactory->newFromLinkTarget( $title );
		$parserOutput = $this->parserCache->get( $wikiPage, ParserOptions::newFromAnon() );
		if ( $parserOutput ) {
			return $parserOutput;
		}
		$fakeParserOutput = $this->parse( $title );
		$this->parserCache->save( $fakeParserOutput, $wikiPage, ParserOptions::newFromAnon() );
		return $fakeParserOutput;
	}

	/**
	 * @param LinkTarget $title
	 * @return ParserOutput
	 * @throws LocalizedHttpException
	 */
	private function parse( LinkTarget $title ): ParserOutput {
		$parsoid = $this->createParsoid();
		$pageConfig = $this->createPageConfig( $title );
		try {
			$pageBundle = $parsoid->wikitext2html( $pageConfig, [
				'discardDataParsoid' => true,
				'pageBundle' => true,
			] );
			return new ParserOutput( $pageBundle->html );
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-html-backend-error' ),
				400,
				[ 'reason' => $e->getMessage() ]
			);
		} catch ( ResourceLimitExceededException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-resource-limit-exceeded' ),
				413,
				[ 'reason' => $e->getMessage() ]
			);
		}
	}
}
