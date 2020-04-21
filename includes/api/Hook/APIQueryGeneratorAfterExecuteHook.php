<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIQueryGeneratorAfterExecuteHook {
	/**
	 * After calling the executeGenerator() method of
	 * an action=query submodule. Use this to extend core API modules.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module Module object
	 * @param ?mixed $resultPageSet ApiPageSet object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQueryGeneratorAfterExecute( $module, $resultPageSet );
}
