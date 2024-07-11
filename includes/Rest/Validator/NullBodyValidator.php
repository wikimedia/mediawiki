<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\RequestInterface;

/**
 * Do-nothing body validator
 *
 * @deprecated since 1.43, because Handler::getBodyValidator() is deprecated.
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
