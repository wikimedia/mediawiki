<?php

namespace MediaWiki\Hook;

/**
 * @deprecated since 1.35, use GetMagicVariableIDsHook instead.
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
