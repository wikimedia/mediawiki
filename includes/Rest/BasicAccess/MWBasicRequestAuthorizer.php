<?php

namespace MediaWiki\Rest\BasicAccess;

use User;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * The concrete implementation of basic read restrictions in MediaWiki
 *
 * @internal
 */
class MWBasicRequestAuthorizer extends BasicRequestAuthorizer {
	/** @var User */
	private $user;

	/** @var PermissionManager */
	private $permissionManager;

	public function __construct( RequestInterface $request, Handler $handler,
		User $user, PermissionManager $permissionManager
	) {
		parent::__construct( $request, $handler );
		$this->user = $user;
		$this->permissionManager = $permissionManager;
	}

	protected function isReadAllowed() {
		return $this->permissionManager->isEveryoneAllowed( 'read' )
		   || $this->isAllowed( 'read' );
	}

	private function isAllowed( $action ) {
		return $this->permissionManager->userHasRight( $this->user, $action );
	}
}
