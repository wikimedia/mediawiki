<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use IApiMessage;
use Message;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiCheckCanExecuteHook {
	/**
	 * This hook is called during ApiMain::checkCanExecute. Use this hook to further
	 * authenticate and authorize API clients before executing the module.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module Module object
	 * @param User $user Current user
	 * @param IApiMessage|Message|string|array &$message API message to die with.
	 *  Specific values accepted depend on the MediaWiki version:
	 *  * 1.29+: IApiMessage, Message, string message key, or key+parameters array to
	 *    pass to ApiBase::dieWithError().
	 *  * 1.27+: IApiMessage, or a key or key+parameters in ApiBase::$messageMap.
	 *  * Earlier: A key or key+parameters in ApiBase::$messageMap.
	 * @return bool|void True or no return value to continue, or false and set a
	 *  message to cancel the request
	 */
	public function onApiCheckCanExecute( $module, $user, &$message );
}
