<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\User\UserIdentity;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * The concrete implementation of basic read/write restrictions in MediaWiki
 *
 * @internal
 */
class MWBasicRequestAuthorizer extends BasicRequestAuthorizer {
	/** @var UserIdentity */
	private $user;

	/** @var PermissionManager */
	private $permissionManager;

	public function __construct( RequestInterface $request, Handler $handler,
		UserIdentity $user, PermissionManager $permissionManager
	) {
		parent::__construct( $request, $handler );
		$this->user = $user;
		$this->permissionManager = $permissionManager;
	}

	protected function isReadAllowed() {
		return $this->permissionManager->isEveryoneAllowed( 'read' )
		   || $this->isAllowed( 'read' );
	}

	protected function isWriteAllowed() {
		return $this->isAllowed( 'writeapi' );
	}

	private function isAllowed( $action ) {
		return $this->permissionManager->userHasRight( $this->user, $action );
	}
}
