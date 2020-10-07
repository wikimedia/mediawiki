<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryTokensRegisterTypesHook {
	/**
	 * Use this hook to add additional token types to action=query&meta=tokens.
	 * Note that most modules will probably be able to use the CSRF token
	 * instead of creating their own token types.
	 *
	 * @since 1.35
	 *
	 * @param array &$salts [ type => salt to pass to User::getEditToken(), or array of salt
	 *   and key to pass to Session::getToken() ]
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryTokensRegisterTypes( &$salts );
}
