<?php

namespace MediaWiki\Rest;

use ExtensionRegistry;
use MediaWiki;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\BasicAccess\MWBasicAuthorizer;
use MediaWiki\Rest\Validator\Validator;
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

	public static function main() {
		// URL safety checks
		global $wgRequest;
		if ( !$wgRequest->checkUrlExtension() ) {
			return;
		}

		$context = RequestContext::getMain();

		// Set $wgTitle and the title in RequestContext, as in api.php
		global $wgTitle;
		$wgTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/rest.php' );
		$context->setTitle( $wgTitle );

		$services = MediaWikiServices::getInstance();
		$conf = $services->getMainConfig();
		$objectFactory = $services->getObjectFactory();

		if ( !$conf->get( 'EnableRestAPI' ) ) {
			wfHttpError( 403, 'Access Denied',
				'Set $wgEnableRestAPI to true to enable the experimental REST API' );
			return;
		}

		$request = new RequestFromGlobals( [
			'cookiePrefix' => $conf->get( 'CookiePrefix' )
		] );

		$responseFactory = new ResponseFactory( self::getTextFormatters( $services ) );

		// @phan-suppress-next-line PhanAccessMethodInternal
		$authorizer = new MWBasicAuthorizer( $context->getUser(),
			$services->getPermissionManager() );

		// @phan-suppress-next-line PhanAccessMethodInternal
		$restValidator = new Validator( $objectFactory,
			$services->getPermissionManager(),
			$request,
			RequestContext::getMain()->getUser()
		);

		global $IP;
		$router = new Router(
			[ "$IP/includes/Rest/coreRoutes.json" ],
			ExtensionRegistry::getInstance()->getAttribute( 'RestRoutes' ),
			$conf->get( 'RestPath' ),
			$services->getLocalServerObjectCache(),
			$responseFactory,
			$authorizer,
			$objectFactory,
			$restValidator
		);

		$entryPoint = new self(
			$context,
			$request,
			$wgRequest->response(),
			$router );
		$entryPoint->execute();
	}

	/**
	 * Get a TextFormatter array from MediaWikiServices
	 *
	 * @param MediaWikiServices $services
	 * @return ITextFormatter[]
	 */
	public static function getTextFormatters( MediaWikiServices $services ) {
		$langs = array_unique( [
			$services->getMainConfig()->get( 'ContLang' )->getCode(),
			'en'
		] );
		$textFormatters = [];
		$factory = $services->getMessageFormatterFactory();
		foreach ( $langs as $lang ) {
			$textFormatters[] = $factory->getTextFormatter( $lang );
		}
		return $textFormatters;
	}

	public function __construct( RequestContext $context, RequestInterface $request,
		WebResponse $webResponse, Router $router
	) {
		$this->context = $context;
		$this->request = $request;
		$this->webResponse = $webResponse;
		$this->router = $router;
	}

	public function execute() {
		ob_start();
		$response = $this->router->execute( $this->request );

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
		$mw->doPostOutputShutdown( 'fast' );
	}
}
