<?php

namespace MediaWiki\Rest\Handler;

use Config;
use ConfigException;
use Exception;
use GuzzleHttp\Psr7\Uri;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use RestbaseVirtualRESTService;
use TitleFormatter;
use UIDGenerator;
use VirtualRESTServiceClient;
use WebRequest;
use Wikimedia\Message\MessageValue;

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

	/** @var VirtualRESTServiceClient */
	private $restClient;

	/** @var array */
	private $htmlResponse;

	/**
	 * @param Config $config
	 * @param PermissionManager $permissionManager
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param VirtualRESTServiceClient $virtualRESTServiceClient
	 */
	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		VirtualRESTServiceClient $virtualRESTServiceClient
	) {
		parent::__construct( $config, $permissionManager, $revisionLookup, $titleFormatter );

		$this->restClient = $virtualRESTServiceClient;
	}

	/**
	 * @param LinkTarget $title
	 * @return array
	 * @throws LocalizedHttpException
	 */
	private function fetchHtmlFromRESTBase( LinkTarget $title ): array {
		if ( $this->htmlResponse !== null ) {
			return $this->htmlResponse;
		}

		list( , $service ) = $this->restClient->getMountAndService( '/restbase/ ' );
		if ( !$service ) {
			try {
				$restConfig = $this->config->get( 'VirtualRestConfig' );
				if ( !isset( $restConfig['modules']['restbase'] ) ) {
					throw new ConfigException(
						__CLASS__ . " requires restbase module configured for VirtualRestConfig"
					);
				}
				$this->restClient->mount( '/restbase/',
					new RestbaseVirtualRESTService( $restConfig['modules']['restbase'] ) );
			} catch ( Exception $e ) {
				// This would usually be config exception, but let's fail on any exception
				throw new LocalizedHttpException( MessageValue::new( 'rest-html-backend-error' ), 500 );
			}
		}

		$this->htmlResponse = $this->restClient->run( [
			'method' => 'GET',
			'url' => '/restbase/local/v1/page/html/' .
				urlencode( $this->titleFormatter->getPrefixedDBkey( $title ) ) .
				'?redirect=false'
		] );
		return $this->htmlResponse;
	}

	/**
	 * @param LinkTarget $title
	 * @return array
	 * @throws LocalizedHttpException
	 */
	private function fetch200HtmlFromRESTBase( LinkTarget $title ): array {
		$restbaseResp = $this->fetchHtmlFromRESTBase( $title );
		if ( $restbaseResp['code'] !== 200 ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-html-backend-error' ),
				$restbaseResp['code']
			);
		}
		return $restbaseResp;
	}

	/**
	 * @return string
	 */
	private function constructHtmlUrl(): string {
		$wr = new WebRequest();
		$urlParts = wfParseUrl( $wr->getFullRequestURL() );
		$currentPathParts = explode( '/', $urlParts['path'] );
		$currentPathParts[ count( $currentPathParts ) - 1 ] = 'html';
		$urlParts['path'] = implode( '/', $currentPathParts );
		return Uri::fromParts( $urlParts );
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
				$body['html_url'] = $this->constructHtmlUrl();
				$response = $this->getResponseFactory()->createJson( $body );
				break;
			case 'html':
				$restbaseResp = $this->fetch200HtmlFromRESTBase( $titleObj );
				$response = $this->getResponseFactory()->create();
				$response->setHeader( 'Content-Type', $restbaseResp[ 'headers' ][ 'content-type' ] );
				$response->setBody( new StringStream( $restbaseResp[ 'body' ] ) );
				break;
			case 'with_html':
				$restbaseResp = $this->fetch200HtmlFromRESTBase( $titleObj );
				$body = $this->constructMetadata( $titleObj, $revision );
				$body['html'] = $restbaseResp['body'];
				$response = $this->getResponseFactory()->createJson( $body );
				break;
			default:
				throw new LogicException( "Unknown HTML type $htmlType" );
		}

		$response->setHeader( 'Cache-Control', 'max-age=' . self::MAX_AGE_200 );
		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() || !$this->isAccessible( $title ) ) {
			return null;
		}
		if ( $this->getHtmlType() === 'bare' ) {
			return '"' . $this->getLatestRevision()->getId() . '"';
		}

		$restbaseRes = $this->fetch200HtmlFromRESTBase( $title );
		return $restbaseRes['headers']['etag'] ?? null;
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() || !$this->isAccessible( $title ) ) {
			return null;
		}

		if ( $this->getHtmlType() === 'bare' ) {
			return $this->getLatestRevision()->getTimestamp();
		}

		$restbaseRes = $this->fetch200HtmlFromRESTBase( $title );
		$restbaseEtag = $restbaseRes['headers']['etag'] ?? null;
		if ( !$restbaseEtag ) {
			return null;
		}

		$etagComponents = [];
		if ( !preg_match( '/^(?:W\/)?"?[^"\/]+(?:\/([^"\/]+))"?$/',
			$restbaseEtag, $etagComponents )
		) {
			return null;
		}

		return UIDGenerator::getTimestampFromUUIDv1( $etagComponents[1] ) ?: null;
	}

	private function getHtmlType(): string {
		return $this->getConfig()['format'];
	}
}
