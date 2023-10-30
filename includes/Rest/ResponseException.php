<?php

namespace MediaWiki\Rest;

/**
 * This is an exception class that wraps a Response and extends
 * HttpException.  It is used when a particular response type
 * (whatever the HTTP status code) is treated as an exceptional output
 * in your API, and you want to be able to throw it from wherever you
 * are and immediately halt request processing.  It can also be used
 * to customize the standard 3xx or 4xx error Responses returned by
 * the standard HttpException, for example to add custom headers.
 *
 * @newable
 * @since 1.36
 */
class ResponseException extends HttpException {

	/**
	 * The wrapped Response.
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @stable to call
	 *
	 * @param ResponseInterface $response The wrapped Response
	 */
	public function __construct( ResponseInterface $response ) {
		parent::__construct( 'Response', $response->getStatusCode() );
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function getResponse(): Response {
		// TODO: Allow this method to return a ResponseInterface.
		//       But that would be a breaking change.
		return Response::cast( $this->response );
	}
}
