<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MagicWordwgVariableIDsHook {
	/**
	 * When defining new magic words IDs.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$variableIDs array of strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMagicWordwgVariableIDs( &$variableIDs );
}
