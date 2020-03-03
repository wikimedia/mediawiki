<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiTokensGetTokenTypesHook {
	/**
	 * DEPRECATED since 1.24! Use ApiQueryTokensRegisterTypes instead.
	 * Use this hook to extend action=tokens with new token types.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$tokenTypes supported token types in format 'type' => callback function
	 *   used to retrieve this type of tokens.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiTokensGetTokenTypes( &$tokenTypes );
}
