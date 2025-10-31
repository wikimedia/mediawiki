<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiQueryBase;
use MediaWiki\Permissions\Authority;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiQueryCheckCanExecute" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryCheckCanExecuteHook {

	/**
	 * This hook is called during at the beginning of ApiQuery::execute and can be used to prevent
	 * execution of query submodules.
	 *
	 * @since 1.44
	 *
	 * @param ApiQueryBase[] $modules
	 * @param Authority $authority Current user
	 * @param MessageSpecifier|string|array &$message API message to die with.
	 * @return bool|void True or no return value to continue, or false and set a
	 *  message to cancel the request
	 *
	 * @see ApiMessage::create() for how the $message parameter is interpreted.
	 * @see ApiCheckCanExecuteHook for non-query modules.
	 */
	public function onApiQueryCheckCanExecute( $modules, $authority, &$message );

}
