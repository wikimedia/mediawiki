<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangeAuthenticationDataAuditHook {
	/**
	 * Called when user changes his password.
	 * No return data is accepted; this hook is for auditing only.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $req AuthenticationRequest object describing the change (and target user)
	 * @param ?mixed $status StatusValue with the result of the action
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangeAuthenticationDataAudit( $req, $status );
}
