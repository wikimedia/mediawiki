<?php

namespace MediaWiki\Rest;

use Config;
use ExtensionRegistry;
use IContextSource;
use MediaWiki;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\BasicAccess\CompoundAuthorizer;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Reporter\MWErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use MWExceptionRenderer;
use RequestContext;
use Title;
use WebResponse;
use Wikimedia\Message\ITextFormatter;

class EntryPoint {
	/** @var RequestInterface */
	private $request;
	/** @var WebResponse */
	private $webResponse;
	/** @var Router */
	private $router;
	/** @var RequestContext */
	private $context;
	/** @var CorsUtils */
	private $cors;
	/** @var ?RequestInterface */
	private static $mainRequest;

	/**
	 * @param IContextSource $context
	 * @param RequestInterface $request
	 * @param ResponseFactory $responseFactory
	 * @param CorsUtils $cors
	 * @return Router
	 */
	private static function createRouter(
		IContextSource $context, RequestInterface $request, ResponseFactory $responseFactory, CorsUtils $cors
	): Router {
		$services = MediaWikiServices::getInstance();
		$conf = $services->getMainConfig();

		$authority = $context->getAuthority();
		$authorizer = new CompoundAuthorizer();
		$authorizer
			->addAuthorizer( new MWBasicAuthorizer( $authority ) )
			->addAuthorizer( $cors );

		$objectFactory = $services->getObjectFactory();
		$restValidator = new Validator( $objectFactory,
			$request,
			$authority
		);

		return ( new Router(
			self::getRouteFiles( $conf ),
			ExtensionRegistry::getInstance()->getAttribute( 'RestRoutes' ),
			new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $conf ),
			$services->getLocalServerObjectCache(),
			$responseFactory,
			$authorizer,
			$authority,
			$objectFactory,
			$restValidator,
			new MWErrorReporter(),
			$services->getHookContainer(),
			$context->getRequest()->getSession()
		) )->setCors( $cors );
	}

	/**
	 * @return RequestInterface The RequestInterface object used by this entry point.
	 */
	public static function getMainRequest(): RequestInterface {
		if ( self::$mainRequest === null ) {
			$conf = MediaWikiServices::getInstance()->getMainConfig();
			self::$mainRequest = new RequestFromGlobals( [
				'cookiePrefix' => $conf->get( MainConfigNames::CookiePrefix )
			] );
		}
		return self::$mainRequest;
	}

	public static function main() {
		// URL safety checks
		global $wgRequest;

		$context = RequestContext::getMain();

		// Set $wgTitle and the title in RequestContext, as in api.php
		global $wgTitle;
		$wgTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/rest.php' );
		$context->setTitle( $wgTitle );

		$services = MediaWikiServices::getInstance();
		$conf = $services->getMainConfig();

		$responseFactory = new ResponseFactory( self::getTextFormatters( $services ) );
		$responseFactory->setShowExceptionDetails( MWExceptionRenderer::shouldShowExceptionDetails() );

		$cors = new CorsUtils(
			new ServiceOptions(
				CorsUtils::CONSTRUCTOR_OPTIONS, $conf
			),
			$responseFactory,
			$context->getUser()
		);

		$request = self::getMainRequest();

		$router = self::createRouter( $context, $request, $responseFactory, $cors );

		$entryPoint = new self(
			$context,
			$request,
			$wgRequest->response(),
			$router,
			$cors
		);
		$entryPoint->execute();
	}

	/**
	 * Get a TextFormatter array from MediaWikiServices
	 *
	 * @param MediaWikiServices $services
	 * @return ITextFormatter[]
	 */
	private static function getTextFormatters( MediaWikiServices $services ) {
		$code = $services->getContentLanguage()->getCode();
		$langs = array_unique( [ $code, 'en' ] );
		$textFormatters = [];
		$factory = $services->getMessageFormatterFactory();

		foreach ( $langs as $lang ) {
			$textFormatters[] = $factory->getTextFormatter( $lang );
		}
		return $textFormatters;
	}

	/**
	 * @param Config $conf
	 * @return string[]
	 */
	private static function getRouteFiles( $conf ) {
		global $IP;
		$extensionsDir = $conf->get( MainConfigNames::ExtensionDirectory );
		// Always include the "official" routes. Include additional routes if specified.
		$routeFiles = array_merge(
			[ 'includes/Rest/coreRoutes.json' ],
			$conf->get( MainConfigNames::RestAPIAdditionalRouteFiles )
		);
		foreach ( $routeFiles as &$file ) {
			if ( str_starts_with( $file, '/' ) ) {
				// Allow absolute paths on non-Windows
			} elseif ( str_starts_with( $file, 'extensions/' ) ) {
				// Support hacks like Wikibase.ci.php
				$file = substr_replace( $file, $extensionsDir, 0, strlen( 'extensions' ) );
			} else {
				$file = "$IP/$file";
			}
		}
		return $routeFiles;
	}

	public function __construct( RequestContext $context, RequestInterface $request,
		WebResponse $webResponse, Router $router, CorsUtils $cors
	) {
		$this->context = $context;
		$this->request = $request;
		$this->webResponse = $webResponse;
		$this->router = $router;
		$this->cors = $cors;
	}

	public function execute() {
		ob_start();
		$response = $this->cors->modifyResponse(
			$this->request,
			$this->router->execute( $this->request )
		);

		$this->webResponse->header(
			'HTTP/' . $response->getProtocolVersion() . ' ' .
			$response->getStatusCode() . ' ' .
			$response->getReasonPhrase() );

		foreach ( $response->getRawHeaderLines() as $line ) {
			$this->webResponse->header( $line );
		}

		foreach ( $response->getCookies() as $cookie ) {
			$this->webResponse->setCookie(
				$cookie['name'],
				$cookie['value'],
				$cookie['expiry'],
				$cookie['options'] );
		}

		// Clear all errors that might have been displayed if display_errors=On
		ob_end_clean();

		$stream = $response->getBody();
		$stream->rewind();

		MediaWiki::preOutputCommit( $this->context );

		if ( $stream instanceof CopyableStreamInterface ) {
			$stream->copyToStream( fopen( 'php://output', 'w' ) );
		} else {
			while ( true ) {
				$buffer = $stream->read( 65536 );
				if ( $buffer === '' ) {
					break;
				}
				echo $buffer;
			}
		}

		$mw = new MediaWiki;
		$mw->doPostOutputShutdown();
	}
}
