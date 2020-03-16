<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MagicWordwgVariableIDsHook {
	/**
	 * This hook is called when defining new magic words IDs.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$variableIDs
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMagicWordwgVariableIDs( &$variableIDs );
}
