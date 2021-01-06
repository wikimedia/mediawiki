<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * A factory for MWBasicRequestAuthorizer which passes through a UserIdentity.
 *
 * @internal
 */
class MWBasicAuthorizer extends BasicAuthorizerBase {
	/** @var Authority */
	private $authority;

	public function __construct( Authority $authority ) {
		$this->authority = $authority;
	}

	protected function createRequestAuthorizer( RequestInterface $request,
		Handler $handler
	): BasicRequestAuthorizer {
		return new MWBasicRequestAuthorizer( $request, $handler, $this->authority );
	}
}
