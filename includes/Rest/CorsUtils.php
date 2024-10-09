<?php

namespace MediaWiki\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\HeaderParser\Origin;
use MediaWiki\User\UserIdentity;

/**
 * @internal
 */
class CorsUtils implements BasicAuthorizerInterface {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::AllowedCorsHeaders,
		MainConfigNames::AllowCrossOrigin,
		MainConfigNames::RestAllowCrossOriginCookieAuth,
		MainConfigNames::CanonicalServer,
		MainConfigNames::CrossSiteAJAXdomains,
		MainConfigNames::CrossSiteAJAXdomainExceptions,
	];

	private ServiceOptions $options;
	private ResponseFactory $responseFactory;
	private UserIdentity $user;

	public function __construct(
		ServiceOptions $options,
		ResponseFactory $responseFactory,
		UserIdentity $user
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->responseFactory = $responseFactory;
		$this->user = $user;
	}

	/**
	 * Only allow registered users to make unsafe cross-origin requests.
	 *
	 * @param RequestInterface $request
	 * @param Handler $handler
	 * @return string|null If the request is denied, the string error code. If
	 *   the request is allowed, null.
	 */
	public function authorize( RequestInterface $request, Handler $handler ) {
		// Handlers that need write access are by definition a cache-miss, therefore there is no
		// need to vary by the origin.
		if (
			$handler->needsWriteAccess()
			&& $request->hasHeader( 'Origin' )
			&& !$this->user->isRegistered()
		) {
			$origin = Origin::parseHeaderList( $request->getHeader( 'Origin' ) );

			if ( !$this->allowOrigin( $origin ) ) {
				return 'rest-cross-origin-anon-write';
			}
		}

		return null;
	}

	/**
	 * @param Origin $origin
	 * @return bool
	 */
	private function allowOrigin( Origin $origin ): bool {
		$allowed = array_merge( [ $this->getCanonicalDomain() ],
			$this->options->get( MainConfigNames::CrossSiteAJAXdomains ) );
		$excluded = $this->options->get( MainConfigNames::CrossSiteAJAXdomainExceptions );

		return $origin->match( $allowed, $excluded );
	}

	/**
	 * @return string
	 */
	private function getCanonicalDomain(): string {
		$res = parse_url( $this->options->get( MainConfigNames::CanonicalServer ) );
		'@phan-var array $res';

		$host = $res['host'] ?? '';
		$port = $res['port'] ?? null;

		return $port ? "$host:$port" : $host;
	}

	/**
	 * Modify response to allow for CORS.
	 *
	 * This method should be executed for every response from the REST API
	 * including errors.
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 */
	public function modifyResponse( RequestInterface $request, ResponseInterface $response ): ResponseInterface {
		if ( !$this->options->get( MainConfigNames::AllowCrossOrigin ) ) {
			return $response;
		}

		$allowedOrigin = '*';

		if ( $this->options->get( MainConfigNames::RestAllowCrossOriginCookieAuth ) ) {
			// @TODO Since we only Vary the response if (1) the method is OPTIONS or (2) the user is
			//       registered, it is safe to only add the Vary: Origin when those two conditions
			//       are met since a response to a logged-in user's request is not cachable.
			//       Therefore, logged out users should always get `Access-Control-Allow-Origin: *`
			//       on all non-OPTIONS request and logged-in users *may* get
			//      `Access-Control-Allow-Origin: <requested origin>`

			// Vary All Requests by the Origin header.
			$response->addHeader( 'Vary', 'Origin' );

			// If the Origin header is missing, there is nothing to check against.
			if ( $request->hasHeader( 'Origin' ) ) {
				$origin = Origin::parseHeaderList( $request->getHeader( 'Origin' ) );
				if ( $this->allowOrigin( $origin ) ) {
					// Only set the allowed origin for preflight requests, or for main requests where a registered
					// user is authenticated. This prevents having to Vary all requests by the Origin.
					// Anonymous users will always get '*', registered users *may* get the requested origin back.
					if ( $request->getMethod() === 'OPTIONS' || $this->user->isRegistered() ) {
						$allowedOrigin = $origin->getSingleOrigin();
					}
				}
			}
		}

		// If the Origin was determined to be something other than *any* allow the session
		// cookies to be sent with the main request. If this is the main request, allow the
		// response to be read.
		//
		// If the client includes the credentials on a simple request (HEAD, GET, etc.), but
		// they do not pass this check, the browser will refuse to allow the client to read the
		// response. The client may resolve this by repeating the request without the
		// credentials.
		if ( $allowedOrigin !== '*' ) {
			$response->setHeader( 'Access-Control-Allow-Credentials', 'true' );
		}

		$response->setHeader( 'Access-Control-Allow-Origin', $allowedOrigin );

		return $response;
	}

	/**
	 * Create a CORS preflight response.
	 *
	 * @param array $allowedMethods
	 * @return Response
	 */
	public function createPreflightResponse( array $allowedMethods ): Response {
		$response = $this->responseFactory->createNoContent();
		$response->setHeader( 'Access-Control-Allow-Methods', $allowedMethods );

		$allowedHeaders = $this->options->get( MainConfigNames::AllowedCorsHeaders );
		$allowedHeaders = array_merge( $allowedHeaders, array_diff( [
			// Authorization header must be explicitly listed which prevent the use of '*'
			'Authorization',
			// REST must allow Content-Type to be operational
			'Content-Type',
			// REST relies on conditional requests for some endpoints
			'If-Mach',
			'If-None-Match',
			'If-Modified-Since',
		], $allowedHeaders ) );
		$response->setHeader( 'Access-Control-Allow-Headers', $allowedHeaders );

		return $response;
	}
}
