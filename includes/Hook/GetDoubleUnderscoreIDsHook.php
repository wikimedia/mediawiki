<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetDoubleUnderscoreIDsHook {
	/**
	 * Use this hook to modify the list of behavior switches (double
	 * underscore variables in wikitext).  Behavior switches are localized
	 * with the magic word system, and this hook is called by
	 * MagicWordFactory.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$doubleUnderscoreIDs Array of magic word identifiers
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs );
}
