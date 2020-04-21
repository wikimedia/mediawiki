<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface APIQueryAfterExecuteHook {
	/**
	 * After calling the execute() method of an
	 * action=query submodule. Use this to extend core API modules.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module Module object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAPIQueryAfterExecute( $module );
}
