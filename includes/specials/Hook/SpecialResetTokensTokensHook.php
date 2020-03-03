<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialResetTokensTokensHook {
	/**
	 * Called when building token list for
	 * SpecialResetTokens.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tokens array of token information arrays in the format of
	 *   	[
	 *   		'preference' => '<preference-name>',
	 *   		'label-message' => '<message-key>',
	 *   	 ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialResetTokensTokens( &$tokens );
}
