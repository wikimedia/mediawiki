<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestInterface;

/**
 * Interface for validating a request body
 */
interface BodyValidator {

	/**
	 * Validate the body of a request.
	 *
	 * This may return a data structure representing the parsed body. When used
	 * in the context of Handler::validateParams(), the returned value will be
	 * available to the handler via Handler::getValidatedBody().
	 *
	 * @param RequestInterface $request
	 * @return mixed
	 * @throws HttpException on validation failure
	 */
	public function validateBody( RequestInterface $request );

}
