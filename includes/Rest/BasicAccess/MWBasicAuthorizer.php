<?php

namespace MediaWiki\Rest\BasicAccess;

use User;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * A factory for MWBasicRequestAuthorizer which passes through a User object
 *
 * @internal
 */
class MWBasicAuthorizer extends BasicAuthorizerBase {
	/** @var User */
	private $user;

	/** @var PermissionManager */
	private $permissionManager;

	public function __construct( User $user, PermissionManager $permissionManager ) {
		$this->user = $user;
		$this->permissionManager = $permissionManager;
	}

	protected function createRequestAuthorizer( RequestInterface $request,
		Handler $handler
	): BasicRequestAuthorizer {
		return new MWBasicRequestAuthorizer( $request, $handler, $this->user,
			$this->permissionManager );
	}
}
