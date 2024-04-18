<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\ConfigException;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\RouteDefinitionException;

/**
 * A generic redirect handler for the REST API.
 *
 * To declare a redirect in a route file, use the following structure:
 * @code
 * {
 *     "path": "/path/to/trigger/a/redirect/{foo}",
 *     "redirect": {
 *         "path": "/redirect/target/{foo}",
 *         "code": 302
 *     }
 * }
 * @endcode
 *
 * It is not necessary to specify the handler class.
 * The default status code is 308.
 * Path parameters and query parameters will be looped through.
 *
 * @since 1.43
 * @package MediaWiki\Rest\Handler
 */
class RedirectHandler extends Handler {

	/**
	 * @return Response
	 * @throws ConfigException
	 */
	public function execute() {
		$path = $this->getConfig()['redirect']['path'] ?? '';
		if ( $path === '' ) {
			throw new RouteDefinitionException( 'No registered redirect for this path' );
		}
		$code = $this->getConfig()['redirect']['code'] ?? 308;
		$pathParams = $this->getRequest()->getPathParams();
		$queryParams = $this->getRequest()->getQueryParams();
		$locationPath = $this->getRouter()->getRoutePath( $path, $pathParams, $queryParams );
		$response = $this->getResponseFactory()->createRedirect( $locationPath, $code );
		return $response;
	}
}
