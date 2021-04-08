<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialResetTokensTokens" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialResetTokensTokensHook {
	/**
	 * This hook is called when building token list for SpecialResetTokens.
	 *
	 * @since 1.35
	 *
	 * @param array &$tokens array of token information arrays in the format of
	 *   [
	 *        'preference' => '<preference-name>',
	 *        'label-message' => '<message-key>',
	 *   ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialResetTokensTokens( &$tokens );
}
