<?php

namespace MediaWiki\Rest\Hook;

use MediaWiki\Api\Hook\APIAfterExecuteHook;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseInterface;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RestAfterExecuteHook" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @since 1.47
 * @see APIAfterExecuteHook
 */
interface RestAfterExecuteHook {
	/**
	 * Called after the handler of a REST API request has finished running (that is, after
	 * Handler::execute() and the various Handler::apply* methods, or after an exception was handled).
	 *
	 * The hook can be used for logging or minor modifications to the response (keep in mind it
	 * might get cached).
	 *
	 * @param Module $module The module responsible for processing the request. (When the handler
	 *   does not belong to a module, this will be an ExtraRoutesModule instance.)
	 * @param Handler|null $handler The handler responsible for processing the request, or null
	 *   for invalid routes when the handler could not be identified.
	 * @param string $path Path of the request. When the handler belongs to a module, doesn't
	 *   include the module prefix.
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @return void This hook must not abort, it must return no value
	 */
	public function onRestAfterExecute(
		Module $module,
		?Handler $handler,
		string $path,
		RequestInterface $request,
		ResponseInterface $response
	): void;
}
