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
	public function __construct(
		RequestInterface $request,
		Handler $handler,
		private readonly Authority $authority,
	) {
		parent::__construct( $request, $handler );
	}

	/** @inheritDoc */
	protected function isReadAllowed() {
		return $this->authority->isAllowed( 'read' );
	}

	/** @inheritDoc */
	protected function isWriteAllowed() {
		return true;
	}
}
