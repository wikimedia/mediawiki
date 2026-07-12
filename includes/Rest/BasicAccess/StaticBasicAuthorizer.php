<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * An authorizer which returns a value from authorize() which is given in the constructor.
 *
 * @internal
 */
class StaticBasicAuthorizer implements BasicAuthorizerInterface {
	/**
	 * @see BasicAuthorizerInterface::authorize()
	 *
	 * @param string|null $value The value returned by authorize(). If the
	 *   request is denied, this is the string error code. If the request is
	 *   allowed, it is null.
	 */
	public function __construct(
		private readonly ?string $value = null,
	) {
	}

	/** @inheritDoc */
	public function authorize( RequestInterface $request, Handler $handler ) {
		return $this->value;
	}
}
