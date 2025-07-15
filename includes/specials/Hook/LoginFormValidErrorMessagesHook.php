<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LoginFormValidErrorMessages" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LoginFormValidErrorMessagesHook {
	/**
	 * This hook is called in {@link LoginHelper::getValidErrorMessages} before returning the
	 * list of message keys that are valid for display on Special:UserLogin or Special:CreateAccount.
	 *
	 * This hook allows extensions to add additional error or warning messages to the array returned by
	 * {@link LoginHelper::getValidErrorMessages}.
	 *
	 * @since 1.35
	 *
	 * @see LoginHelper::getValidErrorMessages for more information
	 * @param string[] &$messages Already added message keys (includes message keys defined by MediaWiki core)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoginFormValidErrorMessages( array &$messages );
}
