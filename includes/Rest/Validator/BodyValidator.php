<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestInterface;

/**
 * Interface for validating a request body
 *
 * @stable to implement
 * @see \MediaWiki\Rest\Handler::getBodyValidator()
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
	 * @return mixed|null
	 * @throws HttpException on validation failure
	 */
	public function validateBody( RequestInterface $request );

}
