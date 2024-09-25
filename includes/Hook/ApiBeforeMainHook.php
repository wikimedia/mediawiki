<?php

namespace MediaWiki\Hook;

use MediaWiki\Api\ApiMain;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiBeforeMain" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiBeforeMainHook {
	/**
	 * This hook is called before calling ApiMain's execute() method in api.php.
	 *
	 * @since 1.35
	 *
	 * @param ApiMain &$main
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiBeforeMain( &$main );
}
