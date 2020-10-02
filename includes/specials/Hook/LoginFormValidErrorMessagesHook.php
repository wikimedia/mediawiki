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
	 * This hook is called in LoginForm when a function gets valid error messages.
	 *
	 * This hook allows extensions to add additional error messages (except messages already
	 * in LoginForm::$validErrorMessages).
	 *
	 * @since 1.35
	 *
	 * @param string[] &$messages Already added message keys (including message
	 *   keys from LoginForm::$validErrorMessages)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoginFormValidErrorMessages( array &$messages );
}
