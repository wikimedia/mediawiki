<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetDoubleUnderscoreIDsHook {
	/**
	 * Use this hook to modify the list of behavior switch (double
	 * underscore) magic words. This hook is called by MagicWord.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$doubleUnderscoreIDs Array of strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs );
}
