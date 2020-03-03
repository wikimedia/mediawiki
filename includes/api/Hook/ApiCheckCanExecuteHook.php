<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiCheckCanExecuteHook {
	/**
	 * Called during ApiMain::checkCanExecute. Use to further
	 * authenticate and authorize API clients before executing the module. Return
	 * false and set a message to cancel the request.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module Module object
	 * @param ?mixed $user Current user
	 * @param ?mixed &$message API message to die with. Specific values accepted depend on the
	 *  MediaWiki version:
	 *  * 1.29+: IApiMessage, Message, string message key, or key+parameters array to
	 *    pass to ApiBase::dieWithError().
	 *  * 1.27+: IApiMessage, or a key or key+parameters in ApiBase::$messageMap.
	 *  * Earlier: A key or key+parameters in ApiBase::$messageMap.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiCheckCanExecute( $module, $user, &$message );
}
