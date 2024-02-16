<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\RequestInterface;

/**
 * Do-nothing body validator
 */
class NullBodyValidator implements BodyValidator {

	/**
	 * @inheritDoc
	 * @return null
	 */
	public function validateBody( RequestInterface $request ) {
		return null;
	}

}
