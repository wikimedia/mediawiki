<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MagicWordwgVariableIDs" to register handlers implementing this interface.
 *
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
