<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * Wraps an array of BasicAuthorizerInterface and checks them
 * all to authorize the request
 * @internal
 * @package MediaWiki\Rest\BasicAccess
 */
class CompoundAuthorizer implements BasicAuthorizerInterface {

	/**
	 * @param BasicAuthorizerInterface[] $authorizers
	 */
	public function __construct(
		private array $authorizers = [],
	) {
	}

	/**
	 * Adds a BasicAuthorizerInterface to the chain of authorizers.
	 */
	public function addAuthorizer( BasicAuthorizerInterface $authorizer ): CompoundAuthorizer {
		$this->authorizers[] = $authorizer;
		return $this;
	}

	/**
	 * Checks all registered authorizers and returns the first encountered error.
	 * @param RequestInterface $request
	 * @param Handler $handler
	 * @return string|null
	 */
	public function authorize( RequestInterface $request, Handler $handler ) {
		foreach ( $this->authorizers as $authorizer ) {
			$result = $authorizer->authorize( $request, $handler );
			if ( $result ) {
				return $result;
			}
		}
		return null;
	}
}
