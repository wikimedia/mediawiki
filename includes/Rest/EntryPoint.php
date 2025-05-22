<?php

namespace MediaWiki\Rest;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\Exception\MWExceptionRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Rest\BasicAccess\CompoundAuthorizer;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Reporter\MWErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\Message\ITextFormatter;

/**
 * @internal
 */
class EntryPoint extends MediaWikiEntryPoint {

	private RequestInterface $request;
	private ?Router $router = null;
	private ?CorsUtils $cors  = null;

	/**
	 * @internal Public for use in core tests
	 *
	 * @param MediaWikiServices $services
	 * @param IContextSource $context
	 * @param RequestInterface $request
	 * @param ResponseFactory $responseFactory
	 * @param CorsUtils $cors
	 *
	 * @return Router
	 */
	public static function createRouter(
		MediaWikiServices $services,
		IContextSource $context,
		RequestInterface $request,
		ResponseFactory $responseFactory,
		CorsUtils $cors
	): Router {
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

		$stats = $services->getStatsFactory();

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
		) )
			->setCors( $cors )
			->setStats( $stats );
	}

	/**
	 * @internal
	 * @return RequestInterface The RequestInterface object used by this entry point.
	 */
	public static function getMainRequest(): RequestInterface {
		static $mainRequest = null;

		if ( $mainRequest === null ) {
			$conf = MediaWikiServices::getInstance()->getMainConfig();
			$mainRequest = new RequestFromGlobals( [
				'cookiePrefix' => $conf->get( MainConfigNames::CookiePrefix )
			] );
		}

		return $mainRequest;
	}

	protected function doSetup() {
		parent::doSetup();

		$context = RequestContext::getMain();

		$responseFactory = new ResponseFactory( $this->getTextFormatters() );
		$responseFactory->setShowExceptionDetails(
			MWExceptionRenderer::shouldShowExceptionDetails()
		);

		$this->cors = new CorsUtils(
			new ServiceOptions(
				CorsUtils::CONSTRUCTOR_OPTIONS,
				$this->getServiceContainer()->getMainConfig()
			),
			$responseFactory,
			$context->getUser()
		);

		if ( !$this->router ) {
			$this->router = $this->createRouter(
				$this->getServiceContainer(),
				$context,
				$this->request,
				$responseFactory,
				$this->cors
			);
		}
	}

	/**
	 * Get a TextFormatter array from MediaWikiServices
	 *
	 * @return ITextFormatter[]
	 */
	private function getTextFormatters() {
		$services = $this->getServiceContainer();

		$code = $services->getContentLanguageCode()->toString();
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
	 *
	 * @return string[]
	 */
	private static function getRouteFiles( $conf ) {
		global $IP;
		$extensionsDir = $conf->get( MainConfigNames::ExtensionDirectory );
		// Always include the "official" routes. Include additional routes if specified.
		$routeFiles = [
			'includes/Rest/coreRoutes.json',
			...$conf->get( MainConfigNames::RestAPIAdditionalRouteFiles ),
		];
		foreach ( $routeFiles as &$file ) {
			if (
				str_starts_with( $file, '/' )
			) {
				// Allow absolute paths on non-Windows
			} elseif (
				str_starts_with( $file, 'extensions/' )
			) {
				// Support hacks like Wikibase.ci.php
				$file = substr_replace( $file, $extensionsDir,
					0, strlen( 'extensions' ) );
			} else {
				$file = "$IP/$file";
			}
		}

		return $routeFiles;
	}

	public function __construct(
		RequestInterface $request,
		RequestContext $context,
		EntryPointEnvironment $environment,
		MediaWikiServices $mediaWikiServices
	) {
		parent::__construct( $context, $environment, $mediaWikiServices );

		$this->request = $request;
	}

	/**
	 * Sets the router to use.
	 * Intended for testing.
	 */
	public function setRouter( Router $router ): void {
		$this->router = $router;
	}

	public function execute() {
		$this->startOutputBuffer();

		// IDEA: Move the call to cors->modifyResponse() into Module,
		//       so it's in the same class as cors->createPreflightResponse().
		$response = $this->cors->modifyResponse(
			$this->request,
			$this->router->execute( $this->request )
		);

		$webResponse = $this->getResponse();

		$webResponse->header(
			'HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' .
			$response->getReasonPhrase()
		);

		foreach ( $response->getRawHeaderLines() as $line ) {
			$webResponse->header( $line );
		}

		foreach ( $response->getCookies() as $cookie ) {
			$webResponse->setCookie(
				$cookie['name'],
				$cookie['value'],
				$cookie['expiry'],
				$cookie['options']
			);
		}

		// Clear all errors that might have been displayed if display_errors=On
		$this->discardOutputBuffer();

		$stream = $response->getBody();
		$stream->rewind();

		$this->prepareForOutput();

		if ( $stream instanceof CopyableStreamInterface ) {
			$stream->copyToStream( fopen( 'php://output', 'w' ) );
		} else {
			while ( true ) {
				$buffer = $stream->read( 65536 );
				if ( $buffer === '' ) {
					break;
				}
				$this->print( $buffer );
			}
		}
	}

}
