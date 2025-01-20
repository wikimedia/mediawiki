<?php

namespace MediaWiki\Rest\Hook;

use MediaWiki\Api\Hook\ApiCheckCanExecuteHook;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestInterface;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RestCheckCanExecute" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @since 1.44
 * @see ApiCheckCanExecuteHook
 */
interface RestCheckCanExecuteHook {
	/**
	 * Called when initializing a REST API request. Use this hook to deny requests to API
	 * endpoints belonging to a different component than the hook handler.
	 *
	 * @param Module $module The module responsible for processing the request. (When the handler
	 *   does not belong to a module, this will be an ExtraRoutesModule instance.)
	 * @param Handler $handler The handler responsible for processing the request.
	 * @param string $path Path of the request. When the handler belongs to a module, doesn't
	 *   include the module prefix.
	 * @param RequestInterface $request
	 * @param HttpException|null &$error Set this to explain why the request cannot be executed.
	 *   Should only be set when returning false.
	 * @return bool Return false to block request execution. $error must be set.
	 */
	public function onRestCheckCanExecute(
		Module $module,
		Handler $handler,
		string $path,
		RequestInterface $request,
		?HttpException &$error
	): bool;
}
