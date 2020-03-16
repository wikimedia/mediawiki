<?php

namespace MediaWiki\Api\Hook;

/**
 * @deprecated since 1.24 Use ApiQueryTokensRegisterTypes instead.
 * @ingroup Hooks
 */
interface ApiTokensGetTokenTypesHook {
	/**
	 * Use this hook to extend action=tokens with new token types.
	 *
	 * @since 1.35
	 *
	 * @param array &$tokenTypes Supported token types in format 'type' => callback function
	 *   used to retrieve this type of token
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiTokensGetTokenTypes( &$tokenTypes );
}
