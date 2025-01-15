<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiBase;
use MediaWiki\Rest\Hook\RestCheckCanExecuteHook;
use MediaWiki\User\User;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiCheckCanExecute" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiCheckCanExecuteHook {
	/**
	 * This hook is called during ApiMain::checkExecutePermissions. Use this hook to further
	 * authenticate and authorize API clients before executing the module.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module
	 * @param User $user Current user
	 * @param MessageSpecifier|string|array &$message API message to die with.
	 *  Specific values accepted depend on the MediaWiki version:
	 *  * 1.43+: MessageSpecifier, string message key, or key+parameters array to
	 *    pass to ApiBase::dieWithError().
	 *  * 1.29+: IApiMessage, Message, string message key, or key+parameters array to
	 *    pass to ApiBase::dieWithError().
	 *  * 1.27+: IApiMessage, or a key or key+parameters in ApiBase::$messageMap.
	 *  * Earlier: A key or key+parameters in ApiBase::$messageMap.
	 * @return bool|void True or no return value to continue, or false and set a
	 *  message to cancel the request
	 *
	 * @see ApiMessage::create() for how the $message parameter is interpreted.
	 * @see ApiQueryCheckCanExecuteHook for query modules.
	 * @see RestCheckCanExecuteHook for REST API modules.
	 */
	public function onApiCheckCanExecute( $module, $user, &$message );
}
