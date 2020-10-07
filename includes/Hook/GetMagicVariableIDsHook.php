<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetMagicVariableIDsHook {
	/**
	 * Use this hook to modify the list of magic variables.
	 * Magic variables are localized with the magic word system,
	 * and this hook is called by MagicWordFactory.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$variableIDs array of magic word identifiers
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetMagicVariableIDs( &$variableIDs );
}
