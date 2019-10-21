<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\User\UserIdentity;

/**
 * A factory for MWBasicRequestAuthorizer which passes through a UserIdentity.
 *
 * @internal
 */
class MWBasicAuthorizer extends BasicAuthorizerBase {
	/** @var UserIdentity */
	private $user;

	/** @var PermissionManager */
	private $permissionManager;

	public function __construct( UserIdentity $user, PermissionManager $permissionManager ) {
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
