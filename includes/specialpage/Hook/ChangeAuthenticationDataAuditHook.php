<?php

namespace MediaWiki\SpecialPage\Hook;

use MediaWiki\Auth\AuthenticationRequest;
use StatusValue;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangeAuthenticationDataAuditHook {
	/**
	 * This hook is called when a user changes their password.
	 * No return data is accepted; this hook is for auditing only.
	 *
	 * @since 1.35
	 *
	 * @param AuthenticationRequest $req AuthenticationRequest object
	 *   describing the change (and target user)
	 * @param StatusValue $status StatusValue with the result of the action
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeAuthenticationDataAudit( $req, $status );
}
