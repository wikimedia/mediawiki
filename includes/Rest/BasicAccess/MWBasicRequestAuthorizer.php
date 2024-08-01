<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * The concrete implementation of basic read/write restrictions in MediaWiki
 *
 * @internal
 */
class MWBasicRequestAuthorizer extends BasicRequestAuthorizer {
	private Authority $authority;

	public function __construct(
		RequestInterface $request,
		Handler $handler,
		Authority $authority
	) {
		parent::__construct( $request, $handler );
		$this->authority = $authority;
	}

	protected function isReadAllowed() {
		return $this->authority->isAllowed( 'read' );
	}

	protected function isWriteAllowed() {
		return true;
	}
}
