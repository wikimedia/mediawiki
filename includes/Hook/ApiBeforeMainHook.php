<?php

namespace MediaWiki\Hook;

use ApiMain;

/**
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
