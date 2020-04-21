<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiBeforeMainHook {
	/**
	 * Before calling ApiMain's execute() method in api.php.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$main ApiMain object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiBeforeMain( &$main );
}
