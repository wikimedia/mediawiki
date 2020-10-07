<?php

namespace MediaWiki\Api\Hook;

use ApiBase;
use ApiPageSet;

/**
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
	 * @param ApiBase $module Module object
	 * @param ApiPageSet $resultPageSet
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQueryGeneratorAfterExecute( $module, $resultPageSet );
}
