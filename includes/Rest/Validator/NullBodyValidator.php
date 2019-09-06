<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\RequestInterface;

/**
 * Do-nothing body validator
 */
class NullBodyValidator implements BodyValidator {

	public function validateBody( RequestInterface $request ) {
		return null;
	}

}
