<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiPageSet;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "APIQueryGeneratorAfterExecute" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface APIQueryGeneratorAfterExecuteHook {
	/**
	 * This hook is called after calling the executeGenerator() method of
	 * an action=query submodule. Use this hook to extend core API modules.
	 *
	 * @since 1.35
	 *
	 * @param ApiBase $module
	 * @param ApiPageSet $resultPageSet
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQueryGeneratorAfterExecute( $module, $resultPageSet );
}
